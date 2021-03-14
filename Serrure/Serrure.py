import paho.mqtt.client as mqtt
from cryptography.fernet import Fernet
import json
import serial

with open('parametres.json', 'r') as f:
    p=json.load(f)
ip=p["ip"]
id_serrure=p["id_serrure"]
port =8883

class MQTTClient(mqtt.Client):
    def __init__(self,cname, ip, port, **kwargs):
        super(MQTTClient,self).__init__(cname,**kwargs)
        try:
            self.connect(ip, port=8883)
            self.subscribe(id_serrure, qos=2)
            self.encryption=self.ReadEncryptionKey()
            self.ser=serial.Serial('/dev/ttyACM0',9600)
        except Exception as e:
            print(e)
            exit(1)

    def on_message(self,client,userdata,message):
        sender,content=self.encryption.decrypt(message.payload).decode("utf-8").split(':')
        if sender!=id_serrure:
            if content=="UNLOCK":
                self.ser.write('1'.encode("utf-8"))
            elif content=="LOCK":
                self.ser.write('0'.encode("utf-8"))

    def ReadEncryptionKey(self):
        file =open('key.key','rb')
        key=file.read()
        file.close()
        return Fernet(key)
    
    def sendMessage(self,userid):
        m=self.encryption.encrypt("{}:{}".format(id_serrure,userid).encode())
        self.publish(id_serrure,m)
    
s=MQTTClient(id_serrure, ip, port)
s.loop_start()
while True:
    try:
        user=int(input("Matricule: "))
    except Exception as e:
        pass
    else:
        s.sendMessage(user)


   
