import ftplib
import sys
import os

files_to_upload = [
    ("app/Controllers/Desktop/ConfiguracionController.php", "/app/Controllers/Desktop/ConfiguracionController.php"),
    ("app/Views/Desktop/configuracion/landing_gestor.php", "/app/Views/Desktop/configuracion/landing_gestor.php"),
    ("config/routes_desktop.php", "/config/routes_desktop.php"),
    ("public/assets/css/landing_admin.css", "/public/assets/css/landing_admin.css"),
    ("public/assets/js/landing_admin.js", "/public/assets/js/landing_admin.js"),
]

host = "ftp.servillantaselpuente.com"
user = "admin@servillantaselpuente.com"
password = "Servi2026!"

try:
    print(f"Connecting to {host}...")
    ftp = ftplib.FTP(host)
    ftp.login(user, password)
    
    for local_path, remote_path in files_to_upload:
        print(f"Uploading {local_path} -> {remote_path}")
        remote_dir = os.path.dirname(remote_path)
        
        # Traverse and create directories if they don't exist
        dirs = remote_dir.split('/')
        current_dir = ''
        for d in dirs:
            if not d: continue
            current_dir += '/' + d
            try:
                ftp.mkd(current_dir)
            except ftplib.error_perm:
                pass # Directory likely already exists

        with open(local_path, 'rb') as f:
            ftp.storbinary(f'STOR {remote_path}', f)
            
    ftp.quit()
    print("Sprint 2 deploy complete!")
except Exception as e:
    print(f"Error: {e}")
