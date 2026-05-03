<?php
$activeTab = $_GET['tab'] ?? 'general';
$eColors = ['activo' => 'green', 'inactivo' => 'gray', 'bloqueado' => 'red'];
?>
<?php if (!empty($flash)): ?>
<div class="toast <?= e($flash['type']) ?>" id="flashToast"><?= e($flash['message']) ?></div>
<?php endif; ?>

<div class="page-head">
  <div><h1>Configuración del sistema</h1><p>Administra usuarios, permisos, plantillas, integraciones y seguridad.</p></div>
  <div class="actions"><button class="btn primary" id="btnSaveSettings">Guardar cambios</button></div>
</div>

<div class="settings-layout">
  <div class="inner-nav" id="settingsNav">
    <button <?= $activeTab==='general'?'class="active"':'' ?> data-tab="general">General</button>
    <button <?= $activeTab==='usuarios'?'class="active"':'' ?> data-tab="usuarios">Usuarios y roles</button>
    <button <?= $activeTab==='politicas'?'class="active"':'' ?> data-tab="politicas">Políticas de cobro</button>
    <button <?= $activeTab==='plantillas'?'class="active"':'' ?> data-tab="plantillas">Plantillas</button>
    <button <?= $activeTab==='integraciones'?'class="active"':'' ?> data-tab="integraciones">Integraciones</button>
    <button <?= $activeTab==='seguridad'?'class="active"':'' ?> data-tab="seguridad">Seguridad</button>
    <button <?= $activeTab==='notificaciones'?'class="active"':'' ?> data-tab="notificaciones">Notificaciones</button>
  </div>

  <div class="stack">

    <!-- TAB: General (editable) -->
    <div class="tab-panel <?= $activeTab==='general'?'active':'' ?>" data-tab="general">
      <form method="post" action="<?= route_url('/configuracion/general/save','desktop') ?>">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
        <div class="grid cols-2">
          <div class="card">
            <div class="card-head"><h3>Perfil de la empresa</h3></div>
            <div class="card-body stack">
              <div class="form-group"><label>Razón social</label><input type="text" name="razon_social" value="<?= e($generalConfig['razon_social'] ?? 'Servillantas El Puente S.A.S.') ?>"></div>
              <div class="form-group"><label>NIT</label><input type="text" name="nit" value="<?= e($generalConfig['nit'] ?? '') ?>"></div>
              <div class="form-group"><label>Dirección</label><input type="text" name="direccion" value="<?= e($generalConfig['direccion'] ?? '') ?>"></div>
              <div class="form-group"><label>Teléfono</label><input type="text" name="telefono" value="<?= e($generalConfig['telefono'] ?? '') ?>"></div>
            </div>
          </div>
          <div class="card">
            <div class="card-head"><h3>Marca y personalización</h3></div>
            <div class="card-body stack">
              <div class="form-group"><label>Color primario</label><input type="color" name="color_primario" value="<?= e($generalConfig['color_primario'] ?? '#E30613') ?>" style="height:40px;"></div>
              <div class="form-group"><label>Color secundario</label><input type="color" name="color_secundario" value="<?= e($generalConfig['color_secundario'] ?? '#1A1A1A') ?>" style="height:40px;"></div>
              <div class="mini-item"><div>Plataforma</div><strong><?= e($generalConfig['plataforma'] ?? 'Liquid Glass 2026') ?></strong></div>
              <div class="mini-item"><div>Versión</div><strong><?= e($generalConfig['version'] ?? 'v1.0.0') ?></strong></div>
            </div>
          </div>
        </div>
        <div style="margin-top:16px;text-align:right;"><button type="submit" class="btn primary">Guardar configuración general</button></div>
      </form>
      <div class="card" style="margin-top:16px;">
        <div class="card-head"><h3>Estadísticas del sistema</h3></div>
        <div class="card-body"><div class="grid cols-4">
          <div class="box"><strong>Usuarios</strong><div style="font-size:24px;font-weight:800;color:var(--blue);margin-top:8px;"><?= array_sum($byEstado) ?></div></div>
          <div class="box"><strong>Roles</strong><div style="font-size:24px;font-weight:800;color:var(--amber);margin-top:8px;"><?= count($roles) ?></div></div>
          <div class="box"><strong>Plantillas</strong><div style="font-size:24px;font-weight:800;color:var(--green);margin-top:8px;"><?= count($plantillas) ?></div></div>
          <div class="box"><strong>Módulos</strong><div style="font-size:24px;font-weight:800;color:var(--red);margin-top:8px;"><?= count($modules) ?></div></div>
        </div></div>
      </div>
    </div>

    <!-- TAB: Usuarios y roles -->
    <div class="tab-panel <?= $activeTab==='usuarios'?'active':'' ?>" data-tab="usuarios">
      <div class="card">
        <div class="card-head">
          <h3>Usuarios del sistema <small><?= count($usuarios) ?> registrados</small></h3>
          <button class="btn primary small" id="btnNewUser">＋ Nuevo usuario</button>
        </div>
        <div class="card-body tight table-wrap">
          <table class="sortable">
            <thead><tr><th>Usuario</th><th>Nombre</th><th>Rol</th><th>Estado</th><th>Último login</th><th>Acciones</th></tr></thead>
            <tbody>
              <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?= e($u['correo']) ?></td>
                <td><strong><?= e($u['nombre']) ?></strong></td>
                <td>
                  <?php
                    $rolColors = ['Administrador' => 'red', 'Gestor' => 'amber', 'Analista' => 'blue', 'Coordinador' => 'green', 'Jurídico' => 'purple'];
                    $rc = 'gray';
                    foreach ($rolColors as $key => $color) { if (stripos($u['rol_nombre'] ?? '', $key) !== false) { $rc = $color; break; } }
                  ?>
                  <span class="pill <?= $rc ?>"><?= e($u['rol_nombre'] ?? '—') ?></span>
                </td>
                <td><span class="pill <?= $eColors[$u['estado']] ?? 'gray' ?>"><?= e(ucfirst($u['estado'])) ?></span></td>
                <td><?= $u['ultimo_login'] ? e(date('d/m/Y H:i', strtotime($u['ultimo_login']))) : '—' ?></td>
                <td class="actions-cell">
                  <button class="btn-icon" title="Editar" data-edit-user='<?= e(json_encode(['id'=>$u['id'],'nombre'=>$u['nombre'],'correo'=>$u['correo'],'telefono'=>$u['telefono']??'','rol_id'=>$u['rol_id'],'estado'=>$u['estado']])) ?>'>✏️</button>
                  <?php if ($u['estado'] === 'activo'): ?>
                    <form method="post" action="<?= route_url('/configuracion/usuarios/toggle','desktop') ?>" style="display:inline;">
                      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                      <input type="hidden" name="id" value="<?= $u['id'] ?>">
                      <input type="hidden" name="estado" value="inactivo">
                      <button type="submit" class="btn-icon" title="Desactivar">⏸️</button>
                    </form>
                  <?php else: ?>
                    <form method="post" action="<?= route_url('/configuracion/usuarios/toggle','desktop') ?>" style="display:inline;">
                      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                      <input type="hidden" name="id" value="<?= $u['id'] ?>">
                      <input type="hidden" name="estado" value="activo">
                      <button type="submit" class="btn-icon" title="Activar">▶️</button>
                    </form>
                  <?php endif; ?>
                  <form method="post" action="<?= route_url('/configuracion/usuarios/reset-password','desktop') ?>" style="display:inline;" onsubmit="return confirm('¿Reiniciar contraseña?')">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <button type="submit" class="btn-icon" title="Reset password">🔑</button>
                  </form>
                  <form method="post" action="<?= route_url('/configuracion/usuarios/delete','desktop') ?>" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?')">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <button type="submit" class="btn-icon danger" title="Eliminar">🗑️</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Roles CRUD -->
      <div class="card" style="margin-top:16px;">
        <div class="card-head">
          <h3>Roles del sistema <small><?= count($roles) ?> registrados</small></h3>
          <button class="btn primary small" id="btnNewRole">＋ Nuevo rol</button>
        </div>
        <div class="card-body tight table-wrap">
          <table>
            <thead><tr><th>Nombre</th><th>Descripción</th><th>Usuarios</th><th>Acciones</th></tr></thead>
            <tbody>
              <?php foreach ($roles as $r): ?>
              <tr>
                <td><strong><?= e($r['nombre']) ?></strong></td>
                <td><?= e($r['descripcion'] ?? '—') ?></td>
                <td><?php
                  $cnt = 0;
                  foreach ($byRol as $rn => $q) { if ($rn === $r['nombre']) { $cnt = $q; break; } }
                  echo $cnt;
                ?></td>
                <td class="actions-cell">
                  <button class="btn-icon" title="Editar" data-edit-role='<?= e(json_encode(['id'=>$r['id'],'nombre'=>$r['nombre'],'descripcion'=>$r['descripcion']??''])) ?>'>✏️</button>
                  <form method="post" action="<?= route_url('/configuracion/roles/delete','desktop') ?>" style="display:inline;" onsubmit="return confirm('¿Eliminar este rol?')">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn-icon danger" title="Eliminar">🗑️</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Editable Permissions Matrix (from DB) -->
      <div class="card" style="margin-top:16px;">
        <div class="card-head"><h3>Matriz de permisos</h3><small>Selecciona permisos por rol y guarda</small></div>
        <div class="card-body tight table-wrap">
          <?php foreach ($roles as $r): ?>
          <form method="post" action="<?= route_url('/configuracion/permisos/sync','desktop') ?>" style="margin-bottom:16px;">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="rol_id" value="<?= $r['id'] ?>">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
              <strong><?= e($r['nombre']) ?></strong>
              <button type="submit" class="btn small primary">Guardar</button>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
              <?php
                $rolPerms = $dbMatrix[(int)$r['id']] ?? [];
                foreach ($allPermisos as $p):
                  $checked = in_array((int)$p['id'], $rolPerms) ? 'checked' : '';
              ?>
              <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                <input type="checkbox" name="permisos[]" value="<?= $p['id'] ?>" <?= $checked ?>>
                <?= e($p['codigo']) ?>
              </label>
              <?php endforeach; ?>
            </div>
          </form>
          <hr style="border:none;border-top:1px solid var(--border);margin:12px 0;">
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- TAB: Políticas de cobro (from DB) -->
    <div class="tab-panel <?= $activeTab==='politicas'?'active':'' ?>" data-tab="politicas">
      <div class="card">
        <div class="card-head">
          <h3>Políticas de cobranza <small><?= count($politicas) ?> registradas</small></h3>
          <button class="btn primary small" id="btnNewPolitica">＋ Nueva política</button>
        </div>
        <div class="card-body tight table-wrap">
          <table>
            <thead><tr><th>Nombre</th><th>Nivel</th><th>Días mora</th><th>Canales</th><th>Frecuencia</th><th>Horario</th><th>Acciones</th></tr></thead>
            <tbody>
              <?php
                $nrColors = ['preventivo'=>'blue','S1'=>'green','S2'=>'amber','S3'=>'red','juridico'=>'purple'];
                foreach ($politicas as $p):
                  $canales = json_decode($p['canales_permitidos'] ?? '[]', true);
              ?>
              <tr>
                <td><strong><?= e($p['nombre']) ?></strong></td>
                <td><span class="pill <?= $nrColors[$p['nivel_riesgo']] ?? 'gray' ?>"><?= e($p['nivel_riesgo']) ?></span></td>
                <td><?= $p['dias_mora_desde'] ?>–<?= $p['dias_mora_hasta'] ?? '∞' ?></td>
                <td><?= e(implode(', ', $canales)) ?></td>
                <td><?= e($p['frecuencia_maxima']) ?></td>
                <td><?= substr($p['horario_inicio'],0,5) ?>–<?= substr($p['horario_fin'],0,5) ?></td>
                <td class="actions-cell">
                  <button class="btn-icon" title="Editar" data-edit-politica='<?= e(json_encode($p)) ?>'>✏️</button>
                  <form method="post" action="<?= route_url('/configuracion/politicas/delete','desktop') ?>" style="display:inline;" onsubmit="return confirm('¿Eliminar esta política?')">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button type="submit" class="btn-icon danger" title="Eliminar">🗑️</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($politicas)): ?>
              <tr><td colspan="7" class="muted" style="text-align:center;padding:20px;">Sin políticas configuradas.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB: Plantillas -->
    <div class="tab-panel <?= $activeTab==='plantillas'?'active':'' ?>" data-tab="plantillas">
      <div class="card">
        <div class="card-head"><h3>Plantillas activas</h3>
          <a href="<?= route_url('/plantillas', 'desktop') ?>" class="btn small primary">Administrar</a>
        </div>
        <div class="card-body stack">
          <?php if (!empty($plantillas)): ?>
            <?php foreach ($plantillas as $p): ?>
            <div class="mini-item">
              <div><?php $cIcons = ['WhatsApp'=>'💬','SMS'=>'📱','Correo'=>'✉','Llamada'=>'☎']; ?>
                <?= $cIcons[$p['canal']] ?? '◌' ?> <?= e($p['nombre']) ?>
                <?php if ($p['nivel_riesgo_aplicable']): ?><span class="pill gray" style="margin-left:6px;"><?= e($p['nivel_riesgo_aplicable']) ?></span><?php endif; ?>
              </div>
              <span class="pill green">Activa</span>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="muted">No hay plantillas activas. <a href="<?= route_url('/plantillas/create', 'desktop') ?>">Crear una</a>.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- TAB: Integraciones -->
    <div class="tab-panel <?= $activeTab==='integraciones'?'active':'' ?>" data-tab="integraciones">
      <div class="card">
        <div class="card-head"><h3>Integraciones de pago y canales</h3></div>
        <div class="card-body"><div class="grid cols-3">
          <div class="box"><strong>PSE</strong><span class="muted">Transferencia bancaria</span><br><br><span class="pill amber">Pendiente configurar</span></div>
          <div class="box"><strong>Nequi</strong><span class="muted">Billetera digital</span><br><br><span class="pill amber">Pendiente configurar</span></div>
          <div class="box"><strong>WhatsApp Business</strong><span class="muted">API de mensajería</span><br><br><span class="pill amber">Pendiente configurar</span></div>
        </div></div>
      </div>
      <div class="card" style="margin-top:16px;">
        <div class="card-head"><h3>Servicios de comunicación</h3></div>
        <div class="card-body"><div class="grid cols-3">
          <div class="box"><strong>Email SMTP</strong><span class="muted">Correos transaccionales</span><br><br><span class="pill amber">Pendiente</span></div>
          <div class="box"><strong>SMS Gateway</strong><span class="muted">Mensajes de texto masivos</span><br><br><span class="pill amber">Pendiente</span></div>
          <div class="box"><strong>Pasarela de pagos</strong><span class="muted">Recaudo en línea</span><br><br><span class="pill amber">Pendiente</span></div>
        </div></div>
      </div>
    </div>

    <!-- TAB: Seguridad -->
    <div class="tab-panel <?= $activeTab==='seguridad'?'active':'' ?>" data-tab="seguridad">
      <div class="grid cols-2">
        <div class="card">
          <div class="card-head"><h3>Seguridad del sistema</h3></div>
          <div class="card-body stack">
            <div class="mini-item"><div>Protección CSRF</div><span class="pill green">Activa</span></div>
            <div class="mini-item"><div>Content Security Policy</div><span class="pill green">Nonce-only</span></div>
            <div class="mini-item"><div>Prepared Statements</div><span class="pill green">Obligatorio</span></div>
            <div class="mini-item"><div>Política de contraseñas</div><strong>Mínimo 8 caracteres</strong></div>
            <div class="mini-item"><div>Tiempo de sesión</div><strong>30 min</strong></div>
            <div class="mini-item"><div>Rate limiting (login)</div><strong>5 intentos / 15 min</strong></div>
          </div>
        </div>
        <div class="card">
          <div class="card-head"><h3>Registro de auditoría</h3></div>
          <div class="card-body stack">
            <div class="mini-item"><div>Auditoría de acciones</div><span class="pill green">Activo</span></div>
            <div class="mini-item"><div>Log de accesos</div><span class="pill green">Activo</span></div>
            <div class="mini-item"><div>Regeneración de sesión</div><span class="pill green">Post-login</span></div>
            <div class="mini-item"><div>Cookies seguras</div><strong>HttpOnly + SameSite=Lax</strong></div>
            <div class="mini-item"><div>APP_DEBUG</div><span class="pill green">false (producción)</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB: Notificaciones -->
    <div class="tab-panel <?= $activeTab==='notificaciones'?'active':'' ?>" data-tab="notificaciones">
      <div class="grid cols-2">
        <div class="card">
          <div class="card-head"><h3>Notificaciones del sistema</h3></div>
          <div class="card-body stack">
            <div class="mini-item"><div>Alertas de cartera vencida</div><span class="pill green">Activas</span></div>
            <div class="mini-item"><div>Validaciones de pago</div><span class="pill green">Activas</span></div>
            <div class="mini-item"><div>Eventos de seguridad</div><span class="pill amber">Solo admin</span></div>
            <div class="mini-item"><div>Seguimientos próximos</div><span class="pill green">Activas</span></div>
            <div class="mini-item"><div>Campañas completadas</div><span class="pill green">Activas</span></div>
          </div>
        </div>
        <div class="card">
          <div class="card-head"><h3>Canales de notificación</h3></div>
          <div class="card-body stack">
            <div class="mini-item"><div>📧 Email</div><span class="pill green">Configurado</span></div>
            <div class="mini-item"><div>💬 WhatsApp</div><span class="pill amber">Pendiente API</span></div>
            <div class="mini-item"><div>📱 SMS</div><span class="pill amber">Pendiente gateway</span></div>
            <div class="mini-item"><div>🔔 In-app</div><span class="pill green">Activo</span></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- MODAL: Crear/Editar Usuario -->
