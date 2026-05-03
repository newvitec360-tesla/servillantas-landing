# Mapa de conexiones — Gestor CMS ↔ Landing Page

Este documento explica cómo conectar el gestor visual con la landing de Servillantas El Puente.

## 1. Principio general

La landing tiene dos versiones separadas:

```txt
desktop/desktop.html
mobile/mobile.html
```

El gestor debe alimentar ambas versiones desde una misma fuente de contenido, pero respetando imágenes separadas cuando aplique:

```txt
hero.slides[n].image.desktop
hero.slides[n].image.mobile
```

## 2. Archivo de datos base

Estructura mockup entregada:

```txt
assets/data/landing-content.mock.json
```

Este archivo sirve como contrato inicial entre frontend y backend.

## 3. Mapa por sección

| Gestor | JSON / Campo | Landing escritorio | Landing móvil | Observación |
|---|---|---|---|---|
| Logo principal | `brand.logoMain` | `.brand-main` | `.mobile-brand img:nth-child(1)` | PNG/SVG/WebP transparente |
| Logo 24 horas | `brand.logoHours` | `.brand-hours` | `.mobile-brand img:nth-child(2)` | PNG/SVG/WebP transparente |
| Menú | `header.menu[]` | `.main-nav a` | `.mobile-menu a` | Respetar `visible` y `order` |
| Botón header | `header.cta` | `.header-cta` | `.menu-cta` | URL de WhatsApp o contacto |
| Banner slide | `hero.slides[]` | `.hero` | `.m-hero` | Usar imagen desktop/móvil por separado |
| Título banner | `hero.slides[n].title` | `.hero h1` | `.m-hero h1` | En móvil puede usarse versión resumida si se agrega campo opcional |
| Descripción banner | `hero.slides[n].description` | `.hero-copy` | `.m-hero p` | Máximo recomendado 160 caracteres |
| Botones banner | `hero.slides[n].primaryCta`, `secondaryCta` | `.hero-actions` | `.hero-actions` | Mantener CTA rojo principal |
| Tarjetas destacadas | `heroHighlights[]` | `.hero-tabs .tab-card` | `.quick-cards article` | 3 tarjetas recomendado |
| Nosotros | `about.*` | `.about` | `.m-about` | Imagen puede ser compartida |
| Misión/Visión/Historia | `values[]` | `.value-cards article` | `.m-values article` | Textos cortos |
| Servicios | `services[]` | `.service-timeline` | `.m-services` | Máximo recomendado 9–12 |
| Aliados | `allies.*` | `.allies` | `.m-allies` | Puede ser franja o logos individuales |
| CTA rojo | `emergencyCta.*` | `.red-cta` | `.m-cta` | Debe ser breve |
| Contacto | `contact.*` | `.contact` | `.m-contact` | Teléfonos, correo, dirección, mapa |
| Footer | `business.*` | `.footer-bar` | `.m-footer` | NIT y copyright |
| SEO | `seo.*` | `<head>` | `<head>` | Meta tags y Open Graph |

## 4. Recomendación de renderizado

### Opción A — Backend renderiza HTML

1. El backend lee la base de datos.
2. Genera el HTML final de escritorio y móvil.
3. Publica archivos estáticos optimizados.

Ideal si quieren máxima velocidad.

### Opción B — Frontend consume API

1. `desktop.js` y `mobile.js` hacen `fetch('/api/landing-content')`.
2. Pintan textos e imágenes en el DOM.
3. Se recomienda dejar HTML fallback para SEO.

Ideal si el contenido cambia con frecuencia.

## 5. Campos críticos que no deben faltar

```json
{
  "brand.logoMain": "Obligatorio",
  "brand.logoHours": "Obligatorio",
  "hero.slides[0].title": "Obligatorio",
  "hero.slides[0].image.desktop": "Obligatorio",
  "hero.slides[0].image.mobile": "Obligatorio",
  "contact.phones[0]": "Obligatorio",
  "contact.address": "Obligatorio",
  "contact.email": "Obligatorio"
}
```

## 6. Validaciones sugeridas

- No permitir banners de escritorio menores a 1600 px de ancho.
- No permitir banners móviles horizontales.
- No permitir imágenes mayores a 2 MB sin compresión.
- No permitir publicar sin teléfono principal.
- No permitir publicar sin por lo menos un slide activo.
- No permitir publicar sin mínimo tres servicios visibles.

## 7. Estado de publicación sugerido

Cada bloque editable puede manejar estados:

```json
{
  "status": "draft | published | archived",
  "updatedBy": "user_id",
  "updatedAt": "2026-04-30T00:00:00Z",
  "publishedAt": "2026-04-30T00:00:00Z"
}
```

## 8. Control de escritorio y móvil

Para evitar que una versión afecte a la otra:

- Archivos de escritorio permanecen en `desktop/`.
- Archivos de móvil permanecen en `mobile/`.
- El gestor solo entrega datos.
- El render de cada versión decide cómo usa esos datos.
- Las imágenes del banner deben tener campos separados.
