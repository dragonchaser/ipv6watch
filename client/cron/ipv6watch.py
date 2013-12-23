import datetime
import re
import threading
import ConfigParser
from time import time, strftime
import sys

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
    routerName = Column(String)
    fqdn = Column(String)
    port = Column(Integer)
    active = Column(Integer)
    username = Column(String)
    password = Column(String)
    sshKey = Column(UnicodeText)
    neighbors = []
    arp = []

    @orm.reconstructor
    def init_on_load(self):
        threading.Thread.__init__(self)

    def __init__(self, routerName, fqdn, port, username, password, sshKey):
        threading.Thread.__init__(self)
        self.routerName = routerName
        self.fqdn = fqdn
        self.port = port
        self.username = username
        self.password = password
        self.sshKey = sshKey
        self.active = 1

    def run(self):
        try:
            print 'Thread %i started' % self.id
            cr.log(logentry='Connecting', type=0, routerid=self.id)
            sshclient = paramiko.SSHClient()
            try:
                sshclient.load_host_keys('hostkeys.cfg')
            except IOError:
                open('hostkeys.cfg', 'w').close()
                sshclient.load_host_keys('hostkeys.cfg')
            sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            if self.sshKey:
                print 'Using ssh key'
                sshclient.connect(hostname=self.fqdn, port=self.port, pkey=paramiko.PKey(data=self.sshKey), timeout=20,
                                  look_for_keys=False)
            else:
                print 'Using password'
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
            #sshclient.save_host_keys('hostkeys.cfg')
            p6 = re.compile(
                r'^(?P<ip>[\da-f:]+)[ \t]+(?P<age>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<state>(STALE|REACH))[ \t]+(?P<if>[a-zA-Z\d/-]+)\Z',
                re.I)
            p4 = re.compile(
                r'^(?P<protocol>[\da-zA-Z]+)[ \t]+(?P<ip>[\d\.]+)[ \t]+(?P<age>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<type>(ARPA))[ \t]+(?P<if>[a-zA-Z\d/-]+)\Z',
                re.I)
            clients6 = []
            clients4 = []
            mode = 0  # 0: undefined, 1: v6 neigh, 2: arp-cache
            for line in lines:
                line = line.strip()
                if line.find('>show ipv6 neigh') > 0:
                    mode = 1
                    print 'mode changed: v6 neigh'
                elif line.find('>show ip arp') > 0:
                    mode = 2
                    print 'mode changed: arp'

                # parse ipv6 neigh cache
                if mode == 1:
                    result = p6.match(line)
                    if result:
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

            print 'Thread finished: ' + self.fqdn
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
        self.seen = seen - datetime.timedelta(minutes=int(age))


class Client4:
    def __init__(self, ip, mac, type, age, router, interface, seen):
        self.ip = ip
        self.mac = mac
        self.type = type
        self.router = router
        self.interface = interface
        if age == '-':
            self.age = '0'
        else:
            self.age = age
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
    hasBeenExported = Column(Integer)
    ipId = Column(Integer, ForeignKey('ipv6_ipaddress.id'))
    routerId = Column(Integer)

    def __init__(self, interface, routerId):
        self.lastseen = func.now()
        self.interface = interface
        self.hasBeenExported = 0
        self.routerId = routerId


class IpAddress(Base):
    __tablename__ = 'ipv6_ipaddress'
    id = Column(Integer, primary_key=True)
    ipv4Address = Column(String)
    ipv6Address = Column(String)
    macAddress = Column(String)
    added = Column(DateTime)
    lastseen = Column(DateTime)
    logs = relationship('Timelog')

    def __init__(self, mac, v4='', v6=''):
        self.ipv4Address = v4
        self.ipv6Address = v6
        self.macAddress = mac
        self.lastseen = func.now()
        self.added = func.now()


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

routerList = session.query(Router).filter(Router.active == 1).all()

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
    cur = session.query(IpAddress).filter(IpAddress.macAddress == c.mac, IpAddress.ipv4Address == c.ip).first()
    if cur:
        cur.lastseen = func.now()
    else:
        cur = IpAddress(mac=c.mac, v4=c.ip)
        ipList.append(cur)
    cur.logs.append(Timelog(interface=c.interface, routerId=c.router))

for c in res6:
    cur = session.query(IpAddress).filter(IpAddress.macAddress == c.mac, IpAddress.ipv6Address == c.ip).first()
    if cur:
        cur.lastseen = func.now()
    else:
        cur = IpAddress(mac=c.mac, v6=c.ip)
        ipList.append(cur)
    cur.logs.append(Timelog(interface=c.interface, routerId=c.router))

session.add_all(ipList)
print 'Writing to DB...'
session.commit()

print 'Database finished'
cr.log(logentry='Main Thread finished with %i results from %i routers' % (len(res6), len(routerList)), type=0)
cr.finish()
session.commit()
conn.close()
print 'Main Thread finished'
print 'runtime: %f' % (time() - starttime)