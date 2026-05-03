import ftplib
import os

host = "ftp.servillantaselpuente.com"
user = "admin@servillantaselpuente.com"
password = "Servi2026!"

files_to_upload = [
    "app/Middlewares/AuthMiddleware.php",
    "app/Controllers/Desktop/AuthController.php",
    "app/Controllers/Desktop/AdminLandingController.php",
    "app/Controllers/Desktop/ConfiguracionController.php",
    "app/Views/desktop/admin/landing_gestor.php",
    "config/routes_desktop.php",
    ".htaccess"
]

def ensure_dir(ftp, remote_dir):
    dirs = remote_dir.split('/')
    current = ''
    for d in dirs:
        if not d: continue
        current += '/' + d
        try:
            ftp.mkd(current)
        except ftplib.error_perm:
            pass

try:
    print(f"Connecting to {host}...")
    ftp = ftplib.FTP(host)
    ftp.login(user, password)
    
    for local_path in files_to_upload:
        if os.path.exists(local_path):
            remote_path = '/' + local_path
            print(f"Uploading {local_path} -> {remote_path}")
            ensure_dir(ftp, os.path.dirname(remote_path))
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
                
    ftp.quit()
    print("Auth decouple deploy complete!")
except Exception as e:
    print(f"Error: {e}")
