#! /usr/bin/python
from gps import *
import time, inspect


f = open(time.strftime("%Y%m%d")+'-gps.dat','w')

gpsd = gps(mode=WATCH_ENABLE|WATCH_NEWSTYLE)

# print 'GPStime utc:latitude,longitude:speed:sats'

try:
    while True:
        report = gpsd.next() #
        if report['class'] == 'TPV':
            GPStime =  str(getattr(report,'time',''))
            lat = str(getattr(report,'lat',0.0))
            lon = str(getattr(report,'lon',0.0))
            speed =  str(getattr(report,'speed','nan'))
            sats = str(len(gpsd.satellites))

            f.write(GPStime + ':' + lat +',' + lon + ':' + speed + ':' + sats + '\n')

            time.sleep(15)

except (KeyboardInterrupt, SystemExit): # when you press ctrl+c
    # print "Done.\nExiting."
    f.close()