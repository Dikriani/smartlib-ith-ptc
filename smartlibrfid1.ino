#include <WiFi.h>
#include <FirebaseESP32.h>
#include <MFRC522.h>

// WiFi credentials
#define WIFI_SSID "TUMHARDI"
#define WIFI_PASSWORD "qwertyuiop"

// Firebase credentials
#define FIREBASE_HOST "https://smartlib-ith-default-rtdb.firebaseio.com/"
#define FIREBASE_AUTH "your_firebase_auth_token"

// Pin for RFID MFRC522
#define RST_PIN 5
#define SS_PIN 21

MFRC522 mfrc522(SS_PIN, RST_PIN);  // Create MFRC522 instance
FirebaseData firebaseData;

void setup() {
  Serial.begin(115200);

  // Connect to Wi-Fi
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");

  // Initialize Firebase
  Firebase.begin(FIREBASE_HOST, FIREBASE_AUTH);

  // Initialize RFID
  SPI.begin();
  mfrc522.PCD_Init();
  Serial.println("Scan a card...");
}

void loop() {
  // Check if a new card is present
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    String rfidValue = "";
    for (byte i = 0; i < mfrc522.uid.size; i++) {
      rfidValue += String(mfrc522.uid.uidByte[i], HEX);
    }
    Serial.print("RFID Value: ");
    Serial.println(rfidValue);

    // Fetch data from Firebase based on the RFID
    String path = "/pendaftaran";
    if (Firebase.getJSON(firebaseData, path)) {
      // Loop through the JSON response and find the matching RFID
      FirebaseJson jsonResponse = firebaseData.jsonData();
      FirebaseJsonData result;
      if (jsonResponse.search("rfid", result)) {
        if (result.stringValue() == rfidValue) {
          Serial.println("Matching RFID found in the database");
          // Here you can do further actions, like displaying the data on a screen
        }
      }
    } else {
      Serial.println("Error fetching data: " + firebaseData.errorReason());
    }
    
    delay(2000); // Delay to avoid multiple scans of the same card
  }
}
