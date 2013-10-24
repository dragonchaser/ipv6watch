__author__ = 'daniel'
#
# a first demo client, quick&dirty, no error handling
# python 2.7.5
# requires paramiko
# https://github.com/paramiko/paramiko
#

import re
import paramiko


class Router:
    def __init__(self, ip, username, password):
        self.ip = ip
        self.username = username
        self.password = password

    def getneighbors(self):
        sshClient = paramiko.SSHClient()
        sshClient.load_system_host_keys()
        sshClient.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        sshClient.connect(self.ip, username=self.username, password=self.password, timeout=20, allow_agent=False,
                          look_for_keys=False)
        channel = sshClient.invoke_shell()
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
        sshClient.close()
        p = re.compile(
            r'^(?P<ip>[\da-f:]+)[ \t]+(?P<age>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<state>(STALE|REACH))[ \t]+(?P<if>[a-zA-Z\d]+)\Z', re.I)
        clientList = []
        for line in lines:
            line = line.strip()
            result = p.match(line)
            if result:
                clientList.append(
                    Client(result.group('ip'), result.group('mac'), result.group('state'), result.group('age'), self.ip,
                           result.group('if')))
        return clientList


class Client:
    def __init__(self, ip, mac, state, age, router, interface):
        self.ip = ip
        self.mac = mac
        self.state = state
        self.age = age
        self.router = router
        self.interface = interface


clientList = []
routerList = [Router('194.95.109.129', 'tracker', 'quetschi12')]
for r in routerList:
    clientList.extend(r.getneighbors())

for c in clientList:
    print c.ip + ' - ' + c.mac + ' - ' + c.router