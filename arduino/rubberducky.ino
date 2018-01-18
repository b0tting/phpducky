  #include <Keyboard.h>

int val = 0;    // variable
int lightstart = 0;
bool button_press = false;

void setup() {
  Serial.begin(9600);      // open the serial port at 9600 bps:    
  pinMode(13, OUTPUT);     
  pinMode(8, INPUT);
  Keyboard.begin();
}

void betterPrintln(String line, int mydelay = 50) {
        Serial.println("Keyboard emulating: " + line);
        Keyboard.println(line);
        Keyboard.press(KEY_RETURN);
        Keyboard.releaseAll();
        delay(mydelay);  
}

void loop() {         
  val = digitalRead(8);
  if(val == HIGH && !button_press) {
      button_press = true;                                                        
      digitalWrite(13, HIGH);
  } else if (button_press && val == LOW) {
    Serial.write("Got a button press!");
      delay(100);
      val = digitalRead(8);
      if(val == LOW) {
        Keyboard.press(KEY_LEFT_GUI);
        Keyboard.press('r');
        delay(50);
        Keyboard.releaseAll();
        delay(20);
        betterPrintln("powershell", 300);
        betterPrintln("echo If you can read this, you managed to either beat the USB Rubber Ducky, or you are running a non-windows OS");
        betterPrintln("echo And if that last one is the case, consider yourself lucky, this could have ended much worse!");
        betterPrintln("cls");
        betterPrintln("$IE=new-object -com internetexplorer.application");
        // Spaties moeten blijven om toetsenbordinstellingen te omzeilen die dubbele quotes gebruiken voor trema
        betterPrintln("$IE.navigate2(\" https://www.neverwinternights2.nl/moeilijkestring\" )");
        betterPrintln("$IE.visible=$true");
        betterPrintln("exit");
        digitalWrite(13, LOW);
        button_press = false;
    }
  }
} 

