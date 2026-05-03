# Matriz de Riesgos - Servillantas MVC

Identificación de deuda técnica y riesgos funcionales del proyecto base que deben ser solucionados antes de escalar a producción.

## Matriz de Riesgos

| Riesgo | Tipo | Ubicación | Impacto | Probabilidad | Prioridad | Acción Recomendada |
|--------|------|-----------|---------|--------------|-----------|--------------------|
| **Falta de Middlewares (Auth / Role)** | Técnico | `app/Core/Router.php` | Alto | 100% | Alta | Implementar middleware de sesión en el router antes de cargar el Controller. |
| **Inyección SQL / XSS** | Seguridad | `app/Controllers/` | Alto | Alta | Alta | Validar uso exclusivo de Prepared Statements en `app/Core/Database.php` y sanear Inputs. |
| **Mezcla de Estilos Desktop/Mobile** | UX / Visual | `public/assets/` | Medio | Alta | Alta | Cumplir política estricta de no cruzar CSS de escritorio en móvil y viceversa. |
| **Lógica Quemada en Vistas** | Técnico | `app/Views/` | Alto | Alta | Alta | Mantener VADR. Ninguna consulta SQL debe ocurrir dentro de los `.php` de las vistas. |
| **Manejo de Uploads** | Funcional / Sec | `storage/uploads/` | Medio | Media | Media | Asegurar ofuscación de nombres y limitación por MIME para los "Soportes Documentales". |
| **Auditoría JSON** | Datos | `auditoria` | Bajo | Media | Media | Validar compatibilidad de la base de datos de producción con el tipo `JSON` para las columnas de `valor_anterior` y `valor_nuevo`. |
| **ETL Fallando en Producción** | Operativo | `EtlImportService.php`| Alto | Alta | Alta | Usar librerías estables como PHPSpreadsheet y encolar el proceso si el Excel es gigantesco. |
| **Sin CSRF Protection** | Seguridad | General Formularios | Alto | Alta | Alta | Implementar tokens CSRF para todas las operaciones mutables web. |

## Conclusión de Riesgo
Actualmente el proyecto es **Seguro por Defecto** simplemente porque no hace nada real aún. El mayor riesgo ocurrirá durante la implementación de los formularios CRUD, donde la validación de roles y la inyección SQL son las amenazas principales.
