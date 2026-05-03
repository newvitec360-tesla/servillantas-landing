<div class="page-head">
  <div>
    <h1>Dashboard general</h1>
    <p>Resumen ejecutivo de cartera, recaudo, riesgo y actividad reciente.</p>
  </div>
  <div class="actions">
    <button class="btn secondary">Exportar resumen</button>
    <button class="btn primary" id="btnCampana">Ejecutar campaña</button>
  </div>
</div>

<!-- KPI Metrics -->
<div class="grid cols-4">
  <div class="card metric red">
    <div class="metric-icon">$</div>
    <div class="metric-meta">
      <span>Cartera total</span>
      <strong>$ <?= number_format($kpis['total_cartera'] ?? 0, 0, ',', '.') ?></strong>
      <small>Consolidado operativo</small>
    </div>
  </div>
  <div class="card metric black">
    <div class="metric-icon">↗</div>
    <div class="metric-meta">
      <span>Recaudo del mes</span>
      <strong>$ <?= number_format($kpis['recaudo_mes'] ?? 0, 0, ',', '.') ?></strong>
      <small>Mes actual</small>
    </div>
  </div>
  <div class="card metric red">
    <div class="metric-icon">!</div>
    <div class="metric-meta">
      <span>Casos S3</span>
      <strong><?= $kpis['casos_s3'] ?? 0 ?></strong>
      <small>Prioridad alta</small>
    </div>
  </div>
  <div class="card metric black">
    <div class="metric-icon">◎</div>
    <div class="metric-meta">
      <span>Efectividad</span>
      <strong>--%</strong>
      <small>Campañas y gestión</small>
    </div>
  </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-2-1">
  <!-- Priority Clients Table -->
  <div class="card">
    <div class="card-head">
      <h3>Clientes prioritarios</h3>
      <div class="actions">
        <a href="<?= route_url('/clientes','desktop') ?>" class="btn small secondary">Ver todos</a>
      </div>
    </div>
    <div class="card-body tight table-wrap">
      <table class="sortable">
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Saldo</th>
            <th>Días mora</th>
            <th>Riesgo</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($kpis['clientes_prioritarios'])): ?>
            <?php foreach ($kpis['clientes_prioritarios'] as $cli): ?>
            <tr>
              <td><?= e($cli['nombre_completo']) ?></td>
              <td data-sort="<?= (float)$cli['saldo_actual'] ?>">$ <?= number_format((float)$cli['saldo_actual'], 0, ',', '.') ?></td>
              <td data-sort="<?= (int)$cli['antiguedad_dias'] ?>"><?= (int)$cli['antiguedad_dias'] ?></td>
              <td>
                <span class="pill <?= $cli['nivel_riesgo'] === 'S3' ? 'red' : ($cli['nivel_riesgo'] === 'S2' ? 'amber' : 'green') ?>">
                  <?= e($cli['nivel_riesgo']) ?>
                </span>
              </td>
              <td><a href="<?= route_url('/cartera','desktop') ?>" class="btn small secondary">Gestionar</a></td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center muted" style="padding:24px;">Sin clientes prioritarios aún</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Right Column -->
  <div class="grid gap-12">
    <!-- Activity Feed -->
    <div class="card">
      <div class="card-head"><h3>Actividad reciente</h3></div>
      <div class="card-body stack">
        <?php if (!empty($kpis['actividad_reciente'])): ?>
          <?php foreach ($kpis['actividad_reciente'] as $act): ?>
          <div class="mini-item">
            <div>
              <strong><?= e($act['titulo']) ?></strong><br>
              <span class="muted"><?= e($act['detalle']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="mini-item">
            <div>
              <strong>Sistema inicializado</strong><br>
              <span class="muted">Plataforma lista para operación</span>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quick Segments -->
    <div class="card">
      <div class="card-head"><h3>Alertas rápidas</h3></div>
      <div class="card-body">
        <div class="grid cols-2">
          <div class="box"><strong><?= $kpis['promesas_vencer'] ?? 0 ?></strong><span class="muted">Promesas por vencer</span></div>
          <div class="box"><strong><?= $kpis['sin_contacto'] ?? 0 ?></strong><span class="muted">Sin contacto válido</span></div>
          <div class="box"><strong><?= $kpis['pagos_validar'] ?? 0 ?></strong><span class="muted">Pagos por validar</span></div>
          <div class="box"><strong><?= $kpis['total_clientes'] ?? 0 ?></strong><span class="muted">Clientes registrados</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
