<?php $current = $_GET['r'] ?? '/dashboard'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Servillantas') ?> — Servillantas El Puente</title>
  <meta name="description" content="Plataforma Integral de Cartera, Crédito y Recaudo — Servillantas El Puente">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('desktop/css/app.css') ?>">
</head>
<body>
  <div class="app-shell">
    <?php require __DIR__ . '/../partials/_sidebar.php'; ?>

    <main class="main-area">
      <?php require __DIR__ . '/../partials/_header.php'; ?>
      
      <section class="content">
        <?= $content ?>
      </section>
    </main>
  </div>

  <!-- Toast container -->
  <div class="toast-wrap" id="toastWrap"></div>

  <script src="<?= asset('desktop/js/app.js') ?>" nonce="<?= defined('CSP_NONCE') ? CSP_NONCE : '' ?>"></script>
</body>
</html>
