# Seguridad, auditoría y cumplimiento

## 1. Seguridad mínima requerida

- Contraseñas con `password_hash`.
- Sesiones seguras.
- Middleware de autenticación.
- Control por roles y permisos.
- Protección CSRF en formularios.
- Validación server-side de todos los inputs.
- Prepared statements / PDO.
- No exponer archivos privados desde `public`.
- Logs de errores fuera del directorio público.

## 2. Auditoría obligatoria

Debe registrarse:

- inicio y cierre de sesión;
- creación y edición de clientes;
- cambios de saldos;
- registro, validación o rechazo de pagos;
- cambios de nivel de riesgo;
- carga de documentos;
- cambios de ubicación física de expediente;
- campañas enviadas;
- cambios en plantillas;
- cambios en permisos.

## 3. Datos personales

La plataforma maneja información sensible de clientes y deudores. El equipo debe contemplar:

- política de tratamiento de datos;
- consentimiento / habeas data;
- mínimos privilegios por rol;
- trazabilidad de consultas;
- acceso restringido a documentos;
- retención y eliminación controlada cuando aplique.

## 4. Portal del deudor

El portal no debe exponer datos completos sin validación mínima. Recomendado:

- enlace único con token vencible;
- validación parcial de documento o placa;
- no mostrar información sensible innecesaria;
- registrar eventos analíticos sin exceder datos personales.
