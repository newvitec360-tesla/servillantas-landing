# Gestor visual — Servillantas El Puente

Este gestor es un **mockup funcional de interfaz**, no es un backend terminado. Está pensado para que el equipo de desarrollo conecte la administración de contenido de la landing page.

## Archivos del gestor

```txt
admin/
├── index.html       # Pantalla principal del gestor
├── admin.css        # Estilos exclusivos del gestor
├── admin.js         # Tabs, previews y guardado local de prueba
└── README_GESTOR.md # Esta guía
```

## Cómo abrirlo

Abrir directamente:

```txt
admin/index.html
```

También se puede entrar desde:

```txt
index.html?view=admin
```

## Qué se puede gestionar

1. Header y menú.
2. Banner principal / slider.
3. Tarjetas destacadas bajo el banner.
4. Sección Nosotros.
5. Misión, Visión e Historia.
6. Servicios.
7. Aliados.
8. Franja roja de atención 24 horas.
9. Contacto, dirección, horarios y mapa.
10. SEO y redes sociales.
11. Publicación de cambios.

## Importante

- El gestor usa atributos `data-bind` para indicar qué campo del JSON debe alimentar cada input.
- Las imágenes críticas deben tener versión separada para escritorio y móvil.
- El botón **Guardar borrador** guarda solo en `localStorage`, como simulación.
- La estructura real de contenido está en:

```txt
assets/data/landing-content.mock.json
```

## Tamaños recomendados de imágenes

| Elemento | Tamaño recomendado | Formato | Peso máximo sugerido |
|---|---:|---|---:|
| Logo principal | 420 × 220 px | PNG/SVG/WebP transparente | 500 KB |
| Logo 24 horas | 360 × 160 px | PNG/SVG/WebP transparente | 400 KB |
| Banner escritorio | 1920 × 760 px | JPG/WebP | 1.5 MB |
| Banner móvil | 1080 × 1350 px | JPG/WebP | 1.2 MB |
| Tarjetas destacadas | 600 × 340 px | JPG/WebP | 700 KB |
| Imagen Nosotros | 960 × 620 px | JPG/WebP | 1 MB |
| Imagen servicio | 600 × 360 px | JPG/WebP | 700 KB |
| Franja aliados | 1600 × 260 px | PNG/SVG/WebP | 700 KB |
| Mapa | 1200 × 600 px | JPG/WebP | 900 KB |
| Imagen SEO/Redes | 1200 × 630 px | JPG/PNG/WebP | 1 MB |

## Regla de conexión con la landing

El equipo debe leer el JSON o la base de datos y renderizar:

- `desktop/desktop.html` con imágenes de escritorio.
- `mobile/mobile.html` con imágenes de móvil.

No se debe usar una sola imagen de banner para ambas versiones, porque se puede dañar la composición visual.
