<?php
// Protección de acceso — Solo usuarios autenticados del gestor de landing
session_name('servillantas_session');
session_start();
if (empty($_SESSION['landing_admin_logged_in']) || $_SESSION['landing_admin_logged_in'] !== true) {
    header('Location: /index.php?r=/admin-landing/login');
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestor Landing | Servillantas El Puente</title>
  <meta name="description" content="Mockup administrativo para gestionar textos, imágenes, banners, servicios, aliados y contacto de la landing page." />
  <link rel="stylesheet" href="admin.css" />
  <style>
    .sidebar { display: flex; flex-direction: column; }
    .sidebar-logout { margin-top: auto; padding: 20px; text-align: center; border-top: 1px solid rgba(255,255,255,0.08); }
    .sidebar-logout a { color: #ff4b4b; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; border-radius: 14px; transition: all 0.2s ease; }
    .sidebar-logout a:hover { background: rgba(255,75,75,0.1); }
  </style>
</head>
<body>
  <div class="admin-app">
    <aside class="sidebar" aria-label="Menú del gestor">
      <div class="brand-lockup">
        <img src="../assets/img/logo-servillantas.png" alt="Servillantas El Puente" />
        <div>
          <b>Gestor Landing</b>
          <span>CMS Landing</span>
        </div>
      </div>

      <nav class="tabs" aria-label="Pestañas del gestor">
        <button class="tab-link active" data-tab="resumen">🏠 Resumen</button>
        <button class="tab-link" data-tab="header">🧭 Header y menú</button>
        <button class="tab-link" data-tab="banner">🖼️ Banner principal</button>
        <button class="tab-link" data-tab="destacados">⭐ Tarjetas destacadas</button>
        <button class="tab-link" data-tab="nosotros">🏢 Nosotros</button>
        <button class="tab-link" data-tab="valores">🎯 Misión / Visión / Historia</button>
        <button class="tab-link" data-tab="servicios">🛠️ Servicios</button>
        <button class="tab-link" data-tab="aliados">🤝 Aliados</button>
        <button class="tab-link" data-tab="cta">📞 Franja 24 horas</button>
        <button class="tab-link" data-tab="contacto">📍 Contacto y mapa</button>
        <button class="tab-link" data-tab="seo">🔎 SEO y redes</button>
        <button class="tab-link" data-tab="publicar">🚀 Publicar</button>
      </nav>

      <div class="sidebar-note">
        <strong>Regla clave</strong>
        <p>El gestor maneja campos separados para escritorio y móvil cuando la imagen lo necesita.</p>
      </div>

      <div class="sidebar-logout">
        <a href="/index.php?r=/admin-landing/logout">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
          Cerrar sesión
        </a>
      </div>
    </aside>

    <main class="main-panel">
      <header class="topbar">
        <div>
          <span class="crumb">Servillantas El Puente / Landing page</span>
          <h1>Gestor de contenido</h1>
          <p>Gestor oficial de la landing page. Los cambios se guardarán en la base de datos.</p>
        </div>
        <div class="top-actions">
          <a class="ghost-btn" href="/" target="_blank" rel="noopener">Ver landing</a>
          <button class="primary-btn" id="saveAllBtn">Guardar borrador</button>
        </div>
      </header>

      <section class="notice-bar">
        <div>
          <b>Gestor conectado:</b> Los cambios se guardan en la base de datos. Usa "Guardar borrador" para guardar sin publicar, y "Publicar cambios" cuando estés listo.
        </div>
        <button id="exportJsonBtn">Exportar JSON</button>
      </section>

      <section class="panel active" id="tab-resumen">
        <div class="section-heading">
          <span>Vista general</span>
          <h2>Panel simple para administrar la landing</h2>
          <p>La idea es que cualquier persona pueda cambiar textos, imágenes, sliders, servicios, aliados y datos de contacto sin tocar código.</p>
        </div>

        <div class="dashboard-grid">
          <article class="metric-card">
            <span>12</span>
            <h3>Secciones editables</h3>
            <p>Header, banner, nosotros, servicios, aliados, contacto y SEO.</p>
          </article>
          <article class="metric-card">
            <span>2</span>
            <h3>Versiones visuales</h3>
            <p>Imágenes separadas para escritorio y móvil donde aplica.</p>
          </article>
          <article class="metric-card">
            <span>JSON</span>
            <h3>Conexión abierta</h3>
            <p>Se entrega estructura de datos para conectar con backend.</p>
          </article>
        </div>

        <div class="workflow-card">
          <h3>Flujo pensado “para niños chiquitos”</h3>
          <div class="steps-row">
            <div><b>1</b><span>Escoge una pestaña</span></div>
            <div><b>2</b><span>Cambia texto o imagen</span></div>
            <div><b>3</b><span>Revisa tamaños recomendados</span></div>
            <div><b>4</b><span>Guarda borrador</span></div>
            <div><b>5</b><span>Publica en landing</span></div>
          </div>
        </div>

        <div class="two-col">
          <article class="info-card">
            <h3>Carpetas nuevas agregadas</h3>
            <ul class="clean-list">
              <li><code>admin/index.html</code> — mockup del gestor.</li>
              <li><code>admin/admin.css</code> — estilos exclusivos del gestor.</li>
              <li><code>admin/admin.js</code> — tabs, previews y simulación de guardado.</li>
              <li><code>assets/data/landing-content.mock.json</code> — estructura de contenido.</li>
              <li><code>docs/MAPA_CONEXIONES_GESTOR_LANDING.md</code> — mapa para programadores.</li>
              <li><code>docs/ESPECIFICACION_BACKEND_GESTOR.md</code> — endpoints y tablas sugeridas.</li>
            </ul>
          </article>
          <article class="info-card danger-soft">
            <h3>Regla para el equipo</h3>
            <p>Los cambios del gestor deben alimentar tanto <b>desktop</b> como <b>mobile</b>, pero sin mezclar sus archivos visuales. El contenido puede ser compartido; las imágenes críticas deben tener versión independiente.</p>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-header">
        <div class="section-heading">
          <span>Header y menú</span>
          <h2>Logos, navegación y botón principal</h2>
          <p>Controla lo que aparece arriba en la landing: logos, opciones del menú y botón rojo de solicitud.</p>
        </div>

        <div class="form-grid">
          <article class="form-card wide">
            <h3>Identidad visual superior</h3>
            <div class="upload-grid">
              <div class="upload-box" data-spec="Logo principal: PNG o SVG transparente, 420x220 px recomendado, máximo 500 KB.">
                <img src="../assets/img/logo-servillantas.png" alt="Preview logo principal" />
                <div>
                  <label>Logo principal</label>
                  <input type="file" accept="image/png,image/svg+xml,image/webp" data-bind="brand.logoMain" />
                  <small>Recomendado: 420 × 220 px · PNG/SVG/WebP · fondo transparente.</small>
                </div>
              </div>
              <div class="upload-box" data-spec="Logo 24 horas: PNG o SVG transparente, 360x160 px recomendado, máximo 400 KB.">
                <img src="../assets/img/logo-24-horas.png" alt="Preview logo 24 horas" />
                <div>
                  <label>Logo servicio 24 horas</label>
                  <input type="file" accept="image/png,image/svg+xml,image/webp" data-bind="brand.logoHours" />
                  <small>Recomendado: 360 × 160 px · PNG/SVG/WebP · fondo transparente.</small>
                </div>
              </div>
            </div>
          </article>

          <article class="form-card">
            <h3>Botón principal</h3>
            <label>Texto del botón</label>
            <input type="text" value="Solicitar servicio" data-bind="header.cta.label" />
            <label>Enlace WhatsApp</label>
            <input type="url" value="https://wa.me/573107922579" data-bind="header.cta.url" />
            <label>Icono visible</label>
            <select data-bind="header.cta.icon">
              <option>Calendario</option>
              <option>WhatsApp</option>
              <option>Teléfono</option>
            </select>
          </article>

          <article class="form-card">
            <h3>Opciones del menú</h3>
            <div class="mini-table">
              <div><b>Texto</b><b>Destino</b><b>Visible</b></div>
              <div><input value="Inicio" data-bind="header.menu.0.label"><input value="#inicio" data-bind="header.menu.0.href"><label class="switch"><input type="checkbox" checked><span></span></label></div>
              <div><input value="Nosotros" data-bind="header.menu.1.label"><input value="#nosotros" data-bind="header.menu.1.href"><label class="switch"><input type="checkbox" checked><span></span></label></div>
              <div><input value="Servicios" data-bind="header.menu.2.label"><input value="#servicios" data-bind="header.menu.2.href"><label class="switch"><input type="checkbox" checked><span></span></label></div>
              <div><input value="Aliados" data-bind="header.menu.3.label"><input value="#aliados" data-bind="header.menu.3.href"><label class="switch"><input type="checkbox" checked><span></span></label></div>
              <div><input value="Contacto" data-bind="header.menu.4.label"><input value="#contacto" data-bind="header.menu.4.href"><label class="switch"><input type="checkbox" checked><span></span></label></div>
            </div>
          </article>
        </div>

        <div class="connection-card">
          <h3>Conexión sugerida</h3>
          <p><code>brand.logoMain</code>, <code>brand.logoHours</code>, <code>header.menu[]</code> y <code>header.cta</code> alimentan el header de <code>desktop/desktop.html</code> y <code>mobile/mobile.html</code>.</p>
        </div>
      </section>

      <section class="panel" id="tab-banner">
        <div class="section-heading">
          <span>Banner principal</span>
          <h2>Slider del hero principal</h2>
          <p>Administra las imágenes grandes del inicio. Cada slide debe tener imagen de escritorio y de móvil para que una no dañe la otra.</p>
        </div>

        <div class="helper-strip">
          <div><b>Escritorio:</b> 1920 × 760 px · JPG/WebP · máximo 1.5 MB.</div>
          <div><b>Móvil:</b> 1080 × 1350 px · JPG/WebP · máximo 1.2 MB.</div>
          <div><b>Zona segura:</b> deja el texto libre al lado izquierdo.</div>
        </div>

        <div class="slider-editor">
          <article class="slide-card active-slide">
            <div class="slide-head"><b>Slide 01</b><span>Activo</span></div>
            <div class="slide-preview" style="background-image:url('../assets/img/hero-bg.jpg')"></div>
            <div class="field-pair">
              <div>
                <label>Título grande</label>
                <textarea rows="2" data-bind="hero.slides.0.title">En Servillantas 24 Horas, trabajamos a su servicio</textarea>
              </div>
              <div>
                <label>Texto de apoyo</label>
                <textarea rows="2" data-bind="hero.slides.0.description">Atención confiable para vehículos livianos y pesados en Bucaramanga, con calidad, rapidez y compromiso</textarea>
              </div>
            </div>
            <div class="field-pair">
              <div class="upload-mini">
                <label>Imagen escritorio</label>
                <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="hero.slides.0.image.desktop" />
                <small>1920 × 760 px · JPG/WebP.</small>
              </div>
              <div class="upload-mini">
                <label>Imagen móvil</label>
                <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="hero.slides.0.image.mobile" />
                <small>1080 × 1350 px · JPG/WebP.</small>
              </div>
            </div>
            <div class="field-pair">
              <div>
                <label>Botón rojo</label>
                <input value="Solicitar servicio" data-bind="hero.slides.0.primaryCta.label" />
              </div>
              <div>
                <label>Botón secundario</label>
                <input value="Nuestros servicios" data-bind="hero.slides.0.secondaryCta.label" />
              </div>
            </div>
          </article>

          <article class="slide-card ghost-slide">
            <div class="slide-head"><b>Slide 02</b><span>Borrador</span></div>
            <div class="empty-preview">+ Subir imagen</div>
            <label>Título grande</label>
            <input placeholder="Ej: Servicio inmediato para tu flota" data-bind="hero.slides.1.title" />
            <label>Descripción</label>
            <textarea rows="2" placeholder="Texto corto del banner" data-bind="hero.slides.1.description"></textarea>
            <button class="outline-btn">Agregar imagen del slide</button>
          </article>

          <article class="slide-card ghost-slide">
            <div class="slide-head"><b>Slide 03</b><span>Borrador</span></div>
            <div class="empty-preview">+ Subir imagen</div>
            <label>Título grande</label>
            <input placeholder="Ej: Llantas, alineación y balanceo" data-bind="hero.slides.2.title" />
            <label>Descripción</label>
            <textarea rows="2" placeholder="Texto corto del banner" data-bind="hero.slides.2.description"></textarea>
            <button class="outline-btn">Agregar imagen del slide</button>
          </article>
        </div>

        <div class="connection-card">
          <h3>Cómo se conecta</h3>
          <p><code>hero.slides[n]</code> alimenta el fondo del hero, título, descripción, botones, flechas y puntos del slider. En escritorio usar <code>image.desktop</code>. En móvil usar <code>image.mobile</code>.</p>
        </div>
      </section>

      <section class="panel" id="tab-destacados">
        <div class="section-heading">
          <span>Tarjetas bajo el banner</span>
          <h2>Beneficios destacados</h2>
          <p>Estas son las tres tarjetas pequeñas que aparecen debajo del hero.</p>
        </div>

        <div class="cards-editor three">
          <article class="edit-card">
            <img src="../assets/img/mini-24h.jpg" alt="Atención 24 horas" />
            <label>Título</label>
            <input value="Atención 24 horas" data-bind="heroHighlights.0.title" />
            <label>Subtítulo</label>
            <input value="Siempre disponibles" data-bind="heroHighlights.0.subtitle" />
            <label>Imagen</label>
            <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="heroHighlights.0.image" />
            <small>600 × 340 px · JPG/WebP · máximo 700 KB.</small>
          </article>
          <article class="edit-card">
            <img src="../assets/img/mini-equipo.jpg" alt="Equipo especializado" />
            <label>Título</label>
            <input value="Equipo especializado" data-bind="heroHighlights.1.title" />
            <label>Subtítulo</label>
            <input value="Tecnología de punta" data-bind="heroHighlights.1.subtitle" />
            <label>Imagen</label>
            <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="heroHighlights.1.image" />
            <small>600 × 340 px · JPG/WebP · máximo 700 KB.</small>
          </article>
          <article class="edit-card">
            <img src="../assets/img/mini-pesados.jpg" alt="Livianos y pesados" />
            <label>Título</label>
            <input value="Para livianos y pesados" data-bind="heroHighlights.2.title" />
            <label>Subtítulo</label>
            <input value="Todos los vehículos" data-bind="heroHighlights.2.subtitle" />
            <label>Imagen</label>
            <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="heroHighlights.2.image" />
            <small>600 × 340 px · JPG/WebP · máximo 700 KB.</small>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-nosotros">
        <div class="section-heading">
          <span>Nosotros</span>
          <h2>Texto corporativo e imagen de fachada</h2>
          <p>Controla la sección donde se explica quiénes son y qué hacen.</p>
        </div>

        <div class="form-grid">
          <article class="form-card wide">
            <h3>Contenido principal</h3>
            <label>Etiqueta roja</label>
            <input value="Nosotros" data-bind="about.kicker" />
            <label>Título</label>
            <textarea rows="2" data-bind="about.title">Más que llantas, somos tu aliado en el camino</textarea>
            <label>Descripción</label>
            <textarea rows="5" data-bind="about.description">En Servillantas El Puente ofrecemos soluciones integrales para el cuidado y mantenimiento de tu vehículo. Contamos con personal calificado, equipos de última tecnología y atención 24 horas para brindarte seguridad y tranquilidad en cada viaje.</textarea>
            <div class="field-pair">
              <div><label>Botón</label><input value="Conócenos más" data-bind="about.cta.label" /></div>
              <div><label>Destino</label><input value="#contacto" data-bind="about.cta.href" /></div>
            </div>
          </article>
          <article class="form-card image-side">
            <h3>Imagen de fachada</h3>
            <img src="../assets/img/about-building.jpg" alt="Fachada" />
            <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="about.image" />
            <small>Recomendado: 960 × 620 px · JPG/WebP · máximo 1 MB.</small>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-valores">
        <div class="section-heading">
          <span>Misión / Visión / Historia</span>
          <h2>Tarjetas institucionales</h2>
          <p>Estas tarjetas deben ser cortas para que no se rompa la altura del diseño.</p>
        </div>

        <div class="cards-editor three">
          <article class="edit-card compact">
            <div class="big-icon">🎯</div>
            <label>Título</label>
            <input value="Misión" data-bind="values.0.title" />
            <label>Texto</label>
            <textarea rows="5" data-bind="values.0.description">Brindar soluciones integrales en servicios de llantas y mantenimiento automotriz, con calidad, honestidad y atención 24 horas.</textarea>
            <small>Máximo recomendado: 180 caracteres.</small>
          </article>
          <article class="edit-card compact">
            <div class="big-icon">👁️</div>
            <label>Título</label>
            <input value="Visión" data-bind="values.1.title" />
            <label>Texto</label>
            <textarea rows="5" data-bind="values.1.description">Ser la serviteca líder en Bucaramanga y la región, reconocida por excelencia, innovación y compromiso.</textarea>
            <small>Máximo recomendado: 180 caracteres.</small>
          </article>
          <article class="edit-card compact">
            <div class="big-icon">👥</div>
            <label>Título</label>
            <input value="Historia" data-bind="values.2.title" />
            <label>Texto</label>
            <textarea rows="5" data-bind="values.2.description">Nacimos con el propósito de ofrecer un servicio confiable y oportuno para conductores y empresas de transporte.</textarea>
            <small>Máximo recomendado: 180 caracteres.</small>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-servicios">
        <div class="section-heading">
          <span>Servicios</span>
          <h2>Listado editable de servicios</h2>
          <p>Cada servicio tiene número, nombre, descripción, icono e imagen. El equipo puede programar agregar, ocultar, ordenar o eliminar.</p>
        </div>

        <div class="toolbar-line">
          <button class="primary-btn small">+ Agregar servicio</button>
          <button class="ghost-btn small">Reordenar servicios</button>
          <span>Imagen recomendada: 600 × 360 px · JPG/WebP · máximo 700 KB.</span>
        </div>

        <div class="service-editor-list">
          <article class="service-edit-row">
            <span class="service-number">01</span><img src="../assets/img/service-montallantas.jpg" alt="Montallantas" />
            <div><label>Nombre</label><input value="Montallantas y reparación" data-bind="services.0.title"></div>
            <div><label>Descripción</label><textarea rows="2" data-bind="services.0.description">Montaje profesional, reparación de llantas y solución de pinchazos.</textarea></div>
            <button class="icon-btn">↕</button>
          </article>
          <article class="service-edit-row">
            <span class="service-number">02</span><img src="../assets/img/service-alineacion.jpg" alt="Alineación" />
            <div><label>Nombre</label><input value="Alineación y balanceo" data-bind="services.1.title"></div>
            <div><label>Descripción</label><textarea rows="2" data-bind="services.1.description">Mayor estabilidad, seguridad y vida útil para tus llantas.</textarea></div>
            <button class="icon-btn">↕</button>
          </article>
          <article class="service-edit-row">
            <span class="service-number">03</span><img src="../assets/img/service-aceite.jpg" alt="Cambio de aceite" />
            <div><label>Nombre</label><input value="Cambio de aceite" data-bind="services.2.title"></div>
            <div><label>Descripción</label><textarea rows="2" data-bind="services.2.description">Aceites de alta calidad para el óptimo rendimiento de tu motor.</textarea></div>
            <button class="icon-btn">↕</button>
          </article>
          <article class="service-edit-row muted-row">
            <span class="service-number">+6</span><div class="more-services">El mismo patrón aplica para Cambio de fluidos, Carga de nitrógeno, Venta de llantas, Rines, Línea SPA automotriz y Re-encauche.</div>
          </article>
        </div>

        <div class="connection-card">
          <h3>Nota para programación</h3>
          <p>La landing actual tiene 9 servicios fijos. El backend puede permitir máximo 12 para no deformar el diseño. Si hay más de 9, el equipo debe definir paginación, carrusel o “Ver más”.</p>
        </div>
      </section>

      <section class="panel" id="tab-aliados">
        <div class="section-heading">
          <span>Aliados</span>
          <h2>Logos de empresas que respaldan</h2>
          <p>Se puede manejar como imagen de franja completa o como logos individuales para mayor control.</p>
        </div>

        <div class="form-grid">
          <article class="form-card wide image-side">
            <h3>Franja actual de logos</h3>
            <img class="wide-preview" src="../assets/img/alliances-strip.png" alt="Aliados" />
            <input type="file" accept="image/png,image/svg+xml,image/webp" data-bind="allies.stripImage" />
            <small>Recomendado franja: 1600 × 260 px · PNG/SVG/WebP · fondo transparente o blanco.</small>
          </article>
          <article class="form-card">
            <h3>Texto de sección</h3>
            <label>Etiqueta</label>
            <input value="Aliados que nos respaldan" data-bind="allies.kicker" />
            <label>Título</label>
            <textarea rows="3" data-bind="allies.title">Trabajamos junto a las mejores empresas de transporte</textarea>
          </article>
        </div>

        <div class="connection-card">
          <h3>Conexión abierta alternativa</h3>
          <p>Para una versión más profesional, el equipo puede reemplazar la franja por <code>allies.items[]</code> con logos individuales: nombre, logo, enlace, orden y visible.</p>
        </div>
      </section>

      <section class="panel" id="tab-cta">
        <div class="section-heading">
          <span>Franja roja</span>
          <h2>Llamado a la acción 24 horas</h2>
          <p>Bloque rojo antes del contacto. Debe ser corto, directo y enfocado en atención inmediata.</p>
        </div>

        <div class="form-grid">
          <article class="form-card wide">
            <h3>Contenido CTA</h3>
            <label>Texto pequeño</label>
            <input value="¿Necesitas ayuda ahora?" data-bind="emergencyCta.preTitle" />
            <label>Título</label>
            <input value="¡Estamos disponibles 24 horas!" data-bind="emergencyCta.title" />
            <label>Descripción</label>
            <input value="Llámanos o escríbenos y recibe atención inmediata." data-bind="emergencyCta.description" />
            <div class="field-pair">
              <div><label>Texto botón</label><input value="Solicitar servicio" data-bind="emergencyCta.button.label" /></div>
              <div><label>Enlace botón</label><input value="https://wa.me/573107922579" data-bind="emergencyCta.button.url" /></div>
            </div>
          </article>
          <article class="live-cta-preview">
            <span>☎</span>
            <div>
              <p>¿Necesitas ayuda ahora?</p>
              <h3>¡Estamos disponibles 24 horas!</h3>
              <small>Llámanos o escríbenos y recibe atención inmediata.</small>
            </div>
            <button>Solicitar servicio</button>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-contacto">
        <div class="section-heading">
          <span>Contacto y ubicación</span>
          <h2>Teléfonos, dirección, correo, horarios y mapa</h2>
          <p>Esta información alimenta el footer, botones y enlaces rápidos.</p>
        </div>

        <div class="form-grid">
          <article class="form-card">
            <h3>Datos principales</h3>
            <label>Título sección</label>
            <input value="Febtamos para servirte" data-bind="contact.title" />
            <label>Teléfono 1</label>
            <input value="310 792 2579" data-bind="contact.phones.0" />
            <label>Teléfono 2</label>
            <input value="637 1102" data-bind="contact.phones.1" />
            <label>Correo</label>
            <input type="email" value="servillantaselpuente2014@gmail.com" data-bind="contact.email" />
            <label>NIT</label>
            <input value="91.350.017-7" data-bind="business.nit" />
          </article>
          <article class="form-card">
            <h3>Dirección y horarios</h3>
            <label>Dirección</label>
            <textarea rows="3" data-bind="contact.address">Calle 70 No. 20W-38 Local 3, Bucaramanga, Santander</textarea>
            <label>Horario</label>
            <textarea rows="3" data-bind="contact.schedule">Servicio 24 horas\nLunes a Domingo</textarea>
            <label>Google Maps URL</label>
            <input value="https://maps.google.com/?q=Servillantas+El+Puente+Bucaramanga" data-bind="contact.mapUrl" />
          </article>
          <article class="form-card wide image-side">
            <h3>Imagen del mapa</h3>
            <img class="wide-preview" src="../assets/img/map-location.jpg" alt="Mapa" />
            <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="contact.mapImage" />
            <small>Recomendado: 1200 × 600 px · JPG/WebP · máximo 900 KB. También puede conectarse con iframe de Google Maps.</small>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-seo">
        <div class="section-heading">
          <span>SEO y redes</span>
          <h2>Información para buscadores y enlaces compartidos</h2>
          <p>Esto ayuda cuando la landing se comparte por WhatsApp, Facebook o Google.</p>
        </div>

        <div class="form-grid">
          <article class="form-card wide">
            <h3>Metadatos</h3>
            <label>Título SEO</label>
            <input value="Servillantas El Puente | Servicio 24 horas en Bucaramanga" data-bind="seo.title" />
            <label>Descripción SEO</label>
            <textarea rows="4" data-bind="seo.description">Servicio de montallantas, alineación, balanceo, cambio de aceite, venta de llantas y atención 24 horas para vehículos livianos y pesados en Bucaramanga.</textarea>
            <label>Palabras clave</label>
            <input value="servillantas, llantas, Bucaramanga, montallantas, alineación, balanceo, 24 horas" data-bind="seo.keywords" />
          </article>
          <article class="form-card image-side">
            <h3>Imagen para compartir</h3>
            <div class="empty-preview social-preview">1200 × 630</div>
            <input type="file" accept="image/jpeg,image/png,image/webp" data-bind="seo.ogImage" />
            <small>Facebook/WhatsApp: 1200 × 630 px · JPG/PNG/WebP.</small>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-publicar">
        <div class="section-heading">
          <span>Publicación</span>
          <h2>Guardar, previsualizar y publicar cambios</h2>
          <p>Pantalla sugerida para que el cliente revise antes de publicar cambios reales en la landing.</p>
        </div>

        <div class="publish-grid">
          <article class="publish-card">
            <span>01</span>
            <h3>Guardar borrador</h3>
            <p>Guarda los cambios sin mostrarlos públicamente.</p>
            <button class="outline-btn" id="saveDraftBtn">Guardar borrador</button>
          </article>
          <article class="publish-card">
            <span>02</span>
            <h3>Previsualizar</h3>
            <p>Abre la landing con los cambios temporales.</p>
            <a class="outline-btn" href="/" target="_blank" style="text-decoration:none;">Vista previa</a>
          </article>
          <article class="publish-card hot">
            <span>03</span>
            <h3>Publicar</h3>
            <p>Envía los cambios a producción.</p>
            <button class="primary-btn" id="publishBtn">Publicar cambios</button>
          </article>
        </div>

        <article class="json-card">
          <div class="json-head">
            <h3>JSON demo generado</h3>
            <button id="copyJsonBtn">Copiar</button>
          </div>
          <pre id="jsonOutput">{
  "message": "Presiona Exportar JSON demo para ver la estructura editable."
}</pre>
        </article>
      </section>
    </main>
  </div>

  <div class="toast" id="toast">Borrador guardado correctamente</div>
  <script src="admin.js"></script>
</body>
</html>
