---
description: Guardar (Servillantas) - Subir cambios a GitHub (push)
---

# Guardar (Push) Servillantas

Guarda y sube todos los cambios al repositorio remoto en GitHub (rama develop).

// turbo-all

1. Verificar qué archivos cambiaron:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git status
```

2. Agregar todos los cambios:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git add -A
```

3. Crear el commit (pedir al usuario el mensaje si no lo proporcionó):
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git commit -m "MENSAJE_DEL_USUARIO"
```

4. Subir a GitHub:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git push origin develop
```

5. Confirmar estado:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git log -n 3 --oneline
```
