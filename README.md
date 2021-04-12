# Mancave
Manage your rolling shutter from your mancave using an ESP32 and a relay.

### Introduction
During my minor Smart Industry I got interested in Arduino based hardware, specifically the ESP32 platform. This is a Arduino-like platform which is designed for IoT. It's fully compatible with the Arduino IDE.

In this period, the popularity of domotics was increasing. After getting Hue light at home, I decided to automate our rolling shutter. There are currently ready-made solutions priced around € 100+.

### Features
- Remotely manage your rolling shutter, even without home WiFi;
- Open and close rolling shutter;
- Set the rolling shutter state to a specific percentage;
- User friendly web app for smartphones; 
- Coded for scalability, can be easily extended;
- Very affordable.

### Requirements
- ESP32 development board with WiFi **[€ 6]** (I recommend boards from WeMoS due stability issues with cheaper ones)
https://www.banggood.com/ESP32-Development-Board-WiFiBluetooth-Ultra-Low-Power-Consumption-Dual-Cores-ESP-32-ESP-32S-Board-p-1109512.html?rmmds=search&cur_warehouse=CN
- 4-way relay **[€ 3]** (for some strange reason a 4 way is cheaper than any smaller one)
https://www.banggood.com/5V-4-Channel-Relay-Module-For-Arduino-PIC-ARM-DSP-AVR-MSP430-Blue-p-87987.html?rmmds=search&cur_warehouse=CN
- ABS case **[€ 2]** (you can use any case)
https://www.banggood.com/search/abs-case.html
- Linux based Webserver with PHP support (this can be a local server or remote webhosting);
- Your rolling shutter must already have a rolling motor and switch.

The total costs would be below **€ 15**, isn't that nice?

### Hardware
In this chapter we will explain the hardware part of the project, which is quite simple to be honest. Below is a photo of the end result:
![hardware](https://i.imgur.com/bCHh407.jpg)
As you can see the 4-way relay is connected to the ESP32. You only need two relays (up and down). The ESP32 controls the relay by sending HIGH/LOW signals to the relay. LOW = relay on, HIGH = relay off. 230V + needs to be connected to both relays because the original shutter switch is a 3-way switch. Connecting + with one of the two cables controlls the rolling motor.

***Tip: use a strong power supply for the ESP32, or get a quality devboard from WeMoS, because the relay drains all power from the board, preventing you from compiling new Arduino code (md5 issues etc).***

You must connect the relay to the 3-way wall switch like this:
![switch](https://i.imgur.com/dMwwKW7.jpg)

### Software
The software is based on PHP code for the controller, and Arduino code for the ESP32 which acts as a device.
*All code is documented for easy customisation.*

### Installation
1. First of all we need to prepare the API, which will act as a controller. You will need webhosting or a home local server with PHP support. Then simply upload the Mancave class and api.php.
2. Now add a new device by running example.php, this will return a device ID. Remember this as we need this in the next step.
3. Now edit mancave.ino and change the following:
- Line 6: replace the ID with the newly created device ID.
- Line 7/8: adjust the WiFi credentials.
- Line 16/17: adjust the pin layout if needed.
- Line 24: adjust the URL of the API/controller.
- Line 50-53: check out the times of your rolling motor and adjust the code.
- Line 65: change 32 with the value of your speed, in this case 100% closed.
- Line 69: change 32 with the value of your speed, in this case 100% opened.
- Line 75/81: change 25 with the value of your speed, in this case 99%, which covers the whole window.
[Line 135 is used to determine new actions sent by the webapp.]
4. Edit webapp/index.php and change the password on Line 12 to something stronger.
5. Access the webapp on your desktop or smartphone through: http://yoururl.com/webapp/?access=password
You can make an app from it by added it to your homescreen on your smartphone. You also might want to translate some parts of the webapp.

The webapp will look like this in iOS 11:
![webapp](https://i.imgur.com/9a733.jpg)

### License
MIT License
