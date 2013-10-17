__author__ = 'daniel'
#
# a first demo client, quick&dirty, no error handling
# python 3.3
#
import subprocess
import locale
import re
import xml.etree.cElementTree as et
import http.client, urllib.parse

cache = True # for debugging purposes only, if true cache.txt instead of stdout is used
host = 'localhost' # http host
command = 'ip -6 neigh show'
encoding = locale.getdefaultlocale()[1]

if cache:
    lst = open('cache.txt').read().splitlines()
else:
    proc = subprocess.Popen(command, stdout=subprocess.PIPE, stderr=None, shell=True)
    output = proc.stdout.read()
    lst = output.decode(encoding).split('\n')

root = et.Element("clients")

p = re.compile(r'^(?P<ip>[\da-f:]*) dev .* lladdr (?P<mac>[\da-f:]*) (.* )?(STALE|REACHABLE)\Z', re.I)
for line in lst:
    result = p.match(line)
    if result:
        print('ip:'+result.group('ip')+' mac:'+result.group('mac'))

        # write xml tree
        field = et.SubElement(root, 'client')
        field.set('ip', result.group('ip'))
        field.set('mac', result.group('mac'))
    else:
        print('no match: '+line)

# send xml to host/foo.php
xmldata = et.tostring(root)
params = urllib.parse.urlencode({'data': xmldata})
print(params)
headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}
conn = http.client.HTTPConnection(host)
conn.request("POST", "/foo.php", params, headers)
response = conn.getresponse()
print(response.status, response.reason)
data = response.read()
conn.close()