__author__ = 'daniel'
#
# a first demo client, quick&dirty, no error handling
# python 2.7.5
# requires paramiko
# https://github.com/paramiko/paramiko
#

import re
import paramiko

client = paramiko.SSHClient()
client.load_system_host_keys()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
print 'Connecting...'
client.connect('194.95.109.129', username='tracker', password='quetschi12', timeout=20, allow_agent=False, look_for_keys=False)
channel = client.invoke_shell()
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
client.close()
print 'Finished'

p = re.compile(r'^(?P<ip>[\da-f:]+)[ \t]+(?P<ttl>[\d\-]+)[ \t]+(?P<mac>[\da-f\.]+)[ \t]+(?P<state>(STALE|REACH))[ \t]+(?P<if>[a-zA-Z\d]+)\Z', re.I)
for line in lines:
    line = line.strip()
    result = p.match(line)
    if result:
        print('ip:' + result.group('ip') + ' mac:' + result.group('mac')+ ' state:' + result.group('state') +' interface:' + result.group('if'))
    else:
        print('no match: ' + line)