<?php $current = $_GET['r'] ?? '/dashboard'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= e($title ?? 'Servillantas') ?> — Mobile</title>
  <link rel="stylesheet" href="<?= asset('mobile/css/app.css') ?>">
</head>
<body>
  <main class="mobile-shell">
    <header class="mobile-header">
      <img src="<?= asset('brand/logo-servillantas-el-puente.jpg') ?>" alt="Servillantas">
      <div>
        <strong><?= e($title ?? 'Inicio') ?></strong>
        <span>Versión móvil independiente</span>
      </div>
    </header>

    <section class="mobile-content">
      <?= $content ?>
    </section>

    <nav class="bottom-nav">
      <a class="<?= $current === '/dashboard' || $current === '/' ? 'active' : '' ?>" href="<?= route_url('/dashboard', 'mobile') ?>">Inicio</a>
      <a class="<?= $current === '/clientes' ? 'active' : '' ?>" href="<?= route_url('/clientes', 'mobile') ?>">Clientes</a>
      <a class="<?= $current === '/cartera' ? 'active' : '' ?>" href="<?= route_url('/cartera', 'mobile') ?>">Cartera</a>
      <a class="<?= $current === '/deudor' ? 'active' : '' ?>" href="<?= route_url('/deudor', 'mobile') ?>">Deudor</a>
    </nav>
  </main>
  <script src="<?= asset('mobile/js/app.js') ?>"></script>
</body>
</html>
