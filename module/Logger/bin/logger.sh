#!/bin/bash

if [ "start" == $1 ]; then
  gpsd /dev/ttyUSB0
fi

if [ "stop" == $1 ]; then
  # shellcheck disable=SC2046
  kill -9 $(pgrep gpsd | awk 'print $1')
fi
