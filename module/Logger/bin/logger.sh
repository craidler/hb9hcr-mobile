#!/bin/bash

# sudo crontab -e
# @reboot /home/pi/hb9hcr-mobile/module/Logger/bin/logger.sh &

sleep 30;

python /home/pi/hb9hcr-mobile/module/Logger/bin/logger.py &