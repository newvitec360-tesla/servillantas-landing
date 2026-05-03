<div class="page-head">
  <div>
    <h1>Ficha del cliente</h1>
    <p><?= e($cliente['nombre_completo']) ?> · <?= e($cliente['tipo_documento'] ?? '') ?> <?= e($cliente['numero_documento']) ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/clientes', 'desktop') ?>" class="btn secondary">← Volver</a>
    <a href="<?= route_url('/clientes/edit', 'desktop') ?>&id=<?= (int)$cliente['id'] ?>" class="btn secondary">Editar</a>
    <?php if (empty($cliente['obligaciones'])): ?>
      <form method="post" action="<?= route_url('/clientes/delete', 'desktop') ?>" style="display:inline;" id="formDeleteCliente">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <input type="hidden" name="id" value="<?= (int)$cliente['id'] ?>">
        <button type="submit" class="btn secondary" style="color:var(--red);">Eliminar</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<!-- Info Summary Bar -->
<div class="card">
  <div class="card-body">
    <div class="grid cols-4">
      <div class="box">
        <strong><?= e($cliente['nombre_completo']) ?></strong>
        <span class="muted"><?= e(($cliente['tipo_documento'] ?? '') . ' ' . ($cliente['numero_documento'] ?? '')) ?></span>
      </div>
      <div class="box">
        <strong>Estado</strong>
        <?php
          $locMap = ['contactable' => ['green','Contactable'], 'contacto_incompleto' => ['amber','Incompleto'], 'inalcanzable' => ['red','Inalcanzable'], 'visita_requerida' => ['blue','Visita requerida']];
          $loc = $locMap[$cliente['estado_localizacion']] ?? ['gray', $cliente['estado_localizacion']];
        ?>
        <span class="pill <?= $loc[0] ?>"><?= e($loc[1]) ?></span>
      </div>
      <div class="box">
        <strong>NIT</strong>
        <span class="muted"><?= e($cliente['nit'] ?: '—') ?></span>
      </div>
      <div class="box">
        <strong>Placa principal</strong>
        <span class="muted"><?= e($cliente['placa_principal'] ?: '—') ?></span>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="grid grid-2-1">
  <!-- Left: Details + Obligations -->
  <div class="grid">
    <!-- Basic Data -->
    <div class="card">
      <div class="card-head"><h3>Datos principales</h3></div>
      <div class="card-body stack">
        <div class="mini-item"><div>Razón social / Referencia</div><strong><?= e($cliente['razon_social_referencia'] ?: '—') ?></strong></div>
        <div class="mini-item"><div>Referido por</div><strong><?= e($cliente['referido_por'] ?: '—') ?></strong></div>
        <div class="mini-item"><div>Habeas data</div><strong><?= $cliente['habeas_data_flag'] ? '✅ Autorizado' : '❌ No autorizado' ?></strong></div>
        <div class="mini-item"><div>Fallecido</div><strong><?= $cliente['fallecido_flag'] ? '⚠ Sí' : 'No' ?></strong></div>
        <?php if ($cliente['observaciones']): ?>
          <div class="mini-item"><div>Observaciones</div><strong><?= e($cliente['observaciones']) ?></strong></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Obligations -->
    <div class="card">
      <div class="card-head">
        <h3>Obligaciones (<?= count($cliente['obligaciones']) ?>)</h3>
        <a href="<?= route_url('/cartera', 'desktop') ?>" class="btn small secondary">Ir a cartera</a>
      </div>
      <?php if (!empty($cliente['obligaciones'])): ?>
      <div class="card-body tight table-wrap">
        <table class="sortable">
          <thead>
            <tr>
              <th>Referencia</th>
              <th>Tipo</th>
              <th>Saldo actual</th>
              <th>Días mora</th>
              <th>Riesgo</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cliente['obligaciones'] as $obl): ?>
            <tr>
              <td><?= e($obl['numero_obligacion'] ?? $obl['id']) ?></td>
              <td><?= e($obl['tipo_obligacion'] ?? '—') ?></td>
              <td data-sort="<?= (float)$obl['saldo_actual'] ?>" style="color:#d30f19; font-weight:700;">$ <?= number_format((float)$obl['saldo_actual'], 0, ',', '.') ?></td>
              <td data-sort="<?= (int)($obl['antiguedad_dias'] ?? 0) ?>"><?= (int)($obl['antiguedad_dias'] ?? 0) ?></td>
              <td>
                <?php $rc = ($obl['nivel_riesgo'] ?? 'S1') === 'S3' ? 'red' : (($obl['nivel_riesgo'] ?? 'S1') === 'S2' ? 'amber' : 'green'); ?>
                <span class="pill <?= $rc ?>"><?= e($obl['nivel_riesgo'] ?? 'S1') ?></span>
              </td>
              <td>
                <?php $ec = ($obl['estado_obligacion'] ?? '') === 'vencida' ? 'red' : (($obl['estado_obligacion'] ?? '') === 'al_dia' ? 'green' : 'amber'); ?>
                <span class="pill <?= $ec ?>"><?= e(ucfirst(str_replace('_', ' ', $obl['estado_obligacion'] ?? '—'))) ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="card-body">
        <div class="empty-state">
          <div class="empty-icon">⟁</div>
          <p>Este cliente no tiene obligaciones registradas.</p>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right: Contact info -->
  <div class="grid">
    <!-- Phones -->
    <div class="card">
      <div class="card-head"><h3>Teléfonos</h3></div>
      <div class="card-body stack">
        <?php if (!empty($cliente['telefonos'])): ?>
          <?php foreach ($cliente['telefonos'] as $tel): ?>
          <div class="mini-item">
            <div>
              <?= e(ucfirst($tel['tipo'])) ?><?= $tel['es_principal'] ? ' · <strong>Principal</strong>' : '' ?>
              <?php if ($tel['observacion']): ?><br><span class="muted"><?= e($tel['observacion']) ?></span><?php endif; ?>
            </div>
            <strong><?= e($tel['numero']) ?></strong>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="mini-item"><div class="muted">Sin teléfonos registrados</div></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Emails -->
    <div class="card">
      <div class="card-head"><h3>Correos</h3></div>
      <div class="card-body stack">
        <?php if (!empty($cliente['correos'])): ?>
          <?php foreach ($cliente['correos'] as $mail): ?>
          <div class="mini-item">
            <div><?= $mail['es_principal'] ? 'Principal' : 'Alterno' ?></div>
            <strong><?= e($mail['correo']) ?></strong>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="mini-item"><div class="muted">Sin correos registrados</div></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="card">
      <div class="card-head"><h3>Acciones rápidas</h3></div>
      <div class="card-body">
        <div class="grid">
          <a href="<?= route_url('/cartera', 'desktop') ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">⟁ Ver cartera</a>
          <a href="<?= route_url('/clientes/edit', 'desktop') ?>&id=<?= (int)$cliente['id'] ?>" class="btn secondary" style="width:100%; justify-content:flex-start;">✎ Editar datos</a>
        </div>
      </div>
    </div>
  </div>
</div>
