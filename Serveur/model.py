from sqlalchemy import Column, Integer, SmallInteger, String, ForeignKey, DateTime
from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.ext.automap import automap_base
from sqlalchemy.orm import Session, relationship
import datetime

Base = declarative_base()

class batiment(Base):
    __tablename__="batiments"
    NumBat=Column(Integer, primary_key=True)
    NomBat=Column(String(128),default=None)

    def __repr__(self):
        return "batiment(NumBat={},NomBat={})".format(self.NumBat,self.NomBat)
    
class porte(Base):
    __tablename__="portes"
    CodePorte=Column(String(16),primary_key=True)
    Nature=Column(String(128),nullable=False)
    NumBat=Column(Integer,ForeignKey('batiments.NumBat'))
    EtatPorte=Column(SmallInteger,nullable=False)
    EtatSerrure=Column(SmallInteger,nullable=False)
    Etage=Column(Integer,default=None)

    def __repr__(self):
        return "porte(CodePorte={},Nature={}, NumBat={},EtatPorte={},EtatSerrure={},Etage={})".format(self.CodePorte,self.Nature,self.NumBat,self.EtatPorte,self.EtatSerrure,self.Etage)
    
class groupe(Base):
    __tablename__="groupes"
    CodeGrp=Column(Integer,nullable=False,autoincrement=True,primary_key=True)
    NomGrp=Column(String(64),default=None)
    Descriptif=(Column(String(64),default=None))

    def __repr__(self):
        return "groupe(CodeGrp={},NomGrp={},Descriptif={})".format(self.CodeGrp,self.NomGrp,self.Descriptif)
    
class utilisateur(Base):
    __tablename__="utilisateurs"
    Matricule = Column(Integer, primary_key=True)
    Nom=Column(String(32),nullable=False)
    Prenom=Column(String(32),nullable=False)
    Fonction=Column(String(128),default=None)

    def __repr__(self):
        return "utilisateur(Matricule={},Nom={},Prenom={},Fonction={})".format(self.Matricule,self.Nom,self.Prenom,self.Fonction)
    
class grp_porte(Base):
    __tablename__ = "appartenancegrp"
    Groupe = Column(Integer, ForeignKey('groupes.CodeGrp'), primary_key=True)
    Porte = Column(String(16), ForeignKey('portes.CodePorte'), primary_key=True)
    
class privilege(Base):
    __tablename__ = "privileges"
    CodeGrp = Column(Integer, ForeignKey('groupes.CodeGrp'), primary_key=True)
    Matricule = Column(Integer, ForeignKey('utilisateurs.Matricule'), primary_key=True)
    
class manipulation(Base):
    __tablename__="manipulation"
    IDOuv=Column(Integer,nullable=False,autoincrement=True,primary_key=True)
    Matricule = Column(Integer, ForeignKey('utilisateurs.Matricule'))
    Action=Column(SmallInteger,nullable=False)
    CodePorte=Column(String(16),ForeignKey('portes.CodePorte'))
    
class historique(Base):
    __tablename__="historique"
    Date=Column(DateTime,nullable=False,primary_key=True)
    Utilisateur = Column(Integer, ForeignKey('utilisateurs.Matricule'),primary_key=True)
    Action=Column(SmallInteger,nullable=False)
    Porte=Column(String(16),ForeignKey('portes.CodePorte'),primary_key=True)
    
class commande(Base):
    __tablename__="commandes"
    IDCmd=Column(Integer,nullable=False,autoincrement=True,primary_key=True)
    NomCmd=Column(String(64),default=None)
    DescCmd=Column(String(256),default=None)
    Commande=Column(SmallInteger,nullable=False)
    CodeGrp=Column(Integer, ForeignKey('groupes.CodeGrp'),nullable=False)
    DateExec=Column(DateTime,nullable=False)
    Repetition=Column(Integer,nullable=False,default=1)



        
        
    

