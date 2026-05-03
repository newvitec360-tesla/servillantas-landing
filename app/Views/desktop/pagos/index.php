<?php
  $medios = $kpis['medios'] ?? [];
  $totalMedios = array_sum($medios) ?: 1;
  $colors = ['#123f76', '#f2a100', '#9b59b6', '#a1a1a8', '#4ea359', '#e94a54'];
  $ci = 0;
  $gradStops = [];
  $cum = 0;
  foreach ($medios as $nombre => $monto) {
    $pct = round($monto / $totalMedios * 100, 1);
    $color = $colors[$ci % count($colors)];
    $gradStops[] = "$color {$cum}% " . ($cum + $pct) . "%";
    $cum += $pct;
    $ci++;
  }
  $donutGrad = implode(', ', $gradStops) ?: '#333 0 100%';
?>

<div class="page-head">
  <div>
    <h1>Pagos y Recaudo</h1>
    <p>Valida, concilia y monitorea todos los pagos recibidos en la operación.</p>
  </div>
  <div class="actions">
    <button class="btn secondary" id="btnExportPagos">Exportar</button>
    <a href="<?= route_url('/pagos/create', 'desktop') ?>" class="btn primary">Registrar pago</a>
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
  <div class="card metric green">
    <div class="metric-icon">💳</div>
    <div class="metric-meta">
      <span>Pagos recibidos hoy</span>
      <strong>$ <?= number_format($kpis['hoy_monto'] ?? 0, 0, ',', '.') ?></strong>
      <small><?= $kpis['hoy_cantidad'] ?? 0 ?> transacciones</small>
    </div>
  </div>
  <div class="card metric blue">
    <div class="metric-icon">↗</div>
    <div class="metric-meta">
      <span>Recaudo del mes</span>
      <strong>$ <?= number_format($kpis['recaudo_mes'] ?? 0, 0, ',', '.') ?></strong>
      <small>Validado</small>
    </div>
  </div>
  <div class="card metric amber">
    <div class="metric-icon">⌛</div>
    <div class="metric-meta">
      <span>Pendientes por validar</span>
      <strong><?= $kpis['pendientes_qty'] ?? 0 ?></strong>
      <small>por $ <?= number_format($kpis['pendientes_monto'] ?? 0, 0, ',', '.') ?></small>
    </div>
  </div>
  <div class="card metric purple">
    <div class="metric-icon">◔</div>
    <div class="metric-meta">
      <span>Medios de pago</span>
      <strong><?= count($medios) ?></strong>
      <small>Canales activos</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">✕</div>
    <div class="metric-meta">
      <span>Rechazados</span>
      <strong><?= $kpis['rechazados'] ?? 0 ?></strong>
      <small>Este mes</small>
    </div>
  </div>
</div>

