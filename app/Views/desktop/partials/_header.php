<header class="topbar">
  <div class="topbar-left">
    <div class="search-box">
      <span class="search-icon">⌕</span>
      <input id="globalSearch" type="text" placeholder="Buscar clientes, documentos, pagos, expedientes..." />
    </div>
  </div>
  <div class="topbar-right">
    <div class="icon-chip" title="Notificaciones">
      🔔<span class="bubble">0</span>
    </div>
    <div class="user-chip">
      <div class="avatar"><?= isset($_SESSION['user']) ? strtoupper(mb_substr($_SESSION['user']['nombre'], 0, 1)) . strtoupper(mb_substr(explode(' ', $_SESSION['user']['nombre'])[1] ?? '', 0, 1)) : 'AD' ?></div>
      <div class="user-info">
        <strong><?= e($_SESSION['user']['nombre'] ?? 'Administrador') ?></strong>
        <span><?= e($_SESSION['user']['rol_nombre'] ?? 'Admin') ?></span>
      </div>
    </div>
    <form method="post" action="<?= route_url('/logout', 'desktop') ?>" id="logoutForm">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
      <button type="submit" class="btn small secondary" title="Cerrar sesión">⏻</button>
    </form>
  </div>
</header>
