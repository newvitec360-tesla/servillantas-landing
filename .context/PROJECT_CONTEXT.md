# 🧠 PROJECT_CONTEXT.md — Servillantas MVC
> Generado por: Super Context Loader (Antigravity AI)
> Última actualización: 2026-04-28
> KI sincronizado en: knowledge/servillantas_mvc_context/

---

## 📋 Vista General
**Propósito**: Plataforma integral para centralizar y recuperar la cartera de Servillantas, ofreciendo seguimiento a cobradores y un portal de recaudo para deudores.
**Tipo**: Web App Enterprise
**Estado**: En Desarrollo (Fase 1 / Scaffold listos)
**Audiencia**: Administradores, Analistas de Cobro, Analistas Jurídicos, Deudores Finales.

---

## ⚙️ Stack Técnico
| Capa | Tecnología | Versión |
|------|-----------|---------|
| Backend | PHP | 8.x |
| Frontend | HTML, CSS Vainilla, JS | - |
| Base de Datos | MySQL/MariaDB | - |

---

## 🏗️ Arquitectura
**Patrón**: Custom MVC con separación total Desktop/Mobile

**Flujo Principal**:
Router → Controller (VADR) → Service / Model → Renderizado View

**Puntos de Entrada**:
- `public/index.php` — Bootstrap MVC

---

## 📐 Convenciones Críticas
> ⚠️ Seguir siempre estas reglas al contribuir al proyecto.

- **Naming**: UpperCamelCase en Clases, snake_case en DB.
- **Estructura**: Separación Estricta `Desktop/` y `Mobile/` en Rutas, Controladores, Vistas y Assets.
- **Validación VADR**: Los controladores solo validan input y delegan lógica.
- **NUNCA**: Mezclar CSS de Escritorio en pantallas Móviles.
- **NUNCA**: Realizar consultas SQL directas en controladores o vistas.
- **NUNCA**: Usar `DELETE` en bases transaccionales financieras; auditar todo.

---

## 🗺️ Mapa de Componentes
Router → redirige según el config de rutas → depende de Core.
Controladores → coordinan la vista y delegación → dependen de Vistas y Servicios.
Servicios (Ej. RiskScoring, Etl) → centralizan reglas de negocio y mutación → dependen de la DB.

---

## 🚦 Estado Actual
**Completo**: Scaffold, BD (Schema.sql), Estructura de carpetas, Estilos Base.
**En Progreso**: Base de datos local.
**Pendiente**: Capa de seguridad Middleware (Auth, CSRF), Lógica real (DB -> Vistas), Carga por ETL Excel.
**Deuda Técnica**: Formulario de Login aislado de MVC, falta RBAC.

---

## 🔧 Cómo Extender este Proyecto
Para agregar una nueva funcionalidad:
1. Validar la base de datos y añadir campo si aplica.
2. Definir la ruta en `config/routes_desktop.php` (o mobile).
3. Añadir el Controller, y la Lógica al Service (Nunca SQL duro en controlador).
4. Proveer vistas responsivas en la carpeta correspondiente de `app/Views/`.

---

## 🔗 Referencias
- KI completo: `knowledge/servillantas_mvc_context/artifacts/`
- Docs Code-Discovery: `docs/code-discovery/`
