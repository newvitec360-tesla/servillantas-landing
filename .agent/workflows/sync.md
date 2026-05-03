---
description: Sincronizar (Servillantas) - Traer cambios desde GitHub (pull)
---

# Sincronizar (Pull) Servillantas

Trae los últimos cambios del repositorio remoto a la rama actual.

// turbo-all

1. Verificar estado actual:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git status; git branch
```

2. Descargar cambios:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git pull origin develop
```

3. Confirmar estado:
```powershell
cd "c:\OtroDisco\Newvitec\Antigravity\servillantas"; git log -n 5 --oneline
```
