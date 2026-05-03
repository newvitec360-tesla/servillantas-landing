<?php
  $aging = $kpis['aging'] ?? ['al_dia'=>0,'1_30'=>0,'31_60'=>0,'61_90'=>0,'mas_90'=>0];
  $totalAging = array_sum($aging) ?: 1;
  $pctAlDia = round($aging['al_dia'] / $totalAging * 100, 1);
  $pct1_30  = round($aging['1_30'] / $totalAging * 100, 1);
  $pct31_60 = round($aging['31_60'] / $totalAging * 100, 1);
  $pct61_90 = round($aging['61_90'] / $totalAging * 100, 1);
  $pctMas90 = round($aging['mas_90'] / $totalAging * 100, 1);
  // Donut gradient stops
  $s1 = $pctAlDia;
  $s2 = $s1 + $pct1_30;
  $s3 = $s2 + $pct31_60;
  $s4 = $s3 + $pct61_90;
?>

<div class="page-head">
  <div>
    <h1>Gestión de Cartera</h1>
    <p>Controla el envejecimiento, las promesas y la estrategia de recuperación.</p>
  </div>
  <div class="actions">
    <button class="btn secondary" id="btnExportCartera">Exportar reporte</button>
    <a href="<?= route_url('/cartera/create', 'desktop') ?>" class="btn primary">Nueva obligación</a>
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
  <div class="card metric red">
    <div class="metric-icon">💼</div>
    <div class="metric-meta">
      <span>Cartera total</span>
      <strong>$ <?= number_format($kpis['total_cartera'] ?? 0, 0, ',', '.') ?></strong>
      <small>Consolidado</small>
    </div>
  </div>
  <div class="card metric black">
    <div class="metric-icon">🗓</div>
    <div class="metric-meta">
      <span>Saldo vencido</span>
      <strong>$ <?= number_format($kpis['saldo_vencido'] ?? 0, 0, ',', '.') ?></strong>
      <small>Mora activa</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">🤝</div>
    <div class="metric-meta">
      <span>Promesas activas</span>
      <strong><?= $kpis['promesas_activas'] ?? 0 ?></strong>
      <small>Compromisos vigentes</small>
    </div>
  </div>
  <div class="card metric black">
    <div class="metric-icon">⚖</div>
    <div class="metric-meta">
      <span>Casos jurídicos</span>
      <strong><?= $kpis['casos_juridicos'] ?? 0 ?></strong>
      <small>En proceso legal</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">◎</div>
    <div class="metric-meta">
      <span>Recuperación del mes</span>
      <strong>$ <?= number_format($kpis['recuperacion_mes'] ?? 0, 0, ',', '.') ?></strong>
      <small>Recaudo validado</small>
    </div>
  </div>
</div>

