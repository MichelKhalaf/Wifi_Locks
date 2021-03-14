import paho.mqtt.client as mqtt
from cryptography.fernet import Fernet
import sys
import json

id_admin="administrateur"
with open('parametres.json', 'r') as f:
    p=json.load(f)
ip=p["ip"]
port =8883

class MQTTClient(mqtt.Client):
    def __init__(self,cname,ip,port,**kwargs):
        super(MQTTClient,self).__init__(cname,**kwargs)
        self.connect(ip,port)
        self.subscribe(cname,qos=2)
        self.subscribe(cname+"Groupe",qos=2)
        self.subscribe(cname+"Commande",qos=2)
        self.encryption=self.ReadEncryptionKey()
        
    def ReadEncryptionKey(self):
        file =open('key.key','rb')
        key=file.read()
        file.close()
        return Fernet(key)
    
    def creationPorte(self,serrure):
        m=self.encryption.encrypt("sub:{}".format(serrure).encode())
        self.publish(id_admin+"SubPorte",m) 
        
    def sendMessagePorte(self,serrure,user):
        m=self.encryption.encrypt("{}:{}".format(serrure,user).encode())
        self.publish(id_admin,m)
        
    def sendMessageGroupe(self,groupe,user,action):
        m=self.encryption.encrypt("{}:{},{}".format(groupe,user,action).encode())
        self.publish(id_admin+"Groupe",m)
        
    def sendMessageCommande(self,action,user,Id):
         m=self.encryption.encrypt("{}:{},{}".format(action,user,Id).encode())
         self.publish(id_admin+"Commande",m)
        
    
s=MQTTClient(id_admin,ip,port)

if len(sys.argv)==4 and sys.argv[1]=="porte":#admin porte user ID 
    s.sendMessagePorte(sys.argv[3], sys.argv[2])

elif len(sys.argv)==3 and sys.argv[1]=="porte":#admin porte ID
    s.creationPorte(sys.argv[2])
    
elif len(sys.argv)==5:
    if (sys.argv[1]=="groupe"):#admin groupe ID user action
        s.sendMessageGroupe(sys.argv[2],sys.argv[3],sys.argv[4])
    elif (sys.argv[1]=="commande"):#admin commande action user ID
        s.sendMessageCommande(sys.argv[2],sys.argv[3],sys.argv[4])


    

