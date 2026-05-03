import ftplib
import os

host = "ftp.servillantaselpuente.com"
user = "admin@servillantaselpuente.com"
password = "Servi2026!"

files_to_upload = [
    "app/Models/LandingPage.php"
]

try:
    print(f"Connecting to {host}...")
    ftp = ftplib.FTP(host)
    ftp.login(user, password)
    
    for local_path in files_to_upload:
        if os.path.exists(local_path):
            remote_path = '/' + local_path
            print(f"Uploading {local_path} -> {remote_path}")
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
                
    ftp.quit()
    print("Emergency Hotfix deploy complete! Site should be back online.")
except Exception as e:
    print(f"Error: {e}")
