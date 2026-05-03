<?php
  $carteraTotal = (float)($cartera['saldo_total'] ?? 0);
  $recaudoMes = (float)($pagos['recaudo_mes'] ?? 0);
  $clientesTotal = (int)($clientes['total'] ?? 0);
?>

<div class="page-head">
  <div>
    <h1>Reportes y Analítica</h1>
    <p>Mide la cartera, recaudo, efectividad y desempeño operativo para tomar mejores decisiones.</p>
  </div>
  <div class="actions">
    <button class="btn secondary" id="btnExportReporte">Exportar reporte</button>
  </div>
</div>

<!-- 5 KPIs — matching mockup line 1723-1728 -->
<div class="grid cols-5" style="margin-bottom:16px;">
  <div class="card metric red">
    <div class="metric-icon">$</div>
    <div class="metric-meta">
      <span>Cartera total</span>
      <strong>$ <?= number_format($carteraTotal, 0, ',', '.') ?></strong>
      <small>Saldo vigente</small>
    </div>
  </div>
  <div class="card metric black">
    <div class="metric-icon">↗</div>
    <div class="metric-meta">
      <span>Recaudo acumulado</span>
      <strong>$ <?= number_format($recaudoMes, 0, ',', '.') ?></strong>
      <small>Este mes</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">⟳</div>
    <div class="metric-meta">
      <span>Tasa de recuperación</span>
      <strong><?= $tasaRecuperacion ?>%</strong>
      <small>Recaudo / Cartera</small>
    </div>
  </div>
  <div class="card metric black">
    <div class="metric-icon">👥</div>
    <div class="metric-meta">
      <span>Clientes gestionados</span>
      <strong><?= number_format($clientesTotal, 0, ',', '.') ?></strong>
      <small>En la base</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">◎</div>
    <div class="metric-meta">
      <span>Gestiones del mes</span>
      <strong><?= number_format($totalGestiones, 0, ',', '.') ?></strong>
      <small>Realizadas</small>
    </div>
  </div>
</div>

