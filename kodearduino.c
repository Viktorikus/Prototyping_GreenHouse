#include <DHT.h>

#define DHTPIN 2        
#define DHTTYPE DHT11   
#define LDRPIN A0        
#define LEDPIN 13       

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(9600); 
  dht.begin();
  pinMode(LEDPIN, OUTPUT);
}

void loop() 
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  int lightLevel = analogRead(LDRPIN);
  bool LED_State = digitalRead(LEDPIN);

  if (lightLevel > 500) {
    digitalWrite(LEDPIN, HIGH); 
  } else {
    digitalWrite(LEDPIN, LOW); 
  }

  Serial.print("[");
  Serial.print(humidity);
  Serial.print(",");
  Serial.print(temperature);
  Serial.print(",");
  Serial.print(lightLevel);
  Serial.print(",");
  Serial.print(LED_State);
  Serial.println("]");

  delay(2000);