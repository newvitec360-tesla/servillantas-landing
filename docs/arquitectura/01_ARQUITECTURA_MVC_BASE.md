# Arquitectura MVC base

## 1. Principio general

La plataforma debe compartir el corazón del negocio, pero separar la experiencia visual por canal.

```txt
Dominio compartido
├── Models
├── Services
├── Middleware
├── Database
├── Auditoría
└── Reglas de negocio

Presentación desktop
├── routes_desktop.php
├── Controllers/Desktop
├── Views/desktop
├── assets/desktop/css
└── assets/desktop/js

Presentación mobile
├── routes_mobile.php
├── Controllers/Mobile
├── Views/mobile
├── assets/mobile/css
└── assets/mobile/js
```

## 2. Qué se puede compartir

Se permite compartir:

- modelos de base de datos;
- servicios de auditoría;
- servicios de cálculo de riesgo;
- validadores puros;
- repositorios;
- permisos;
- reglas parametrizables;
- integraciones.

## 3. Qué NO se debe compartir

No se debe compartir entre desktop y mobile:

- layouts;
- componentes HTML de presentación;
- CSS visual;
- JS de interfaz;
- navegación;
- medidas, proporciones o grillas;
- comportamiento de tarjetas, modales o menús.

## 4. Flujo de request

```txt
Usuario entra a /desktop/index.php?r=/clientes
→ carga bootstrap
→ carga rutas desktop
→ Router encuentra controlador Desktop\ClientesController
→ controlador renderiza app/Views/desktop/clientes/index.php
→ layout desktop aplica CSS/JS desktop
```

```txt
Usuario entra a /mobile/index.php?r=/clientes
→ carga bootstrap
→ carga rutas mobile
→ Router encuentra controlador Mobile\ClientesController
→ controlador renderiza app/Views/mobile/clientes/index.php
→ layout mobile aplica CSS/JS mobile
```

## 5. Evolución recomendada

Para producción, el equipo puede evolucionar esta base hacia:

- repositorios por entidad;
- DTOs para requests;
- validadores por módulo;
- service layer más robusto;
- middleware real de autenticación;
- policies por permiso;
- API REST o endpoints JSON para frontend enriquecido;
- cola de trabajos para campañas y ETL.
