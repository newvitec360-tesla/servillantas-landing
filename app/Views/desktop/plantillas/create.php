<div class="page-head">
  <div>
    <h1><?= isset($editing) ? 'Editar plantilla' : 'Nueva plantilla' ?></h1>
    <p><?= isset($editing) ? 'Modifica el contenido de la plantilla.' : 'Crea una plantilla de mensaje reutilizable para tus campañas.' ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/plantillas', 'desktop') ?>" class="btn secondary">← Volver</a>
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
    <div class="card-head"><h3>Configuración de plantilla</h3></div>
    <div class="card-body">
      <form method="post" action="<?= isset($editing) ? route_url('/plantillas/update', 'desktop') : route_url('/plantillas/store', 'desktop') ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <?php if (isset($editing) && !empty($plantilla['id'])): ?>
          <input type="hidden" name="id" value="<?= (int)$plantilla['id'] ?>">
        <?php endif; ?>

        <div class="field">
          <label>Nombre de la plantilla *</label>
          <input type="text" name="nombre" value="<?= e($plantilla['nombre'] ?? '') ?>" required placeholder="Ej: Recordatorio de pago - WhatsApp">
        </div>

        <div class="modal-grid">
          <div class="field">
            <label>Canal *</label>
            <select name="canal" required>
              <option value="">Seleccionar...</option>
              <?php foreach (['WhatsApp','SMS','Correo','Llamada'] as $c): ?>
                <option value="<?= $c ?>" <?= ($plantilla['canal'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label>Nivel de riesgo</label>
            <select name="nivel_riesgo_aplicable">
              <option value="">Todos los niveles</option>
              <option value="S1" <?= ($plantilla['nivel_riesgo_aplicable'] ?? '') === 'S1' ? 'selected' : '' ?>>S1 — Bajo</option>
              <option value="S2" <?= ($plantilla['nivel_riesgo_aplicable'] ?? '') === 'S2' ? 'selected' : '' ?>>S2 — Medio</option>
              <option value="S3" <?= ($plantilla['nivel_riesgo_aplicable'] ?? '') === 'S3' ? 'selected' : '' ?>>S3 — Alto</option>
            </select>
          </div>
        </div>

        <div class="modal-grid">
          <div class="field">
            <label>Asunto (para correo)</label>
            <input type="text" name="asunto" value="<?= e($plantilla['asunto'] ?? '') ?>" placeholder="Solo aplica para canal Correo">
          </div>
          <div class="field">
            <label>Estado</label>
            <select name="estado">
              <option value="activa" <?= ($plantilla['estado'] ?? 'activa') === 'activa' ? 'selected' : '' ?>>Activa</option>
              <option value="inactiva" <?= ($plantilla['estado'] ?? '') === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
            </select>
          </div>
        </div>

        <div class="field">
          <label>Contenido del mensaje *</label>
          <textarea name="contenido" rows="8" required placeholder="Escribe el mensaje usando los campos dinámicos disponibles..."><?= e($plantilla['contenido'] ?? '') ?></textarea>
        </div>

        <div class="actions" style="margin-top:18px;">
          <button type="submit" class="btn primary"><?= isset($editing) ? 'Actualizar' : 'Crear plantilla' ?></button>
          <a href="<?= route_url('/plantillas', 'desktop') ?>" class="btn secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Right: Field Reference + Preview -->
  <div class="grid">
    <div class="card">
      <div class="card-head"><h3>Campos dinámicos</h3></div>
      <div class="card-body">
        <div class="box" style="line-height:2; font-size:13px;">
          <code>{nombre}</code> Nombre del cliente<br>
          <code>{documento}</code> Número de documento<br>
          <code>{monto}</code> Saldo pendiente<br>
          <code>{dias_mora}</code> Días en mora<br>
          <code>{codigo_obligacion}</code> Código obligación<br>
          <code>{fecha_limite}</code> Fecha límite de pago<br>
          <code>{enlace_pago}</code> URL de pago online<br>
          <code>{empresa}</code> Nombre de la empresa
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-head"><h3>Ejemplo de uso</h3></div>
      <div class="card-body">
        <div class="box" style="line-height:1.65; font-size:13px;">
          Hola <code>{nombre}</code>,<br><br>
          Le recordamos que tiene un saldo pendiente de <code>{monto}</code> con <code>{dias_mora}</code> días de mora.<br><br>
          Realice su pago antes del <code>{fecha_limite}</code>.<br><br>
          👉 <code>{enlace_pago}</code><br><br>
          ¡Gracias por confiar en <code>{empresa}</code>!
        </div>
      </div>
    </div>
  </div>
</div>
