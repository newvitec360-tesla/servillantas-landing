<?php
/**
 * Servillantas El Puente - Landing Page Dinámica (Desktop)
 * Lee el contenido publicado desde la base de datos.
 * Si no hay contenido en DB, usa los valores por defecto hardcodeados.
 */

// --- Conexión a DB y carga de contenido ---
$content = null;
try {
    $envFile = __DIR__ . '/../../.env';
    $env = [];
    if (file_exists($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim(trim($value), '"\'');
        }
    }

    $host = $env['DB_HOST'] ?? '127.0.0.1';
    $port = $env['DB_PORT'] ?? '3306';
    $db   = $env['DB_DATABASE'] ?? 'servilla_admin';
    $user = $env['DB_USERNAME'] ?? 'servilla_admin';
    $pass = $env['DB_PASSWORD'] ?? '';

    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->query("SELECT content_json FROM landing_pages WHERE status = 'published' ORDER BY updated_at DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['content_json'])) {
        $content = json_decode($row['content_json'], true);
    }
} catch (Exception $e) {
    // Silently fail — use defaults
    error_log('[LANDING_DESKTOP] DB error: ' . $e->getMessage());
}

// --- Helper para escapar HTML ---
function e(?string $val, string $default = ''): string {
    return htmlspecialchars($val ?? $default, ENT_QUOTES, 'UTF-8');
}

// --- Valores con fallback ---
$about_kicker     = e($content['about.kicker'] ?? $content['about']['kicker'] ?? null, 'Nosotros');
$about_title      = e($content['about.title'] ?? $content['about']['title'] ?? null, 'Más que llantas, somos tu aliado en el camino');
$about_desc       = e($content['about.description'] ?? $content['about']['description'] ?? null, 'En Servillantas El Puente ofrecemos soluciones integrales para el cuidado y mantenimiento de tu vehículo. Contamos con personal calificado, equipos de última tecnología y atención 24 horas para brindarte seguridad y tranquilidad en cada viaje.');
$about_cta_label  = e($content['about.cta.label'] ?? $content['about']['cta']['label'] ?? null, 'Conócenos más');
$about_cta_href   = e($content['about.cta.href'] ?? $content['about']['cta']['href'] ?? null, '#contacto');

$hero_title       = e($content['hero.slides.0.title'] ?? $content['hero']['slides'][0]['title'] ?? null, 'En Servillantas 24 Horas, trabajamos a su servicio');
$hero_desc        = e($content['hero.slides.0.description'] ?? $content['hero']['slides'][0]['description'] ?? null, 'Atención confiable para vehículos livianos y pesados en Bucaramanga, con calidad, rapidez y compromiso');
$hero_cta_label   = e($content['hero.slides.0.primaryCta.label'] ?? null, 'Solicitar servicio');
$hero_2nd_label   = e($content['hero.slides.0.secondaryCta.label'] ?? null, 'Nuestros servicios');

$contact_title    = e($content['contact.title'] ?? $content['contact']['title'] ?? null, 'Estamos para servirte');
$contact_phone0   = e($content['contact.phones.0'] ?? $content['contact']['phones'][0] ?? null, '310 792 2579');
$contact_phone1   = e($content['contact.phones.1'] ?? $content['contact']['phones'][1] ?? null, '637 1102');
$contact_address  = e($content['contact.address'] ?? $content['contact']['address'] ?? null, 'Calle 70 No. 20W-38 Local 3, Bucaramanga, Santander');
$contact_email    = e($content['contact.email'] ?? $content['contact']['email'] ?? null, 'servillantaselpuente2014@gmail.com');

$allies_kicker    = e($content['allies.kicker'] ?? $content['allies']['kicker'] ?? null, 'Aliados que nos respaldan');
$allies_title     = e($content['allies.title'] ?? $content['allies']['title'] ?? null, 'Trabajamos junto a las mejores empresas de transporte');

