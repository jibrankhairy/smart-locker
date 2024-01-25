#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <SoftwareSerial.h>

const char* ssid = "KRIDA OXY";
const char* password = "vitoabel";

ESP8266WebServer server(80);
SoftwareSerial node(D1,D2);

void setup() {
  Serial.begin(9600);
  node.begin(9600);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Konek ke wifi...");
  }
  Serial.println("Connected to WiFi, IP: ");
  Serial.print(WiFi.localIP());

  server.on("/eksekusi-kode-A", HTTP_GET, [](){
    node.print("BLINK");
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(200, "application/json", "{\"status\": \"Kode A (Kedip) berhasil dijalankan\"}");
  });

  server.on("/eksekusi-kode-B", HTTP_GET, [](){
    for(int i=0;i<5000;i++){
      node.print("STOP");
    }
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(200, "application/json", "{\"status\": \"Kode B (tetap menyala) berhasil dijalankan\"}");
  });

  server.begin();
}

void loop() {
  server.handleClient();
}
