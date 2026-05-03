# Topografía Funcional - Servillantas

Este documento cruza la existencia de los módulos documentados contra los archivos físicos presentes en la base actual.

## Módulos Mapeados

### 1. Autenticación y control de acceso
- **Requerimientos**: RF-001, RF-002, RF-003.
- **En la Base**: Existe un mock en `app/Core/Auth.php`. Falta el controlador `AuthController` y las vistas de login (aunque `public/login.php` está suelto, debería integrarse al MVC). La base de datos soporta `usuarios`, `roles` y `permisos`.
- **Estado**: 🟡 Mockup/Demo

### 2. Carga, migración y validación (ETL)
- **Requerimientos**: RF-004 a RF-008.
- **En la Base**: `EtlImportService.php` está creado como archivo, pero carece de lógica de lectura Excel (phpspreadsheet no está en dependencias), validación o inserción en la DB.
- **Estado**: 🔴 Pendiente

### 3. Clientes
- **Requerimientos**: RF-009 a RF-013.
- **En la Base**: Existe `ClientesController` y `desktop/clientes/index.php`. El DB Schema soporta la abstracción (incluyendo `clientes_telefonos` y `clientes_correos`). Faltan vistas CRUD y el Repository.
- **Estado**: 🟡 UI Demo, Lógica Pendiente

### 4. Cartera / Obligaciones
- **Requerimientos**: RF-014 a RF-020.
- **En la Base**: `CarteraController` y vistas presentes. DB soporta la granularidad.
- **Estado**: 🟡 UI Demo

### 5. Expedientes y soportes
- **Requerimientos**: RF-026 a RF-030.
- **En la Base**: `ExpedientesController` existe. El modelo de datos (`soportes_documentales`, `ubicaciones_fisicas_expediente`) está bien diseñado.
- **Estado**: 🟡 UI Demo

### 6. Gestión de Cobranza (Traza)
- **Requerimientos**: RF-031 a RF-035.
- **En la Base**: `gestiones_cobranza` existe en BD. No hay un controlador dedicado para gestionar esto aislado, probablemente vive dentro de `ClientesController` o `CarteraController` como un modal/slide-over.
- **Estado**: 🔴 Lógica Pendiente

### 7. Comunicaciones / Campañas
- **Requerimientos**: RF-036 a RF-042.
- **En la Base**: `CampanasController` presente. Las tablas (`campanas`, `plantillas_mensajes`, `mensajes_enviados`) son excelentes. Faltaría un servicio de envíos (Mailer/SMS).
- **Estado**: 🟡 UI Demo

### 8. Recaudo y Pagos
- **Requerimientos**: RF-049 a RF-052.
- **En la Base**: `PagosController` presente. Tablas listas. Faltan integraciones a pasarelas (PSE/Nequi).
- **Estado**: 🟡 UI Demo

### 9. Dashboard y Analítica
- **Requerimientos**: RF-053 a RF-057.
- **En la Base**: `DashboardController` listo para Desktop y Mobile. El esquema tiene la tabla `eventos_analiticos` (muy robusto).
- **Estado**: 🟡 UI Demo

### 10. Portal del Deudor
- **Requerimientos**: RF-043 a RF-048.
- **En la Base**: `DeudorController` y vistas móviles presentes. Faltan endpoints de consulta por enlace único.
- **Estado**: 🟡 UI Demo
