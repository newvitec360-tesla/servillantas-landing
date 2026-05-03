<?php
/**
 * Servillantas El Puente - Landing Page Dinámica (Mobile)
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
    error_log('[LANDING_MOBILE] DB error: ' . $e->getMessage());
}

// --- Helper ---
function e(?string $val, string $default = ''): string {
    return htmlspecialchars($val ?? $default, ENT_QUOTES, 'UTF-8');
}

// --- Valores con fallback ---
$about_kicker    = e($content['about.kicker'] ?? $content['about']['kicker'] ?? null, 'Nosotros');
$about_title     = e($content['about.title'] ?? $content['about']['title'] ?? null, 'Más que llantas, somos tu aliado en el camino');
$about_desc      = e($content['about.description'] ?? $content['about']['description'] ?? null, 'Ofrecemos soluciones integrales para el cuidado y mantenimiento de tu vehículo, con personal calificado, equipos de última tecnología y atención permanente.');

$hero_title      = e($content['hero.slides.0.title'] ?? $content['hero']['slides'][0]['title'] ?? null, 'Trabajamos a su servicio');
$hero_desc       = e($content['hero.slides.0.description'] ?? $content['hero']['slides'][0]['description'] ?? null, 'Atención confiable para vehículos livianos y pesados en Bucaramanga.');

$contact_title   = e($content['contact.title'] ?? $content['contact']['title'] ?? null, 'Estamos para servirte');
$contact_phone0  = e($content['contact.phones.0'] ?? $content['contact']['phones'][0] ?? null, '310 792 2579');
$contact_phone1  = e($content['contact.phones.1'] ?? $content['contact']['phones'][1] ?? null, '637 1102');
$contact_address = e($content['contact.address'] ?? $content['contact']['address'] ?? null, 'Calle 70 No. 20W-38 Local 3, Bucaramanga, Santander');
$contact_email   = e($content['contact.email'] ?? $content['contact']['email'] ?? null, 'servillantaselpuente2014@gmail.com');

$allies_kicker   = e($content['allies.kicker'] ?? $content['allies']['kicker'] ?? null, 'Aliados que nos respaldan');
$allies_title    = e($content['allies.title'] ?? $content['allies']['title'] ?? null, 'Trabajamos junto a empresas de transporte');

$cta_title       = e($content['emergencyCta.title'] ?? $content['emergencyCta']['title'] ?? null, '¡Estamos disponibles 24 horas!');
$cta_desc        = e($content['emergencyCta.description'] ?? $content['emergencyCta']['description'] ?? null, 'Llámanos o escríbenos y recibe atención inmediata.');

$seo_title       = e($content['seo.title'] ?? $content['seo']['title'] ?? null, 'Servillantas El Puente | Móvil');
$seo_desc        = e($content['seo.description'] ?? $content['seo']['description'] ?? null, 'Servicio de montallantas, alineación, balanceo y atención 24 horas en Bucaramanga.');

$values = [
    ['icon' => '◎', 'title' => e($content['values.0.title'] ?? $content['values'][0]['title'] ?? null, 'Misión'),
     'desc' => e($content['values.0.description'] ?? $content['values'][0]['description'] ?? null, 'Soluciones integrales en servicios de llantas y mantenimiento automotriz.')],
    ['icon' => '◉', 'title' => e($content['values.1.title'] ?? $content['values'][1]['title'] ?? null, 'Visión'),
     'desc' => e($content['values.1.description'] ?? $content['values'][1]['description'] ?? null, 'Ser la serviteca líder en Bucaramanga y la región.')],
    ['icon' => '●●', 'title' => e($content['values.2.title'] ?? $content['values'][2]['title'] ?? null, 'Historia'),
     'desc' => e($content['values.2.description'] ?? $content['values'][2]['description'] ?? null, 'Servicio confiable para conductores y empresas de transporte.')],
];

$services_default = [
    ['num' => '01', 'title' => 'Montallantas y reparación', 'desc' => 'Montaje profesional, reparación de llantas y solución de pinchazos.', 'img' => 'service-montallantas.jpg'],
    ['num' => '02', 'title' => 'Alineación y balanceo', 'desc' => 'Mayor estabilidad, seguridad y vida útil para tus llantas.', 'img' => 'service-alineacion.jpg'],
    ['num' => '03', 'title' => 'Cambio de aceite', 'desc' => 'Aceites de alta calidad para el óptimo rendimiento de tu motor.', 'img' => 'service-aceite.jpg'],
    ['num' => '04', 'title' => 'Cambio de fluidos', 'desc' => 'Renovamos fluidos esenciales para el funcionamiento del vehículo.', 'img' => 'service-fluidos.jpg'],
    ['num' => '05', 'title' => 'Carga de nitrógeno', 'desc' => 'Mejora la presión, estabilidad y rendimiento de tus llantas.', 'img' => 'service-nitrogeno.jpg'],
    ['num' => '06', 'title' => 'Venta de llantas', 'desc' => 'Marcas reconocidas para todo tipo de vehículos y necesidades.', 'img' => 'service-llantas.jpg'],
    ['num' => '07', 'title' => 'Rines', 'desc' => 'Variedad de diseños y tamaños con la mejor calidad.', 'img' => 'service-rines.jpg'],
    ['num' => '08', 'title' => 'Línea SPA automotriz', 'desc' => 'Productos especializados para el cuidado y embellecimiento.', 'img' => 'service-spa.jpg'],
    ['num' => '09', 'title' => 'Re-encauche', 'desc' => 'Solución económica y ecológica que extiende la vida útil.', 'img' => 'service-reencauche.jpg'],
];
$services = [];
foreach ($services_default as $i => $def) {
    $services[] = [
        'num'   => $def['num'],
        'title' => e($content["services.{$i}.title"] ?? $content['services'][$i]['title'] ?? null, $def['title']),
        'desc'  => e($content["services.{$i}.description"] ?? $content['services'][$i]['description'] ?? null, $def['desc']),
        'img'   => $def['img'],
    ];
}

$nit       = e($content['business.nit'] ?? $content['business']['nit'] ?? null, '91.350.017-7');
$copyright = e($content['business.copyright'] ?? $content['business']['copyright'] ?? null, '© 2024 Servillantas El Puente.');

$highlights_default = [
    ['title' => 'Atención 24 horas', 'sub' => 'Siempre disponibles', 'img' => 'mini-24h.jpg'],
    ['title' => 'Equipo especializado', 'sub' => 'Tecnología de punta', 'img' => 'mini-equipo.jpg'],
    ['title' => 'Livianos y pesados', 'sub' => 'Todos los vehículos', 'img' => 'mini-pesados.jpg'],
];
$highlights = [];
foreach ($highlights_default as $i => $def) {
    $highlights[] = [
        'title' => e($content["heroHighlights.{$i}.title"] ?? $content['heroHighlights'][$i]['title'] ?? null, $def['title']),
        'sub'   => e($content["heroHighlights.{$i}.subtitle"] ?? $content['heroHighlights'][$i]['subtitle'] ?? null, $def['sub']),
        'img'   => $def['img'],
    ];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $seo_title ?></title>
  <meta name="description" content="<?= $seo_desc ?>" />
  <link rel="stylesheet" href="mobile.css" />
</head>
<body>
  <header class="mobile-header" id="inicio">
    <a class="mobile-brand" href="#inicio" aria-label="Inicio">
      <img src="../assets/img/logo-servillantas.png" alt="Servillantas El Puente" />
      <img src="../assets/img/logo-24-horas.png" alt="Servicio 24 horas" />
    </a>
    <button class="menu-btn" id="menuBtn" aria-label="Abrir menú" aria-expanded="false">☰</button>
  </header>

  <nav class="mobile-menu" id="mobileMenu" aria-label="Menú móvil">
    <a href="#inicio">Inicio</a>
    <a href="#nosotros">Nosotros</a>
    <a href="#servicios">Servicios</a>
    <a href="#aliados">Aliados</a>
    <a href="#contacto">Contacto</a>
    <a class="menu-cta" href="https://wa.me/573107922579" target="_blank" rel="noopener">Solicitar servicio</a>
  </nav>

  <main>
    <section class="m-hero">
      <div class="hero-copy">
        <span>Servillantas 24 horas</span>
        <h1><?= $hero_title ?></h1>
        <p><?= $hero_desc ?></p>
        <div class="hero-actions">
          <a class="btn btn-red" href="https://wa.me/573107922579" target="_blank" rel="noopener">Solicitar servicio</a>
          <a class="btn btn-dark" href="#servicios">Ver servicios</a>
        </div>
      </div>
    </section>

    <section class="quick-cards" aria-label="Beneficios destacados">
<?php foreach ($highlights as $h): ?>
      <article><img src="../assets/img/<?= $h['img'] ?>" alt="<?= $h['title'] ?>"><h3><?= $h['title'] ?></h3><p><?= $h['sub'] ?></p></article>
<?php endforeach; ?>
    </section>

    <section class="m-about" id="nosotros">
      <span class="kicker"><?= $about_kicker ?></span>
      <h2><?= $about_title ?></h2>
      <p><?= $about_desc ?></p>
      <img src="../assets/img/about-building.jpg" alt="Fachada Servillantas El Puente" />
    </section>

    <section class="m-values" aria-label="Valores corporativos">
<?php foreach ($values as $v): ?>
      <article><strong><?= $v['icon'] ?></strong><h3><?= $v['title'] ?></h3><p><?= $v['desc'] ?></p></article>
<?php endforeach; ?>
    </section>

    <section class="m-services" id="servicios">
      <div class="section-title">
        <span class="kicker">Servicios</span>
        <h2>Soluciones completas para tu vehículo</h2>
      </div>
<?php foreach ($services as $s): ?>
      <article class="service-card"><span><?= $s['num'] ?></span><img src="../assets/img/<?= $s['img'] ?>" alt="<?= $s['title'] ?>"><div><h3><?= $s['title'] ?></h3><p><?= $s['desc'] ?></p></div></article>
<?php endforeach; ?>
    </section>

    <section class="m-allies" id="aliados">
      <span class="kicker"><?= $allies_kicker ?></span>
      <h2><?= $allies_title ?></h2>
      <img src="../assets/img/alliances-strip.png" alt="Aliados" />
    </section>

    <section class="m-cta">
      <div class="phone">☎</div>
      <h2><?= $cta_title ?></h2>
      <p><?= $cta_desc ?></p>
      <a class="btn btn-white" href="https://wa.me/573107922579" target="_blank" rel="noopener">Solicitar servicio</a>
    </section>

    <section class="m-contact" id="contacto">
      <span class="kicker">Contáctanos</span>
      <h2><?= $contact_title ?></h2>
      <ul>
        <li><b>☎</b><span><?= $contact_phone0 ?> - <?= $contact_phone1 ?></span></li>
        <li><b>⌖</b><span><?= nl2br($contact_address) ?></span></li>
        <li><b>✉</b><span><?= $contact_email ?></span></li>
        <li><b>◷</b><span>Servicio 24 horas<br>Lunes a Domingo</span></li>
      </ul>
      <img class="map" src="../assets/img/map-location.jpg" alt="Mapa ubicación" />
    </section>
  </main>

  <footer class="m-footer">
    <p><?= $copyright ?></p>
    <p>NIT. <?= $nit ?></p>
  </footer>

  <a class="floating-whatsapp" href="https://wa.me/573107922579" target="_blank" rel="noopener" aria-label="Solicitar servicio por WhatsApp">☎</a>

  <script src="../assets/js/mobile.js"></script>
</body>
</html>
