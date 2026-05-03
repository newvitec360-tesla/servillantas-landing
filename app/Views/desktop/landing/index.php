<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Servillantas El Puente | Escritorio</title>
  <link rel="stylesheet" href="/public/assets/landing/desktop.css" />
</head>
<body>
  <header class="site-header" id="inicio">
    <div class="header-shell">
      <a class="brand-group" href="#inicio" aria-label="Ir al inicio">
        <img class="brand-main" src="/public/assets/landing/img/logo-servillantas.png" alt="Servillantas El Puente" />
        <span class="brand-separator" aria-hidden="true"></span>
        <img class="brand-hours" src="/public/assets/landing/img/logo-24-horas.png" alt="Servicio las 24 horas" />
      </a>

      <nav class="main-nav" aria-label="Navegación principal">
        <a class="active" href="#inicio">Inicio</a>
        <a href="#nosotros">Nosotros</a>
        <a href="#servicios">Servicios</a>
        <a href="#aliados">Aliados</a>
        <a href="#contacto">Contacto</a>
      </nav>

      <a class="btn btn-glass" href="/index.php?r=/login" style="margin-right:15px; border-color: rgba(255,255,255,0.3);">🔑 Acceso CRM</a>
      <a class="btn btn-red header-cta" href="https://wa.me/573107922579" target="_blank" rel="noopener">
        <span class="btn-icon" aria-hidden="true">▣</span>
        Solicitar servicio
      </a>
    </div>
  </header>

  <main>
    <section class="hero" aria-label="Hero principal">
      <button class="hero-arrow hero-arrow-left" aria-label="Anterior">‹</button>
      <div class="hero-content">
        <p class="eyebrow">Servicio automotriz 24 horas</p>
        <h1><?= nl2br(htmlspecialchars($data['hero']['slides'][0]['title'] ?? 'En Servillantas 24 Horas,\ntrabajamos a su servicio')) ?></h1>
        <p class="hero-copy"><?= htmlspecialchars($data['hero']['slides'][0]['description'] ?? 'Atención confiable para vehículos livianos y pesados en Bucaramanga, con calidad, rapidez y compromiso') ?></p>
        <div class="hero-actions">
          <a class="btn btn-red" href="https://wa.me/573107922579" target="_blank" rel="noopener"><span class="btn-icon">▣</span> Solicitar servicio</a>
          <a class="btn btn-glass" href="#servicios"><span class="btn-icon">☑</span> Nuestros servicios</a>
        </div>
      </div>
      <button class="hero-arrow hero-arrow-right" aria-label="Siguiente">›</button>
    </section>

    <section class="hero-tabs" aria-label="Beneficios destacados">
      <article class="tab-card active-card">
        <img src="/public/assets/landing/img/mini-24h.jpg" alt="Atención 24 horas" />
        <div>
          <h3><?= htmlspecialchars($data['heroHighlights'][0]['title'] ?? 'Atención 24 horas') ?></h3>
          <p><?= htmlspecialchars($data['heroHighlights'][0]['subtitle'] ?? 'Siempre disponibles') ?></p>
        </div>
      </article>
      <article class="tab-card">
        <img src="/public/assets/landing/img/mini-equipo.jpg" alt="Equipo especializado" />
        <div>
          <h3><?= htmlspecialchars($data['heroHighlights'][1]['title'] ?? 'Equipo especializado') ?></h3>
          <p><?= htmlspecialchars($data['heroHighlights'][1]['subtitle'] ?? 'Tecnología de punta') ?></p>
        </div>
      </article>
      <article class="tab-card">
        <img src="/public/assets/landing/img/mini-pesados.jpg" alt="Vehículos livianos y pesados" />
        <div>
          <h3><?= htmlspecialchars($data['heroHighlights'][2]['title'] ?? 'Para livianos y pesados') ?></h3>
          <p><?= htmlspecialchars($data['heroHighlights'][2]['subtitle'] ?? 'Todos los vehículos') ?></p>
        </div>
      </article>
    </section>
    <div class="hero-dots" aria-hidden="true"><span class="on"></span><span></span><span></span></div>

    <section class="about section-shell" id="nosotros">
      <div class="about-copy">
        <span class="section-kicker">Nosotros</span>
        <h2><?= nl2br(htmlspecialchars($data['about']['title'] ?? 'Más que llantas, somos\ntu aliado en el camino')) ?></h2>
        <p><?= htmlspecialchars($data['about']['description'] ?? 'En Servillantas El Puente ofrecemos soluciones integrales para el cuidado y mantenimiento de tu vehículo. Contamos con personal calificado, equipos de última tecnología y atención 24 horas para brindarte seguridad y tranquilidad en cada viaje.') ?></p>
        <a class="btn btn-outline" href="#contacto"><span class="btn-icon">✚</span> Conócenos más</a>
      </div>
      <figure class="about-image">
        <img src="/public/assets/landing/img/about-building.jpg" alt="Fachada Servillantas El Puente" />
      </figure>
    </section>

    <section class="value-cards section-shell" aria-label="Misión, visión e historia">
      <article>
        <span class="card-icon target"></span>
        <div>
          <h3>Misión</h3>
          <p><?= htmlspecialchars($data['values'][0]['description'] ?? 'Brindar soluciones integrales en servicios de llantas y mantenimiento automotriz, con calidad, honestidad y atención 24 horas.') ?></p>
        </div>
      </article>
      <article>
        <span class="card-icon eye"></span>
        <div>
          <h3>Visión</h3>
          <p><?= htmlspecialchars($data['values'][1]['description'] ?? 'Ser la serviteca líder en Bucaramanga y la región, reconocida por excelencia, innovación y compromiso.') ?></p>
        </div>
      </article>
      <article>
        <span class="card-icon people"></span>
        <div>
          <h3>Historia</h3>
          <p><?= htmlspecialchars($data['values'][2]['description'] ?? 'Nacimos con el propósito de ofrecer un servicio confiable y oportuno para conductores y empresas de transporte.') ?></p>
        </div>
      </article>
    </section>

    <section class="services section-shell" id="servicios">
      <div class="section-title centered">
        <span class="section-kicker">Servicios</span>
        <h2>Soluciones completas para<br>tu vehículo</h2>
        <span class="red-rule"></span>
      </div>

      <div class="service-timeline">
        <article class="service-item left">
          <div class="service-card"><span class="number">01</span><div><h3>Montallantas y reparación</h3><p>Montaje profesional, reparación de llantas y solución de pinchazos.</p></div></div>
          <img src="/public/assets/landing/img/service-montallantas.jpg" alt="Montallantas y reparación" />
          <span class="bubble">◍</span>
        </article>

        <article class="service-item right">
          <span class="bubble">▣</span>
          <img src="/public/assets/landing/img/service-alineacion.jpg" alt="Alineación y balanceo" />
          <div class="service-card"><span class="number">02</span><div><h3>Alineación y balanceo</h3><p>Mayor estabilidad, seguridad y vida útil para tus llantas.</p></div></div>
        </article>

        <article class="service-item left">
          <div class="service-card"><span class="number">03</span><div><h3>Cambio de aceite</h3><p>Aceites de alta calidad para el óptimo rendimiento de tu motor.</p></div></div>
          <img src="/public/assets/landing/img/service-aceite.jpg" alt="Cambio de aceite" />
          <span class="bubble">◒</span>
        </article>

        <article class="service-item right">
          <span class="bubble">◊</span>
          <img src="/public/assets/landing/img/service-fluidos.jpg" alt="Cambio de fluidos" />
          <div class="service-card"><span class="number">04</span><div><h3>Cambio de fluidos</h3><p>Renovamos los fluidos esenciales para el buen funcionamiento de tu vehículo.</p></div></div>
        </article>

        <article class="service-item left">
          <div class="service-card"><span class="number">05</span><div><h3>Carga de nitrógeno</h3><p>Mejora la presión, estabilidad y rendimiento de tus llantas.</p></div></div>
          <img src="/public/assets/landing/img/service-nitrogeno.jpg" alt="Carga de nitrógeno" />
          <span class="bubble">N₂</span>
        </article>

        <article class="service-item right">
          <span class="bubble">◍</span>
          <img src="/public/assets/landing/img/service-llantas.jpg" alt="Venta de llantas" />
          <div class="service-card"><span class="number">06</span><div><h3>Venta de llantas</h3><p>Marcas reconocidas para todo tipo de vehículos y necesidades.</p></div></div>
        </article>

        <article class="service-item left">
          <div class="service-card"><span class="number">07</span><div><h3>Rines</h3><p>Variedad de diseños y tamaños con la mejor calidad.</p></div></div>
          <img src="/public/assets/landing/img/service-rines.jpg" alt="Rines" />
          <span class="bubble">✺</span>
        </article>

        <article class="service-item right">
          <span class="bubble">✣</span>
          <img src="/public/assets/landing/img/service-spa.jpg" alt="Línea SPA automotriz" />
          <div class="service-card"><span class="number">08</span><div><h3>Línea SPA automotriz</h3><p>Productos especializados para el cuidado y embellecimiento de tu vehículo.</p></div></div>
        </article>

        <article class="service-item left">
          <div class="service-card"><span class="number">09</span><div><h3>Re-encauche</h3><p>Solución económica y ecológica que extiende la vida útil de tus llantas.</p></div></div>
          <img src="/public/assets/landing/img/service-reencauche.jpg" alt="Re-encauche" />
          <span class="bubble">▥</span>
        </article>
      </div>
    </section>

    <section class="allies section-shell" id="aliados">
      <div class="allies-card">
        <span class="section-kicker">Aliados que nos respaldan</span>
        <h2>Trabajamos junto a las mejores empresas de transporte</h2>
        <img src="/public/assets/landing/img/alliances-strip.png" alt="Logos de aliados de transporte" />
      </div>
    </section>

    <section class="red-cta section-shell" aria-label="Solicitud de servicio">
      <div class="phone-circle">☎</div>
      <div>
        <p><?= htmlspecialchars($data['emergencyCta']['preTitle'] ?? '¿Necesitas ayuda ahora?') ?></p>
        <h2><?= htmlspecialchars($data['emergencyCta']['title'] ?? '¡Estamos disponibles 24 horas!') ?></h2>
        <span><?= htmlspecialchars($data['emergencyCta']['description'] ?? 'Llámanos o escríbenos y recibe atención inmediata.') ?></span>
      </div>
      <a class="btn btn-white" href="https://wa.me/573107922579" target="_blank" rel="noopener"><span class="btn-icon">▣</span> Solicitar servicio</a>
    </section>

    <section class="contact" id="contacto">
      <div class="contact-shell">
        <div class="contact-info">
          <span class="section-kicker">Contáctanos</span>
          <h2><?= htmlspecialchars($data['contact']['title'] ?? 'Estamos para servirte') ?></h2>
          <ul>
            <li><span>☎</span> <?= htmlspecialchars(($data['contact']['phones'][0] ?? '310 792 2579') . ' - ' . ($data['contact']['phones'][1] ?? '637 1102')) ?></li>
            <li><span>⌖</span> <?= nl2br(htmlspecialchars($data['contact']['address'] ?? 'Calle 70 No. 20W-38 Local 3\nBucaramanga, Santander')) ?></li>
            <li><span>✉</span> <?= htmlspecialchars($data['contact']['email'] ?? 'servillantaselpuente2014@gmail.com') ?></li>
            <li><span>◷</span> <?= nl2br(htmlspecialchars($data['contact']['schedule'] ?? 'Servicio 24 horas\nLunes a Domingo')) ?></li>
          </ul>
          <div class="socials" aria-label="Redes sociales">
            <a href="#" aria-label="WhatsApp">◌</a><a href="#" aria-label="Facebook">f</a><a href="#" aria-label="Instagram">◎</a>
          </div>
        </div>
        <figure class="map-card"><img src="/public/assets/landing/img/map-location.jpg" alt="Mapa de ubicación Servillantas El Puente" /></figure>
      </div>
    </section>
  </main>

  <footer class="footer-bar">
    <div class="section-shell footer-inner">
      <p>© 2024 Servillantas El Puente. Todos los derechos reservados.</p>
      <p>NIT. 91.350.017-7</p>
    </div>
  </footer>

  <script src="/public/assets/landing/js/desktop.js"></script>
</body>
</html>
