# Backlog técnico por fases

## Fase 1 — Núcleo operativo

### Objetivo
Construir el corazón administrativo mínimo para operar cartera de forma ordenada.

### Historias principales

1. Como administrador, quiero iniciar sesión de forma segura para acceder al sistema.
2. Como administrador, quiero crear usuarios y roles para controlar permisos.
3. Como analista, quiero importar Excel para migrar cartera histórica.
4. Como analista, quiero consultar clientes por cédula, NIT, placa, nombre o teléfono.
5. Como analista, quiero ver la deuda consolidada y el detalle por obligación.
6. Como analista, quiero registrar gestiones de cobranza.
7. Como analista, quiero registrar soportes documentales y ubicación física.
8. Como gerencia, quiero ver un dashboard básico de cartera y recaudo.

### Entregables técnicos

- CRUD de usuarios y roles.
- CRUD de clientes.
- CRUD de obligaciones.
- Importador Excel con validación inicial.
- Ficha integral de cliente.
- Bitácora de gestiones.
- Dashboard base.
- Auditoría inicial.

---

## Fase 2 — Automatización y recaudo

### Objetivo
Mejorar cobro, comunicación y pagos.

### Historias principales

1. Como analista, quiero crear segmentos de cartera para campañas.
2. Como administrador, quiero gestionar plantillas por riesgo.
3. Como deudor, quiero consultar mi saldo desde celular.
4. Como deudor, quiero pagar o hacer abonos.
5. Como analista, quiero validar comprobantes manuales.
6. Como gerencia, quiero medir clics, aperturas y pagos por campaña.

### Entregables técnicos

- Campañas por email/SMS.
- Plantillas parametrizables.
- Links únicos por cliente.
- Portal deudor mobile-first.
- Registro de pagos.
- Conciliación básica.
- Analítica de campañas.

---

## Fase 3 — Inteligencia y madurez

### Objetivo
Usar datos históricos para scoring, alertas y decisiones de crédito futuro.

### Historias principales

1. Como gerencia, quiero ver probabilidad de recuperación por cliente.
2. Como crédito, quiero consultar comportamiento histórico antes de otorgar crédito.
3. Como jurídico, quiero filtrar casos con mayor fortaleza documental.
4. Como administrador, quiero configurar reglas sin tocar código.
5. Como sistema, quiero generar alertas automáticas por mora, promesas y comportamiento digital.

### Entregables técnicos

- Scoring avanzado.
- Motor de reglas.
- Alertas.
- Reportes de crédito futuro.
- Integraciones profundas con pagos, contabilidad o ERP.
