import time

__author__ = 'daniel'
#
# a first demo client, quick&dirty, no error handling
# python 2.7.5
# requires paramiko
# https://github.com/paramiko/paramiko
#

import re
import paramiko
import threading


class Router(threading.Thread):
    def __init__(self, ip, username, password):
        self.ip = ip
        self.username = username
        self.password = password
        self.neighbors = []
        threading.Thread.__init__(self)

    def run(self):
        print 'Thread started: '+self.ip
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
        timestamp = time.time()
        sshclient.close()
        p = re.compile(
            r'^(?P<ip>[\da-f:]+)[ \t]+(?P<age>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<state>(STALE|REACH))[ \t]+(?P<if>[a-zA-Z\d]+)\Z', re.I)
        clients = []
        for line in lines:
            line = line.strip()
            result = p.match(line)
            if result:
                clients.append(
                    Client(result.group('ip'), result.group('mac'), result.group('state'), result.group('age'), self.ip,
                           result.group('if'), timestamp))
        print 'Thread finished: '+self.ip
        self.neighbors = clients


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
threads = []
results = []
routerList = [Router('194.95.109.129', 'tracker', 'quetschi12')]

# start a thread for every router
for r in routerList:
    threads.append(r)
    r.start()

# wait until all threads are finished
for t in threads:
    t.join()
    results.extend(t.neighbors)

for c in results:
    print c.ip + ' - ' + c.mac + ' - ' + c.router
print 'Main Thread finished'