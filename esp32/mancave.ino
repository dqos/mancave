#include <WiFi.h>
#include <ArduinoJson.h>
#include <HTTPClient.h>

// The ID number of the device, this must be unique and is returned by the Mancave_Controller.
String id = "675200000";
const char* ssid = "network";
const char* password = "password";
long lastTime;
long localTime;
long currentTime;
float state;
float pstate;

// Define the PINS to the relay. We need two relays for a rolling shutter. Up/down!
#define RELAY1 12
#define RELAY2 14

// This function returns JSON data for the devices based on ID from the API.
// Replace YOURURL with your domain or LAN IP.
void getJSON() {

HTTPClient http;
http.begin("http://YOURURL/mancave/api.php?id=" + id);
int httpCode = http.GET();
String payload;

if (httpCode > 0) {
payload = http.getString();
} else {
Serial.println("Error on HTTP request");
return;
}
http.end();

DynamicJsonBuffer  jsonBuffer(400);
JsonObject& root = jsonBuffer.parseObject(payload);

if (!root.success()) {
Serial.println("parseObject() failed");
return;
}

lastTime = root["time"];
currentTime = root["unix"];
state = root["state"];
pstate = root["pstate"];
}

// This function executes the up/down process with provided times.
// 32 seconds = 100% closed
// 25 seconds = 99% closed
// 11 seconds = 50% closed
void rolluikState(float procent) {
digitalWrite(RELAY1,HIGH);
digitalWrite(RELAY2,HIGH);
delay(200);
Serial.println(procent);
float calc;
float netto;

if (procent == 100) {
  Serial.println("CLOSE");
  digitalWrite(RELAY1,LOW);
  delay(32000); // 32s * 1000
} else if (procent == 0) {
  Serial.println("OPEN");
  digitalWrite(RELAY2,LOW);
  delay(32000);
} else {
  
  // We are doing some math here, to calculate the amount of time based on percentage.
  if (procent >= pstate) {
  netto = procent-pstate;
  calc = netto / 100 * 25 * 1000;
  digitalWrite(RELAY1,LOW);
  delay(100);
  delay(calc);
  } else if (procent < pstate) {
  netto = pstate-procent;
  calc = netto / 100 * 25 * 1000;
  digitalWrite(RELAY2,LOW);
  delay(100);
  delay(calc);
  }

  Serial.println(netto);
  Serial.println(calc);
}

digitalWrite(RELAY1,HIGH);
digitalWrite(RELAY2,HIGH);
delay(3000);
}

void setup() {
pinMode(RELAY1, OUTPUT);
pinMode(RELAY2, OUTPUT);
digitalWrite(RELAY1,HIGH);
digitalWrite(RELAY2,HIGH);

// This is not tested yet, it allows you to change the hostname of the ESP32:
//tcpip_adapter_set_hostname(TCPIP_ADAPTER_IF_STA, "rolluik1");

Serial.begin(115200);
delay(10);
Serial.println();
Serial.println();
Serial.print("Connecting to ");
Serial.println(ssid);

WiFi.begin(ssid, password);

while (WiFi.status() != WL_CONNECTED) {
delay(500);
Serial.print(".");
}

Serial.println("");
Serial.println("WiFi connected");
Serial.println("IP address: ");
Serial.println(WiFi.localIP());

}

void loop() {
digitalWrite(RELAY1,HIGH);
digitalWrite(RELAY2,HIGH);

if ((WiFi.status() == WL_CONNECTED)) {

getJSON();

// If the request is within 3 seconds of the timestamp, we actually do something, else do nothing.
if ((lastTime + 3) > currentTime) {
Serial.println("Doe iets!");
rolluikState(state);
} else {
Serial.println("Expired...");
}

delay(1000);

}

}
