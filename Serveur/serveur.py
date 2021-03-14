from database import *
from datetime import datetime
import paho.mqtt.client as mqttc
from cryptography.fernet import Fernet
import time
import threading
import json
from apscheduler.schedulers.background import BackgroundScheduler
lock=threading.Lock()
with open('parametres.json', 'r') as f:
    p=json.load(f)
ip=p["ip"]
port =8883

class Serveur():
    __instance=None
    @staticmethod
    def getInstance():
        if Serveur.__instance==None:
            Serveur().serveur.loop_start()
        return Serveur.__instance
    
    def __init__(self):
        if Serveur.__instance != None:
            raise Exception("This class is a singleton!")
        else:
            Serveur.__instance = self
        self.name       = "Serveur"
        self.db         = db_wifi_locks()
        self.encryption = self.ReadEncryptionKey()
        self.serveur    = mqttc.Client(client_id=self.name)
        self.serveur.connected_flag=False
        self.sched = BackgroundScheduler(daemon=True)
        self.sched.start()
        try:
            self.serveur.connect(ip,8883)
            self.serveur.on_message = self.on_message
            self.serveur.on_connect = self.on_connect
            self.serveur.on_disconnect = self.on_disconnect
            self._subscribe_to_doors()
        except Exception as e:
            print(e)
            self.serveur.disconnect()
        self.serveur.subscribe("administrateur",qos=2)
        self.serveur.subscribe("administrateurSubPorte",qos=2)
        self.serveur.subscribe("administrateurGroupe",qos=2)
        self.serveur.subscribe("administrateurCommande",qos=2)
        
    def subscribe_porte(self,CodePorte):
        self.serveur.subscribe(CodePorte,qos=2)
        
    def _subscribe_to_doors(self):
        doors=self.db.get_all_doors()
        for door in doors:
            self.subscribe_porte(door.CodePorte)
            
    def _add_existing_commands(self):
        commands=self.db.get_all_commands()
        admin=self.db.get_administrateur()
        for cmd in commands:
            self.serveur.add_commande(admin,cmd.IDCmd)

            
    def add_commande(self,user,IDCmd):#option cron ou date
        commande=self.db.get_commande(IDCmd)
        if commande.Repetition==0:#once
            self.sched.add_job(self.manip_grp,args=[commande.CodeGrp,user,commande.Commande,commande],id=IDCmd,trigger='date',run_date=commande.DateExec,replace_existing=True)
        elif commande.Repetition==1:#evrey day
            self.sched.add_job(self.manip_grp,args=[commande.CodeGrp,user,commande.Commande],id=IDCmd,trigger='cron',minute=commande.DateExec.minute,hour=commande.DateExec.hour,replace_existing=True)

    def delete_commande(self,IDCmd):
        self.sched.remove_job(IDCmd)    
            
    def ReadEncryptionKey(self):
        file = open('key.key', 'rb')
        key = file.read()
        file.close()
        return Fernet(key)
    
    def GetDateTime(self):
        now=datetime.now()
        dt = now.strftime("%Y-%m-%d %H:%M:%S")
        return dt
    
    def manip_door(self,door,user,action):
        dt=self.GetDateTime()
        if action==0:
            Saction="LOCK"
        else:
            Saction="UNLOCK"
        m=self.encryption.encrypt("{}:{}".format(self.name,Saction).encode())
        self.serveur.publish(door,m)
        self.db.db_ouv(door,user,action,dt)
        
    def manip_grp(self,grp,user,action,cmd_once=None):
        doors=self.db.get_doors_grp(grp)
        for door in doors:
            if action!=self.db.get_etat_serrure(door[0]):
                self.manip_door(door[0],user,action)
        if cmd_once!=None:
            self.db.deleterow(cmd_once)
                    
    def on_connect(self,client,userdata,flags,rc):
        lock.acquire()
    
    def on_disconnect(self,client,userdata,rc):
        lock.release()
    
    def on_message(self,client, userdata, message):
        sender,content=str(self.encryption.decrypt(message.payload).decode("utf-8")).split(":")
        print(sender,content)
        if  message.topic=="administrateurCommande":
            content=content.split(',')
            if sender=="create":
                self.add_commande(content[0],content[1])
            elif sender=="delete":
                self.delete_commande(content[1])
        elif message.topic=="administrateurSubPorte":
            print(222)
            self.subscribe_porte(content)
        elif message.topic=="administrateurGroupe":
            content=content.split(',')
            self.manip_grp(sender,content[0],int(content[1]))
        elif sender!=self.name:
            if self.db.has_privileges(content,sender):
                a=self.db.get_etat_serrure(sender)
                self.manip_door(sender,content,int(not a))
                
s = Serveur.getInstance()
time.sleep(5)
lock.acquire()
lock.release()
  
