<div class="page-head">
  <div>
    <h1>Obligación <?= e($obligacion['codigo_interno']) ?></h1>
    <p><?= e($obligacion['nombre_completo']) ?> · <?= e($obligacion['tipo_documento'] ?? '') ?> <?= e($obligacion['numero_documento']) ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/cartera', 'desktop') ?>" class="btn secondary">← Volver</a>
    <a href="<?= route_url('/cartera/edit', 'desktop') ?>&id=<?= (int)$obligacion['id'] ?>" class="btn secondary">Editar</a>
    <a href="<?= route_url('/clientes/show', 'desktop') ?>&id=<?= (int)$obligacion['cliente_id'] ?>" class="btn primary">Ver cliente</a>
  </div>
</div>

<!-- Summary Bar -->
<div class="card">
  <div class="card-body">
    <div class="grid cols-4">
      <div class="box">
        <strong>Saldo actual</strong>
        <span style="color:#d30f19; font-weight:800; font-size:18px;">$ <?= number_format((float)$obligacion['saldo_actual'], 0, ',', '.') ?></span>
      </div>
      <div class="box">
        <strong>Riesgo</strong>
        <?php $rc = $obligacion['nivel_riesgo'] === 'S3' ? 'red' : ($obligacion['nivel_riesgo'] === 'S2' ? 'amber' : 'green'); ?>
        <span class="pill <?= $rc ?>"><?= e($obligacion['nivel_riesgo']) ?></span>
      </div>
      <div class="box">
        <strong>Días mora</strong>
        <span><?= (int)$obligacion['antiguedad_dias'] ?> días</span>
      </div>
      <div class="box">
        <strong>Estado</strong>
        <?php
          $estadoMap = ['vigente'=>'green','vencida'=>'red','critica'=>'red','en_gestion'=>'blue','en_acuerdo'=>'amber','pagada'=>'green','parcialmente_pagada'=>'amber','castigada'=>'gray','fallecido'=>'gray','juridico'=>'purple'];
          $ec = $estadoMap[$obligacion['estado_obligacion']] ?? 'gray';
        ?>
        <span class="pill <?= $ec ?>"><?= e(ucfirst(str_replace('_', ' ', $obligacion['estado_obligacion']))) ?></span>
      </div>
    </div>
  </div>
</div>

<!-- Details -->
<div class="grid grid-2-1">
  <div class="card">
    <div class="card-head"><h3>Datos de la obligación</h3></div>
    <div class="card-body stack">
      <div class="mini-item"><div>Código interno</div><strong><?= e($obligacion['codigo_interno']) ?></strong></div>
      <div class="mini-item"><div>Tipo</div><strong><?= e($obligacion['tipo_obligacion'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Concepto</div><strong><?= e($obligacion['concepto'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Origen / Talonario</div><strong><?= e($obligacion['origen_talonario'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Valor inicial</div><strong>$ <?= number_format((float)$obligacion['valor_inicial'], 0, ',', '.') ?></strong></div>
      <div class="mini-item"><div>Fecha generación</div><strong><?= e($obligacion['fecha_generacion'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Fecha vencimiento</div><strong><?= e($obligacion['fecha_vencimiento'] ?: '—') ?></strong></div>
      <?php if ($obligacion['fecha_ultimo_abono']): ?>
        <div class="mini-item"><div>Último abono</div><strong><?= e($obligacion['fecha_ultimo_abono']) ?> · $ <?= number_format((float)($obligacion['valor_ultimo_abono'] ?? 0), 0, ',', '.') ?></strong></div>
      <?php endif; ?>
      <?php if ($obligacion['observaciones']): ?>
        <div class="mini-item"><div>Observaciones</div><strong><?= e($obligacion['observaciones']) ?></strong></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="grid">
    <div class="card">
      <div class="card-head"><h3>Cliente asociado</h3></div>
      <div class="card-body stack">
        <div class="mini-item"><div>Nombre</div><strong><?= e($obligacion['nombre_completo']) ?></strong></div>
        <div class="mini-item"><div>Documento</div><strong><?= e(($obligacion['tipo_documento'] ?? '') . ' ' . $obligacion['numero_documento']) ?></strong></div>
        <?php if ($obligacion['nit']): ?>
          <div class="mini-item"><div>NIT</div><strong><?= e($obligacion['nit']) ?></strong></div>
        <?php endif; ?>
        <?php if ($obligacion['placa_principal']): ?>
          <div class="mini-item"><div>Placa</div><strong><?= e($obligacion['placa_principal']) ?></strong></div>
        <?php endif; ?>
      </div>
    </div>
    <div class="card">
      <div class="card-head"><h3>Acciones</h3></div>
      <div class="card-body">
        <div class="grid">
          <a href="<?= route_url('/clientes/show', 'desktop') ?>&id=<?= (int)$obligacion['cliente_id'] ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">◫ Ver ficha del cliente</a>
          <a href="<?= route_url('/cartera/edit', 'desktop') ?>&id=<?= (int)$obligacion['id'] ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">✎ Editar obligación</a>
          <a href="<?= route_url('/pagos', 'desktop') ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">◪ Registrar pago</a>
        </div>
      </div>
    </div>
  </div>
</div>
