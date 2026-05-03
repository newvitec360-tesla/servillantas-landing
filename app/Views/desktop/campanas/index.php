<div class="page-head">
  <div>
    <h1>Campañas y Automatización</h1>
    <p>Crea, segmenta y ejecuta campañas para mejorar tu recaudo.</p>
  </div>
  <div class="actions">
    <button class="btn secondary" id="btnExportCampanas">Exportar</button>
    <a href="<?= route_url('/plantillas', 'desktop') ?>" class="btn secondary">📝 Plantillas</a>
    <a href="<?= route_url('/campanas/create', 'desktop') ?>" class="btn primary">Crear campaña</a>
  </div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="card" style="padding:14px 18px; border-left:4px solid <?= $_SESSION['flash']['type'] === 'success' ? 'var(--green)' : 'var(--red)' ?>">
    <strong><?= e($_SESSION['flash']['message']) ?></strong>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- KPI Metrics -->
<div class="grid cols-5">
  <div class="card metric black">
    <div class="metric-icon">📧</div>
    <div class="metric-meta">
      <span>Campañas activas</span>
      <strong><?= $kpis['activas'] ?? 0 ?></strong>
      <small>Borrador + Programadas</small>
    </div>
  </div>
  <div class="card metric green">
    <div class="metric-icon">✓</div>
    <div class="metric-meta">
      <span>Campañas enviadas</span>
      <strong><?= $kpis['enviadas'] ?? 0 ?></strong>
      <small>Completadas</small>
    </div>
  </div>
  <div class="card metric blue">
    <div class="metric-icon">↗</div>
    <div class="metric-meta">
      <span>Mensajes del mes</span>
      <strong><?= number_format($kpis['mensajes_mes'] ?? 0, 0, ',', '.') ?></strong>
      <small>Enviados</small>
    </div>
  </div>
  <div class="card metric amber">
    <div class="metric-icon">👁</div>
    <div class="metric-meta">
      <span>Tasa de apertura</span>
      <strong><?= $kpis['tasa_apertura'] ?? 0 ?>%</strong>
      <small>Promedio global</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">🖱</div>
    <div class="metric-meta">
      <span>Tasa de clic</span>
      <strong><?= $kpis['tasa_clic'] ?? 0 ?>%</strong>
      <small>Conversión</small>
    </div>
  </div>
</div>

<!-- Alerts + Quick Actions -->
<div class="grid grid-2-1" style="margin-bottom:16px;">
  <div class="card">
    <div class="card-head"><h3>Alertas y recomendaciones</h3></div>
    <div class="card-body stack">
      <div class="mini-item"><div><strong>Plantillas disponibles</strong><br><span class="muted"><?= $kpis['plantillas'] ?? 0 ?> plantillas activas para campañas.</span></div></div>
      <div class="mini-item"><div><strong>Campañas por programar</strong><br><span class="muted"><?= $kpis['activas'] ?? 0 ?> campañas en borrador listas para enviar.</span></div></div>
      <?php if (($kpis['tasa_apertura'] ?? 0) < 20): ?>
      <div class="mini-item"><div><strong>⚠ Apertura baja</strong><br><span class="muted">La tasa de apertura está por debajo del 20%. Revisa asuntos y horarios.</span></div></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><h3>Acciones rápidas</h3></div>
    <div class="card-body">
      <div class="grid">
        <a href="<?= route_url('/campanas/create', 'desktop') ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">📧 Nueva campaña</a>
        <a href="<?= route_url('/plantillas', 'desktop') ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">📝 Plantillas de mensajes</a>
        <a href="<?= route_url('/expedientes', 'desktop') ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">📋 Ver gestiones</a>
        <a href="<?= route_url('/cartera', 'desktop') ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">⟁ Segmentar cartera</a>
      </div>
    </div>
  </div>
</div>

