char let= '5';
int Led=2;
void setup() {
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(Led, OUTPUT);
  Serial.begin(9600); // opens serial port, sets data rate to 9600 bps
}

 


void loop() {
  
  if (Serial.available() > 0) {
    let=Serial.read();
  
  if (let == '1'){
  digitalWrite(LED_BUILTIN, HIGH);
  digitalWrite(Led, HIGH);
    } 
          
  else if (let == '0'){            
   digitalWrite(LED_BUILTIN, LOW);
   digitalWrite(Led, LOW); 
        }
  }
}
