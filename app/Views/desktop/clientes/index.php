<div class="page-head">
  <div>
    <h1>Bandeja de clientes</h1>
    <p>Consulta y gestión centralizada de clientes de cartera.</p>
  </div>
  <div class="actions">
    <button class="btn secondary" id="btnExportClientes">Exportar</button>
    <a href="<?= route_url('/clientes/create', 'desktop') ?>" class="btn primary">Nuevo cliente</a>
  </div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="card" style="padding:14px 18px; border-left:4px solid <?= $_SESSION['flash']['type'] === 'success' ? 'var(--green)' : 'var(--red)' ?>">
    <strong><?= e($_SESSION['flash']['message']) ?></strong>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- KPI Metrics -->
<div class="grid cols-4">
  <div class="card metric black">
    <div class="metric-icon">👥</div>
    <div class="metric-meta">
      <span>Total clientes</span>
      <strong><?= number_format($kpis['total'] ?? 0, 0, ',', '.') ?></strong>
      <small>Base consolidada</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">$</div>
    <div class="metric-meta">
      <span>Saldo total cartera</span>
      <strong>$ <?= number_format($kpis['saldo_total'] ?? 0, 0, ',', '.') ?></strong>
      <small>Activa + vencida</small>
    </div>
  </div>
  <div class="card metric amber">
    <div class="metric-icon">☎</div>
    <div class="metric-meta">
      <span>Sin contacto válido</span>
      <strong><?= $kpis['sin_contacto'] ?? 0 ?></strong>
      <small><?= ($kpis['total'] ?? 0) > 0 ? number_format(($kpis['sin_contacto'] ?? 0) / $kpis['total'] * 100, 1) : 0 ?>%</small>
    </div>
  </div>
  <div class="card metric purple">
    <div class="metric-icon">◫</div>
    <div class="metric-meta">
      <span>Resultados</span>
      <strong><?= number_format($pagination['total'] ?? 0, 0, ',', '.') ?></strong>
      <small>Pág. <?= $pagination['page'] ?? 1 ?> de <?= $pagination['total_pages'] ?? 1 ?></small>
    </div>
  </div>
</div>

<!-- Filter Bar -->
<div class="card">
  <div class="card-body">
    <form method="get" action="<?= route_url('/clientes', 'desktop') ?>" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
      <input type="hidden" name="r" value="/clientes">
      <div class="field" style="flex:1; min-width:200px; margin:0;">
        <input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Buscar por nombre, documento, NIT, placa..." style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
      </div>
      <div class="field" style="width:200px; margin:0;">
        <select name="estado" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Todos los estados</option>
          <option value="contactable" <?= ($filters['estado'] ?? '') === 'contactable' ? 'selected' : '' ?>>Contactable</option>
          <option value="contacto_incompleto" <?= ($filters['estado'] ?? '') === 'contacto_incompleto' ? 'selected' : '' ?>>Contacto incompleto</option>
          <option value="inalcanzable" <?= ($filters['estado'] ?? '') === 'inalcanzable' ? 'selected' : '' ?>>Inalcanzable</option>
          <option value="visita_requerida" <?= ($filters['estado'] ?? '') === 'visita_requerida' ? 'selected' : '' ?>>Visita requerida</option>
        </select>
      </div>
      <button type="submit" class="btn primary">Buscar</button>
      <?php if (!empty($filters['q']) || !empty($filters['estado'])): ?>
        <a href="<?= route_url('/clientes', 'desktop') ?>" class="btn ghost">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Clients Table -->
<div class="card">
  <div class="card-head">
    <h3>Listado de clientes</h3>
    <small>Haz clic en los encabezados para ordenar</small>
  </div>
  <div class="card-body tight table-wrap">
    <table class="sortable" id="clientsTable">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Documento</th>
          <th>NIT / Placa</th>
          <th>Saldo total</th>
          <th>Días mora</th>
          <th>Riesgo</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($clientes)): ?>
          <?php foreach ($clientes as $cli): ?>
          <tr>
            <td><strong><?= e($cli['nombre_completo']) ?></strong></td>
            <td><?= e(($cli['tipo_documento'] ? $cli['tipo_documento'] . ' ' : '') . $cli['numero_documento']) ?></td>
            <td><?= e($cli['nit'] ?: ($cli['placa_principal'] ?: '—')) ?></td>
            <td data-sort="<?= (float)($cli['saldo_total'] ?? 0) ?>" <?= ($cli['saldo_total'] ?? 0) > 0 ? 'style="color:#d30f19; font-weight:700;"' : '' ?>>
              $ <?= number_format((float)($cli['saldo_total'] ?? 0), 0, ',', '.') ?>
            </td>
            <td data-sort="<?= (int)($cli['max_mora'] ?? 0) ?>"><?= (int)($cli['max_mora'] ?? 0) ?></td>
            <td>
              <?php
                $riesgo = $cli['max_riesgo'] ?? 'S1';
                $riesgoClass = $riesgo === 'S3' ? 'red' : ($riesgo === 'S2' ? 'amber' : 'green');
              ?>
              <span class="pill <?= $riesgoClass ?>"><?= e($riesgo) ?></span>
            </td>
            <td>
              <?php
                $locMap = ['contactable' => ['green','Contactable'], 'contacto_incompleto' => ['amber','Incompleto'], 'inalcanzable' => ['red','Inalcanzable'], 'visita_requerida' => ['blue','Visita req.']];
                $loc = $locMap[$cli['estado_localizacion']] ?? ['gray', $cli['estado_localizacion']];
              ?>
              <span class="pill <?= $loc[0] ?>"><?= e($loc[1]) ?></span>
            </td>
            <td>
              <div class="actions">
                <a href="<?= route_url('/clientes/show', 'desktop') ?>&id=<?= (int)$cli['id'] ?>" class="btn small secondary">Ver</a>
                <a href="<?= route_url('/clientes/edit', 'desktop') ?>&id=<?= (int)$cli['id'] ?>" class="btn small secondary">Editar</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <div class="empty-icon">◫</div>
                <p>No hay clientes registrados<?= !empty($filters['q']) ? ' con ese criterio de búsqueda' : '' ?>.</p>
                <a href="<?= route_url('/clientes/create', 'desktop') ?>" class="btn primary" style="margin-top:12px;">Crear primer cliente</a>
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
        $params = ['r' => '/clientes', 'page' => $p];
        if (!empty($filters['q'])) $params['q'] = $filters['q'];
        if (!empty($filters['estado'])) $params['estado'] = $filters['estado'];
        $href = '/index.php?' . http_build_query($params);
      ?>
      <a href="<?= $href ?>" class="btn small <?= $p === ($pagination['page'] ?? 1) ? 'primary' : 'secondary' ?>">
        <?= $p ?>
      </a>
    <?php endfor; ?>
  </div>
</div>
<?php endif; ?>
