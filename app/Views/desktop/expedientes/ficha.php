<div class="page-head">
  <div>
    <h1>Ficha integral del cliente</h1>
    <p>Vista consolidada de datos, soportes, obligaciones y gestiones.</p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/expedientes', 'desktop') ?>" class="btn secondary">← Volver</a>
    <a href="<?= route_url('/clientes/edit', 'desktop') ?>&id=<?= (int)$cliente['id'] ?>" class="btn secondary">Editar cliente</a>
    <a href="<?= route_url('/expedientes/create', 'desktop') ?>&cliente_id=<?= (int)$cliente['id'] ?>" class="btn primary">Registrar gestión</a>
  </div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="card" style="padding:14px 18px; border-left:4px solid <?= $_SESSION['flash']['type'] === 'success' ? 'var(--green)' : 'var(--red)' ?>">
    <strong><?= e($_SESSION['flash']['message']) ?></strong>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Client Summary Bar -->
<div class="card">
  <div class="card-body">
    <div class="grid cols-4">
      <div class="box">
        <strong><?= e($cliente['nombre_completo']) ?></strong>
        <span class="muted"><?= e(($cliente['tipo_documento'] ?? '') . ' ' . $cliente['numero_documento']) ?></span>
      </div>
      <div class="box">
        <strong>Estado</strong>
        <?php
          $locMap = ['contactable'=>['green','Contactable'],'contacto_incompleto'=>['amber','Incompleto'],'inalcanzable'=>['red','Inalcanzable'],'visita_requerida'=>['blue','Visita req.']];
          $loc = $locMap[$cliente['estado_localizacion']] ?? ['gray', $cliente['estado_localizacion']];
        ?>
        <span class="pill <?= $loc[0] ?>"><?= e($loc[1]) ?></span>
      </div>
      <div class="box">
        <strong>Obligaciones</strong>
        <span class="muted"><?= count($cliente['obligaciones'] ?? []) ?> activas</span>
      </div>
      <div class="box">
        <strong>Gestiones</strong>
        <span class="muted"><?= count($gestiones) ?> registradas</span>
      </div>
    </div>
  </div>
</div>

