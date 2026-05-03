$ErrorActionPreference = 'Stop'

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "🚀 Iniciando Deploy - Servillantas Landing" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 2. Despliegue FTP
Write-Host "Subiendo archivos vía FTP..." -ForegroundColor Yellow

$ftpServer = "ftp.servillantaselpuente.com"
$ftpUser = "admin@servillantaselpuente.com"
$ftpPass = "Servi2026!"

# Use git to get the list of tracked files to avoid uploading node_modules, .git, etc.
$files = git ls-files

foreach ($file in $files) {
    # Skip environment file or any secret
    if ($file -match "\.env$") { continue }
    
    $localPath = $file
    # Replace backslashes with forward slashes for FTP
    $remotePath = $file -replace "\\", "/"
    
    Write-Host "Uploading $localPath..."
    # Execute curl for each file
    curl.exe --ftp-ssl --insecure --ftp-create-dirs -u "$($ftpUser):$($ftpPass)" -T "$localPath" "ftp://$($ftpServer)/$($remotePath)" --silent
}

Write-Host "✅ Deploy FTP completado" -ForegroundColor Green

Write-Host "Verificando en producción..." -ForegroundColor Yellow
$response = curl.exe -I -s "https://servillantaselpuente.com/"
if ($response -match "HTTP/.* (200|301|302)") {
    Write-Host "✅ Verificación exitosa" -ForegroundColor Green
} else {
    Write-Host "⚠️ Verificación fallida o servidor no responde con 200/301/302." -ForegroundColor Red
}