$cta_pretitle     = e($content['emergencyCta.preTitle'] ?? $content['emergencyCta']['preTitle'] ?? null, '¿Necesitas ayuda ahora?');
$cta_title        = e($content['emergencyCta.title'] ?? $content['emergencyCta']['title'] ?? null, '¡Estamos disponibles 24 horas!');
$cta_desc         = e($content['emergencyCta.description'] ?? $content['emergencyCta']['description'] ?? null, 'Llámanos o escríbenos y recibe atención inmediata.');
$cta_btn_label    = e($content['emergencyCta.button.label'] ?? $content['emergencyCta']['button']['label'] ?? null, 'Solicitar servicio');

$seo_title        = e($content['seo.title'] ?? $content['seo']['title'] ?? null, 'Servillantas El Puente | Servicio 24 horas en Bucaramanga');
$seo_desc         = e($content['seo.description'] ?? $content['seo']['description'] ?? null, 'Servicio de montallantas, alineación, balanceo, cambio de aceite, venta de llantas y atención 24 horas para vehículos livianos y pesados en Bucaramanga.');

$values = [
    ['icon' => 'target', 'title' => e($content['values.0.title'] ?? $content['values'][0]['title'] ?? null, 'Misión'),
     'desc' => e($content['values.0.description'] ?? $content['values'][0]['description'] ?? null, 'Brindar soluciones integrales en servicios de llantas y mantenimiento automotriz, con calidad, honestidad y atención 24 horas.')],
    ['icon' => 'eye', 'title' => e($content['values.1.title'] ?? $content['values'][1]['title'] ?? null, 'Visión'),
     'desc' => e($content['values.1.description'] ?? $content['values'][1]['description'] ?? null, 'Ser la serviteca líder en Bucaramanga y la región, reconocida por excelencia, innovación y compromiso.')],
    ['icon' => 'people', 'title' => e($content['values.2.title'] ?? $content['values'][2]['title'] ?? null, 'Historia'),
     'desc' => e($content['values.2.description'] ?? $content['values'][2]['description'] ?? null, 'Nacimos con el propósito de ofrecer un servicio confiable y oportuno para conductores y empresas de transporte.')],
];

$services_default = [
    ['num' => '01', 'title' => 'Montallantas y reparación', 'desc' => 'Montaje profesional, reparación de llantas y solución de pinchazos.', 'img' => 'service-montallantas.jpg'],
    ['num' => '02', 'title' => 'Alineación y balanceo', 'desc' => 'Mayor estabilidad, seguridad y vida útil para tus llantas.', 'img' => 'service-alineacion.jpg'],
    ['num' => '03', 'title' => 'Cambio de aceite', 'desc' => 'Aceites de alta calidad para el óptimo rendimiento de tu motor.', 'img' => 'service-aceite.jpg'],
    ['num' => '04', 'title' => 'Cambio de fluidos', 'desc' => 'Renovamos los fluidos esenciales para el buen funcionamiento de tu vehículo.', 'img' => 'service-fluidos.jpg'],
    ['num' => '05', 'title' => 'Carga de nitrógeno', 'desc' => 'Mejora la presión, estabilidad y rendimiento de tus llantas.', 'img' => 'service-nitrogeno.jpg'],
    ['num' => '06', 'title' => 'Venta de llantas', 'desc' => 'Marcas reconocidas para todo tipo de vehículos y necesidades.', 'img' => 'service-llantas.jpg'],
    ['num' => '07', 'title' => 'Rines', 'desc' => 'Variedad de diseños y tamaños con la mejor calidad.', 'img' => 'service-rines.jpg'],
    ['num' => '08', 'title' => 'Línea SPA automotriz', 'desc' => 'Productos especializados para el cuidado y embellecimiento de tu vehículo.', 'img' => 'service-spa.jpg'],
    ['num' => '09', 'title' => 'Re-encauche', 'desc' => 'Solución económica y ecológica que extiende la vida útil de tus llantas.', 'img' => 'service-reencauche.jpg'],
];

// Build services from DB or defaults
$services = [];
foreach ($services_default as $i => $def) {
    $services[] = [
        'num'   => $def['num'],
        'title' => e($content["services.{$i}.title"] ?? $content['services'][$i]['title'] ?? null, $def['title']),
        'desc'  => e($content["services.{$i}.description"] ?? $content['services'][$i]['description'] ?? null, $def['desc']),
        'img'   => $def['img'],
    ];
}

