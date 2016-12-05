## == Dependencies:
##      https://aur.archlinux.org/packages/python-raspberry-gpio/
##      https://github.com/Torxed/slimHTTP
##
##   This script is designed to run under slimHTTP mainly because
##   slimHTTP expects `def main()` to be present and thus called.
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setup(18, GPIO.OUT)

def main(request=None):
	print('DEBUG:', request)
	if request:
		if b'on' in request:
			print('Setting lights to off')
			GPIO.output(18, GPIO.HIGH)
		else:
			print('Setting lights to on')
			GPIO.output(18, GPIO.LOW)

	with open('index.html', 'rb') as template:
		body = template.read()
	return {'body' : body}
