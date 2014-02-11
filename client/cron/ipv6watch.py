import datetime
import re
import threading
import ConfigParser
from time import time, strftime
import paramiko
from sqlalchemy import orm
from sqlalchemy.exc import OperationalError
from sqlalchemy import *
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, relationship

Base = declarative_base()


class Router(Base, threading.Thread):
    __tablename__ = 'ipv6_router'
    id = Column(Integer, primary_key=True)
    routername = Column(String)
    fqdn = Column(String)
    port = Column(Integer)
    active = Column(Integer)
    username = Column(String)
    password = Column(String)
    sshkey = Column(UnicodeText)
    neighbors = []  # results from neighborhood discovery (ipv6)
    arp = []  # results from arp cache (ipv4)

    @orm.reconstructor
    def init_on_load(self):
        threading.Thread.__init__(self)

    def __init__(self, routername, fqdn, port, username, password, sshkey):
        threading.Thread.__init__(self)
        self.routername = routername
        self.fqdn = fqdn
        self.port = port
        self.username = username
        self.password = password
        self.sshkey = sshkey
        self.active = 1

    def run(self):
        try:
            print 'Thread %i started' % self.id
            cr.log(logentry='Connecting', type=0, routerid=self.id)
            sshclient = paramiko.SSHClient()
            sshclient.known_hosts = None

            # load the hostkeys from hostkeys.cfg
            try:
                sshclient.load_host_keys('hostkeys.cfg')
            except IOError:
                # hostkeys.cfg doesn't exist, let's create it
                open('hostkeys.cfg', 'w').close()
                sshclient.load_host_keys('hostkeys.cfg')

            # automatically add new host keys on first connection
            sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            if self.sshkey:
                # login using ssh-key
                sshclient.connect(hostname=self.fqdn, port=self.port, pkey=paramiko.PKey(data=self.sshkey), timeout=20,
                                  look_for_keys=False)
            else:
                # login using username/password
                sshclient.connect(hostname=self.fqdn, port=int(self.port), username=self.username,
                                  password=self.password, timeout=20, look_for_keys=False)

            channel = sshclient.invoke_shell()
            stdin = channel.makefile('wb')
            stdout = channel.makefile('rb')

            # terminal length 0: set the number of lines of output to display on the terminal screen for the current session
            # show ipv6 neigh: display IPv6 neighbor discovery cache
            # exit: close the shell
            stdin.write("terminal length 0\nshow ipv6 neigh\nshow ip arp\nexit\n")
            lines = stdout.readlines()
            timestamp = datetime.datetime.now()
            sshclient.close()
            sshclient.save_host_keys('hostkeys.cfg')

            # regex for parsing the neighborhood discovery output
            p6 = re.compile(
                r'^(?P<ip>[\da-f:]+)[ \t]+(?P<age>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<state>(STALE|REACH))[ \t]+(?P<if>[a-zA-Z\d/-]+)\Z',
                re.I)

            # regex for parsing the arp cache output
            p4 = re.compile(
                r'^(?P<protocol>[\da-zA-Z]+)[ \t]+(?P<ip>[\d\.]+)[ \t]+(?P<age>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<type>(ARPA))[ \t]+(?P<if>[a-zA-Z\d/-]+)\Z',
                re.I)

            clients6 = []
            clients4 = []
            mode = 0  # 0: undefined, 1: v6/neigh, 2: v4/arp-cache
            for line in lines:
                line = line.strip()
                if line.find('>show ipv6 neigh') > 0:
                    mode = 1
                elif line.find('>show ip arp') > 0:
                    mode = 2

                # parse ipv6 neigh cache
                if mode == 1:
                    result = p6.match(line)
                    # ignore fe80-IPs
                    if result and result.group('ip').partition(':')[0].lower() != 'fe80':
                        clients6.append(
                            Client6(result.group('ip'), result.group('mac').replace('.', ''), result.group('state'),
                                    result.group('age'), self.id,
                                    result.group('if'),
                                    timestamp))

                # parse arp cache
                elif mode == 2:
                    result = p4.match(line)
                    if result:
                        clients4.append(
                            Client4(result.group('ip'), result.group('mac').replace('.', ''), result.group('type'),
                                    result.group('age'), self.id,
                                    result.group('if'),
                                    timestamp))

            print 'Thread %i finished' % self.id

            cr.log(logentry='Finished with %i ipv6 and %i ipv4 results' % (len(clients6), len(clients4)), type=0,
                   routerid=self.id)
            self.neighbors = clients6
            self.arp = clients4
        except Exception, e:
            print 'Thread %i failed: %s' % (self.id, str(e))
            cr.log(logentry='Error: %s' % str(e), type=1, routerid=self.id)


class Client6:
    def __init__(self, ip, mac, state, age, router, interface, seen):
        self.ip = ip
        self.mac = mac
        self.state = state
        self.age = age
        self.router = router
        self.interface = interface
        # calculate last seen time based on age
        self.seen = seen - datetime.timedelta(minutes=int(age))


