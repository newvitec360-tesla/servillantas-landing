<?php
  $canales = $kpis['canales'] ?? [];
  $resultados = $kpis['resultados'] ?? [];
?>

<div class="page-head">
  <div>
    <h1>Gestiones de Cobranza</h1>
    <p>Registra y monitorea las acciones de cobro: llamadas, visitas, correos y compromisos.</p>
  </div>
  <div class="actions">
    <button class="btn secondary" id="btnExportGestiones">Exportar</button>
    <a href="<?= route_url('/expedientes/create', 'desktop') ?>" class="btn primary">Nueva gestión</a>
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
    <div class="metric-icon">📋</div>
    <div class="metric-meta">
      <span>Gestiones del mes</span>
      <strong><?= number_format($kpis['total_mes'] ?? 0, 0, ',', '.') ?></strong>
      <small>Registradas</small>
    </div>
  </div>
  <div class="card metric blue">
    <div class="metric-icon">📞</div>
    <div class="metric-meta">
      <span>Gestiones hoy</span>
      <strong><?= $kpis['hoy'] ?? 0 ?></strong>
      <small>Realizadas</small>
    </div>
  </div>
  <div class="card metric green">
    <div class="metric-icon">🤝</div>
    <div class="metric-meta">
      <span>Promesas activas</span>
      <strong><?= $kpis['promesas'] ?? 0 ?></strong>
      <small>$ <?= number_format($kpis['valor_promesas'] ?? 0, 0, ',', '.') ?></small>
    </div>
  </div>
  <div class="card metric amber">
    <div class="metric-icon">🗓</div>
    <div class="metric-meta">
      <span>Próximas (7 días)</span>
      <strong><?= $kpis['proximas_semana'] ?? 0 ?></strong>
      <small>Seguimientos pendientes</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">◎</div>
    <div class="metric-meta">
      <span>Resultados</span>
      <strong><?= number_format($pagination['total'] ?? 0, 0, ',', '.') ?></strong>
      <small>Pág. <?= $pagination['page'] ?? 1 ?> de <?= $pagination['total_pages'] ?? 1 ?></small>
    </div>
  </div>
</div>

<!-- Stats: Channel + Results Distribution -->
<div class="grid grid-2-1">
  <div class="card">
    <div class="card-head"><h3>Distribución por canal</h3></div>
    <div class="card-body stack">
      <?php if (!empty($canales)): ?>
        <?php
          $canalIcons = ['Llamada' => '☎', 'Visita' => '🏠', 'Correo' => '✉', 'WhatsApp' => '💬', 'SMS' => '📱', 'Mensaje' => '💬'];
          foreach ($canales as $c => $qty):
        ?>
        <div class="mini-item">
          <div><?= $canalIcons[$c] ?? '◌' ?> <?= e($c) ?></div>
          <strong><?= $qty ?></strong>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="mini-item"><div class="muted">Sin gestiones este mes</div></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><h3>Resultados del mes</h3></div>
    <div class="card-body stack">
      <?php if (!empty($resultados)): ?>
        <?php
          $resColors = ['Contacto efectivo' => 'green', 'Sin respuesta' => 'amber', 'Promesa de pago' => 'blue', 'Número errado' => 'red', 'Buzón' => 'gray', 'Pago realizado' => 'green', 'Negativa' => 'red'];
          foreach ($resultados as $r => $qty):
            $rc = $resColors[$r] ?? 'gray';
        ?>
        <div class="mini-item">
          <div><span class="pill <?= $rc ?>"><?= e($r) ?></span></div>
          <strong><?= $qty ?></strong>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="mini-item"><div class="muted">Sin datos</div></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Filter Bar -->
<div class="card">
  <div class="card-body">
    <form method="get" action="<?= route_url('/expedientes', 'desktop') ?>" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
      <input type="hidden" name="r" value="/expedientes">
      <div class="field" style="flex:1; min-width:180px; margin:0;">
        <input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Buscar cliente o nota..." style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
      </div>
      <div class="field" style="width:150px; margin:0;">
        <select name="canal" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Canal</option>
          <?php foreach (['Llamada','Visita','Correo','WhatsApp','SMS','Mensaje'] as $c): ?>
            <option value="<?= $c ?>" <?= ($filters['canal'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field" style="width:180px; margin:0;">
        <select name="resultado" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Resultado</option>
          <?php foreach (['Contacto efectivo','Sin respuesta','Promesa de pago','Número errado','Buzón','Pago realizado','Negativa'] as $r): ?>
            <option value="<?= $r ?>" <?= ($filters['resultado'] ?? '') === $r ? 'selected' : '' ?>><?= $r ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn primary">Filtrar</button>
      <?php if (!empty($filters['q']) || !empty($filters['canal']) || !empty($filters['resultado'])): ?>
        <a href="<?= route_url('/expedientes', 'desktop') ?>" class="btn ghost">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Gestiones Table -->
<div class="card">
  <div class="card-head"><h3>Historial de gestiones</h3></div>
  <div class="card-body tight table-wrap">
    <table class="sortable">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Canal</th>
          <th>Resultado</th>
          <th>Gestor</th>
          <th>Compromiso</th>
          <th>Próxima</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($gestiones)): ?>
          <?php foreach ($gestiones as $g): ?>
          <tr>
            <td><?= e(date('d/m/Y H:i', strtotime($g['fecha_gestion']))) ?></td>
            <td><strong><?= e($g['nombre_completo']) ?></strong></td>
            <td>
              <?php $cIcons = ['Llamada'=>'☎','Visita'=>'🏠','Correo'=>'✉','WhatsApp'=>'💬','SMS'=>'📱']; ?>
              <?= $cIcons[$g['canal']] ?? '◌' ?> <?= e($g['canal']) ?>
            </td>
            <td>
              <?php $rc = $resColors[$g['resultado']] ?? 'gray'; ?>
              <span class="pill <?= $rc ?>"><?= e($g['resultado']) ?></span>
            </td>
            <td><?= e($g['gestor_nombre']) ?></td>
            <td>
              <?php if ($g['compromiso_pago_fecha']): ?>
                <?= e($g['compromiso_pago_fecha']) ?><br>
                <small>$ <?= number_format((float)($g['compromiso_pago_valor'] ?? 0), 0, ',', '.') ?></small>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
            <td><?= $g['proxima_gestion_fecha'] ? e(date('d/m/Y', strtotime($g['proxima_gestion_fecha']))) : '—' ?></td>
            <td>
              <a href="<?= route_url('/expedientes/ficha', 'desktop') ?>&id=<?= (int)$g['cliente_id'] ?>" class="btn small secondary">Ficha</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <div class="empty-icon">📋</div>
                <p>No hay gestiones registradas<?= !empty($filters['q']) ? ' con ese criterio' : '' ?>.</p>
                <a href="<?= route_url('/expedientes/create', 'desktop') ?>" class="btn primary" style="margin-top:12px;">Registrar primera gestión</a>
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
        $params = ['r' => '/expedientes', 'page' => $p];
        if (!empty($filters['q'])) $params['q'] = $filters['q'];
        if (!empty($filters['canal'])) $params['canal'] = $filters['canal'];
        if (!empty($filters['resultado'])) $params['resultado'] = $filters['resultado'];
      ?>
      <a href="/index.php?<?= http_build_query($params) ?>" class="btn small <?= $p === ($pagination['page'] ?? 1) ? 'primary' : 'secondary' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
</div>
<?php endif; ?>
