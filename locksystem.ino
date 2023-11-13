#include <Adafruit_Fingerprint.h>
#include <Arduino.h>

SoftwareSerial mySerial(2, 3);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

#define ON 1
#define OFF 0
#define RELAY_PIN       4
#define ACCESS_DELAY    4000 // Keep lock unlocked for 3 seconds 

const int buzzer = 13;
const int vb = A5;
int present_condition = 0;
int previous_condition = 0;

void setup()
{
  // set the data rate for the sensor serial port
  pinMode(buzzer, OUTPUT);
  pinMode(vb, INPUT);
  Serial.begin(9600); 
  finger.begin(57600);
  delay(5);
  if (finger.verifyPassword()) 
  {
  } 
  else 
  {
    while (1) { delay(1); }
  }
  
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, HIGH);   //Switch off relay initially. Relay is LOW level triggered relay so we need to write HIGH.
}

void loop()
{
  int datasensor = digitalRead(vb);
  if (datasensor == HIGH){
    digitalWrite(buzzer, HIGH);
    delay(200);
    digitalWrite(buzzer, LOW);
    delay(50);
    digitalWrite(buzzer, HIGH);
    delay(200);
    digitalWrite(buzzer, LOW);
  }
  if ( getFingerPrint() != -1)
  {
    digitalWrite(buzzer,HIGH);
    delay(50);
    digitalWrite(buzzer,LOW);
    delay(50);
    digitalWrite(buzzer,HIGH);
    delay(50);
    digitalWrite(buzzer,LOW);
    digitalWrite(RELAY_PIN, LOW);
    delay(ACCESS_DELAY);
    digitalWrite(RELAY_PIN, HIGH);   
  }  
  delay(50);            //Add some delay before next scan.
}

// returns -1 if failed, otherwise returns ID #
int getFingerPrint() 
{
  int p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;

  // found a match!
  return finger.fingerID;
}