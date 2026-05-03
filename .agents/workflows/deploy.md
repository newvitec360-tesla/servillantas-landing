# Deploy Workflow - Servillantas MVC

Este script despliega los cambios en el repositorio GitHub y los sincroniza vía FTP al servidor de producción.

## Requisitos Previos
- Tener `git` instalado.
- Tener `curl` instalado y disponible en el PATH.
- Asegurarse de que el repositorio de GitHub exista y tenga el remoto configurado como `origin`.

## Script de Deploy (PowerShell)

```powershell
# Script de Despliegue - deploy.ps1
$ErrorActionPreference = 'Stop'

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "🚀 Iniciando Deploy - Servillantas MVC" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# 1. Sincronización con GitHub
Write-Host "1. Sincronizando con GitHub..." -ForegroundColor Yellow
$commitMessage = Read-Host "Ingresa el mensaje del commit (default: 'Auto-deploy update')"
if ([string]::IsNullOrWhiteSpace($commitMessage)) {
    $commitMessage = "Auto-deploy update"
}

git add .
git commit -m "$commitMessage"
git push origin main
Write-Host "✅ GitHub Sincronizado" -ForegroundColor Green

# 2. Despliegue FTP
Write-Host "2. Subiendo archivos vía FTP..." -ForegroundColor Yellow

$ftpServer = "ftp.servillantaselpuente.com"
$ftpUser = "admin@servillantaselpuente.com"
$ftpPass = "Servi2026!"

# Subir archivos ignorando .git y node_modules y storage/logs
# NOTA: En un flujo real avanzado usaríamos ncftp o git-ftp.
# Para este MVP en PowerShell usamos curl.

# Comando curl para sincronizar (requiere un script más complejo para recorrer directorios,
# recomendamos usar winscp en consola o git-ftp para proyectos grandes. 
# Si el usuario ejecuta este deploy.md lo convertiremos a un script robusto o recomendaremos GitHub Actions).
```

## GitHub Actions (Recomendado)
Para proyectos Enterprise, lo ideal es que al hacer push a la rama `main`, GitHub Actions se encargue del FTP usando la acción `SamKirkland/FTP-Deploy-Action`. 

### .github/workflows/main.yml
```yaml
name: Deploy via FTP
on:
  push:
    branches:
      - main
jobs:
  deploy:
    name: Deploy
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v3
    - name: Sync FTP
      uses: SamKirkland/FTP-Deploy-Action@v4.3.4
      with:
        server: ftp.servillantaselpuente.com
        username: admin@servillantaselpuente.com
        password: ${{ secrets.FTP_PASSWORD }}
```
