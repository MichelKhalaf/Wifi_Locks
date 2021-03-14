from sqlalchemy.orm import sessionmaker
from model import *

class db_wifi_locks:
    def __init__(self):
        try:
            engine = create_engine('mysql+pymysql://root:@localhost:3306/wifi_locks')
            self.Session = sessionmaker(bind=engine)
        except Exception as e:
            print("Error:",e)
            
    def has_privileges(self,user,door):
        session = self.Session()
        try:
            query=session.query(privilege).join(grp_porte,grp_porte.Groupe==privilege.CodeGrp).filter(grp_porte.Porte==door).\
                   filter(privilege.Matricule==user).all()
        except Exception as e:
            print("Error:",e)
            session.close()
        if query:
            session.close()
            return True
        session.close()
        return False

    def addrow(self,instance):
        session=self.Session()
        try:
            session.add(instance)
            session.commit()
        except Exception as e:
            print("Error:",e)
            session.rollback()
        finally:
            session.close()
            
    def deleterow(self,instance):
        session=self.Session()
        try:
            session.delete(instance)
            session.commit()
        except Exception as e:
            print("Error:",e)
            session.rollback()
        finally:
            session.close()
    def get_all_commands(self):
        try:
            session = self.Session()
            commands=session.query(commande).all()
            return commands
        except Exception as e:
            print("Error:",e)
        finally:
            session.close()
            
    def get_all_doors(self):
        try:
            session = self.Session()
            doors=session.query(porte).all()
            return doors
        except Exception as e:
            print("Error:",e)
        finally:
            session.close()
            
    def get_etat_serrure(self,door):
        try:
            session=self.Session()
            p= session.query(porte).get(door).EtatSerrure
            return p
        except Exception as e :
            print("Error:",e)
        finally:
            session.close()
        
    def get_doors_grp(self,grp):
        try:
            session=self.Session()
            p= session.query(grp_porte.Porte).filter(grp_porte.Groupe==grp).all()
            return p
        except Exception as e :
            print("Error:",e)
        finally:
            session.close()
            
    def get_commande(self,IDCmd):
        try:
            session=self.Session()
            p= session.query(commande).get(IDCmd)
            return p
        except Exception as e :
            print("Error:",e)
        finally:
            session.close()
            
    def get_administrateur(self):
        try:
            session=self.Session()
            p= session.query(commande).filter(fonction="administrateur").all()
            return p
        except Exception as e :
            print("Error:",e)
        finally:
            session.close()

    def db_ouv(self,door,utilisateur,action,date):
        try:
            session=self.Session()
            p= session.query(porte).get(door)
            p.EtatSerrure=action
            session.add(manipulation(Matricule=utilisateur,CodePorte=door,Action=action))
            session.add(historique(Date=date,Utilisateur=utilisateur,Porte=door,Action=action))
            session.commit()
        except Exception as e :
            print("Error:",e)
            session.rollback()
        finally:
            session.close()
            