$highlights_default = [
    ['title' => 'Atención 24 horas', 'sub' => 'Siempre disponibles', 'img' => 'mini-24h.jpg'],
    ['title' => 'Equipo especializado', 'sub' => 'Tecnología de punta', 'img' => 'mini-equipo.jpg'],
    ['title' => 'Para livianos y pesados', 'sub' => 'Todos los vehículos', 'img' => 'mini-pesados.jpg'],
];
$highlights = [];
foreach ($highlights_default as $i => $def) {
    $highlights[] = [
        'title' => e($content["heroHighlights.{$i}.title"] ?? $content['heroHighlights'][$i]['title'] ?? null, $def['title']),
        'sub'   => e($content["heroHighlights.{$i}.subtitle"] ?? $content['heroHighlights'][$i]['subtitle'] ?? null, $def['sub']),
        'img'   => $def['img'],
    ];
}

$nit       = e($content['business.nit'] ?? $content['business']['nit'] ?? null, '91.350.017-7');
$copyright = e($content['business.copyright'] ?? $content['business']['copyright'] ?? null, '© 2024 Servillantas El Puente. Todos los derechos reservados.');

$bubbles = ['◍','▣','◒','◊','N₂','◍','✺','✣','▥'];
$sides   = ['left','right'];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $seo_title ?></title>
  <meta name="description" content="<?= $seo_desc ?>" />
  <link rel="stylesheet" href="desktop.css" />
</head>
<body>
  <header class="site-header" id="inicio">
    <div class="header-shell">
      <a class="brand-group" href="#inicio" aria-label="Ir al inicio">
        <img class="brand-main" src="../assets/img/logo-servillantas.png" alt="Servillantas El Puente" />
        <span class="brand-separator" aria-hidden="true"></span>
        <img class="brand-hours" src="../assets/img/logo-24-horas.png" alt="Servicio las 24 horas" />
      </a>
      <nav class="main-nav" aria-label="Navegación principal">
        <a class="active" href="#inicio">Inicio</a>
        <a href="#nosotros">Nosotros</a>
        <a href="#servicios">Servicios</a>
        <a href="#aliados">Aliados</a>
        <a href="#contacto">Contacto</a>
      </nav>
      <a class="btn btn-red header-cta" href="https://wa.me/573107922579" target="_blank" rel="noopener">
        <span class="btn-icon" aria-hidden="true">▣</span>
        <?= $hero_cta_label ?>
      </a>
    </div>
  </header>

  <main>
    <section class="hero" aria-label="Hero principal">
      <button class="hero-arrow hero-arrow-left" aria-label="Anterior">‹</button>
      <div class="hero-content">
        <p class="eyebrow">Servicio automotriz 24 horas</p>
        <h1><?= nl2br($hero_title) ?></h1>
        <p class="hero-copy"><?= $hero_desc ?></p>
        <div class="hero-actions">
          <a class="btn btn-red" href="https://wa.me/573107922579" target="_blank" rel="noopener"><span class="btn-icon">▣</span> <?= $hero_cta_label ?></a>
          <a class="btn btn-glass" href="#servicios"><span class="btn-icon">☑</span> <?= $hero_2nd_label ?></a>
        </div>
      </div>
      <button class="hero-arrow hero-arrow-right" aria-label="Siguiente">›</button>
    </section>

    <section class="hero-tabs" aria-label="Beneficios destacados">
<?php foreach ($highlights as $i => $h): ?>
      <article class="tab-card<?= $i === 0 ? ' active-card' : '' ?>">
        <img src="../assets/img/<?= $h['img'] ?>" alt="<?= $h['title'] ?>" />
        <div><h3><?= $h['title'] ?></h3><p><?= $h['sub'] ?></p></div>
      </article>
