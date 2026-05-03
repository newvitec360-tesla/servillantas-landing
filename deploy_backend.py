import ftplib
import sys
import os

files_to_upload = [
    ("database/migrations/004_landing_tables.sql", "/database/migrations/004_landing_tables.sql"),
    ("database/schema.sql", "/database/schema.sql"),
    ("run_landing_migration.php", "/run_landing_migration.php"),
    ("app/Models/LandingPage.php", "/app/Models/LandingPage.php"),
    ("app/Models/MediaAsset.php", "/app/Models/MediaAsset.php"),
    ("app/Controllers/Desktop/LandingApiController.php", "/app/Controllers/Desktop/LandingApiController.php"),
    ("config/routes_desktop.php", "/config/routes_desktop.php"),
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
        # Make dir if doesn't exist
        remote_dir = os.path.dirname(remote_path)
        try:
            ftp.mkd(remote_dir)
        except:
            pass # Usually exists or we don't care
        
        with open(local_path, 'rb') as f:
            ftp.storbinary(f'STOR {remote_path}', f)
            
    ftp.quit()
    print("Backend deploy complete!")
except Exception as e:
    print(f"Error: {e}")
