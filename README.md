# Servillantas El Puente — Landing Page

Sitio web comercial de **Servillantas El Puente**, servicio de llantas 24 horas en Bucaramanga.

## Dominio
- **Landing:** `https://servillantaselpuente.com`
- **Cartera (separada):** `https://cartera.servillantaselpuente.com` (repo: servillantas-cartera)

## Estructura
```
├── index.html           ← Redirect a landing/
├── .htaccess            ← HTTPS, seguridad, redirect
├── landing/
│   ├── index.html       ← Router desktop/mobile automático
│   ├── desktop/         ← Landing escritorio (.php + .css)
│   ├── mobile/          ← Landing móvil (.php + .css)
│   ├── admin/           ← CMS gestor de contenido
│   └── assets/          ← Imágenes, JS, datos
├── public/assets/       ← CSS/IMG servidos por vistas
└── storage/logs/        ← Logs
```

## Deploy
```powershell
.\deploy.ps1
```

## FTP
- **User:** `admin@servillantaselpuente.com`
- **Server:** `ftp.servillantaselpuente.com`
- **Target:** `public_html/`
