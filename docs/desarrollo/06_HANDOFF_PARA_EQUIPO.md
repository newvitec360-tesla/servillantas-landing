# Handoff para equipo de desarrollo y UX

## 1. Primer sprint recomendado

1. Montar repositorio con esta base.
2. Revisar `database/schema.sql`.
3. Convertir schema a migraciones propias del framework o patrón interno.
4. Implementar autenticación real.
5. Implementar CRUD de clientes.
6. Implementar CRUD de obligaciones.
7. Construir ficha integral desktop.
8. Construir primera versión mobile de búsqueda y gestión rápida.

## 2. Control de fidelidad visual

Antes de entregar una pantalla, validar:

- ¿usa los colores correctos?
- ¿respeta el logo?
- ¿se parece al demo aprobado?
- ¿desktop no depende de mobile?
- ¿mobile no depende de desktop?
- ¿las tarjetas y tablas tienen el mismo lenguaje visual?
- ¿los estados por color son consistentes?

## 3. Definition of Done por módulo

Un módulo solo se considera listo si tiene:

- ruta registrada;
- controlador;
- vista desktop;
- vista mobile cuando aplique;
- permisos definidos;
- validaciones server-side;
- auditoría en acciones críticas;
- pruebas manuales mínimas;
- documentación de uso.
