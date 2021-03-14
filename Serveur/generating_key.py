from cryptography.fernet import Fernet
key = Fernet.generate_key()
file = open('key.key', 'wb')
file.write(key) 
file.close()
file = open('key.key', 'rb')
key = file.read() # The key will be type bytes
file.close()
f = Fernet(key)
