import ftplib
import os

host = "ftp.servillantaselpuente.com"
user = "admin@servillantaselpuente.com"
password = "Servi2026!"

directories_to_upload = [
    "public/assets/landing",
    "app/Views/desktop/landing",
    "app/Views/mobile/landing"
]

files_to_upload = [
    "app/Controllers/Desktop/PublicLandingController.php",
    "app/Controllers/Mobile/PublicLandingController.php",
    "config/routes_desktop.php",
    "config/routes_mobile.php",
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

def upload_directory(ftp, local_dir):
    for root, dirs, files in os.walk(local_dir):
        remote_root = '/' + root.replace('\\', '/')
        ensure_dir(ftp, remote_root)
        for file in files:
            local_path = os.path.join(root, file)
            remote_path = remote_root + '/' + file
            print(f"Uploading {local_path} -> {remote_path}")
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)

try:
    print(f"Connecting to {host}...")
    ftp = ftplib.FTP(host)
    ftp.login(user, password)
    
    for local_dir in directories_to_upload:
        if os.path.exists(local_dir):
            upload_directory(ftp, local_dir)

    for local_path in files_to_upload:
        if os.path.exists(local_path):
            remote_path = '/' + local_path
            print(f"Uploading {local_path} -> {remote_path}")
            ensure_dir(ftp, os.path.dirname(remote_path))
            with open(local_path, 'rb') as f:
                ftp.storbinary(f'STOR {remote_path}', f)
                
    ftp.quit()
    print("Sprint 3 deploy complete!")
except Exception as e:
    print(f"Error: {e}")
