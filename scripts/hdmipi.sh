#!/bin/bash
cat << EOT | sudo tee -a /boot/config.txt
display_rotate=3
hdmi_ignore_edid=0xa5000080
hdmi_group=2 # HDMIPi for 1280 x 800
hdmi_drive=2 # for alternative modes get sound
hdmi_mode=28 # 1280 x 800 @ 60 Hz Specifcations
EOT

sudo reboot
