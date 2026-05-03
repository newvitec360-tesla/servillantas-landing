<div class="page-head">
  <div>
    <h1>Registrar gestión de cobranza</h1>
    <p>Documenta cada contacto con el cliente para trazabilidad completa.</p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/expedientes', 'desktop') ?>" class="btn secondary">← Volver</a>
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
  <div class="card-head"><h3>Datos de la gestión</h3></div>
  <div class="card-body">
    <form method="post" action="<?= route_url('/expedientes/store', 'desktop') ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

      <div class="modal-grid">
        <div class="field">
          <label>Cliente *</label>
          <select name="cliente_id" required>
            <option value="">Seleccionar cliente...</option>
            <?php foreach ($clientes as $cli): ?>
              <option value="<?= (int)$cli['id'] ?>" <?= ((int)($gestion['cliente_id'] ?? 0)) === (int)$cli['id'] ? 'selected' : '' ?>>
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
              <option value="<?= (int)$obl['id'] ?>" <?= ((int)($gestion['obligacion_id'] ?? 0)) === (int)$obl['id'] ? 'selected' : '' ?>>
                <?= e($obl['codigo_interno']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Canal *</label>
          <select name="canal" required>
            <option value="">Seleccionar...</option>
            <?php foreach (['Llamada','Visita','Correo','WhatsApp','SMS','Mensaje'] as $c): ?>
              <option value="<?= $c ?>" <?= ($gestion['canal'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Resultado *</label>
          <select name="resultado" required>
            <option value="">Seleccionar...</option>
            <?php foreach (['Contacto efectivo','Sin respuesta','Promesa de pago','Número errado','Buzón','Pago realizado','Negativa','Buzón de voz','Tercero responde'] as $r): ?>
              <option value="<?= $r ?>" <?= ($gestion['resultado'] ?? '') === $r ? 'selected' : '' ?>><?= $r ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Fecha y hora</label>
          <input type="datetime-local" name="fecha_gestion" value="<?= e($gestion['fecha_gestion'] ?? date('Y-m-d\TH:i')) ?>">
        </div>
        <div class="field">
          <label>Próxima gestión</label>
          <input type="datetime-local" name="proxima_gestion_fecha" value="<?= e($gestion['proxima_gestion_fecha'] ?? '') ?>">
        </div>
      </div>

      <div class="field">
        <label>Observaciones</label>
        <textarea name="observacion" rows="3" placeholder="Describe lo sucedido en el contacto..."><?= e($gestion['observacion'] ?? '') ?></textarea>
      </div>

      <div style="border:1px solid var(--line); border-radius:14px; padding:18px; margin-top:12px;">
        <h4 style="margin:0 0 12px;">Compromiso de pago (opcional)</h4>
        <div class="modal-grid">
          <div class="field">
            <label>Fecha compromiso</label>
            <input type="date" name="compromiso_pago_fecha" value="<?= e($gestion['compromiso_pago_fecha'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Valor compromiso</label>
            <input type="number" name="compromiso_pago_valor" value="<?= e($gestion['compromiso_pago_valor'] ?? '') ?>" min="0" step="0.01" placeholder="Ej: 500000">
          </div>
        </div>
      </div>

      <div class="actions" style="margin-top:18px;">
        <button type="submit" class="btn primary">Registrar gestión</button>
        <a href="<?= route_url('/expedientes', 'desktop') ?>" class="btn secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
