# Mapa MVC - Servillantas

Este documento mapea la relación entre rutas, controladores, modelos, servicios y vistas del proyecto.

## 1. Capa de Rutas y Controladores

### Desktop (`config/routes_desktop.php`)
| Ruta | Controlador (Desktop) | Acción | Vista Asociada |
|------|----------------------|--------|----------------|
| `/` o `/dashboard` | `DashboardController` | `index()` | `desktop/dashboard/index.php` |
| `/clientes` | `ClientesController` | `index()` | `desktop/clientes/index.php` |
| `/cartera` | `CarteraController` | `index()` | `desktop/cartera/index.php` |
| `/expedientes` | `ExpedientesController`| `index()` | `desktop/expedientes/index.php` |
| `/campanas` | `CampanasController` | `index()` | `desktop/campanas/index.php` |
| `/pagos` | `PagosController` | `index()` | `desktop/pagos/index.php` |
| `/reportes` | `ReportesController` | `index()` | `desktop/reportes/index.php` |
| `/configuracion` | `ConfiguracionController`| `index()`| `desktop/configuracion/index.php` |

### Mobile (`config/routes_mobile.php`)
| Ruta | Controlador (Mobile) | Acción | Vista Asociada |
|------|---------------------|--------|----------------|
| `/` o `/dashboard` | `DashboardController` | `index()` | `mobile/dashboard/index.php` |
| `/clientes` | `ClientesController`| `index()` | `mobile/clientes/index.php` |
| `/cartera` | `CarteraController` | `index()` | `mobile/cartera/index.php` |
| `/deudor` | `DeudorController` | `index()` | `mobile/deudor/index.php` |

## 2. Capa de Lógica de Negocio (Services)
- `AuditService.php`: Deberá inyectarse en los Controladores para generar trazas de auditoría (RF-060).
- `EtlImportService.php`: Manejará la importación de archivos (RF-004, RF-005).
- `RiskScoringService.php`: Asignará S1, S2, S3 según la morosidad y variables (RF-021).

## 3. Capa de Datos (Models)
- `Auditoria` -> Tabla `auditoria`
- `Campana` -> Tabla `campanas`
- `Cliente` -> Tablas `clientes`, `clientes_telefonos`, `clientes_correos`
- `GestionCobranza` -> Tabla `gestiones_cobranza`
- `Obligacion` -> Tabla `obligaciones`
- `Pago` -> Tabla `pagos`
- `SoporteDocumental` -> Tabla `soportes_documentales`

## 4. Deficiencias Detectadas en el Mapeo
1. **Faltan Rutas CRUD**: Actualmente solo existe la ruta `index` para visualizar las pantallas principales. Faltan rutas POST/PUT/DELETE (Ej. `/clientes/store`, `/pagos/create`).
2. **Falta Separación API**: Las llamadas AJAX (muy probables en la UI) requerirán rutas específicas para evitar cargar vistas HTML enteras. Se sugiere un `/api/v1/...`.
