<div class="page-head">
  <div>
    <h1><?= isset($editing) ? 'Editar campaña' : 'Nueva campaña' ?></h1>
    <p><?= isset($editing) ? 'Modifica la configuración de la campaña.' : 'Configura y programa una nueva campaña de cobranza.' ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/campanas', 'desktop') ?>" class="btn secondary">← Volver</a>
  </div>
</div>

<?php if (!empty($errors)): ?>
  <div class="card" style="padding:14px 18px; border-left:4px solid var(--red)">
    <?php foreach ($errors as $err): ?>
      <strong style="color:var(--red);"><?= e($err) ?></strong><br>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="grid grid-2-1">
  <!-- Left: Form -->
  <div class="card">
    <div class="card-head"><h3>Configuración de campaña</h3></div>
    <div class="card-body">
      <form method="post" action="<?= isset($editing) ? route_url('/campanas/update', 'desktop') : route_url('/campanas/store', 'desktop') ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <?php if (isset($editing) && !empty($campana['id'])): ?>
          <input type="hidden" name="id" value="<?= (int)$campana['id'] ?>">
        <?php endif; ?>

        <div class="field">
          <label>Nombre de la campaña *</label>
          <input type="text" name="nombre" value="<?= e($campana['nombre'] ?? '') ?>" required placeholder="Ej: Recordatorio de pago - Mayo 2024">
        </div>

        <div class="modal-grid">
          <div class="field">
            <label>Canal *</label>
            <select name="canal" required>
              <option value="">Seleccionar...</option>
              <?php foreach (['WhatsApp','SMS','Correo','Llamada'] as $c): ?>
                <option value="<?= $c ?>" <?= ($campana['canal'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label>Plantilla</label>
            <select name="plantilla_id">
              <option value="">Sin plantilla</option>
              <?php foreach ($plantillas as $p): ?>
                <option value="<?= (int)$p['id'] ?>" <?= ((int)($campana['plantilla_id'] ?? 0)) === (int)$p['id'] ? 'selected' : '' ?>>
                  <?= e($p['nombre']) ?> (<?= e($p['canal']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="modal-grid">
          <div class="field">
            <label>Fecha de envío</label>
            <input type="datetime-local" name="fecha_envio" value="<?= e($campana['fecha_envio'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Estado</label>
            <select name="estado">
              <option value="borrador" <?= ($campana['estado'] ?? 'borrador') === 'borrador' ? 'selected' : '' ?>>Borrador</option>
              <option value="programada" <?= ($campana['estado'] ?? '') === 'programada' ? 'selected' : '' ?>>Programada</option>
            </select>
          </div>
        </div>

        <!-- Segment Builder -->
        <div style="border:1px solid var(--line); border-radius:14px; padding:18px; margin-top:12px;">
          <h4 style="margin:0 0 12px;">Constructor de segmento</h4>
          <div class="modal-grid">
            <div class="field">
              <label>Nivel de riesgo</label>
              <select name="segmento[riesgo]">
                <option value="">Todos</option>
                <option value="S1" <?= ($campana['segmento']['riesgo'] ?? '') === 'S1' ? 'selected' : '' ?>>S1 — Bajo</option>
                <option value="S2" <?= ($campana['segmento']['riesgo'] ?? '') === 'S2' ? 'selected' : '' ?>>S2 — Medio</option>
                <option value="S3" <?= ($campana['segmento']['riesgo'] ?? '') === 'S3' ? 'selected' : '' ?>>S3 — Alto</option>
              </select>
            </div>
            <div class="field">
              <label>Días mora mínimos</label>
              <input type="number" name="segmento[dias_mora_min]" value="<?= e($campana['segmento']['dias_mora_min'] ?? '') ?>" min="0" placeholder="Ej: 30">
            </div>
          </div>
          <div class="modal-grid">
            <div class="field">
              <label>Estado de obligación</label>
              <select name="segmento[estado_obligacion]">
                <option value="">Todos</option>
                <option value="vencida" <?= ($campana['segmento']['estado_obligacion'] ?? '') === 'vencida' ? 'selected' : '' ?>>Vencida</option>
                <option value="critica" <?= ($campana['segmento']['estado_obligacion'] ?? '') === 'critica' ? 'selected' : '' ?>>Crítica</option>
                <option value="en_gestion" <?= ($campana['segmento']['estado_obligacion'] ?? '') === 'en_gestion' ? 'selected' : '' ?>>En gestión</option>
              </select>
            </div>
            <div class="field">
              <label>Contactable</label>
              <select name="segmento[contactable]">
                <option value="">Todos</option>
                <option value="1" <?= ($campana['segmento']['contactable'] ?? '') === '1' ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= ($campana['segmento']['contactable'] ?? '') === '0' ? 'selected' : '' ?>>No</option>
              </select>
            </div>
          </div>
        </div>

        <div class="actions" style="margin-top:18px;">
          <button type="submit" class="btn primary"><?= isset($editing) ? 'Actualizar' : 'Crear campaña' ?></button>
          <a href="<?= route_url('/campanas', 'desktop') ?>" class="btn secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Right: Tips -->
  <div class="grid">
    <div class="card">
      <div class="card-head"><h3>Campos disponibles</h3></div>
      <div class="card-body">
        <div class="box" style="line-height:1.8; font-size:13px;">
          <code>{nombre}</code> · <code>{monto}</code> · <code>{dias_mora}</code> · <code>{enlace_pago}</code> · <code>{fecha_limite}</code> · <code>{codigo_obligacion}</code>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-head"><h3>Ejemplo de mensaje</h3></div>
      <div class="card-body">
        <div class="box" style="line-height:1.65; font-size:13px;">
          Hola {nombre},<br><br>
          Te recordamos que tienes un saldo pendiente de {monto}, con {dias_mora} días de mora.<br><br>
          Ponte al día aquí:<br>
          👉 {enlace_pago}<br><br>
          Válido hasta {fecha_limite}.<br><br>
          ¡Gracias por confiar en Servillantas El Puente!
        </div>
      </div>
    </div>
  </div>
</div>
