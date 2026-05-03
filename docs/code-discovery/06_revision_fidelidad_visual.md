# Revisión de Fidelidad Visual - Servillantas

Análisis de las interfaces existentes en la base MVC frente a los mockups prototipo aprobados: `Login.html` y `demo_mockup_servillantas_el_puente_pro.html`.

## 1. Reglas de Contraste
1. **Paleta de Colores**: Rojo, Negro, Blanco y Grises.
2. **Componentes Clave**: Sidebar oscuro con acentos rojos, Cards de bordes redondeados, Badges de estado, Botones de llamado a la acción.
3. **Distribución**: Desktop es Sidebar-heavy, Mobile es Bottom/Hamburger-Nav heavy.

## 2. Matriz Visual

| Pantalla | Referencia Mockup | Estado Actual (MVC) | Fidelidad Esperada (1-10) | Diferencias / Riesgos Detectados | Acción Requerida |
|----------|-------------------|---------------------|---------------------------|----------------------------------|------------------|
| **Login** | `Login.html` | Existe archivo base | Pendiente | Verificar carga de estilos y responsividad del half-split. | Integrar `Login.html` puro dentro del MVC. |
| **Dashboard (Desktop)** | `demo_mockup_pro.html` | `desktop/dashboard/index.php` | Alta | Faltan datos reales; los gráficos deben ser reemplazados por librerías como Chart.js inyectadas con datos del Backend. | Inyectar data real al renderizar la vista. |
| **Dashboard (Mobile)** | `demo_mockup_pro.html` | `mobile/dashboard/index.php` | Alta | La navegación móvil suele romperse si se comparten IDs con Desktop. | Aislar componentes CSS específicos. |
| **Clientes** | `demo_mockup_pro.html` | `desktop/clientes/index.php` | Media | Tablas actualmente pueden estar estáticas o usando DataTables genérico sin el estilo Servillantas. | Asegurar estilos de los badges de estado. |
| **Portal Deudor (Mobile)** | N/A en maqueta completa | `mobile/deudor/index.php` | Por definir | Es una de las interfaces más críticas (RF-045). No debe tener barras administrativas. | Diseñar la UI del deudor respetando paleta base. |

## 3. Riesgos de Separación (Desktop/Mobile)
El principal riesgo arquitectónico de UI es la mezcla accidental. Al modificar `app.css` de escritorio, un desarrollador podría intentar aplicarlo a la vista móvil para "ahorrar tiempo".

**Acción Correctiva Obligatoria**:
- Todo controlador `Desktop` SOLO carga `app.php` de Desktop, y este SOLO enlaza `/assets/desktop/css/app.css`.
- Todo controlador `Mobile` SOLO carga `app.php` de Mobile, enlazando `/assets/mobile/css/app.css`.
- Los estilos comunes estrictos deben moverse a `/assets/shared/css/tokens.css` (variables CSS).
