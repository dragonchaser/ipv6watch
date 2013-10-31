import datetime
import re
import paramiko
import threading
import ConfigParser
from sqlalchemy import create_engine, select, sql, Table, exc, func, MetaData


class Router(threading.Thread):
    def __init__(self, id, name, ip, username, password):
        threading.Thread.__init__(self)
        self.id = id
        self.name = name
        self.ip = ip
        self.username = username
        self.password = password
        self.neighbors = []

    def run(self):
        try:
            print 'Thread %i started' % self.id
            sshclient = paramiko.SSHClient()
            sshclient.load_system_host_keys()
            sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            sshclient.connect(self.ip, username=self.username, password=self.password, timeout=20, allow_agent=False,
                              look_for_keys=False)
            channel = sshclient.invoke_shell()
            stdin = channel.makefile('wb')
            stdout = channel.makefile('rb')

            # terminal length 0: set the number of lines of output to display on the terminal screen for the current session
            # show ipv6 neigh: display IPv6 neighbor discovery cache
            # exit: close the shell
            stdin.write('''
            terminal length 0
            show ipv6 neigh
            exit
            ''')
            lines = stdout.read().splitlines()
            timestamp = datetime.datetime.now()
            sshclient.close()
            p = re.compile(
                r'^(?P<ip>[\da-f:]+)[ \t]+(?P<age>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<state>(STALE|REACH))[ \t]+(?P<if>[a-zA-Z\d]+)\Z',
                re.I)
            clients = []
            for line in lines:
                line = line.strip()
                result = p.match(line)
                if result:
                    clients.append(
                        Client(result.group('ip'), result.group('mac').replace('.', ''), result.group('state'),
                               result.group('age'), self.id,
                               result.group('if'), timestamp - datetime.timedelta(minutes=int(result.group('age')))))
            print 'Thread finished: ' + self.ip
            self.neighbors = clients
        except Exception, e:
            print 'Thread %i failed: %s' % (self.id, str(e))


class Client:
    def __init__(self, ip, mac, state, age, router, interface, seen):
        self.ip = ip
        self.mac = mac
        self.state = state
        self.age = age
        self.router = router
        self.interface = interface
        self.seen = seen


print 'Main Thread started'
print 'Parsing Config'
config = ConfigParser.ConfigParser()
config.read('config.cfg')

engine = create_engine(
    config.get('Database', 'type') + '://' + config.get('Database', 'user') + ':' + config.get('Database', 'pass') + '@' + config.get(
        'Database', 'host') + '/' + config.get('Database', 'database'), echo=True)
conn = engine.connect()
metadata = MetaData(engine)
tbl_router = Table(u'ipv6_routerdata', metadata, autoload=True)
tbl_logentry = Table(u'ipv6_logentry', metadata, autoload=True)
tbl_timelog = Table(u'ipv6_timelog', metadata, autoload=True)
res = select([tbl_router.c.id, tbl_router.c.routerName, tbl_router.c.ipv4Address, tbl_router.c.username,
              tbl_router.c.password]).where(tbl_router.c.active == 1).execute()

threads = []
routerList = []
for row in res:
    r = Router(row['id'], row['routerName'], row['ipv4Address'], row['username'], row['password'])
    threads.append(r)
    r.start()

results = []

# wait until all threads are finished
for t in threads:
    t.join()
    results.extend(t.neighbors)

for c in results:
    try:
        res = tbl_logentry.insert().values(
            {'ipv6Address': c.ip, 'macAddress': c.mac, 'date_added': func.now(), 'RouterData': c.router}).execute()
        logid = res.lastrowid
    except exc.IntegrityError:
        res = select([tbl_logentry.c.id]).where(
            sql.and_(tbl_logentry.c.ipv6Address == c.ip, tbl_logentry.c.macAddress == c.mac)).execute()
        logid = res.fetchone()[0]
    finally:
        res = tbl_timelog.insert().values({'lastseen': c.seen, 'logentry': logid}).execute()

conn.close()
print 'Main Thread finished'