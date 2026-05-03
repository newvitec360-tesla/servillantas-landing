# Roadmap Técnico - Servillantas MVC

Este roadmap detalla el plan paso a paso para convertir el esqueleto actual en una plataforma funcional a gran escala, respetando las expectativas de la directiva NewViTec.

## FASE 1 - Core Operativo & Seguridad Inicial
**Objetivo**: Establecer autenticación, inyectar base de datos y proveer los CRUDs básicos para uso interno.

1. **Configuración DB y Middlewares**
   - Configurar `Database.php` y conectar con MySQL/MariaDB.
   - Desarrollar `AuthMiddleware` y `RoleMiddleware`.
   - Implementar control de Sesión y CSRF Token.
2. **Autenticación (Login)**
   - Completar `AuthController` (Login/Logout).
   - Generación de hashes seguros de contraseña.
3. **Gestión de Clientes y Obligaciones**
   - CRUD en `ClientesController` y vistas.
   - CRUD en `CarteraController` (Obligaciones).
   - Crear los Repositorios y Servicios correspondientes.
4. **Importador Básico (ETL)**
   - Completar `EtlImportService.php` para carga manual por CSV o Excel básico.

## FASE 2 - Cobranza, Soportes y Portal Deudor
**Objetivo**: Habilitar el registro de gestión, control de documentos físicos y experiencia de deudores.

1. **Expedientes y Soportes Documentales**
   - Habilitar subida segura de archivos en `ExpedientesController`.
   - Control de ubicación física.
2. **Gestión de Cobranza (Traza)**
   - Funcionalidad de bitácora y compromisos asociada a cada Cliente.
   - Programación de próximas gestiones.
3. **Portal Móvil del Deudor**
   - Habilitar `DeudorController` para accesos con Token/Identificación.
   - Vista móvil nativa de resumen de deuda.
4. **Registro de Pagos Manuales**
   - Funcionalidad en `PagosController` para que los asesores registren consignaciones y abonos.

## FASE 3 - Automatización, Campañas e Inteligencia
**Objetivo**: Desplegar analítica, envíos masivos y scoring de riesgo.

1. **Comunicaciones y Campañas**
   - Gestión de plantillas de correo/SMS.
   - `CampanasController` y servicio de mensajería asíncrona.
2. **Dashboard de Analítica y Auditoría**
   - Conectar `DashboardController` con `Auditoria` y KPIs financieros.
   - Análisis de comportamiento en el portal del deudor.
3. **Risk Scoring S1/S2/S3**
   - Implementar algoritmos de clasificación de deudores.
4. **Integración con Pasarela de Pagos (Opcional Futuro)**
   - Conectar Wompi, ePayco o PSE directamente en el Portal Deudor.
