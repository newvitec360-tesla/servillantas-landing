<aside class="sidebar">
  <div class="brand-card">
    <img src="<?= asset('brand/logo-servillantas-el-puente.jpg') ?>" alt="Servillantas El Puente">
    <p>Plataforma Integral de Cartera, Crédito y Recaudo</p>
  </div>
  <nav class="side-nav">
    <?php
      $items = [
        '/dashboard'     => ['▣', 'Dashboard'],
        '/clientes'      => ['◫', 'Clientes'],
        '/cartera'       => ['⟁', 'Cartera'],
        '/expedientes'   => ['☷', 'Expedientes'],
        '/campanas'      => ['✉', 'Campañas'],
        '/pagos'         => ['◪', 'Pagos'],
        '/reportes'      => ['◧', 'Reportes'],
        '/configuracion' => ['⚙', 'Configuración'],
      ];
      foreach ($items as $url => [$icon, $label]):
        $active = ($current === $url || ($current === '/' && $url === '/dashboard')) ? 'active' : '';
    ?>
      <a class="<?= $active ?>" href="<?= route_url($url, 'desktop') ?>">
        <span class="icon"><?= $icon ?></span><?= e($label) ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <div class="sidebar-note">
    <strong>Servillantas El Puente</strong>
    Gestión integral de cartera para llantas y servicios automotriz.
  </div>
</aside>
