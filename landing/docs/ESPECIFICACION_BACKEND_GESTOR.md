# Especificación backend sugerida — Gestor Landing Servillantas

Este documento no programa el backend; deja abiertas las rutas y estructuras para que el equipo lo implemente.

## 1. Módulos mínimos del gestor

1. Autenticación de administrador.
2. Gestión de contenido de landing.
3. Gestión de imágenes / media library.
4. Borradores.
5. Publicación.
6. Historial de cambios.

## 2. Endpoints sugeridos

### Obtener contenido publicado

```http
GET /api/landing-content
```

Respuesta:

```json
{
  "status": "ok",
  "data": {}
}
```

### Obtener borrador actual

```http
GET /api/admin/landing-content/draft
```

### Guardar borrador

```http
PUT /api/admin/landing-content/draft
Content-Type: application/json
```

Body: misma estructura de `assets/data/landing-content.mock.json`.

### Publicar cambios

```http
POST /api/admin/landing-content/publish
```

Respuesta sugerida:

```json
{
  "status": "ok",
  "publishedAt": "2026-04-30T00:00:00Z"
}
```

### Subir imagen

```http
POST /api/admin/media/upload
Content-Type: multipart/form-data
```

Campos:

| Campo | Descripción |
|---|---|
| `file` | Archivo de imagen |
| `section` | Ej: `hero`, `services`, `about` |
| `variant` | Ej: `desktop`, `mobile`, `default` |
| `alt` | Texto alternativo |

Respuesta:

```json
{
  "status": "ok",
  "asset": {
    "id": 15,
    "url": "/uploads/landing/hero-desktop.webp",
    "width": 1920,
    "height": 760,
    "mime": "image/webp",
    "sizeKb": 480
  }
}
```

### Eliminar imagen

```http
DELETE /api/admin/media/{assetId}
```

## 3. Tablas sugeridas

### `landing_pages`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint | ID |
| `slug` | varchar | Ej: `servillantas-el-puente` |
| `content_json` | json | Contenido completo |
| `draft_json` | json | Borrador actual |
| `status` | varchar | `draft`, `published` |
| `published_at` | datetime | Fecha de publicación |
| `created_at` | datetime | Creación |
| `updated_at` | datetime | Actualización |

### `media_assets`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint | ID |
| `section` | varchar | Sección: hero, about, services |
| `variant` | varchar | desktop, mobile, default |
| `original_name` | varchar | Nombre original |
| `path` | varchar | Ruta interna |
| `url` | varchar | URL pública |
| `mime` | varchar | Tipo de archivo |
| `width` | int | Ancho |
| `height` | int | Alto |
| `size_kb` | int | Peso |
| `alt` | varchar | Texto alternativo |
| `created_at` | datetime | Creación |

### `landing_revisions`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint | ID |
| `landing_page_id` | bigint | Relación |
| `content_json` | json | Copia publicada |
| `created_by` | bigint | Usuario |
| `created_at` | datetime | Fecha |

## 4. Validaciones de imágenes

| Uso | Mínimo aceptado | Recomendado | Formatos |
|---|---:|---:|---|
| Banner desktop | 1600 × 620 px | 1920 × 760 px | JPG/WebP |
| Banner móvil | 900 × 1100 px | 1080 × 1350 px | JPG/WebP |
| Servicios | 500 × 300 px | 600 × 360 px | JPG/WebP |
| Nosotros | 800 × 520 px | 960 × 620 px | JPG/WebP |
| Logos | 300 px ancho | SVG/PNG transparente | SVG/PNG/WebP |
| SEO/Redes | 1200 × 630 px | 1200 × 630 px | JPG/PNG/WebP |

## 5. Seguridad mínima

- Proteger rutas `/api/admin/*` con sesión o token.
- Validar MIME real del archivo, no solo extensión.
- Renombrar archivos subidos.
- Comprimir imágenes al subir.
- Guardar historial antes de publicar.
- Sanitizar textos para evitar inyección HTML.
- Limitar peso máximo por archivo.

## 6. Publicación recomendada

1. Administrador edita contenido.
2. Guarda borrador.
3. Sistema valida campos obligatorios.
4. Administrador previsualiza.
5. Administrador publica.
6. Sistema copia `draft_json` a `content_json`.
7. Landing consume el contenido publicado.

## 7. Integración con frontend actual

El equipo puede iniciar conectando solo estos puntos:

- Header.
- Hero.
- Servicios.
- Contacto.

Luego conectar el resto de secciones.
