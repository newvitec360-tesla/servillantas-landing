
CREATE TABLE roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL UNIQUE,
  descripcion TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE permisos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(120) NOT NULL UNIQUE,
  nombre VARCHAR(160) NOT NULL,
  descripcion TEXT NULL
);

CREATE TABLE roles_permisos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  rol_id BIGINT UNSIGNED NOT NULL,
  permiso_id BIGINT UNSIGNED NOT NULL,
  UNIQUE KEY uq_rol_permiso (rol_id, permiso_id),
  CONSTRAINT fk_roles_permisos_rol FOREIGN KEY (rol_id) REFERENCES roles(id),
  CONSTRAINT fk_roles_permisos_permiso FOREIGN KEY (permiso_id) REFERENCES permisos(id)
);

CREATE TABLE usuarios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(180) NOT NULL,
  correo VARCHAR(180) NOT NULL UNIQUE,
  telefono VARCHAR(40) NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol_id BIGINT UNSIGNED NOT NULL,
  estado ENUM('activo','inactivo','bloqueado') DEFAULT 'activo',
  ultimo_login DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuarios_roles FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE clientes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tipo_documento VARCHAR(30) NULL,
  numero_documento VARCHAR(60) NOT NULL,
  nit VARCHAR(60) NULL,
  nombre_completo VARCHAR(220) NOT NULL,
  razon_social_referencia VARCHAR(220) NULL,
  placa_principal VARCHAR(40) NULL,
  referido_por VARCHAR(220) NULL,
  estado_localizacion ENUM('contactable','contacto_incompleto','inalcanzable','visita_requerida') DEFAULT 'contactable',
  observaciones TEXT NULL,
  fallecido_flag BOOLEAN DEFAULT FALSE,
  habeas_data_flag BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_cliente_documento (tipo_documento, numero_documento),
  INDEX idx_clientes_busqueda (numero_documento, nit, placa_principal, nombre_completo)
);

CREATE TABLE clientes_telefonos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  tipo VARCHAR(50) DEFAULT 'movil',
  numero VARCHAR(40) NOT NULL,
  es_principal BOOLEAN DEFAULT FALSE,
  observacion TEXT NULL,
  estado ENUM('valido','errado','sin_respuesta','desactualizado') DEFAULT 'valido',
  CONSTRAINT fk_clientes_telefonos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE clientes_correos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  correo VARCHAR(180) NOT NULL,
  es_principal BOOLEAN DEFAULT FALSE,
  estado ENUM('valido','rebotado','desactualizado') DEFAULT 'valido',
  CONSTRAINT fk_clientes_correos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE obligaciones (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  codigo_interno VARCHAR(80) NOT NULL,
  tipo_obligacion VARCHAR(80) NULL,
  concepto VARCHAR(120) NULL,
  origen_talonario VARCHAR(80) NULL,
  fecha_generacion DATE NULL,
  fecha_vencimiento DATE NULL,
  valor_inicial DECIMAL(15,2) NOT NULL DEFAULT 0,
  saldo_actual DECIMAL(15,2) NOT NULL DEFAULT 0,
  estado_obligacion ENUM('vigente','vencida','critica','en_gestion','en_acuerdo','pagada','parcialmente_pagada','castigada','fallecido','juridico') DEFAULT 'vigente',
  nivel_riesgo ENUM('S1','S2','S3') DEFAULT 'S1',
  fecha_ultimo_abono DATE NULL,
  valor_ultimo_abono DECIMAL(15,2) NULL,
  antiguedad_dias INT UNSIGNED DEFAULT 0,
  observaciones TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_obligaciones_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  INDEX idx_obligaciones_riesgo_mora (nivel_riesgo, antiguedad_dias),
  INDEX idx_obligaciones_estado (estado_obligacion)
);

CREATE TABLE pagos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  obligacion_id BIGINT UNSIGNED NULL,
  fecha_pago DATETIME NOT NULL,
  valor DECIMAL(15,2) NOT NULL,
  medio_pago VARCHAR(80) NOT NULL,
  referencia_transaccion VARCHAR(160) NULL,
  comprobante_url VARCHAR(500) NULL,
  estado_validacion ENUM('pendiente','validado','rechazado') DEFAULT 'pendiente',
  registrado_por BIGINT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pagos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_pagos_obligacion FOREIGN KEY (obligacion_id) REFERENCES obligaciones(id),
  CONSTRAINT fk_pagos_usuario FOREIGN KEY (registrado_por) REFERENCES usuarios(id),
  INDEX idx_pagos_fecha (fecha_pago),
  INDEX idx_pagos_estado (estado_validacion)
);

CREATE TABLE soportes_documentales (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  obligacion_id BIGINT UNSIGNED NULL,
  tipo_soporte VARCHAR(120) NOT NULL,
  existe_flag BOOLEAN DEFAULT FALSE,
  vigente_flag BOOLEAN DEFAULT FALSE,
  archivo_url VARCHAR(500) NULL,
  observacion TEXT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_soportes_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_soportes_obligacion FOREIGN KEY (obligacion_id) REFERENCES obligaciones(id),
  INDEX idx_soportes_tipo (tipo_soporte, existe_flag, vigente_flag)
);

