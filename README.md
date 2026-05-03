# Servillantas El Puente — Proyecto base MVC

Base técnica y visual para iniciar el desarrollo de la **Plataforma Integral de Gestión de Cartera, Crédito, Recaudo y Analítica** de Servillantas.

Este paquete no es la plataforma final. Es un **esqueleto funcional de arranque** para que el equipo de desarrollo y el equipo UX/UI trabajen con orden, manteniendo la fidelidad visual del demo aprobado y respetando una regla central:

> **La versión escritorio y la versión móvil deben vivir separadas e independientes.**

---

## Qué incluye este ZIP

```txt
servillantas_mvc_base/
├── app/                         # Núcleo MVC PHP
│   ├── Core/                    # Router, Controller, View, Database
│   ├── Controllers/             # Controladores separados por Desktop / Mobile
│   ├── Models/                  # Modelos base del dominio
│   ├── Services/                # Servicios de negocio: scoring, auditoría, ETL
│   └── Views/                   # Vistas separadas desktop y mobile
├── config/                      # Configuración, rutas, permisos, bootstrap
├── database/                    # Schema SQL y semillas demo
├── public/                      # Entrada pública del proyecto
│   ├── desktop/                 # Front controller exclusivo escritorio
│   ├── mobile/                  # Front controller exclusivo móvil
│   ├── assets/desktop/          # CSS/JS exclusivos escritorio
│   ├── assets/mobile/           # CSS/JS exclusivos móvil
│   └── assets/brand/            # Logo y recursos de marca
├── docs/                        # Lineamientos para desarrollo, UX/UI y arquitectura
├── prototypes/reference/        # Login y demo HTML originales como referencia visual
├── resources/                   # Design system y backlog base
├── docker/                      # Dockerfile base
├── docker-compose.yml           # Entorno local opcional
└── .env.example                 # Variables de entorno sugeridas
```

---

## Cómo correrlo rápido

### Opción A — PHP local

```bash
php -S localhost:8000 -t public
```

Abrir:

```txt
http://localhost:8000/login.php
http://localhost:8000/desktop/index.php
http://localhost:8000/mobile/index.php
```

### Opción B — Docker

```bash
docker compose up --build
```

Abrir:

```txt
http://localhost:8080/login.php
http://localhost:8080/desktop/index.php
http://localhost:8080/mobile/index.php
```

---

## Regla de oro: independencia visual

El equipo puede compartir **modelo de datos, servicios de negocio, auditoría, seguridad y reglas**, pero no debe mezclar la capa visual.

| Elemento | Desktop | Mobile |
|---|---|---|
| Controladores de presentación | `app/Controllers/Desktop` | `app/Controllers/Mobile` |
| Vistas | `app/Views/desktop` | `app/Views/mobile` |
| CSS | `public/assets/desktop/css` | `public/assets/mobile/css` |
| JS | `public/assets/desktop/js` | `public/assets/mobile/js` |
| Rutas | `config/routes_desktop.php` | `config/routes_mobile.php` |
| Layout | `desktop/layouts/app.php` | `mobile/layouts/app.php` |

**Prohibido:** resolver mobile solo con media queries del desktop. El mobile debe tener su propia estructura, proporciones, navegación, tarjetas, UX y pruebas.

---

## Módulos base contemplados

- Autenticación y roles.
- Carga y validación de Excel.
- Maestro de clientes.
- Cartera / obligaciones.
- Soporte documental y ubicación física del expediente.
- Gestión de cobranza.
- Campañas y comunicaciones.
- Recaudo y pagos.
- Dashboard y analítica.
- Parametrización.
- Auditoría y trazabilidad.
- Portal / landing del deudor.

---

## Archivos clave para revisar primero

1. `docs/00_INDICE_EJECUTIVO.md`
2. `docs/arquitectura/01_ARQUITECTURA_MVC_BASE.md`
3. `docs/ux-ui/02_REGLAS_FIDELIDAD_VISUAL.md`
4. `docs/desarrollo/03_BACKLOG_TECNICO_POR_FASES.md`
5. `database/schema.sql`
6. `prototypes/reference/Login.html`
7. `prototypes/reference/demo_mockup_servillantas_el_puente_pro.html`

---

## Estado del entregable

Este paquete sirve como **punto de partida profesional**. Todavía requiere que el equipo:

- conecte base de datos real;
- implemente autenticación completa;
- desarrolle APIs y validaciones reales;
- conecte pasarela de pagos, email, SMS o WhatsApp cuando aplique;
- construya ETL real para los Excel históricos;
- cierre pruebas de seguridad y cumplimiento de datos personales.
