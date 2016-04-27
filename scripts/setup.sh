#!/bin/sh

# Update and upgrade pre-installed packages
sudo apt-get update
sudo apt-get upgrade -y

# Install midori
sudo apt-get install midori -y

# Install apache and php with curl
sudo apt-get install apache2 -y
sudo apt-get install php5 libapache2-mod-php5 php5-curl -y
sudo service apache2 restart

# Install unclutter
sudo apt-get install unclutter -y

# Disable screensaver and add startup programs
sudo sed -i '3s/.*/#@xscreensaver -no-splash/' /home/pi/.config/lxsession/LXDE-pi/autostart

cat << EOT | sudo tee -a /home/pi/.config/lxsession/LXDE-pi/autostart
@xset s noblank
@xset s off
@xset -dpms

@midori -e Fullscreen -a http://127.0.0.1/
@unclutter -display :0 -noevents -grab
EOT

sudo rm -rf /var/www/html
sudo ln -s /home/pi/mirror/www/ /var/www/html
chmod -R 777 /home/pi/mirror/www/images