<div class="modal-overlay" id="userModal" style="display:none;">
  <div class="modal-box">
    <div class="modal-head">
      <h3 id="userModalTitle">Nuevo usuario</h3>
      <button class="btn-icon" id="closeUserModal">✕</button>
    </div>
    <form method="post" id="userForm">
      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
      <input type="hidden" name="id" id="userId" value="">
      <div class="form-group">
        <label>Nombre completo *</label>
        <input type="text" name="nombre" id="userNombre" required placeholder="Ej: Juan Pérez">
      </div>
      <div class="form-group">
        <label>Correo electrónico *</label>
        <input type="email" name="correo" id="userCorreo" required placeholder="usuario@empresa.com">
      </div>
      <div class="form-group">
        <label>Teléfono</label>
        <input type="tel" name="telefono" id="userTelefono" placeholder="(+57) 300 000 0000">
      </div>
      <div class="form-group">
        <label>Rol *</label>
        <select name="rol_id" id="userRol" required>
          <option value="">Seleccionar rol...</option>
          <?php foreach ($roles as $r): ?>
            <option value="<?= $r['id'] ?>"><?= e($r['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group" id="passwordGroup">
        <label>Contraseña * <small>(mínimo 8 caracteres)</small></label>
        <input type="password" name="password" id="userPassword" minlength="8" placeholder="••••••••">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn ghost" id="cancelUserModal">Cancelar</button>
        <button type="submit" class="btn primary" id="submitUserBtn">Crear usuario</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Crear/Editar Rol -->
<div class="modal-overlay" id="roleModal" style="display:none;">
  <div class="modal-box">
    <div class="modal-head">
      <h3 id="roleModalTitle">Nuevo rol</h3>
      <button class="btn-icon" id="closeRoleModal">✕</button>
    </div>
    <form method="post" id="roleForm">
      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
      <input type="hidden" name="id" id="roleId" value="">
      <div class="form-group">
        <label>Nombre del rol *</label>
        <input type="text" name="nombre" id="roleNombre" required placeholder="Ej: coordinador_ventas">
      </div>
      <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" id="roleDescripcion" placeholder="Descripción breve del rol">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn ghost" id="cancelRoleModal">Cancelar</button>
        <button type="submit" class="btn primary" id="submitRoleBtn">Crear rol</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Crear/Editar Política -->
<div class="modal-overlay" id="politicaModal" style="display:none;">
  <div class="modal-box">
    <div class="modal-head">
      <h3 id="politicaModalTitle">Nueva política</h3>
      <button class="btn-icon" id="closePoliticaModal">✕</button>
    </div>
    <form method="post" id="politicaForm">
      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
      <input type="hidden" name="id" id="polId" value="">
      <div class="form-group"><label>Nombre *</label><input type="text" name="nombre" id="polNombre" required></div>
      <div class="grid cols-2">
        <div class="form-group"><label>Nivel de riesgo *</label>
          <select name="nivel_riesgo" id="polNivel" required>
            <option value="preventivo">Preventivo</option>
            <option value="S1">S1 — Bajo</option>
            <option value="S2">S2 — Medio</option>
            <option value="S3">S3 — Alto</option>
            <option value="juridico">Jurídico</option>
          </select>
        </div>
        <div class="form-group"><label>Frecuencia máxima</label><input type="text" name="frecuencia_maxima" id="polFreq" placeholder="Ej: 1 contacto / semana"></div>
      </div>
      <div class="grid cols-2">
        <div class="form-group"><label>Días mora desde</label><input type="number" name="dias_mora_desde" id="polDesde" min="0" value="0"></div>
        <div class="form-group"><label>Días mora hasta</label><input type="number" name="dias_mora_hasta" id="polHasta" min="0"></div>
      </div>
      <div class="grid cols-2">
        <div class="form-group"><label>Horario inicio</label><input type="time" name="horario_inicio" id="polHI" value="08:00"></div>
        <div class="form-group"><label>Horario fin</label><input type="time" name="horario_fin" id="polHF" value="18:00"></div>
      </div>
      <div class="form-group"><label>Canales permitidos</label>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
          <label style="display:flex;align-items:center;gap:4px;font-size:13px;"><input type="checkbox" name="canales[]" value="SMS"> SMS</label>
          <label style="display:flex;align-items:center;gap:4px;font-size:13px;"><input type="checkbox" name="canales[]" value="Correo"> Correo</label>
          <label style="display:flex;align-items:center;gap:4px;font-size:13px;"><input type="checkbox" name="canales[]" value="WhatsApp"> WhatsApp</label>
          <label style="display:flex;align-items:center;gap:4px;font-size:13px;"><input type="checkbox" name="canales[]" value="Llamada"> Llamada</label>
          <label style="display:flex;align-items:center;gap:4px;font-size:13px;"><input type="checkbox" name="canales[]" value="Visita"> Visita</label>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn ghost" id="cancelPoliticaModal">Cancelar</button>
        <button type="submit" class="btn primary" id="submitPolBtn">Crear política</button>
      </div>
    </form>
  </div>
</div>

<!-- Tab switching + Modal JS -->
<script nonce="<?= CSP_NONCE ?>">
(function() {
  // Tab switching
  var nav = document.getElementById('settingsNav');
  if (!nav) return;
  var buttons = nav.querySelectorAll('button');
  var panels = document.querySelectorAll('.tab-panel');
  buttons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      var tab = this.getAttribute('data-tab');
      buttons.forEach(function(b) { b.classList.remove('active'); });
      panels.forEach(function(p) { p.classList.remove('active'); });
      this.classList.add('active');
      var target = document.querySelector('.tab-panel[data-tab="' + tab + '"]');
      if (target) target.classList.add('active');
    });
  });

  // ── User Modal ──
  var modal = document.getElementById('userModal');
  var form = document.getElementById('userForm');
  var storeUrl = '<?= route_url("/configuracion/usuarios/store", "desktop") ?>';
  var updateUrl = '<?= route_url("/configuracion/usuarios/update", "desktop") ?>';

  function openModal(isEdit, data) {
    document.getElementById('userModalTitle').textContent = isEdit ? 'Editar usuario' : 'Nuevo usuario';
    document.getElementById('submitUserBtn').textContent = isEdit ? 'Guardar cambios' : 'Crear usuario';
    form.action = isEdit ? updateUrl : storeUrl;
    document.getElementById('userId').value = data.id || '';
    document.getElementById('userNombre').value = data.nombre || '';
    document.getElementById('userCorreo').value = data.correo || '';
    document.getElementById('userTelefono').value = data.telefono || '';
    document.getElementById('userRol').value = data.rol_id || '';
    var pwGroup = document.getElementById('passwordGroup');
    var pwInput = document.getElementById('userPassword');
    if (isEdit) { pwGroup.style.display = 'none'; pwInput.removeAttribute('required'); }
    else { pwGroup.style.display = ''; pwInput.setAttribute('required', ''); }
    modal.style.display = 'flex';
  }

  function closeModal() { modal.style.display = 'none'; }

  document.getElementById('btnNewUser').addEventListener('click', function() { openModal(false, {}); });
  document.getElementById('closeUserModal').addEventListener('click', closeModal);
  document.getElementById('cancelUserModal').addEventListener('click', closeModal);
  modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });

  document.querySelectorAll('[data-edit-user]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      openModal(true, JSON.parse(this.getAttribute('data-edit-user')));
    });
  });

  // ── Role Modal ──
  var roleModal = document.getElementById('roleModal');
  var roleForm = document.getElementById('roleForm');
  var roleStoreUrl = '<?= route_url("/configuracion/roles/store", "desktop") ?>';
  var roleUpdateUrl = '<?= route_url("/configuracion/roles/update", "desktop") ?>';

  function openRoleModal(isEdit, data) {
    document.getElementById('roleModalTitle').textContent = isEdit ? 'Editar rol' : 'Nuevo rol';
    document.getElementById('submitRoleBtn').textContent = isEdit ? 'Guardar cambios' : 'Crear rol';
    roleForm.action = isEdit ? roleUpdateUrl : roleStoreUrl;
    document.getElementById('roleId').value = data.id || '';
    document.getElementById('roleNombre').value = data.nombre || '';
    document.getElementById('roleDescripcion').value = data.descripcion || '';
    roleModal.style.display = 'flex';
  }

  function closeRoleModal() { roleModal.style.display = 'none'; }

  document.getElementById('btnNewRole').addEventListener('click', function() { openRoleModal(false, {}); });
  document.getElementById('closeRoleModal').addEventListener('click', closeRoleModal);
  document.getElementById('cancelRoleModal').addEventListener('click', closeRoleModal);
  roleModal.addEventListener('click', function(e) { if (e.target === roleModal) closeRoleModal(); });

  document.querySelectorAll('[data-edit-role]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      openRoleModal(true, JSON.parse(this.getAttribute('data-edit-role')));
    });
  });

  // ── Política Modal ──
  var polModal = document.getElementById('politicaModal');
  var polForm = document.getElementById('politicaForm');
  var polStoreUrl = '<?= route_url("/configuracion/politicas/store", "desktop") ?>';
  var polUpdateUrl = '<?= route_url("/configuracion/politicas/update", "desktop") ?>';

  function openPolModal(isEdit, data) {
    document.getElementById('politicaModalTitle').textContent = isEdit ? 'Editar política' : 'Nueva política';
    document.getElementById('submitPolBtn').textContent = isEdit ? 'Guardar cambios' : 'Crear política';
    polForm.action = isEdit ? polUpdateUrl : polStoreUrl;
    document.getElementById('polId').value = data.id || '';
    document.getElementById('polNombre').value = data.nombre || '';
    document.getElementById('polNivel').value = data.nivel_riesgo || 'S1';
    document.getElementById('polFreq').value = data.frecuencia_maxima || '';
    document.getElementById('polDesde').value = data.dias_mora_desde || 0;
    document.getElementById('polHasta').value = data.dias_mora_hasta || '';
    document.getElementById('polHI').value = (data.horario_inicio || '08:00:00').substring(0,5);
    document.getElementById('polHF').value = (data.horario_fin || '18:00:00').substring(0,5);
    // Set canales checkboxes
    var canales = [];
    try { canales = JSON.parse(data.canales_permitidos || '[]'); } catch(e) {}
    polForm.querySelectorAll('input[name="canales[]"]').forEach(function(cb) {
      cb.checked = canales.indexOf(cb.value) !== -1;
    });
    polModal.style.display = 'flex';
  }

  function closePolModal() { polModal.style.display = 'none'; }

  document.getElementById('btnNewPolitica').addEventListener('click', function() { openPolModal(false, {}); });
  document.getElementById('closePoliticaModal').addEventListener('click', closePolModal);
  document.getElementById('cancelPoliticaModal').addEventListener('click', closePolModal);
  polModal.addEventListener('click', function(e) { if (e.target === polModal) closePolModal(); });

  document.querySelectorAll('[data-edit-politica]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      openPolModal(true, JSON.parse(this.getAttribute('data-edit-politica')));
    });
  });

  // ── Flash auto-hide ──
  var flashToast = document.getElementById('flashToast');
  if (flashToast) { setTimeout(function() { flashToast.style.opacity = '0'; setTimeout(function() { flashToast.remove(); }, 500); }, 6000); }
})();
</script>

