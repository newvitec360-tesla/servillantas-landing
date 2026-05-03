# Índice ejecutivo — Servillantas MVC Base

## Propósito del paquete

Este ZIP organiza la base para que el equipo pueda iniciar una plataforma MVC robusta para Servillantas, usando como insumos:

- el documento consolidado de requerimientos;
- el demo HTML aprobado por el cliente;
- el login aprobado;
- el logo original de Servillantas El Puente.

## Decisión técnica principal

La plataforma se estructura como un **MVC PHP modular**, con separación fuerte entre:

- **capa de dominio compartida:** modelos, servicios, reglas, auditoría, base de datos;
- **capa de presentación desktop:** rutas, controladores, vistas, CSS y JS propios;
- **capa de presentación mobile:** rutas, controladores, vistas, CSS y JS propios.

## Por qué esta separación es importante

Porque el cliente ya aprobó una línea visual de escritorio y login, pero la versión móvil necesita su propia experiencia. Si el equipo usa el mismo CSS para todo, cualquier ajuste de escritorio puede romper mobile y cualquier ajuste mobile puede deformar escritorio.

## Resultado esperado

Una base ordenada para construir:

1. autenticación y roles;
2. gestión de clientes;
3. cartera y obligaciones;
4. soporte documental;
5. gestión de cobranza;
6. campañas;
7. pagos;
8. reportes;
9. configuración;
10. portal del deudor mobile-first.
