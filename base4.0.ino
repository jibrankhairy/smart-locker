#include <SoftwareSerial.h>
#include <Adafruit_Fingerprint.h>
#include <Arduino.h>
#include <LiquidCrystal_I2C.h>

#define ON 1
#define OFF 0
#define RELAY_PIN       4
#define ACCESS_DELAY    4000 

SoftwareSerial uno(2,3); //RX,TX
int ledPin = 13;
SoftwareSerial mySerial(6, 7); //TX RX fingerprint
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
LiquidCrystal_I2C lcd(0x27, 16, 2); //SCL = A5 , SDA = A4

const int buzzer = 13;
const int vb = 5;
int present_condition = 0;
int previous_condition = 0;

uint8_t id;

String data;
char c;

void setup() {
  data="";
  pinMode(buzzer, OUTPUT);
  pinMode(vb, INPUT);
  Serial.begin(9600);
  Serial.println(data);
  Serial.println("y");
  lcd.begin();
  uno.begin(9600);
  while(true){
    lcd.setCursor(0,0);   
    lcd.print("     LOKER      ");
    lcd.setCursor(0,1);
    lcd.print("  TIDAK AKTIF   ");
    if(uno.available()>0){
      delay(10);
      c = uno.read();
      Serial.println(c);
      data+=c;
    }
    
    Serial.println(data);
    if (data == "BLINK"){
      digitalWrite(buzzer, HIGH);
      delay(700);
      digitalWrite(buzzer, LOW);
      daftar();
      data="";
      set();
      break;
    }
  }
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, HIGH);
}

uint8_t readnumber(void) 
{
  uint8_t num = 0;

  while (num == 0) 
  {
    while (! Serial.available());
    num = Serial.parseInt();
  }
  return num;
}

void loop() {
  lcd.begin();
  finger.begin(57600);
  lcd.setCursor(0,0);   
  lcd.print("     LOKER      ");
  lcd.setCursor(0,1);
  lcd.print("     AKTIF      ");
  for(int i=0;i<500;i++){
    if(i==100||i==250||i==400){
      lcd.begin();
      lcd.setCursor(0,0);   
      lcd.print("     LOKER      ");
      lcd.setCursor(0,1);
      lcd.print("     AKTIF      ");
    }
    if ( getFinger() != -1)
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
    delay(50);            
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
  }

  uno.begin(9600);
  data="";
  lcd.begin();
  lcd.setCursor(0,0);   
  lcd.print("   Cek Status   ");
  lcd.setCursor(0,1);
  lcd.print("                ");
  delay(5000);
    for(int i=0;i<10;i++){
      if(uno.available()>0){
        delay(10);
        c = uno.read();
        data+=c;
        if (data.length()>0){
          if (data == "STOP"){
            digitalWrite(buzzer, HIGH);
            delay(500);
            digitalWrite(buzzer, LOW);
            data="";
            asm volatile(" jmp 0");
          }
        }
      }
    }
}

int getFinger() 
{
  int p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;

  return finger.fingerID;
}

int set(){
  finger.begin(57600);
  delay(5);
  if (finger.verifyPassword()) {
  } 
  else 
  {
    while (1) { delay(1); }
  }
}

int daftar(){
    Serial.println("\n\nReady to enroll fingerprints for ID #1!");
    Serial.print("Enrolling fingerprints for ID #");
    Serial.println(id);
    
    for (int i = 0; i < 2; i++){
      Serial.print("Enroll fingerprint ");
      Serial.println(i + 1);
      if (i == 0){
        lcd.setCursor(0,0);   
        lcd.print("Letakkan Jari    ");
        lcd.setCursor(0,1);
        lcd.print("Pertama          ");
      }else if (i == 1){
        lcd.setCursor(0,0);   
        lcd.print("Letakkan Jari       ");
        lcd.setCursor(0,1);
        lcd.print("Kedua            ");
      }
      while (!enrollFingerprint());
    }
}

uint8_t enrollFingerprint() {
  mySerial.begin(57600);
  int p = -1;
  Serial.print("Waiting for a valid finger to enroll as ID #");
  Serial.println(id);

  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
      case FINGERPRINT_OK:
        Serial.println("Image taken");
        break;
      case FINGERPRINT_NOFINGER:
        break;
      case FINGERPRINT_PACKETRECIEVEERR:
        Serial.println("Communication error");
        return false;
      case FINGERPRINT_IMAGEFAIL:
        Serial.println("Imaging error");
        return false;
      default:
        Serial.println("Unknown error");
        return false;
    }
  }

  p = finger.image2Tz(1);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return false;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return false;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return false;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return false;
    default:
      Serial.println("Unknown error");
      return false;
  }

  Serial.println("Remove finger");
  lcd.setCursor(0,0);   
  lcd.print("Lepaskan Jari     ");
  lcd.setCursor(0,1);
  lcd.print("                  ");
  delay(2000);
  p = 0;
  while (p != FINGERPRINT_NOFINGER) {
    p = finger.getImage();
  }
  Serial.println("Place the same finger again");
  lcd.setCursor(0,0);
  lcd.print("Letakkan Jari      ");
  lcd.setCursor(0,1);
  lcd.print("Kembali           ");
  p = -1;
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
      case FINGERPRINT_OK:
        Serial.println("Image taken");
        break;
      case FINGERPRINT_NOFINGER:
        break;
      case FINGERPRINT_PACKETRECIEVEERR:
        Serial.println("Communication error");
        return false;
      case FINGERPRINT_IMAGEFAIL:
        Serial.println("Imaging error");
        return false;
      default:
        Serial.println("Unknown error");
        return false;
    }
  }

  p = finger.image2Tz(2);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return false;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return false;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return false;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return false;
    default:
      Serial.println("Unknown error");
      return false;
  }

  Serial.print("Creating model for ID #");
  Serial.println(id);

  p = finger.createModel();
  if (p == FINGERPRINT_OK) {
    Serial.println("Prints matched!");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return false;
  } else if (p == FINGERPRINT_ENROLLMISMATCH) {
    Serial.println("Fingerprints did not match");
    return false;
  } else {
    Serial.println("Unknown error");
    return false;
  }

  Serial.print("ID #");
  Serial.println(id);
  p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    Serial.println("Stored!");
    lcd.setCursor(0,0);   
    lcd.print("Sidik Jari        ");
    lcd.setCursor(0,1);
    lcd.print("Terdaftarkan      ");
    delay(3000);
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return false;
  } else if (p == FINGERPRINT_BADLOCATION) {
    Serial.println("Could not store in that location");
    return false;
  } else if (p == FINGERPRINT_FLASHERR) {
    Serial.println("Error writing to flash");
    return false;
  } else {
    Serial.println("Unknown error");
    return false;
  }
  return true;
}