<?php endforeach; ?>
    </section>
    <div class="hero-dots" aria-hidden="true"><span class="on"></span><span></span><span></span></div>

    <section class="about section-shell" id="nosotros">
      <div class="about-copy">
        <span class="section-kicker"><?= $about_kicker ?></span>
        <h2><?= nl2br($about_title) ?></h2>
        <p><?= $about_desc ?></p>
        <a class="btn btn-outline" href="<?= $about_cta_href ?>"><span class="btn-icon">✚</span> <?= $about_cta_label ?></a>
      </div>
      <figure class="about-image">
        <img src="../assets/img/about-building.jpg" alt="Fachada Servillantas El Puente" />
      </figure>
    </section>

    <section class="value-cards section-shell" aria-label="Misión, visión e historia">
<?php foreach ($values as $v): ?>
      <article>
        <span class="card-icon <?= $v['icon'] ?>"></span>
        <div><h3><?= $v['title'] ?></h3><p><?= $v['desc'] ?></p></div>
      </article>
<?php endforeach; ?>
    </section>

    <section class="services section-shell" id="servicios">
      <div class="section-title centered">
        <span class="section-kicker">Servicios</span>
        <h2>Soluciones completas para<br>tu vehículo</h2>
        <span class="red-rule"></span>
      </div>
      <div class="service-timeline">
<?php foreach ($services as $i => $s): $side = $sides[$i % 2]; ?>
        <article class="service-item <?= $side ?>">
<?php if ($side === 'left'): ?>
          <div class="service-card"><span class="number"><?= $s['num'] ?></span><div><h3><?= $s['title'] ?></h3><p><?= $s['desc'] ?></p></div></div>
          <img src="../assets/img/<?= $s['img'] ?>" alt="<?= $s['title'] ?>" />
          <span class="bubble"><?= $bubbles[$i] ?? '◍' ?></span>
<?php else: ?>
          <span class="bubble"><?= $bubbles[$i] ?? '◍' ?></span>
          <img src="../assets/img/<?= $s['img'] ?>" alt="<?= $s['title'] ?>" />
          <div class="service-card"><span class="number"><?= $s['num'] ?></span><div><h3><?= $s['title'] ?></h3><p><?= $s['desc'] ?></p></div></div>
<?php endif; ?>
        </article>
<?php endforeach; ?>
      </div>
    </section>

    <section class="allies section-shell" id="aliados">
      <div class="allies-card">
        <span class="section-kicker"><?= $allies_kicker ?></span>
        <h2><?= $allies_title ?></h2>
        <img src="../assets/img/alliances-strip.png" alt="Logos de aliados de transporte" />
      </div>
    </section>

    <section class="red-cta section-shell" aria-label="Solicitud de servicio">
      <div class="phone-circle">☎</div>
      <div>
        <p><?= $cta_pretitle ?></p>
        <h2><?= $cta_title ?></h2>
        <span><?= $cta_desc ?></span>
      </div>
      <a class="btn btn-white" href="https://wa.me/573107922579" target="_blank" rel="noopener"><span class="btn-icon">▣</span> <?= $cta_btn_label ?></a>
    </section>

    <section class="contact" id="contacto">
      <div class="contact-shell">
        <div class="contact-info">
          <span class="section-kicker">Contáctanos</span>
          <h2><?= $contact_title ?></h2>
          <ul>
            <li><span>☎</span> <?= $contact_phone0 ?> - <?= $contact_phone1 ?></li>
            <li><span>⌖</span> <?= nl2br($contact_address) ?></li>
            <li><span>✉</span> <?= $contact_email ?></li>
            <li><span>◷</span> Servicio 24 horas<br>Lunes a Domingo</li>
          </ul>
          <div class="socials" aria-label="Redes sociales">
            <a href="#" aria-label="WhatsApp">◌</a><a href="#" aria-label="Facebook">f</a><a href="#" aria-label="Instagram">◎</a>
          </div>
        </div>
        <figure class="map-card"><img src="../assets/img/map-location.jpg" alt="Mapa de ubicación Servillantas El Puente" /></figure>
      </div>
    </section>
  </main>

  <footer class="footer-bar">
    <div class="section-shell footer-inner">
      <p><?= $copyright ?></p>
      <p>NIT. <?= $nit ?></p>
    </div>
  </footer>

  <script src="../assets/js/desktop.js"></script>
</body>
</html>