<!-- Filter Bar -->
<div class="card">
  <div class="card-body">
    <form method="get" action="<?= route_url('/campanas', 'desktop') ?>" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
      <input type="hidden" name="r" value="/campanas">
      <div class="field" style="flex:1; min-width:180px; margin:0;">
        <input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Buscar campaña..." style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
      </div>
      <div class="field" style="width:150px; margin:0;">
        <select name="estado" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Estado</option>
          <option value="borrador" <?= ($filters['estado'] ?? '') === 'borrador' ? 'selected' : '' ?>>Borrador</option>
          <option value="programada" <?= ($filters['estado'] ?? '') === 'programada' ? 'selected' : '' ?>>Programada</option>
          <option value="enviada" <?= ($filters['estado'] ?? '') === 'enviada' ? 'selected' : '' ?>>Enviada</option>
          <option value="cancelada" <?= ($filters['estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
        </select>
      </div>
      <div class="field" style="width:150px; margin:0;">
        <select name="canal" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Canal</option>
          <?php foreach (['WhatsApp','SMS','Correo','Llamada'] as $c): ?>
            <option value="<?= $c ?>" <?= ($filters['canal'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn primary">Filtrar</button>
      <?php if (!empty($filters['q']) || !empty($filters['estado']) || !empty($filters['canal'])): ?>
        <a href="<?= route_url('/campanas', 'desktop') ?>" class="btn ghost">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Campaigns Table -->
<div class="card">
  <div class="card-head"><h3>Campañas</h3><small><?= number_format($pagination['total'] ?? 0, 0, ',', '.') ?> registros</small></div>
  <div class="card-body tight table-wrap">
    <table class="sortable">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Canal</th>
          <th>Estado</th>
          <th>Enviados</th>
          <th>Abiertos</th>
          <th>Clics</th>
          <th>Fecha envío</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($campanas)): ?>
          <?php foreach ($campanas as $camp): ?>
          <tr>
            <td><strong><?= e($camp['nombre']) ?></strong></td>
            <td>
              <?php $cIcons = ['WhatsApp'=>'💬','SMS'=>'📱','Correo'=>'✉','Llamada'=>'☎']; ?>
              <?= $cIcons[$camp['canal']] ?? '◌' ?> <?= e($camp['canal']) ?>
            </td>
            <td>
              <?php
                $eMap = ['borrador'=>'gray','programada'=>'blue','enviada'=>'green','cancelada'=>'red'];
                $eColor = $eMap[$camp['estado']] ?? 'gray';
              ?>
              <span class="pill <?= $eColor ?>"><?= e(ucfirst($camp['estado'])) ?></span>
            </td>
            <td data-sort="<?= (int)$camp['enviados'] ?>"><?= number_format((int)$camp['enviados'], 0, ',', '.') ?></td>
            <td data-sort="<?= (int)$camp['abiertos'] ?>"><?= number_format((int)$camp['abiertos'], 0, ',', '.') ?></td>
            <td data-sort="<?= (int)$camp['clics'] ?>"><?= number_format((int)$camp['clics'], 0, ',', '.') ?></td>
            <td><?= $camp['fecha_envio'] ? e(date('d/m/Y H:i', strtotime($camp['fecha_envio']))) : '—' ?></td>
            <td>
              <div class="actions">
                <a href="<?= route_url('/campanas/show', 'desktop') ?>&id=<?= (int)$camp['id'] ?>" class="btn small secondary">Ver</a>
                <?php if ($camp['estado'] === 'borrador'): ?>
                  <a href="<?= route_url('/campanas/edit', 'desktop') ?>&id=<?= (int)$camp['id'] ?>" class="btn small secondary">Editar</a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <div class="empty-icon">📧</div>
                <p>No hay campañas<?= !empty($filters['q']) ? ' con ese criterio' : '' ?>.</p>
                <a href="<?= route_url('/campanas/create', 'desktop') ?>" class="btn primary" style="margin-top:12px;">Crear primera campaña</a>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Pagination -->
<?php if (($pagination['total_pages'] ?? 1) > 1): ?>
<div class="card">
  <div class="card-body" style="display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">
    <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
      <?php
        $params = ['r' => '/campanas', 'page' => $p];
        if (!empty($filters['q'])) $params['q'] = $filters['q'];
        if (!empty($filters['estado'])) $params['estado'] = $filters['estado'];
        if (!empty($filters['canal'])) $params['canal'] = $filters['canal'];
      ?>
      <a href="/index.php?<?= http_build_query($params) ?>" class="btn small <?= $p === ($pagination['page'] ?? 1) ? 'primary' : 'secondary' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
</div>
<?php endif; ?>
