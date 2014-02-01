import ConfigParser
from time import strftime
from sqlalchemy.exc import OperationalError
from sqlalchemy import *
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

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

res = conn.execute('DELETE FROM ipv6_ipaddress WHERE lastseen < DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -7 DAY)')
print res