class Client4:
    def __init__(self, ip, mac, type, age, router, interface, seen):
        self.ip = ip
        self.mac = mac
        self.type = type
        self.router = router
        self.interface = interface
        # arp cache output can contain '-' as age
        if age == '-':
            self.age = '0'
        else:
            self.age = age
            # calculate last seen time based on age
        self.seen = seen - datetime.timedelta(minutes=int(self.age))


class Cronlog(Base):
    __tablename__ = 'ipv6_cronlog'
    id = Column(Integer, primary_key=True)
    time = Column(DateTime)
    cron_id = Column(Integer, ForeignKey('ipv6_cronruns.id'))
    type = Column(Integer)
    logentry = Column(String)
    router_id = Column(Integer)

    def __init__(self, time, logentry, type, routerid):
        self.time = time
        self.logentry = logentry
        self.type = type
        self.router_id = routerid


class Cronrun(Base):
    __tablename__ = 'ipv6_cronruns'
    id = Column(Integer, primary_key=True)
    starttime = Column(DateTime)
    endtime = Column(DateTime)
    logs = relationship('Cronlog')

    def __init__(self):
        self.starttime = func.now()
        self.endtime = null()

    def log(self, logentry, type, routerid=null()):
        self.logs.append(Cronlog(time=func.now(), logentry=logentry, type=type, routerid=routerid))

    def finish(self):
        self.endtime = func.now()


class Timelog(Base):
    __tablename__ = 'ipv6_timelog'
    id = Column(Integer, primary_key=True)
    interface = Column(String)
    lastseen = Column(DateTime)
    hasbeenexported = Column(Integer)
    ipid = Column(Integer, ForeignKey('ipv6_ipaddress.id'))
    routerid = Column(Integer)

    def __init__(self, interface, routerId):
        self.lastseen = func.now()
        self.interface = interface
        self.hasbeenexported = 0
        self.routerid = routerId


class IpAddress(Base):
    __tablename__ = 'ipv6_ipaddress'
    id = Column(Integer, primary_key=True)
    ipv4address = Column(String)
    ipv6address = Column(String)
    macaddress = Column(String)
    added = Column(DateTime)
    lastseen = Column(DateTime)
    logs = relationship('Timelog')

    def __init__(self, mac, v4='', v6=''):
        self.ipv4address = v4
        self.ipv6address = v6
        self.macaddress = mac
        self.lastseen = func.now()
        self.added = func.now()


def prune():
    prunetime = datetime.datetime.today() - datetime.timedelta(days=1)
    res_timelog = session.query(Timelog).filter(Timelog.hasbeenexported == 1).filter(
        Timelog.lastseen < prunetime).delete()
    res_ip = session.query(IpAddress).filter(IpAddress.logs == None).delete(synchronize_session='fetch')
    cr.log(logentry='Pruning: deleted %i Logentrys and %i orphaned ipAddresses' % (res_timelog, res_ip), type=0)


starttime = time()

# read the config.cfg
config = ConfigParser.ConfigParser()
config.read('config.cfg')

# engine = create_engine(
#     config.get('Database', 'type') + '://' + config.get('Database', 'user') + ':' + config.get('Database',
#                                                                                                'pass') + '@' + config.get(
#         'Database', 'host') + '/' + config.get('Database', 'database'), echo=False)

engine = create_engine('%s://%s:%s@%s/%s' % (
config.get('Database', 'type'), config.get('Database', 'user'), config.get('Database', 'pass'),
config.get('Database', 'host'), config.get('Database', 'database')), echo=False)

try:
    conn = engine.connect()
except OperationalError, e:
    f = open('error.log', 'a')
    f.write('%s Fatal Error: %s\n' % (strftime("%Y-%m-%d %H:%M:%S"), str(e)))
    f.close()
    print str(e)
    sys.exit(0)

metadata = MetaData(engine)
Session = sessionmaker(bind=engine)
session = Session()

cr = Cronrun()
session.add(cr)
cr.log('Main Thread started', 0)
session.commit()

# get all active router objects
routerList = session.query(Router).filter(Router.active == True).all()

res6 = []
res4 = []

# start threads
for r in routerList:
    r.start()

# wait until all threads are finished
for r in routerList:
    r.join()
    res6.extend(r.neighbors)
    res4.extend(r.arp)

ipList = []
for c in res4:
    cur = session.query(IpAddress).filter(IpAddress.macaddress == c.mac, IpAddress.ipv4address == c.ip).first()
    if cur:
        cur.lastseen = func.now()
    else:
        cur = IpAddress(mac=c.mac, v4=c.ip)
        ipList.append(cur)
    cur.logs.append(Timelog(interface=c.interface, routerId=c.router))

for c in res6:
    cur = session.query(IpAddress).filter(IpAddress.macaddress == c.mac, IpAddress.ipv6address == c.ip).first()
    if cur:
        cur.lastseen = func.now()
    else:
        cur = IpAddress(mac=c.mac, v6=c.ip)
        ipList.append(cur)
    cur.logs.append(Timelog(interface=c.interface, routerId=c.router))

session.add_all(ipList)
session.commit()

cr.log(logentry='Main Thread finished with %i results from %i routers in %.2f seconds' % (
len(res6), len(routerList), (time() - starttime)), type=0)

prune()

cr.finish()
session.commit()
conn.close()