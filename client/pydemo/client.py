import datetime
import re
import paramiko
import threading
import ConfigParser
from time import time, strftime
from sqlalchemy import create_engine, select, sql, Table, exc, func, MetaData
import sys


def writelog(entry, etype, routerid=0):
    if routerid > 0:
        tbl_cronlog.insert().values(
            {'cronid': cronrunid, 'time': func.now(), 'routerid': routerid, 'type': etype, 'logentry': entry}).execute()
    else:
        tbl_cronlog.insert().values(
            {'cronid': cronrunid, 'time': func.now(), 'type': etype, 'logentry': entry}).execute()


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
            writelog('Connecting', 0, self.id)
            sshclient = paramiko.SSHClient()
            try:
                sshclient.load_host_keys('hostkeys.cfg')
            except IOError:
                open('hostkeys.cfg', 'w').close()
                sshclient.load_host_keys('hostkeys.cfg')
            sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            sshclient.connect(self.ip, username=self.username, password=self.password, timeout=20)
            channel = sshclient.invoke_shell()
            stdin = channel.makefile('wb')
            stdout = channel.makefile('rb')

            # terminal length 0: set the number of lines of output to display on the terminal screen for the current session
            # show ipv6 neigh: display IPv6 neighbor discovery cache
            # exit: close the shell
            stdin.write("terminal length 0\nshow ipv6 neigh\nexit\n")
            lines = stdout.read().splitlines()
            timestamp = datetime.datetime.now()
            sshclient.close()
            sshclient.save_host_keys('hostkeys.cfg')
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
            writelog('Finished with %i results' % len(clients), 0, self.id)
            self.neighbors = clients
        except Exception, e:
            print 'Thread %i failed: %s' % (self.id, str(e))
            writelog('Error: %s' % str(e), 1, self.id)


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
starttime = time()
print 'Parsing Config'
config = ConfigParser.ConfigParser()
config.read('config.cfg')

engine = create_engine(
    config.get('Database', 'type') + '://' + config.get('Database', 'user') + ':' + config.get('Database',
                                                                                               'pass') + '@' + config.get(
        'Database', 'host') + '/' + config.get('Database', 'database'), echo=False)

try:
    conn = engine.connect()
except exc.OperationalError, e:
    f = open('error.log','a')
    f.write('%s Fatal Error: %s\n' % (strftime("%Y-%m-%d %H:%M:%S"), str(e)))
    f.close()
    print str(e)
    sys.exit(0)

metadata = MetaData(engine)

tbl_router = Table(u'ipv6_routerdata', metadata, autoload=True)
tbl_logentry = Table(u'ipv6_logentry', metadata, autoload=True)
tbl_timelog = Table(u'ipv6_timelog', metadata, autoload=True)
tbl_cronruns = Table(u'ipv6_cronruns', metadata, autoload=True)
tbl_cronlog = Table(u'ipv6_cronlog', metadata, autoload=True)

res = tbl_cronruns.insert().values({'starttime': func.now()}).execute()
cronrunid = res.lastrowid
writelog('Main Thread started', 0)

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

print 'Writing Logs to Database'

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
        tbl_timelog.insert().values({'lastseen': c.seen, 'logentry': logid}).execute()

tbl_cronruns.update().values({'endtime': func.now()}).where(tbl_cronruns.c.id == cronrunid).execute()
print 'Database finished'
writelog('Main Thread finished with %i results from %i routers' % (len(results), len(threads)), 0)
conn.close()
print 'Main Thread finished'
delta = 'runtime: %f' % (time() - starttime)
print delta