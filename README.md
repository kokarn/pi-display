## Pi Display

![Preview](/preview.png)

### Features
 ##### Time  
 ##### Weather
  * Selected location  

##### Upcoming events  
  * Optional time
  * Data from external sources  
    * Ice hockey world championships
    * Series from [MyEpisodes](http://myepisodes.com)
    * Euro 2016
    * Custom

## Setup

Everything assumes a plain Raspbian Jesse installation already set-up with SSH access

Then ssh to the server and clone the repo
```bash
git clone https://github.com/kokarn/pi-display.git
cd pi-display
```

To setup everything needed for the local web server and stuff, run this
```bash
chmod +x scripts/setup.sh
./scripts/setup.sh
cp www/_config.php www/config.php
```

The only step left is to add your keys to `www/config.php` with something like nano
```bash
cp www/_config.php www/config.php
nano www/config.php
```

### Incorrect timezone
If the timezone is incorrect, run this from a shell to configure
```bash
sudo dpkg-reconfigure tzdata
```

### HDMiPi
If you have a HDMiPi, run the HDMiPi script to fix the screen rotation and size
```bash
chmod +x scripts/hdmipi.sh
./scripts/hdmipi.sh
```
