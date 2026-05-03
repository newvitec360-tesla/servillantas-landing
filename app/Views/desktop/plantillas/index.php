<div class="page-head">
  <div>
    <h1>Plantillas de Mensajes</h1>
    <p>Administra las plantillas para campañas SMS, WhatsApp, correo y llamada.</p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/campanas', 'desktop') ?>" class="btn secondary">← Campañas</a>
    <a href="<?= route_url('/plantillas/create', 'desktop') ?>" class="btn primary">Nueva plantilla</a>
  </div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="card" style="padding:14px 18px; border-left:4px solid <?= $_SESSION['flash']['type'] === 'success' ? 'var(--green)' : 'var(--red)' ?>">
    <strong><?= e($_SESSION['flash']['message']) ?></strong>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Stats by channel -->
<div class="grid cols-4" style="margin-bottom:16px;">
  <?php
    $cIcons = ['WhatsApp'=>'💬','SMS'=>'📱','Correo'=>'✉','Llamada'=>'☎'];
    $cColors = ['WhatsApp'=>'green','SMS'=>'blue','Correo'=>'amber','Llamada'=>'red'];
    foreach (['WhatsApp','SMS','Correo','Llamada'] as $c):
  ?>
  <div class="card metric <?= $cColors[$c] ?>">
    <div class="metric-icon"><?= $cIcons[$c] ?></div>
    <div class="metric-meta">
      <span><?= $c ?></span>
      <strong><?= $porCanal[$c] ?? 0 ?></strong>
      <small>plantillas activas</small>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Filter Bar -->
<div class="card">
  <div class="card-body">
    <form method="get" action="<?= route_url('/plantillas', 'desktop') ?>" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
      <input type="hidden" name="r" value="/plantillas">
      <div class="field" style="flex:1; min-width:180px; margin:0;">
        <input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Buscar plantilla..." style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
      </div>
      <div class="field" style="width:140px; margin:0;">
        <select name="canal" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Canal</option>
          <?php foreach (['WhatsApp','SMS','Correo','Llamada'] as $c): ?>
            <option value="<?= $c ?>" <?= ($filters['canal'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field" style="width:140px; margin:0;">
        <select name="estado" style="border:1px solid var(--line); border-radius:14px; padding:12px 13px; width:100%;">
          <option value="">Estado</option>
          <option value="activa" <?= ($filters['estado'] ?? '') === 'activa' ? 'selected' : '' ?>>Activa</option>
          <option value="inactiva" <?= ($filters['estado'] ?? '') === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
        </select>
      </div>
      <button type="submit" class="btn primary">Filtrar</button>
      <?php if (!empty($filters['q']) || !empty($filters['canal']) || !empty($filters['estado'])): ?>
        <a href="<?= route_url('/plantillas', 'desktop') ?>" class="btn ghost">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Templates Grid -->
<div class="grid cols-2">
  <?php if (!empty($plantillas)): ?>
    <?php foreach ($plantillas as $p): ?>
    <div class="card">
      <div class="card-head">
        <h3><?= e($p['nombre']) ?></h3>
        <div class="actions">
          <?php $eColor = $p['estado'] === 'activa' ? 'green' : 'gray'; ?>
          <span class="pill <?= $eColor ?>"><?= e(ucfirst($p['estado'])) ?></span>
          <a href="<?= route_url('/plantillas/edit', 'desktop') ?>&id=<?= (int)$p['id'] ?>" class="btn small secondary">Editar</a>
        </div>
      </div>
      <div class="card-body stack">
        <div class="mini-item"><div>Canal</div><strong><?= $cIcons[$p['canal']] ?? '◌' ?> <?= e($p['canal']) ?></strong></div>
        <?php if ($p['asunto']): ?>
        <div class="mini-item"><div>Asunto</div><strong><?= e($p['asunto']) ?></strong></div>
        <?php endif; ?>
        <?php if ($p['nivel_riesgo_aplicable']): ?>
        <div class="mini-item"><div>Riesgo</div><strong><?= e($p['nivel_riesgo_aplicable']) ?></strong></div>
        <?php endif; ?>
        <div style="background:rgba(255,255,255,0.04); border-radius:10px; padding:12px; margin-top:8px; font-size:13px; line-height:1.6; white-space:pre-line; max-height:120px; overflow:hidden;"><?= e($p['contenido']) ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="card" style="grid-column:span 2;">
      <div class="card-body">
        <div class="empty-state">
          <div class="empty-icon">📝</div>
          <p>No hay plantillas<?= !empty($filters['q']) ? ' con ese criterio' : '' ?>.</p>
          <a href="<?= route_url('/plantillas/create', 'desktop') ?>" class="btn primary" style="margin-top:12px;">Crear primera plantilla</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
