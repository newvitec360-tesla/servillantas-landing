# Demo Landing Page — Servillantas El Puente

Este paquete está preparado para que el equipo de desarrollo continúe la landing page manteniendo separadas las versiones de **escritorio** y **móvil**.

## Estructura del proyecto

```txt
servillantas_el_puente_demo/
├── index.html                 # Router automático según ancho de pantalla
├── desktop/
│   ├── desktop.html           # Maquetación exclusiva de escritorio
│   └── desktop.css            # Estilos exclusivos de escritorio
├── mobile/
│   ├── mobile.html            # Maquetación exclusiva de móvil
│   └── mobile.css             # Estilos exclusivos de móvil
├── assets/
│   ├── img/                   # Imágenes recortadas desde la referencia visual
│   └── js/
│       ├── desktop.js         # Interacciones exclusivas escritorio
│       └── mobile.js          # Interacciones exclusivas móvil
└── README.md
```

## Regla principal de desarrollo

- Los cambios de escritorio deben hacerse únicamente en:
  - `desktop/desktop.html`
  - `desktop/desktop.css`
  - `assets/js/desktop.js`

- Los cambios de móvil deben hacerse únicamente en:
  - `mobile/mobile.html`
  - `mobile/mobile.css`
  - `assets/js/mobile.js`

Esto evita que una modificación en escritorio dañe la versión móvil o viceversa.

## Cómo abrir el demo

1. Abre `index.html` en el navegador.
2. El archivo detecta el ancho de pantalla y redirige automáticamente:
   - Pantallas mayores a 767px: `desktop/desktop.html`
   - Pantallas de 767px o menos: `mobile/mobile.html`

También puedes abrir directamente:

- Escritorio: `desktop/desktop.html`
- Móvil: `mobile/mobile.html`

## Guía de fidelidad visual

El diseño replica la referencia entregada con estas secciones:

1. Header con logos, navegación y botón rojo.
2. Hero oscuro con mensaje principal y CTA.
3. Tarjetas destacadas debajo del hero.
4. Sección Nosotros con imagen de fachada.
5. Tarjetas de Misión, Visión e Historia.
6. Línea de servicios con numeración 01–09.
7. Aliados.
8. CTA rojo de atención 24 horas.
9. Footer de contacto con mapa.

## Notas para el equipo

- Se dejó la imagen original de referencia en `assets/img/referencia-diseno-original.png`.
- Las imágenes actuales fueron recortadas desde la referencia visual para mantener coherencia gráfica en el demo.
- Los botones de WhatsApp apuntan temporalmente a `https://wa.me/573107922579`; pueden cambiarse por el número oficial definitivo.
- No se usaron librerías externas para que el demo sea fácil de revisar, compartir y continuar.
- El demo está pensado como base visual/HTML, no como versión final conectada a backend.

---

## Gestor visual agregado

Se agregó un mockup completo del administrador de contenido en:

```txt
admin/index.html
```

También se puede abrir desde:

```txt
index.html?view=admin
```

El gestor incluye pestañas para editar:

1. Header y menú.
2. Banner principal / sliders.
3. Tarjetas destacadas.
4. Nosotros.
5. Misión, Visión e Historia.
6. Servicios.
7. Aliados.
8. Franja roja de atención 24 horas.
9. Contacto, dirección, teléfonos, correo y mapa.
10. SEO y redes.
11. Publicación.

### Archivos nuevos importantes

```txt
admin/
├── index.html             # Mockup del gestor visual
├── admin.css              # Estilos exclusivos del gestor
├── admin.js               # Tabs, previews y simulación de guardado local
└── README_GESTOR.md       # Guía rápida del gestor

assets/data/
└── landing-content.mock.json

docs/
├── MAPA_CONEXIONES_GESTOR_LANDING.md
└── ESPECIFICACION_BACKEND_GESTOR.md
```

### Regla de imágenes escritorio/móvil

Para el banner principal se deben manejar imágenes independientes:

```json
"image": {
  "desktop": "assets/img/hero-bg.jpg",
  "mobile": "assets/img/hero-bg-mobile.jpg"
}
```

Esto evita que una imagen cargada para escritorio dañe la composición de móvil o viceversa.
