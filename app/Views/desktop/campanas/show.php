<?php
  $total = max((int)$campana['total_mensajes'], 1);
  $envRate = round((int)$campana['enviados'] / $total * 100, 1);
  $openRate = round((int)$campana['abiertos'] / $total * 100, 1);
  $clickRate = round((int)$campana['clics'] / $total * 100, 1);
?>

<div class="page-head">
  <div>
    <h1><?= e($campana['nombre']) ?></h1>
    <p><?= e($campana['canal']) ?> · <?= e(ucfirst($campana['estado'])) ?> · Creada por <?= e($campana['creador_nombre'] ?? '—') ?></p>
  </div>
  <div class="actions">
    <a href="<?= route_url('/campanas', 'desktop') ?>" class="btn secondary">← Volver</a>
    <?php if ($campana['estado'] === 'borrador'): ?>
      <a href="<?= route_url('/campanas/edit', 'desktop') ?>&id=<?= (int)$campana['id'] ?>" class="btn secondary">Editar</a>
    <?php endif; ?>
  </div>
</div>

<!-- Results KPIs -->
<div class="grid cols-5" style="margin-bottom:16px;">
  <div class="box"><strong>Enviados</strong><div style="font-size:26px;font-weight:800;color:var(--blue);margin-top:8px;"><?= number_format((int)$campana['total_mensajes'], 0, ',', '.') ?></div></div>
  <div class="box"><strong>Entregados</strong><div style="font-size:26px;font-weight:800;color:var(--green);margin-top:8px;"><?= number_format((int)$campana['entregados'], 0, ',', '.') ?></div></div>
  <div class="box"><strong>Abiertos</strong><div style="font-size:26px;font-weight:800;color:var(--amber);margin-top:8px;"><?= number_format((int)$campana['abiertos'], 0, ',', '.') ?></div></div>
  <div class="box"><strong>Clics</strong><div style="font-size:26px;font-weight:800;color:var(--red);margin-top:8px;"><?= number_format((int)$campana['clics'], 0, ',', '.') ?></div></div>
  <div class="box"><strong>Rebotes</strong><div style="font-size:26px;font-weight:800;color:#a1a1a8;margin-top:8px;"><?= number_format((int)$campana['rebotes'], 0, ',', '.') ?></div></div>
</div>

<!-- Funnel + Summary -->
<div class="grid grid-2-1">
  <!-- Funnel -->
  <div class="card">
    <div class="card-head"><h3>Conversión del proceso</h3></div>
    <div class="card-body funnel">
      <div class="frow"><div>Mensajes enviados</div><div class="fbar"><span style="width:100%"></span></div><strong>100%</strong></div>
      <div class="frow"><div>Entregados</div><div class="fbar"><span style="width:<?= $envRate ?>%"></span></div><strong><?= $envRate ?>%</strong></div>
      <div class="frow"><div>Abiertos</div><div class="fbar"><span style="width:<?= $openRate ?>%"></span></div><strong><?= $openRate ?>%</strong></div>
      <div class="frow"><div>Clics</div><div class="fbar"><span style="width:<?= $clickRate ?>%"></span></div><strong><?= $clickRate ?>%</strong></div>
    </div>
  </div>

  <!-- Campaign Info -->
  <div class="card">
    <div class="card-head"><h3>Resumen de la campaña</h3></div>
    <div class="card-body stack">
      <div class="mini-item"><div>Nombre</div><strong><?= e($campana['nombre']) ?></strong></div>
      <div class="mini-item"><div>Canal</div><strong><?= e($campana['canal']) ?></strong></div>
      <div class="mini-item"><div>Estado</div>
        <?php $eMap = ['borrador'=>'gray','programada'=>'blue','enviada'=>'green','cancelada'=>'red']; ?>
        <span class="pill <?= $eMap[$campana['estado']] ?? 'gray' ?>"><?= e(ucfirst($campana['estado'])) ?></span>
      </div>
      <div class="mini-item"><div>Plantilla</div><strong><?= e($campana['plantilla_nombre'] ?: '—') ?></strong></div>
      <div class="mini-item"><div>Fecha envío</div><strong><?= $campana['fecha_envio'] ? e(date('d/m/Y H:i', strtotime($campana['fecha_envio']))) : '—' ?></strong></div>
      <?php if ($campana['segmento_definicion']): ?>
        <?php $seg = json_decode($campana['segmento_definicion'], true) ?? []; ?>
        <div class="mini-item"><div>Segmento</div><strong>
          <?php
            $parts = [];
            if (!empty($seg['riesgo'])) $parts[] = "Riesgo {$seg['riesgo']}";
            if (!empty($seg['dias_mora_min'])) $parts[] = "+{$seg['dias_mora_min']} días mora";
            if (!empty($seg['estado_obligacion'])) $parts[] = ucfirst($seg['estado_obligacion']);
            echo e(implode(' · ', $parts) ?: 'Todos');
          ?>
        </strong></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Messages Table -->
<div class="card">
  <div class="card-head"><h3>Mensajes enviados</h3><small><?= number_format(count($campana['mensajes'] ?? []), 0, ',', '.') ?> registros</small></div>
  <div class="card-body tight table-wrap">
    <table class="sortable">
      <thead>
        <tr><th>Cliente</th><th>Destinatario</th><th>Canal</th><th>Enviado</th><th>Abierto</th><th>Clic</th><th>Estado</th></tr>
      </thead>
      <tbody>
        <?php if (!empty($campana['mensajes'])): ?>
          <?php foreach ($campana['mensajes'] as $msg): ?>
          <tr>
            <td><strong><?= e($msg['nombre_completo']) ?></strong></td>
            <td><?= e($msg['destinatario']) ?></td>
            <td><?= e($msg['canal']) ?></td>
            <td><?= $msg['fecha_envio'] ? e(date('d/m/Y H:i', strtotime($msg['fecha_envio']))) : '—' ?></td>
            <td><?= $msg['fecha_apertura'] ? e(date('d/m/Y H:i', strtotime($msg['fecha_apertura']))) : '—' ?></td>
            <td><?= $msg['fecha_clic'] ? e(date('d/m/Y H:i', strtotime($msg['fecha_clic']))) : '—' ?></td>
            <td>
              <?php
                $sMap = ['pendiente'=>'gray','enviado'=>'blue','entregado'=>'blue','abierto'=>'amber','clic'=>'green','rebote'=>'red','spam'=>'red','fallido'=>'red'];
                $sc = $sMap[$msg['estado_envio']] ?? 'gray';
              ?>
              <span class="pill <?= $sc ?>"><?= e(ucfirst($msg['estado_envio'])) ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7"><div class="empty-state"><div class="empty-icon">📧</div><p>No hay mensajes registrados para esta campaña.</p></div></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
