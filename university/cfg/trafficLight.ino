void setup()
{
	Serial.begin(9600);
	pinMode(2, OUTPUT);
	pinMode(3, OUTPUT);
	pinMode(4, OUTPUT);
}

void loop()
{
	int GREEN = 2;
	int YELLOW = 3;
	int RED = 4;
	
	int STOP = 2;
	int GO = 1;
	int IDLE = 0;
	int state = 0;
	
	int pressurePlate = 0;
	int lastGreen = millis()-5000;
	int goTimer = 0;
	int goTrigger = false;
	int stopTrigger = false;
	int stopDelay = millis();
	
	int lastCar = millis();
	
	digitalWrite(RED, HIGH);
	digitalWrite(YELLOW, LOW);
	digitalWrite(GREEN, LOW);
	
	while (1) {
		pressurePlate = analogRead(A0);
		int thisCycle = millis();
		
		if (state == IDLE) {
			if(pressurePlate > 500) {
				lastCar = thisCycle;
				
				if (thisCycle - lastGreen > 5000) {
					lastGreen = thisCycle;
					goTimer = thisCycle;
					goTrigger = true;
					state = GO;
					digitalWrite(YELLOW, HIGH);
				}
			}
			
		} else if (state == GO) {
			if (thisCycle - goTimer > 1000) {
				digitalWrite(GREEN, HIGH);
				digitalWrite(YELLOW, LOW);
				digitalWrite(RED, LOW);
			}
			
			if (thisCycle - lastCar > 5000) {
				stopTrigger = true;
				goTrigger = false;
				stopDelay = thisCycle;
				state = STOP;
			}
			
		} else if (state == STOP) {
			if (thisCycle - stopDelay < 2000) {
				digitalWrite(RED, LOW);
				digitalWrite(YELLOW, HIGH);
				digitalWrite(GREEN, HIGH);
			} else {
				digitalWrite(RED, HIGH);
				digitalWrite(YELLOW, LOW);
				digitalWrite(GREEN, LOW);
				stopTrigger = false;
				state = IDLE;
			}

			lastGreen = thisCycle;
		}
		
		Serial.println(pressurePlate);
		delay(50);
	}
}
