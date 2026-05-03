import ftplib
import os

views = [
    'app/Views/desktop/landing/index.php',
    'app/Views/mobile/landing/index.php'
]

# 1. Update HTML files locally
for view in views:
    with open(view, 'r') as f:
        content = f.read()
    
    # Fix asset paths
    content = content.replace('/assets/landing/', '/public/assets/landing/')
    # Fix login route
    content = content.replace('href="/login"', 'href="/index.php?r=/login"')
    
    with open(view, 'w') as f:
        f.write(content)

print("Local views updated with correct paths.")

# 2. Upload to FTP
host = "ftp.servillantaselpuente.com"
user = "admin@servillantaselpuente.com"
password = "Servi2026!"

try:
    print(f"Connecting to {host}...")
    ftp = ftplib.FTP(host)
    ftp.login(user, password)
    
    for view in views:
        remote_path = '/' + view
        print(f"Uploading {view} -> {remote_path}")
        with open(view, 'rb') as f:
            ftp.storbinary(f'STOR {remote_path}', f)
            
    ftp.quit()
    print("Hotfix deploy complete!")
except Exception as e:
    print(f"Error: {e}")
