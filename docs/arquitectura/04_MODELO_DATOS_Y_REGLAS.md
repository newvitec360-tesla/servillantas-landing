# Modelo de datos y reglas de negocio

## Entidades principales

- Usuario
- Rol
- Permiso
- Cliente
- Teléfono
- Correo
- Obligación
- Pago
- Soporte documental
- Ubicación física de expediente
- Gestión de cobranza
- Campaña
- Mensaje enviado
- Plantilla
- Evento analítico
- Estado paramétrico
- Auditoría

## Reglas críticas

1. El cliente debe ser la entidad fuerte del sistema.
2. Una obligación no debe perder su historial al editarse.
3. La deuda se debe ver consolidada y también detallada.
4. La fecha de último abono es estratégica.
5. S3 debe aparecer primero en tableros y bandejas.
6. Sin habeas data válido, el sistema debe restringir mensajería masiva cuando aplique.
7. Todo cambio de saldo, riesgo, pago, soporte o dato sensible debe auditarse.
8. Los fallecidos tienen ruta diferenciada.
9. La fortaleza documental debe ayudar a priorizar casos jurídicos.
10. El comportamiento digital debe alimentar la gestión: clic, ingreso, pago, no pago.

## Tablas

El archivo principal es:

```txt
database/schema.sql
```

Ese archivo trae un modelo lógico inicial en MySQL para que el equipo lo revise, ajuste y convierta en migraciones formales.
