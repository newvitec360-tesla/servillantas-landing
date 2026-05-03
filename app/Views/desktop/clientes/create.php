<div class="page-head">
  <div>
    <h1><?= isset($editing) ? 'Editar cliente' : 'Nuevo cliente' ?></h1>
    <p><?= isset($editing) ? 'Actualiza los datos del cliente.' : 'Registra un nuevo cliente en la base de cartera.' ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/clientes', 'desktop') ?>" class="btn secondary">← Volver al listado</a>
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
  <div class="card-head">
    <h3>Datos del cliente</h3>
  </div>
  <div class="card-body">
    <form method="post" action="<?= isset($editing) ? route_url('/clientes/update', 'desktop') : route_url('/clientes/store', 'desktop') ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
      <?php if (isset($editing) && !empty($cliente['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$cliente['id'] ?>">
      <?php endif; ?>

      <div class="modal-grid">
        <div class="field">
          <label>Nombre completo / Razón social *</label>
          <input type="text" name="nombre_completo" value="<?= e($cliente['nombre_completo'] ?? '') ?>" required placeholder="Ej: Transportes del Valle S.A.S.">
        </div>
        <div class="field">
          <label>Tipo de documento</label>
          <select name="tipo_documento">
            <option value="">Seleccionar...</option>
            <?php
              $tipos = ['CC' => 'Cédula de ciudadanía', 'NIT' => 'NIT', 'CE' => 'Cédula de extranjería', 'PP' => 'Pasaporte', 'TI' => 'Tarjeta de identidad'];
              foreach ($tipos as $code => $label):
            ?>
              <option value="<?= $code ?>" <?= ($cliente['tipo_documento'] ?? '') === $code ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Número de documento *</label>
          <input type="text" name="numero_documento" value="<?= e($cliente['numero_documento'] ?? '') ?>" required placeholder="Ej: 900.123.456-7">
        </div>
        <div class="field">
          <label>NIT</label>
          <input type="text" name="nit" value="<?= e($cliente['nit'] ?? '') ?>" placeholder="Ej: 900.123.456-7">
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Razón social / Referencia</label>
          <input type="text" name="razon_social_referencia" value="<?= e($cliente['razon_social_referencia'] ?? '') ?>" placeholder="Nombre comercial">
        </div>
        <div class="field">
          <label>Placa principal</label>
          <input type="text" name="placa_principal" value="<?= e($cliente['placa_principal'] ?? '') ?>" placeholder="Ej: ABC 123" maxlength="10" style="text-transform:uppercase;">
        </div>
      </div>

      <div class="modal-grid">
        <div class="field">
          <label>Referido por</label>
          <input type="text" name="referido_por" value="<?= e($cliente['referido_por'] ?? '') ?>" placeholder="¿Quién lo refirió?">
        </div>
        <div class="field">
          <label>Estado de localización</label>
          <select name="estado_localizacion">
            <option value="contactable" <?= ($cliente['estado_localizacion'] ?? '') === 'contactable' ? 'selected' : '' ?>>Contactable</option>
            <option value="contacto_incompleto" <?= ($cliente['estado_localizacion'] ?? '') === 'contacto_incompleto' ? 'selected' : '' ?>>Contacto incompleto</option>
            <option value="inalcanzable" <?= ($cliente['estado_localizacion'] ?? '') === 'inalcanzable' ? 'selected' : '' ?>>Inalcanzable</option>
            <option value="visita_requerida" <?= ($cliente['estado_localizacion'] ?? '') === 'visita_requerida' ? 'selected' : '' ?>>Visita requerida</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label>Observaciones</label>
        <textarea name="observaciones" rows="3" placeholder="Notas adicionales sobre el cliente..."><?= e($cliente['observaciones'] ?? '') ?></textarea>
      </div>

      <div class="field" style="flex-direction:row; gap:10px; align-items:center;">
        <input type="checkbox" name="habeas_data_flag" id="habeasData" value="1" <?= !empty($cliente['habeas_data_flag']) ? 'checked' : '' ?>>
        <label for="habeasData" style="margin:0;">Autorización habeas data firmada</label>
      </div>

      <div class="actions" style="margin-top:18px;">
        <button type="submit" class="btn primary"><?= isset($editing) ? 'Actualizar cliente' : 'Crear cliente' ?></button>
        <a href="<?= route_url('/clientes', 'desktop') ?>" class="btn secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
