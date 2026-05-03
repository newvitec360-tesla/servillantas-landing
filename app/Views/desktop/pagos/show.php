<div class="page-head">
  <div>
    <h1>Pago #<?= (int)$pago['id'] ?></h1>
    <p><?= e($pago['nombre_completo']) ?> · <?= e($pago['medio_pago']) ?> · <?= e(date('d/m/Y H:i', strtotime($pago['fecha_pago']))) ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/pagos', 'desktop') ?>" class="btn secondary">← Volver</a>
    <a href="<?= route_url('/clientes/show', 'desktop') ?>&id=<?= (int)$pago['cliente_id'] ?>" class="btn secondary">Ver cliente</a>
  </div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="card" style="padding:14px 18px; border-left:4px solid <?= $_SESSION['flash']['type'] === 'success' ? 'var(--green)' : 'var(--red)' ?>">
    <strong><?= e($_SESSION['flash']['message']) ?></strong>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Summary Bar -->
<div class="card">
  <div class="card-body">
    <div class="grid cols-4">
      <div class="box">
        <strong>Monto</strong>
        <span style="color:var(--green); font-weight:800; font-size:20px;">$ <?= number_format((float)$pago['valor'], 0, ',', '.') ?></span>
      </div>
      <div class="box">
        <strong>Estado</strong>
        <?php
          $ec = ['pendiente' => 'amber', 'validado' => 'green', 'rechazado' => 'red'];
          $eColor = $ec[$pago['estado_validacion']] ?? 'gray';
          $eLabel = ['pendiente' => 'Pendiente', 'validado' => 'Aprobado', 'rechazado' => 'Rechazado'];
        ?>
        <span class="pill <?= $eColor ?>"><?= $eLabel[$pago['estado_validacion']] ?? $pago['estado_validacion'] ?></span>
      </div>
      <div class="box">
        <strong>Canal</strong>
        <span><?= e($pago['medio_pago']) ?></span>
      </div>
      <div class="box">
        <strong>Fecha</strong>
        <span><?= e(date('d/m/Y H:i', strtotime($pago['fecha_pago']))) ?></span>
      </div>
    </div>
  </div>
</div>

<div class="grid grid-2-1">
  <!-- Left: Details -->
  <div class="card">
    <div class="card-head"><h3>Detalles del pago</h3></div>
    <div class="card-body stack">
      <div class="mini-item"><div>Cliente</div><strong><?= e($pago['nombre_completo']) ?></strong></div>
      <div class="mini-item"><div>Documento</div><strong><?= e(($pago['tipo_documento'] ?? '') . ' ' . $pago['numero_documento']) ?></strong></div>
      <div class="mini-item"><div>Obligación</div><strong><?= e($pago['obligacion_codigo'] ?? '— Sin obligación específica') ?></strong></div>
      <?php if (!empty($pago['obligacion_saldo'])): ?>
        <div class="mini-item"><div>Saldo obligación</div><strong style="color:#d30f19;">$ <?= number_format((float)$pago['obligacion_saldo'], 0, ',', '.') ?></strong></div>
      <?php endif; ?>
      <div class="mini-item"><div>Referencia</div><strong><?= e($pago['referencia_transaccion'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Registrado por</div><strong><?= e($pago['registrado_por_nombre'] ?? '—') ?></strong></div>
      <div class="mini-item"><div>Registrado el</div><strong><?= e($pago['created_at'] ?? '—') ?></strong></div>
    </div>
  </div>

  <!-- Right: Actions -->
  <div class="grid">
    <?php if ($pago['estado_validacion'] === 'pendiente'): ?>
    <div class="card">
      <div class="card-head"><h3>Acciones de validación</h3></div>
      <div class="card-body">
        <div class="grid">
          <form method="post" action="<?= route_url('/pagos/validate', 'desktop') ?>" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="id" value="<?= (int)$pago['id'] ?>">
            <button type="submit" class="btn primary" style="width:100%;">✓ Aprobar pago</button>
          </form>
          <form method="post" action="<?= route_url('/pagos/reject', 'desktop') ?>" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="id" value="<?= (int)$pago['id'] ?>">
            <button type="submit" class="btn secondary" style="width:100%; color:var(--red);">✕ Rechazar pago</button>
          </form>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-head"><h3>Navegación</h3></div>
      <div class="card-body">
        <div class="grid">
          <a href="<?= route_url('/clientes/show', 'desktop') ?>&id=<?= (int)$pago['cliente_id'] ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">◫ Ver ficha del cliente</a>
          <?php if ($pago['obligacion_id']): ?>
            <a href="<?= route_url('/cartera/show', 'desktop') ?>&id=<?= (int)$pago['obligacion_id'] ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">⟁ Ver obligación</a>
          <?php endif; ?>
          <a href="<?= route_url('/pagos/create', 'desktop') ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">💳 Registrar otro pago</a>
        </div>
      </div>
    </div>
  </div>
</div>