<!-- Aging Analysis + Alerts -->
<div class="grid grid-2-1">
  <div class="card">
    <div class="card-head"><h3>Análisis de antigüedad de cartera</h3></div>
    <div class="card-body">
      <div style="display:grid; grid-template-columns:1fr auto; gap:24px; align-items:center;">
        <div class="stack">
          <div class="mini-item">
            <div><span class="pill green">Al día</span></div>
            <div>$ <?= number_format($aging['al_dia'], 0, ',', '.') ?> · <?= $pctAlDia ?>%</div>
          </div>
          <div class="mini-item">
            <div><span class="pill amber">1 a 30 días</span></div>
            <div>$ <?= number_format($aging['1_30'], 0, ',', '.') ?> · <?= $pct1_30 ?>%</div>
          </div>
          <div class="mini-item">
            <div><span class="pill amber">31 a 60 días</span></div>
            <div>$ <?= number_format($aging['31_60'], 0, ',', '.') ?> · <?= $pct31_60 ?>%</div>
          </div>
          <div class="mini-item">
            <div><span class="pill red">61 a 90 días</span></div>
            <div>$ <?= number_format($aging['61_90'], 0, ',', '.') ?> · <?= $pct61_90 ?>%</div>
          </div>
          <div class="mini-item">
            <div><span class="pill red">+90 días</span></div>
            <div>$ <?= number_format($aging['mas_90'], 0, ',', '.') ?> · <?= $pctMas90 ?>%</div>
          </div>
        </div>
        <div>
          <div class="donut" style="background:conic-gradient(#4ea359 0 <?= $s1 ?>%, #f2a100 <?= $s1 ?>% <?= $s2 ?>%, #ff7c1f <?= $s2 ?>% <?= $s3 ?>%, #e94a54 <?= $s3 ?>% <?= $s4 ?>%, #c00d16 <?= $s4 ?>% 100%);" data-label="$ <?= number_format($totalAging / 1000000, 0) ?>M&#10;Total&#10;cartera"></div>
        </div>
      </div>
      <div class="legend" style="margin-top:14px;">
        <span><i style="background:#4ea359"></i>Al día</span>
        <span><i style="background:#f2a100"></i>1 a 30</span>
        <span><i style="background:#ff7c1f"></i>31 a 60</span>
        <span><i style="background:#e94a54"></i>61 a 90</span>
        <span><i style="background:#c00d16"></i>+90</span>
      </div>
    </div>
  </div>

  <div class="grid">
    <!-- Risk distribution -->
    <div class="card">
      <div class="card-head"><h3>Distribución por riesgo</h3></div>
      <div class="card-body stack">
        <?php $rc = $kpis['risk_counts'] ?? ['S1'=>0,'S2'=>0,'S3'=>0]; ?>
        <div class="mini-item"><div><span class="pill green">S1 — Bajo</span></div><strong><?= $rc['S1'] ?></strong></div>
        <div class="mini-item"><div><span class="pill amber">S2 — Medio</span></div><strong><?= $rc['S2'] ?></strong></div>
        <div class="mini-item"><div><span class="pill red">S3 — Alto</span></div><strong><?= $rc['S3'] ?></strong></div>
      </div>
    </div>
    <!-- Alerts -->
    <div class="card">
      <div class="card-head"><h3>Alertas de cartera</h3></div>
      <div class="card-body stack">
        <div class="mini-item">
          <div><strong>Concentración +90 días</strong><br><span class="muted"><?= $pctMas90 ?>% de la cartera en riesgo alto.</span></div>
          <strong class="down">$ <?= number_format($aging['mas_90'], 0, ',', '.') ?></strong>
        </div>
        <div class="mini-item">
          <div><strong>Promesas activas</strong><br><span class="muted"><?= $kpis['promesas_activas'] ?? 0 ?> compromisos vigentes.</span></div>
          <strong><?= $kpis['promesas_activas'] ?? 0 ?></strong>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filter Bar -->
<div class="card">
  <div class="card-body">
    <form method="get" action="<?= route_url('/cartera', 'desktop') ?>" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
      <input type="hidden" name="r" value="/cartera">
      <div class="field" style="flex:1; min-width:200px; margin:0;">
        <input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Buscar por cliente o código..." style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
      </div>
      <div class="field" style="width:140px; margin:0;">
        <select name="riesgo" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Riesgo</option>
          <option value="S1" <?= ($filters['riesgo'] ?? '') === 'S1' ? 'selected' : '' ?>>S1 — Bajo</option>
          <option value="S2" <?= ($filters['riesgo'] ?? '') === 'S2' ? 'selected' : '' ?>>S2 — Medio</option>
          <option value="S3" <?= ($filters['riesgo'] ?? '') === 'S3' ? 'selected' : '' ?>>S3 — Alto</option>
        </select>
      </div>
      <div class="field" style="width:180px; margin:0;">
        <select name="estado" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Estado</option>
          <option value="vigente" <?= ($filters['estado'] ?? '') === 'vigente' ? 'selected' : '' ?>>Vigente</option>
          <option value="vencida" <?= ($filters['estado'] ?? '') === 'vencida' ? 'selected' : '' ?>>Vencida</option>
          <option value="critica" <?= ($filters['estado'] ?? '') === 'critica' ? 'selected' : '' ?>>Crítica</option>
          <option value="en_gestion" <?= ($filters['estado'] ?? '') === 'en_gestion' ? 'selected' : '' ?>>En gestión</option>
          <option value="en_acuerdo" <?= ($filters['estado'] ?? '') === 'en_acuerdo' ? 'selected' : '' ?>>En acuerdo</option>
          <option value="juridico" <?= ($filters['estado'] ?? '') === 'juridico' ? 'selected' : '' ?>>Jurídico</option>
        </select>
      </div>
      <button type="submit" class="btn primary">Filtrar</button>
      <?php if (!empty($filters['q']) || !empty($filters['riesgo']) || !empty($filters['estado'])): ?>
        <a href="<?= route_url('/cartera', 'desktop') ?>" class="btn ghost">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Obligations Table -->