<!-- Charts Row: Aging + Risk Donut — matching mockup -->
<div class="grid main-3" style="margin-bottom:16px;">
  <!-- Aging Distribution -->
  <div class="card">
    <div class="card-head"><h3>Antigüedad de cartera</h3></div>
    <div class="card-body stack-bars">
      <?php
        $aging = $cartera['aging'] ?? [];
        $agingTotal = max(array_sum($aging), 1);
        $agingLabels = ['0-30 días', '31-60 días', '61-90 días', '91-120 días', '>120 días'];
        $agingColors = ['#4ea359', '#f3c100', '#ff8d09', '#e10914', '#8b1a1e'];
      ?>
      <?php if (!empty($aging)): ?>
        <div class="row">
          <div>Distribución</div>
          <div class="bar-track">
            <?php $i = 0; foreach ($aging as $bucket => $val):
              $pct = round($val / $agingTotal * 100, 1);
              if ($pct > 2):
            ?>
            <span class="seg" style="width:<?= $pct ?>%; background:<?= $agingColors[$i] ?? '#aaa' ?>;"><?= $pct ?>%</span>
            <?php endif; $i++; endforeach; ?>
          </div>
        </div>
        <div class="stack" style="margin-top:12px;">
          <?php $i = 0; foreach ($aging as $bucket => $val): ?>
          <div class="mini-item">
            <div><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:<?= $agingColors[$i] ?? '#aaa' ?>;margin-right:6px;"></span><?= $agingLabels[$i] ?? $bucket ?></div>
            <strong><?= number_format($val, 0, ',', '.') ?> oblig.</strong>
          </div>
          <?php $i++; endforeach; ?>
        </div>
      <?php else: ?>
        <div class="muted">Sin datos de aging</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Risk Distribution with Donut -->
  <div class="card">
    <div class="card-head"><h3>Distribución por nivel de riesgo</h3></div>
    <div class="card-body">
      <?php
        $risk = $cartera['riesgo'] ?? [];
        $riskTotal = max(array_sum($risk), 1);
        $s1 = round(($risk['S1'] ?? 0) / $riskTotal * 100, 1);
        $s2 = round(($risk['S2'] ?? 0) / $riskTotal * 100, 1);
        $s3 = round(($risk['S3'] ?? 0) / $riskTotal * 100, 1);
        $p1 = $s1;
        $p2 = $p1 + $s2;
      ?>
      <div class="donut" style="background:conic-gradient(#4ea359 0 <?= $p1 ?>%, #f3c100 <?= $p1 ?>% <?= $p2 ?>%, #e10914 <?= $p2 ?>% 100%);" data-label="Total\A $ <?= number_format($carteraTotal / 1000000, 0, ',', '.') ?>M"></div>
      <div class="stack" style="margin-top:16px;">
        <div class="mini-item"><div><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#4ea359;margin-right:6px;"></span>Riesgo bajo (S1)</div><strong><?= $s1 ?>%</strong></div>
        <div class="mini-item"><div><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#f3c100;margin-right:6px;"></span>Riesgo medio (S2)</div><strong><?= $s2 ?>%</strong></div>
        <div class="mini-item"><div><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#e10914;margin-right:6px;"></span>Riesgo alto (S3)</div><strong><?= $s3 ?>%</strong></div>
      </div>
    </div>
  </div>

  <!-- Pagos por canal -->
  <div class="card">
    <div class="card-head"><h3>Canales de recaudo</h3></div>
    <div class="card-body">
      <?php
        $canales = $pagos['canales'] ?? [];
        $totalCanales = max(array_sum($canales), 1);
        $cColors = ['#4ea359', '#2196f3', '#f3c100', '#e10914', '#9c27b0', '#607d8b'];
        $cIdx = 0;
        $gradParts = [];
        $cumP = 0;
        foreach ($canales as $c => $v) {
            $pct = round($v / $totalCanales * 100, 1);
            $gradParts[] = ($cColors[$cIdx] ?? '#aaa') . " {$cumP}% " . ($cumP + $pct) . "%";
            $cumP += $pct;
            $cIdx++;
        }
        $grad = implode(', ', $gradParts) ?: '#1a1a1e 0 100%';
      ?>
      <div class="donut" style="background:conic-gradient(<?= $grad ?>);" data-label="Canales\A <?= count($canales) ?>"></div>
      <div class="stack" style="margin-top:16px;">
        <?php $cIdx = 0; foreach ($canales as $c => $v): ?>
        <div class="mini-item">
          <div><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:<?= $cColors[$cIdx] ?? '#aaa' ?>;margin-right:6px;"></span><?= e($c) ?></div>
          <strong><?= round($v / $totalCanales * 100, 1) ?>%</strong>
        </div>
        <?php $cIdx++; endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Funnel + Hallazgos — matching mockup lines 1770-1818 -->
