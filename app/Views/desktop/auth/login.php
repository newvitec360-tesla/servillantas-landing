<?php $logo = '/public/assets/brand/logo-servillantas-el-puente.jpg'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'Login', ENT_QUOTES, 'UTF-8') ?> — Servillantas El Puente</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/assets/desktop/css/login.css">
</head>
<body>
  <main class="login-shell">
    <section class="login-hero">
      <img src="<?= $logo ?>" alt="Servillantas El Puente" class="brand-logo">
      <h1>Gestión de cartera y recaudo</h1>
      <p>Administra clientes, controla pagos y optimiza la gestión comercial desde una plataforma robusta.</p>
      <div class="hero-grid">
        <article><strong>Gestión eficiente</strong><span>Control centralizado de cartera y clientes.</span></article>
        <article><strong>Recaudo efectivo</strong><span>Seguimiento, pagos y promesas trazables.</span></article>
        <article><strong>Decisiones inteligentes</strong><span>Indicadores y riesgo para actuar mejor.</span></article>
      </div>
    </section>
    <section class="login-panel">
      <form class="login-card" action="/index.php?r=/login" method="post">
        <?php if (isset($_SESSION['csrf_token'])): ?>
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <?php endif; ?>
        <div class="lock">🔒</div>
        <h2>Acceso al sistema</h2>
        <p>Ingresa tus credenciales para acceder al sistema de <strong>Servillantas El Puente.</strong></p>
        
        <?php if (!empty($error)): ?>
          <div style="color: red; margin-bottom: 15px; font-weight: bold;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <label>Correo corporativo
          <input type="email" name="email" placeholder="usuario@servillantaselpuente.com" required>
        </label>
        <label>Contraseña
          <input type="password" name="password" placeholder="••••••••" required>
        </label>
        <div class="login-row">
          <label class="check"><input type="checkbox"> Recordarme</label>
          <a href="#">¿Olvidaste tu contraseña?</a>
        </div>
        <button type="submit">Ingresar al sistema →</button>
        <small>Demo base MVC · el flujo real debe conectar autenticación segura y auditoría.</small>
      </form>
    </section>
  </main>
</body>
</html>
