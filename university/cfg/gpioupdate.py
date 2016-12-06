## == A script designed to run as cron/systemd-timer script.
##    It will try to poll a external website for json data -
##    and based on it turn the GPIO pin on/off.

from json import loads
import RPi.GPIO as GPIO
import urllib.request

## == Get the pin state from a external webserver.
##    (Mainly just so we can have a GUI gateway)
url = 'http://hvornum.se/gpio.php?format=json'
response = urllib.request.urlopen(url)
jData = loads(response.read().decode('UTF-8'))

## == Update the pin according to what the jason response was.
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(18, GPIO.OUT)

#print(jData)
if jData['state']:
        print('Turning on the lights')
        GPIO.output(18, GPIO.HIGH)
else:
        print('Turning off the lights')
        GPIO.output(18, GPIO.LOW)