<div class="card">
  <div class="card-head">
    <h3>Gestión prioritaria de cartera</h3>
    <small>Pág. <?= $pagination['page'] ?? 1 ?> de <?= $pagination['total_pages'] ?? 1 ?> · <?= number_format($pagination['total'] ?? 0, 0, ',', '.') ?> resultados</small>
  </div>
  <div class="card-body tight table-wrap">
    <table class="sortable">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Código</th>
          <th>Tipo</th>
          <th>Saldo actual</th>
          <th>Valor inicial</th>
          <th>Días mora</th>
          <th>Riesgo</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($obligaciones)): ?>
          <?php foreach ($obligaciones as $obl): ?>
          <tr>
            <td><strong><?= e($obl['nombre_completo']) ?></strong></td>
            <td><?= e($obl['codigo_interno']) ?></td>
            <td><?= e($obl['tipo_obligacion'] ?: '—') ?></td>
            <td data-sort="<?= (float)$obl['saldo_actual'] ?>" style="color:#d30f19; font-weight:700;">
              $ <?= number_format((float)$obl['saldo_actual'], 0, ',', '.') ?>
            </td>
            <td data-sort="<?= (float)$obl['valor_inicial'] ?>">
              $ <?= number_format((float)$obl['valor_inicial'], 0, ',', '.') ?>
            </td>
            <td data-sort="<?= (int)$obl['antiguedad_dias'] ?>"><?= (int)$obl['antiguedad_dias'] ?></td>
            <td>
              <?php $rc = $obl['nivel_riesgo'] === 'S3' ? 'red' : ($obl['nivel_riesgo'] === 'S2' ? 'amber' : 'green'); ?>
              <span class="pill <?= $rc ?>"><?= e($obl['nivel_riesgo']) ?></span>
            </td>
            <td>
              <?php
                $estadoMap = [
                  'vigente' => 'green', 'vencida' => 'red', 'critica' => 'red',
                  'en_gestion' => 'blue', 'en_acuerdo' => 'amber', 'pagada' => 'green',
                  'parcialmente_pagada' => 'amber', 'castigada' => 'gray',
                  'fallecido' => 'gray', 'juridico' => 'purple',
                ];
                $ec = $estadoMap[$obl['estado_obligacion']] ?? 'gray';
              ?>
              <span class="pill <?= $ec ?>"><?= e(ucfirst(str_replace('_', ' ', $obl['estado_obligacion']))) ?></span>
            </td>
            <td>
              <div class="actions">
                <a href="<?= route_url('/cartera/show', 'desktop') ?>&id=<?= (int)$obl['id'] ?>" class="btn small secondary">Ver</a>
                <a href="<?= route_url('/cartera/edit', 'desktop') ?>&id=<?= (int)$obl['id'] ?>" class="btn small secondary">Editar</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="9">
              <div class="empty-state">
                <div class="empty-icon">⟁</div>
                <p>No hay obligaciones registradas<?= !empty($filters['q']) ? ' con ese criterio' : '' ?>.</p>
                <a href="<?= route_url('/cartera/create', 'desktop') ?>" class="btn primary" style="margin-top:12px;">Crear primera obligación</a>
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
        $params = ['r' => '/cartera', 'page' => $p];
        if (!empty($filters['q'])) $params['q'] = $filters['q'];
        if (!empty($filters['riesgo'])) $params['riesgo'] = $filters['riesgo'];
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