<!-- Main Content: 3-column grid -->
<div class="grid grid-3">
  <!-- Column 1: Datos + Contacto -->
  <div class="card">
    <div class="card-head"><h3>Datos principales</h3></div>
    <div class="card-body stack">
      <div class="mini-item"><div>NIT</div><strong><?= e($cliente['nit'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Razón social</div><strong><?= e($cliente['razon_social_referencia'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Placa</div><strong><?= e($cliente['placa_principal'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Referido por</div><strong><?= e($cliente['referido_por'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Habeas data</div><strong><?= $cliente['habeas_data_flag'] ? '✅ Sí' : '❌ No' ?></strong></div>
    </div>
  </div>

  <!-- Column 2: Contactos -->
  <div class="card">
    <div class="card-head"><h3>Contactos</h3></div>
    <div class="card-body stack">
      <?php if (!empty($cliente['telefonos'])): ?>
        <?php foreach ($cliente['telefonos'] as $tel): ?>
        <div class="mini-item">
          <div>☎ <?= e(ucfirst($tel['tipo'])) ?><?= $tel['es_principal'] ? ' · Principal' : '' ?></div>
          <strong><?= e($tel['numero']) ?></strong>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="mini-item"><div class="muted">Sin teléfonos</div></div>
      <?php endif; ?>
      <?php if (!empty($cliente['correos'])): ?>
        <?php foreach ($cliente['correos'] as $mail): ?>
        <div class="mini-item">
          <div>✉ <?= $mail['es_principal'] ? 'Principal' : 'Alterno' ?></div>
          <strong><?= e($mail['correo']) ?></strong>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="mini-item"><div class="muted">Sin correos</div></div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Column 3: Quick actions -->
  <div class="card">
    <div class="card-head"><h3>Acción sugerida</h3></div>
    <div class="card-body stack">
      <?php
        $saldoTotal = array_sum(array_column($cliente['obligaciones'] ?? [], 'saldo_actual'));
      ?>
      <div class="mini-item"><div>Saldo total</div><strong style="color:#d30f19;">$ <?= number_format($saldoTotal, 0, ',', '.') ?></strong></div>
      <div class="mini-item"><div>Próxima acción</div><strong><?= !empty($gestiones) && $gestiones[0]['proxima_gestion_fecha'] ? 'Seguimiento: ' . date('d/m/Y', strtotime($gestiones[0]['proxima_gestion_fecha'])) : 'Sin seguimiento programado' ?></strong></div>
      <a href="<?= route_url('/expedientes/create', 'desktop') ?>&cliente_id=<?= (int)$cliente['id'] ?>" class="btn primary" style="margin-top:8px;">Registrar gestión</a>
    </div>
  </div>
</div>

<!-- Obligations -->
<?php if (!empty($cliente['obligaciones'])): ?>
<div class="card">
  <div class="card-head"><h3>Obligaciones (<?= count($cliente['obligaciones']) ?>)</h3></div>
  <div class="card-body tight table-wrap">
    <table class="sortable">
      <thead>
        <tr><th>Código</th><th>Tipo</th><th>Monto total</th><th>Saldo actual</th><th>Días mora</th><th>Estado</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php foreach ($cliente['obligaciones'] as $obl): ?>
        <tr>
          <td><?= e($obl['codigo_interno'] ?? $obl['id']) ?></td>
          <td><?= e($obl['tipo_obligacion'] ?? '—') ?></td>
          <td data-sort="<?= (float)$obl['valor_inicial'] ?>">$ <?= number_format((float)$obl['valor_inicial'], 0, ',', '.') ?></td>
          <td data-sort="<?= (float)$obl['saldo_actual'] ?>" style="color:#d30f19; font-weight:700;">$ <?= number_format((float)$obl['saldo_actual'], 0, ',', '.') ?></td>
          <td data-sort="<?= (int)($obl['antiguedad_dias'] ?? 0) ?>"><?= (int)($obl['antiguedad_dias'] ?? 0) ?></td>
          <td>
            <?php $ec = ($obl['estado_obligacion'] ?? '') === 'vencida' ? 'red' : (($obl['estado_obligacion'] ?? '') === 'vigente' ? 'green' : 'amber'); ?>
            <span class="pill <?= $ec ?>"><?= e(ucfirst(str_replace('_', ' ', $obl['estado_obligacion'] ?? '—'))) ?></span>
          </td>
          <td><a href="<?= route_url('/cartera/show', 'desktop') ?>&id=<?= (int)$obl['id'] ?>" class="btn small secondary">Ver</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- Gestiones Timeline -->
<div class="card">
  <div class="card-head">
    <h3>Historial de gestiones (<?= count($gestiones) ?>)</h3>
    <a href="<?= route_url('/expedientes/create', 'desktop') ?>&cliente_id=<?= (int)$cliente['id'] ?>" class="btn small primary">+ Nueva gestión</a>
  </div>
  <div class="card-body">
    <?php if (!empty($gestiones)): ?>
    <div class="stack">
      <?php
        $cIcons = ['Llamada'=>'☎','Visita'=>'🏠','Correo'=>'✉','WhatsApp'=>'💬','SMS'=>'📱','Mensaje'=>'💬'];
        foreach ($gestiones as $g):
      ?>
      <div class="insight">
        <div class="dot"><?= $cIcons[$g['canal']] ?? '◌' ?></div>
        <div>
          <strong><?= e(date('d/m/Y · h:i a', strtotime($g['fecha_gestion']))) ?></strong> · <?= e($g['canal']) ?> · <span class="muted"><?= e($g['gestor_nombre']) ?></span>
          <br><span class="muted"><?= e($g['observacion'] ?: $g['resultado']) ?></span>
          <?php if ($g['compromiso_pago_fecha']): ?>
            <br><span class="pill blue" style="margin-top:4px;">Promesa: <?= e($g['compromiso_pago_fecha']) ?> · $ <?= number_format((float)($g['compromiso_pago_valor'] ?? 0), 0, ',', '.') ?></span>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
      <div class="empty-icon">📋</div>
      <p>No hay gestiones registradas para este cliente.</p>
      <a href="<?= route_url('/expedientes/create', 'desktop') ?>&cliente_id=<?= (int)$cliente['id'] ?>" class="btn primary" style="margin-top:12px;">Registrar primera gestión</a>
    </div>
    <?php endif; ?>
  </div>
</div>
