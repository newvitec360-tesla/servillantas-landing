---
description: Lanzar (Servillantas) - Merge develop a main y deploy FTP (merge)
---

# Lanzar (Merge & Deploy) Servillantas

Fusiona la rama `develop` en `main`, sube los cambios y despliega vía FTP al servidor de producción.

// turbo-all

1. Asegurar que estamos en develop y todo está commiteado:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git status
```

2. Cambiar a main y actualizar:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git checkout main; git pull origin main
```

3. Fusionar develop en main:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git merge develop -m "Merge develop into main for release"
```

4. Subir main a GitHub:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git push origin main
```

5. Deploy vía FTP — Leer credenciales del .env y sincronizar archivos al servidor:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; $envHash = @{}; Get-Content .env | ForEach-Object { if ($_ -match '^([^=]+)=(.*)$') { $envHash[$matches[1]] = $matches[2] } }; $ftpHost = $envHash['FTP_HOST']; $ftpUser = $envHash['FTP_USER']; $ftpPass = $envHash['FTP_PASS']; $ftpDir = $envHash['FTP_DIR']; $base = (Get-Location).Path; Get-ChildItem -Recurse -File | Where-Object { $_.FullName -notmatch '\\\.git\\' -and $_.FullName -notmatch '\\\.agent\\' -and $_.FullName -notmatch '\\\.agents\\' -and $_.FullName -notmatch '\\\.context\\' -and $_.FullName -notmatch '\\docker\\' -and $_.FullName -notmatch '\\storage\\logs\\' -and $_.Name -ne '.env' -and $_.Name -ne '.gitignore' -and $_.Name -ne 'Datos-FTP-DB.txt' -and $_.Name -ne 'docker-compose.yml' -and $_.Name -ne 'cookie.txt' -and $_.Name -ne 'cookie_test.txt' } | ForEach-Object { $relativePath = $_.FullName.Replace($base, '').TrimStart('\').Replace('\', '/'); $encodedPath = $relativePath -replace ' ', '%20'; $remotePath = "ftp://${ftpHost}${ftpDir}${encodedPath}"; Write-Host "Uploading: $relativePath"; curl.exe --ftp-ssl --insecure --ftp-create-dirs -T $_.FullName -u "${ftpUser}:${ftpPass}" $remotePath --silent --show-error 2>&1; if ($LASTEXITCODE -eq 0) { Write-Host "  OK" -ForegroundColor Green } else { Write-Host "  FAILED" -ForegroundColor Red } }
```

6. Volver a develop para seguir trabajando:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git checkout develop
```

7. Verificar deploy con HEAD request:
```powershell
curl.exe -I https://servillantaselpuente.com/
```
