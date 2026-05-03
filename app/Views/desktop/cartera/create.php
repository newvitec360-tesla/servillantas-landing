<div class="page-head">
  <div>
    <h1><?= isset($editing) ? 'Editar obligación' : 'Nueva obligación' ?></h1>
    <p><?= isset($editing) ? 'Actualiza los datos de la obligación.' : 'Registra una nueva obligación de cartera.' ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/cartera', 'desktop') ?>" class="btn secondary">← Volver</a>
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
  <div class="card-head"><h3>Datos de la obligación</h3></div>
  <div class="card-body">
    <form method="post" action="<?= isset($editing) ? route_url('/cartera/update', 'desktop') : route_url('/cartera/store', 'desktop') ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
      <?php if (isset($editing) && !empty($obligacion['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$obligacion['id'] ?>">
      <?php endif; ?>

      <div class="modal-grid">
        <div class="field">
          <label>Cliente *</label>
          <select name="cliente_id" required <?= isset($editing) ? 'disabled' : '' ?>>
            <option value="">Seleccionar cliente...</option>
            <?php foreach ($clientes as $cli): ?>
              <option value="<?= (int)$cli['id'] ?>" <?= ((int)($obligacion['cliente_id'] ?? 0)) === (int)$cli['id'] ? 'selected' : '' ?>>
                <?= e($cli['nombre_completo']) ?> — <?= e($cli['numero_documento']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($editing)): ?>
            <input type="hidden" name="cliente_id" value="<?= (int)$obligacion['cliente_id'] ?>">
          <?php endif; ?>
        </div>
        <div class="field">
          <label>Código interno *</label>
          <input type="text" name="codigo_interno" value="<?= e($obligacion['codigo_interno'] ?? '') ?>" required placeholder="Ej: OBL-2024-00123">
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Tipo de obligación</label>
          <select name="tipo_obligacion">
            <option value="">Seleccionar...</option>
            <?php $tipos = ['Pagaré','Letra de cambio','Factura','Leasing','Compra de llantas','Servicio','Crédito directo','Otro'];
              foreach ($tipos as $t): ?>
              <option value="<?= $t ?>" <?= ($obligacion['tipo_obligacion'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Concepto</label>
          <input type="text" name="concepto" value="<?= e($obligacion['concepto'] ?? '') ?>" placeholder="Descripción breve">
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Valor inicial *</label>
          <input type="number" name="valor_inicial" value="<?= e($obligacion['valor_inicial'] ?? '') ?>" required min="1" step="0.01" placeholder="Ej: 5000000">
        </div>
        <div class="field">
          <label>Saldo actual</label>
          <input type="number" name="saldo_actual" value="<?= e($obligacion['saldo_actual'] ?? '') ?>" min="0" step="0.01" placeholder="Igual al valor inicial si es nueva">
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Fecha de generación</label>
          <input type="date" name="fecha_generacion" value="<?= e($obligacion['fecha_generacion'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Fecha de vencimiento</label>
          <input type="date" name="fecha_vencimiento" value="<?= e($obligacion['fecha_vencimiento'] ?? '') ?>">
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Estado</label>
          <select name="estado_obligacion">
            <?php $estados = ['vigente'=>'Vigente','vencida'=>'Vencida','critica'=>'Crítica','en_gestion'=>'En gestión','en_acuerdo'=>'En acuerdo','parcialmente_pagada'=>'Parcialmente pagada','juridico'=>'Jurídico','castigada'=>'Castigada'];
              foreach ($estados as $k => $v): ?>
              <option value="<?= $k ?>" <?= ($obligacion['estado_obligacion'] ?? 'vigente') === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Nivel de riesgo</label>
          <select name="nivel_riesgo">
            <option value="S1" <?= ($obligacion['nivel_riesgo'] ?? 'S1') === 'S1' ? 'selected' : '' ?>>S1 — Bajo</option>
            <option value="S2" <?= ($obligacion['nivel_riesgo'] ?? '') === 'S2' ? 'selected' : '' ?>>S2 — Medio</option>
            <option value="S3" <?= ($obligacion['nivel_riesgo'] ?? '') === 'S3' ? 'selected' : '' ?>>S3 — Alto</option>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Origen / Talonario</label>
          <input type="text" name="origen_talonario" value="<?= e($obligacion['origen_talonario'] ?? '') ?>" placeholder="Referencia">
        </div>
        <div class="field">
          <label>Días de mora</label>
          <input type="number" name="antiguedad_dias" value="<?= e($obligacion['antiguedad_dias'] ?? '0') ?>" min="0">
        </div>
      </div>

      <div class="field">
        <label>Observaciones</label>
        <textarea name="observaciones" rows="3" placeholder="Notas..."><?= e($obligacion['observaciones'] ?? '') ?></textarea>
      </div>

      <div class="actions" style="margin-top:18px;">
        <button type="submit" class="btn primary"><?= isset($editing) ? 'Actualizar' : 'Crear obligación' ?></button>
        <a href="<?= route_url('/cartera', 'desktop') ?>" class="btn secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