CREATE TABLE ubicaciones_fisicas_expediente (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  descripcion_ubicacion VARCHAR(255) NOT NULL,
  responsable VARCHAR(180) NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ubicaciones_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE gestiones_cobranza (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  obligacion_id BIGINT UNSIGNED NULL,
  usuario_id BIGINT UNSIGNED NOT NULL,
  fecha_gestion DATETIME NOT NULL,
  canal VARCHAR(80) NOT NULL,
  resultado VARCHAR(120) NOT NULL,
  observacion TEXT NULL,
  compromiso_pago_fecha DATE NULL,
  compromiso_pago_valor DECIMAL(15,2) NULL,
  proxima_gestion_fecha DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_gestiones_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_gestiones_obligacion FOREIGN KEY (obligacion_id) REFERENCES obligaciones(id),
  CONSTRAINT fk_gestiones_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  INDEX idx_gestiones_fecha (fecha_gestion),
  INDEX idx_gestiones_resultado (resultado)
);

CREATE TABLE plantillas_mensajes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(180) NOT NULL,
  canal VARCHAR(60) NOT NULL,
  asunto VARCHAR(180) NULL,
  contenido TEXT NOT NULL,
  nivel_riesgo_aplicable ENUM('S1','S2','S3') NULL,
  estado ENUM('activa','inactiva') DEFAULT 'activa'
);

CREATE TABLE campanas (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(180) NOT NULL,
  canal VARCHAR(60) NOT NULL,
  segmento_definicion JSON NULL,
  plantilla_id BIGINT UNSIGNED NULL,
  fecha_envio DATETIME NULL,
  enviado_por BIGINT UNSIGNED NULL,
  estado ENUM('borrador','programada','enviada','cancelada') DEFAULT 'borrador',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_campanas_plantilla FOREIGN KEY (plantilla_id) REFERENCES plantillas_mensajes(id),
  CONSTRAINT fk_campanas_usuario FOREIGN KEY (enviado_por) REFERENCES usuarios(id)
);

CREATE TABLE mensajes_enviados (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  campana_id BIGINT UNSIGNED NOT NULL,
  cliente_id BIGINT UNSIGNED NOT NULL,
  canal VARCHAR(60) NOT NULL,
  destinatario VARCHAR(180) NOT NULL,
  mensaje_generado TEXT NOT NULL,
  url_unica VARCHAR(500) NULL,
  estado_envio ENUM('pendiente','enviado','entregado','abierto','clic','rebote','spam','fallido') DEFAULT 'pendiente',
  fecha_envio DATETIME NULL,
  fecha_apertura DATETIME NULL,
  fecha_clic DATETIME NULL,
  metadatos JSON NULL,
  CONSTRAINT fk_mensajes_campana FOREIGN KEY (campana_id) REFERENCES campanas(id),
  CONSTRAINT fk_mensajes_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  INDEX idx_mensajes_estado (estado_envio)
);

CREATE TABLE eventos_analiticos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NULL,
  tipo_evento VARCHAR(120) NOT NULL,
  canal_origen VARCHAR(80) NULL,
  ip VARCHAR(80) NULL,
  dispositivo VARCHAR(120) NULL,
  user_agent TEXT NULL,
  url VARCHAR(500) NULL,
  fecha_evento DATETIME DEFAULT CURRENT_TIMESTAMP,
  metadatos JSON NULL,
  CONSTRAINT fk_eventos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  INDEX idx_eventos_tipo_fecha (tipo_evento, fecha_evento)
);

CREATE TABLE estados_parametricos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria VARCHAR(100) NOT NULL,
  codigo VARCHAR(100) NOT NULL,
  nombre VARCHAR(160) NOT NULL,
  color VARCHAR(40) NULL,
  orden INT DEFAULT 0,
  UNIQUE KEY uq_estado_categoria_codigo (categoria, codigo)
);

CREATE TABLE auditoria (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id BIGINT UNSIGNED NULL,
  entidad VARCHAR(120) NOT NULL,
  entidad_id VARCHAR(80) NOT NULL,
  accion VARCHAR(120) NOT NULL,
  valor_anterior JSON NULL,
  valor_nuevo JSON NULL,
  ip VARCHAR(80) NULL,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_auditoria_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  INDEX idx_auditoria_entidad (entidad, entidad_id),
  INDEX idx_auditoria_fecha (fecha)
);

CREATE TABLE landing_pages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) NOT NULL UNIQUE,
  content_json JSON NULL,
  draft_json JSON NULL,
  status ENUM('draft','published','archived') DEFAULT 'draft',
  published_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE media_assets (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  section VARCHAR(60) NULL,
  variant VARCHAR(60) NULL,
  original_name VARCHAR(180) NOT NULL,
  path VARCHAR(255) NOT NULL,
  url VARCHAR(500) NOT NULL,
  mime VARCHAR(60) NOT NULL,
  width INT UNSIGNED NULL,
  height INT UNSIGNED NULL,
  size_kb INT UNSIGNED NOT NULL,
  alt VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE landing_revisions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  landing_page_id BIGINT UNSIGNED NOT NULL,
  content_json JSON NOT NULL,
  created_by BIGINT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_landing_revisions_page FOREIGN KEY (landing_page_id) REFERENCES landing_pages(id) ON DELETE CASCADE,
  CONSTRAINT fk_landing_revisions_user FOREIGN KEY (created_by) REFERENCES usuarios(id) ON DELETE SET NULL
);
