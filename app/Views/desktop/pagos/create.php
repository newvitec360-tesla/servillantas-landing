<div class="page-head">
  <div>
    <h1>Registrar pago</h1>
    <p>Registra un nuevo pago recibido por cualquier canal.</p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/pagos', 'desktop') ?>" class="btn secondary">← Volver</a>
  </div>
</div>

<?php if (!empty($errors)): ?>
  <div class="card" style="padding:14px 18px; border-left:4px solid var(--red)">
    <?php foreach ($errors as $err): ?>
      <strong style="color:var(--red);"><?= e($err) ?></strong><br>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-head"><h3>Datos del pago</h3></div>
  <div class="card-body">
    <form method="post" action="<?= route_url('/pagos/store', 'desktop') ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

      <div class="modal-grid">
        <div class="field">
          <label>Cliente *</label>
          <select name="cliente_id" required>
            <option value="">Seleccionar cliente...</option>
            <?php foreach ($clientes as $cli): ?>
              <option value="<?= (int)$cli['id'] ?>" <?= ((int)($pago['cliente_id'] ?? 0)) === (int)$cli['id'] ? 'selected' : '' ?>>
                <?= e($cli['nombre_completo']) ?> — <?= e($cli['numero_documento']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Obligación (opcional)</label>
          <select name="obligacion_id">
            <option value="">Sin obligación específica</option>
            <?php foreach ($obligaciones as $obl): ?>
              <option value="<?= (int)$obl['id'] ?>" <?= ((int)($pago['obligacion_id'] ?? 0)) === (int)$obl['id'] ? 'selected' : '' ?>>
                <?= e($obl['codigo_interno']) ?> — $ <?= number_format((float)$obl['saldo_actual'], 0, ',', '.') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Valor del pago *</label>
          <input type="number" name="valor" value="<?= e($pago['valor'] ?? '') ?>" required min="1" step="0.01" placeholder="Ej: 1250000">
        </div>
        <div class="field">
          <label>Medio de pago *</label>
          <select name="medio_pago" required>
            <option value="">Seleccionar...</option>
            <?php foreach (['PSE','Nequi','Transferencia','Tarjeta','Efectivo','Consignación','Otro'] as $m): ?>
              <option value="<?= $m ?>" <?= ($pago['medio_pago'] ?? '') === $m ? 'selected' : '' ?>><?= $m ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Fecha y hora del pago</label>
          <input type="datetime-local" name="fecha_pago" value="<?= e($pago['fecha_pago'] ?? date('Y-m-d\TH:i')) ?>">
        </div>
        <div class="field">
          <label>Referencia de transacción</label>
          <input type="text" name="referencia_transaccion" value="<?= e($pago['referencia_transaccion'] ?? '') ?>" placeholder="Ej: PSE-987654">
        </div>
      </div>

      <div class="field">
        <label>Estado inicial</label>
        <select name="estado_validacion">
          <option value="pendiente" <?= ($pago['estado_validacion'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente de validación</option>
          <option value="validado" <?= ($pago['estado_validacion'] ?? '') === 'validado' ? 'selected' : '' ?>>Validado/Aprobado</option>
        </select>
      </div>

      <div class="actions" style="margin-top:18px;">
        <button type="submit" class="btn primary">Registrar pago</button>
        <a href="<?= route_url('/pagos', 'desktop') ?>" class="btn secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
