# Topografía de Datos - Servillantas

Análisis de la estructura relacional basada en `database/schema.sql` frente a las necesidades funcionales del negocio.

## 1. Nivel Estructural General
El esquema SQL entregado es sumamente completo y está altamente alineado con un enfoque Enterprise-grade. Cuenta con 18 tablas bien normalizadas que cumplen con casi la totalidad de los Requerimientos Funcionales (RF).

## 2. Análisis por Entidades Clave

### Entidades de Seguridad y Acceso
- `roles`, `permisos`, `roles_permisos`, `usuarios`: Cumplen perfectamente los RF-001 y RF-002. El uso de RBAC (Role-Based Access Control) está garantizado.

### Entidades de Cliente
- `clientes`: Contiene la bandera vital `fallecido_flag` y `habeas_data_flag`.
- `clientes_telefonos`, `clientes_correos`: Separan inteligentemente el contacto permitiendo historial y marcación de vigencia (RF-012).

### Entidades Financieras
- `obligaciones`: Incluye conceptos de consolidación, tipo de obligación, y algo crucial: la separación entre `valor_inicial` y `saldo_actual`. Soporta el cálculo S1, S2, S3 con `nivel_riesgo`.
- `pagos`: Tiene los estados de validación manuales (`pendiente`, `validado`, `rechazado`) cumpliendo RF-051.

### Entidades Operativas
- `gestiones_cobranza`: Totalmente apta para RF-031, soporta promesas de pago y agendamientos.
- `soportes_documentales`, `ubicaciones_fisicas_expediente`: Extraordinaria granularidad.

### Entidades de Comunicación y Analítica
- `campanas`, `plantillas_mensajes`, `mensajes_enviados`: Permiten la trazabilidad de lectura, clic y rebote requerida.
- `eventos_analiticos`: Captura el User-Agent y las IPs, ideal para auditoría de los deudores cuando entran al enlace.
- `auditoria`: Asegura la trazabilidad general.

## 3. Posibles Ajustes (Deuda/Riesgo de Esquema)
1. **Auditoría Global**: La tabla `auditoria` guarda `valor_anterior` y `valor_nuevo` como JSON. Para bases de datos muy grandes o MariaDB antiguo, confirmar que el tipo JSON sea nativo y óptimo.
2. **Soft Deletes**: Actualmente ninguna tabla maneja una columna como `deleted_at` (a excepción de los estados inactivos). Al ser un sistema financiero, **nada se debe borrar con DELETE**. Se sugiere implementar un soft-delete o apoyarse exclusivamente en la auditoría y estados.
3. **Control de Concurrencia**: `obligaciones.saldo_actual` podría requerir concurrencia optimista si el sistema crece en usuarios modificando pagos al mismo tiempo.
