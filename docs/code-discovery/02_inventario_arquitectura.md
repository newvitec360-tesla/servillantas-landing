# Inventario de Arquitectura - Servillantas MVC

Este documento cataloga la estructura real del proyecto a nivel de carpetas y archivos.

## 1. Estructura Raíz
El proyecto sigue una distribución estándar de un MVC custom:
- `/app`: Código fuente PHP (Controllers, Core, Models, Services, Views).
- `/config`: Archivos de configuración (Rutas, Base de datos).
- `/database`: Scripts SQL para esquema y semillas.
- `/docker`: Archivos de contenerización.
- `/docs`: Documentación técnica y funcional original.
- `/prototypes`: Maquetas HTML/CSS de referencia.
- `/public`: Document Root (index.php, login.php, assets estáticos).
- `/resources`: Archivos en crudo, diseño, tokens.
- `/storage`: Almacenamiento local (logs, uploads temporales).

## 2. Catálogo de Archivos Relevantes

| Archivo | Ruta | Tipo | Propósito | Estado Actual |
|---------|------|------|-----------|---------------|
| `Controller.php` | `app/Core/` | Abstract | Clase base para controladores. Maneja render de vistas y respuestas JSON. | Base / Funcional |
| `Router.php` | `app/Core/` | Core | Enrutador de la aplicación. | Base |
| `routes_desktop.php` | `config/` | Config | Rutas exclusivas para Desktop. | Scaffold |
| `routes_mobile.php` | `config/` | Config | Rutas exclusivas para Mobile. | Scaffold |
| `DashboardController.php` | `app/Controllers/Desktop/` | Controller | Controlador de la vista principal escritorio. | Demo |
| `ClientesController.php` | `app/Controllers/Desktop/` | Controller | Gestión de clientes escritorio. | Demo |
| `DeudorController.php` | `app/Controllers/Mobile/` | Controller | Controlador del portal deudor móvil. | Demo |
| `Cliente.php` | `app/Models/` | Model | Representación de tabla clientes. | Base |
| `Obligacion.php` | `app/Models/` | Model | Representación de tabla obligaciones. | Base |
| `RiskScoringService.php`| `app/Services/` | Service | Cálculo de riesgo (S1, S2, S3). | Pendiente |
| `EtlImportService.php` | `app/Services/` | Service | Importador desde Excel. | Pendiente |
| `schema.sql` | `database/` | SQL | DDL de la base de datos. | Completo |
| `app.css` (Desktop) | `public/assets/desktop/css/`| CSS | Estilos exclusivos desktop. | Estructurado |
| `app.css` (Mobile) | `public/assets/mobile/css/` | CSS | Estilos exclusivos mobile. | Estructurado |

## 3. Riesgos Arquitectónicos
- Faltan Repositorios: El acceso a datos probablemente se esté intentando hacer en Modelos en lugar de Repositorios (Patrón Repository).
- Middlewares: No hay una carpeta `app/Middleware/` evidente, lo que dificultará interceptar requests para Auth, CSRF o Rate Limiting.
