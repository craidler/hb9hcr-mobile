#!/bin/sh -e
### BEGIN INIT INFO
# Provides:          gpsd
# Required-Start:
# Required-Stop:
# Default-Start:     1 2 3 4 5
# Default-Stop:
# Short-Description: Run my GPSd
### END INIT INFO
#
case "$1" in
  start)
    gpsd /dev/ttyUSB0 -F /var/run/gpsd.sock
    ;;
  stop)
    killall -KILL gpsd
    ;;
  restart|force-reload)
    killall -KILL gpsd
    sleep 3
    gpsd /dev/ttyUSB0 -F /var/run/gpsd.sock
    ;;
  *) echo "Usage: $0 {start|stop|restart|force-reload}" >&2; exit 1 ;;
esac