<div class="grid grid-2-1">
  <!-- Left: Table + Filters -->
  <div class="grid">
    <!-- Filter Bar -->
    <div class="card">
      <div class="card-body">
        <form method="get" action="<?= route_url('/pagos', 'desktop') ?>" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
          <input type="hidden" name="r" value="/pagos">
          <div class="field" style="flex:1; min-width:180px; margin:0;">
            <input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Buscar cliente o referencia..." style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          </div>
          <div class="field" style="width:160px; margin:0;">
            <select name="estado" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
              <option value="">Estado</option>
              <option value="pendiente" <?= ($filters['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
              <option value="validado" <?= ($filters['estado'] ?? '') === 'validado' ? 'selected' : '' ?>>Validado</option>
              <option value="rechazado" <?= ($filters['estado'] ?? '') === 'rechazado' ? 'selected' : '' ?>>Rechazado</option>
            </select>
          </div>
          <div class="field" style="width:160px; margin:0;">
            <select name="medio" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
              <option value="">Medio</option>
              <?php foreach (['PSE','Nequi','Transferencia','Tarjeta','Efectivo','Otro'] as $m): ?>
                <option value="<?= $m ?>" <?= ($filters['medio'] ?? '') === $m ? 'selected' : '' ?>><?= $m ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn primary">Filtrar</button>
          <?php if (!empty($filters['q']) || !empty($filters['estado']) || !empty($filters['medio'])): ?>
            <a href="<?= route_url('/pagos', 'desktop') ?>" class="btn ghost">Limpiar</a>
          <?php endif; ?>
        </form>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
      <div class="card-head">
        <h3>Transacciones recientes</h3>
        <small>Pág. <?= $pagination['page'] ?? 1 ?> de <?= $pagination['total_pages'] ?? 1 ?> · <?= number_format($pagination['total'] ?? 0, 0, ',', '.') ?> registros</small>
      </div>
      <div class="card-body tight table-wrap">
        <table class="sortable">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Canal</th>
              <th>Referencia</th>
              <th>Monto</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pagos)): ?>
              <?php foreach ($pagos as $pago): ?>
              <tr>
                <td><?= e(date('d/m/Y H:i', strtotime($pago['fecha_pago']))) ?></td>
                <td><strong><?= e($pago['nombre_completo']) ?></strong></td>
                <td><?= e($pago['medio_pago']) ?></td>
                <td><?= e($pago['referencia_transaccion'] ?: '—') ?></td>
                <td data-sort="<?= (float)$pago['valor'] ?>" style="font-weight:700; color:var(--green);">
                  $ <?= number_format((float)$pago['valor'], 0, ',', '.') ?>
                </td>
                <td>
                  <?php
                    $ec = ['pendiente' => 'amber', 'validado' => 'green', 'rechazado' => 'red'];
                    $eColor = $ec[$pago['estado_validacion']] ?? 'gray';
                    $eLabel = ['pendiente' => 'Pendiente', 'validado' => 'Aprobado', 'rechazado' => 'Rechazado'];
                  ?>
                  <span class="pill <?= $eColor ?>"><?= $eLabel[$pago['estado_validacion']] ?? $pago['estado_validacion'] ?></span>
                </td>
                <td>
                  <a href="<?= route_url('/pagos/show', 'desktop') ?>&id=<?= (int)$pago['id'] ?>" class="btn small secondary">Ver</a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7">
                  <div class="empty-state">
                    <div class="empty-icon">💳</div>
                    <p>No hay pagos registrados<?= !empty($filters['q']) ? ' con ese criterio' : '' ?>.</p>
                    <a href="<?= route_url('/pagos/create', 'desktop') ?>" class="btn primary" style="margin-top:12px;">Registrar primer pago</a>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right: Donut + Validations -->
  <div class="grid">
    <div class="card">
      <div class="card-head"><h3>Medios de pago</h3></div>
      <div class="card-body">
        <div class="donut" style="background:conic-gradient(<?= $donutGrad ?>);" data-label="Total&#10;$ <?= number_format($totalMedios / 1000000, 0) ?>M"></div>
        <div class="stack" style="margin-top:16px;">
          <?php $ci = 0; foreach ($medios as $nombre => $monto): ?>
          <div class="mini-item">
            <div><i style="display:inline-block; width:10px; height:10px; border-radius:50%; background:<?= $colors[$ci % count($colors)] ?>; margin-right:6px;"></i><?= e($nombre) ?></div>
            <strong>$ <?= number_format($monto, 0, ',', '.') ?></strong>
          </div>
          <?php $ci++; endforeach; ?>
          <?php if (empty($medios)): ?>
            <div class="mini-item"><div class="muted">Sin datos este mes</div></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-head"><h3>Validaciones pendientes</h3></div>
      <div class="card-body stack">
        <div class="mini-item"><div>Pagos por validar</div><strong><?= $kpis['pendientes_qty'] ?? 0 ?></strong></div>
        <div class="mini-item"><div>Monto pendiente</div><strong>$ <?= number_format($kpis['pendientes_monto'] ?? 0, 0, ',', '.') ?></strong></div>
        <div class="mini-item"><div>Rechazados este mes</div><strong style="color:var(--red);"><?= $kpis['rechazados'] ?? 0 ?></strong></div>
      </div>
    </div>
  </div>
</div>

<!-- Pagination -->
<?php if (($pagination['total_pages'] ?? 1) > 1): ?>
<div class="card">
  <div class="card-body" style="display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">
    <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
      <?php
        $params = ['r' => '/pagos', 'page' => $p];
        if (!empty($filters['q'])) $params['q'] = $filters['q'];
        if (!empty($filters['estado'])) $params['estado'] = $filters['estado'];
        if (!empty($filters['medio'])) $params['medio'] = $filters['medio'];
        $href = '/index.php?' . http_build_query($params);
      ?>
      <a href="<?= $href ?>" class="btn small <?= $p === ($pagination['page'] ?? 1) ? 'primary' : 'secondary' ?>">
        <?= $p ?>
      </a>
    <?php endfor; ?>
  </div>
</div>
<?php endif; ?>