<div class="grid main-2-1">
  <div class="grid cols-2">
    <!-- Conversion Funnel -->
    <div class="card">
      <div class="card-head"><h3>Conversión del proceso</h3></div>
      <div class="card-body funnel">
        <div class="frow"><div>Gestiones realizadas</div><div class="fbar"><span style="width:100%"></span></div><strong>100%</strong></div>
        <div class="frow"><div>Contactos efectivos</div><div class="fbar"><span style="width:<?= min($tasaContactoEfectivo, 100) ?>%"></span></div><strong><?= $tasaContactoEfectivo ?>%</strong></div>
        <div class="frow"><div>Compromisos de pago</div><div class="fbar"><span style="width:<?= min($tasaPromesa, 100) ?>%"></span></div><strong><?= $tasaPromesa ?>%</strong></div>
        <div class="frow"><div>Tasa de recuperación</div><div class="fbar"><span style="width:<?= min($tasaRecuperacion, 100) ?>%"></span></div><strong><?= $tasaRecuperacion ?>%</strong></div>
        <div class="mini-item" style="margin-top:12px;"><div>Conversión total</div><strong style="color:var(--red)"><?= $tasaRecuperacion ?>%</strong></div>
      </div>
    </div>

    <!-- Gestiones by Channel -->
    <div class="card">
      <div class="card-head"><h3>Efectividad por canal</h3></div>
      <div class="card-body stack">
        <?php
          $gCanales = $gestiones['canales'] ?? [];
          $cIcons = ['Llamada'=>'☎','Visita'=>'🏠','Correo'=>'✉','WhatsApp'=>'💬','SMS'=>'📱','Mensaje'=>'💬'];
          if (!empty($gCanales)):
            foreach ($gCanales as $c => $qty):
        ?>
        <div class="mini-item">
          <div><?= $cIcons[$c] ?? '◌' ?> <?= e($c) ?></div>
          <strong><?= $qty ?></strong>
        </div>
        <?php endforeach; else: ?>
        <div class="muted">Sin datos de gestiones</div>
        <?php endif; ?>

        <?php if (!empty($gestiones['resultados'])): ?>
        <hr style="border:0; border-top:1px solid var(--line); margin:8px 0;">
        <h4 style="margin:4px 0 8px; font-size:13px;">Resultados</h4>
        <?php
          $resColors = ['Contacto efectivo' => 'green', 'Sin respuesta' => 'amber', 'Promesa de pago' => 'blue', 'Número errado' => 'red', 'Pago realizado' => 'green', 'Negativa' => 'red'];
          foreach ($gestiones['resultados'] as $r => $qty):
            $rc = $resColors[$r] ?? 'gray';
        ?>
        <div class="mini-item">
          <div><span class="pill <?= $rc ?>"><?= e($r) ?></span></div>
          <strong><?= $qty ?></strong>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>

  <!-- Hallazgos + Comparativo -->
  <div class="grid">
    <div class="card">
      <div class="card-head"><h3>Hallazgos clave</h3></div>
      <div class="card-body">
        <div class="insight"><div class="dot">↗</div><div>Recaudo acumulado del mes: <strong>$ <?= number_format($recaudoMes, 0, ',', '.') ?></strong></div></div>
        <div class="insight"><div class="dot">!</div><div>Cartera total vigente: <strong>$ <?= number_format($carteraTotal, 0, ',', '.') ?></strong></div></div>
        <div class="insight"><div class="dot">◎</div><div>Tasa de recuperación: <strong><?= $tasaRecuperacion ?>%</strong></div></div>
        <div class="insight"><div class="dot">🤝</div><div>Promesas de pago activas: <strong><?= $promesas ?></strong> ($ <?= number_format((float)($gestiones['valor_promesas'] ?? 0), 0, ',', '.') ?>)</div></div>
        <?php if (($pagos['pendientes'] ?? 0) > 0): ?>
        <div class="insight"><div class="dot">⚠</div><div>Pagos pendientes de validación: <strong><?= $pagos['pendientes'] ?></strong></div></div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card">
      <div class="card-head"><h3>Resumen operativo</h3></div>
      <div class="card-body stack">
        <div class="mini-item"><div>Gestiones hoy</div><strong><?= $gestiones['hoy'] ?? 0 ?></strong></div>
        <div class="mini-item"><div>Gestiones del mes</div><strong><?= $totalGestiones ?></strong></div>
        <div class="mini-item"><div>Seguimientos (7 días)</div><strong><?= $gestiones['proximas_semana'] ?? 0 ?></strong></div>
        <div class="mini-item"><div>Campañas activas</div><strong><?= $campanas['activas'] ?? 0 ?></strong></div>
        <div class="mini-item"><div>Mensajes enviados (mes)</div><strong><?= number_format($campanas['mensajes_mes'] ?? 0, 0, ',', '.') ?></strong></div>
        <div class="mini-item"><div>Plantillas activas</div><strong><?= $campanas['plantillas'] ?? 0 ?></strong></div>
      </div>
    </div>
  </div>
</div>
