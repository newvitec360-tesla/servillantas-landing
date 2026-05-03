-- Sprint 13: Configuración General + Políticas de Cobranza
-- Ejecutar en producción ANTES del deploy de código

CREATE TABLE IF NOT EXISTS configuracion_sistema (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(120) NOT NULL UNIQUE,
  valor TEXT NULL,
  tipo ENUM('text','number','boolean','json','color') DEFAULT 'text',
  grupo VARCHAR(80) NOT NULL DEFAULT 'general',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS politicas_cobranza (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(180) NOT NULL,
  nivel_riesgo ENUM('S1','S2','S3','juridico','preventivo') NOT NULL,
  dias_mora_desde INT DEFAULT 0,
  dias_mora_hasta INT NULL,
  canales_permitidos JSON NOT NULL,
  frecuencia_maxima VARCHAR(80) NOT NULL,
  horario_inicio TIME DEFAULT '08:00:00',
  horario_fin TIME DEFAULT '18:00:00',
  activa BOOLEAN DEFAULT TRUE,
  orden INT DEFAULT 0
);

-- Seed: Configuración General
INSERT INTO configuracion_sistema (clave, valor, tipo, grupo) VALUES
('razon_social', 'Servillantas El Puente S.A.S.', 'text', 'general'),
('nit', '900.123.456-7', 'text', 'general'),
('direccion', 'Calle 45 #23-67, Bogotá', 'text', 'general'),
('telefono', '(+57) 601 123 4567', 'text', 'general'),
('color_primario', '#E30613', 'color', 'marca'),
('color_secundario', '#1A1A1A', 'color', 'marca'),
('plataforma', 'Liquid Glass 2026', 'text', 'marca'),
('version', 'v1.0.0', 'text', 'marca')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);

-- Seed: Políticas de Cobranza
INSERT INTO politicas_cobranza (nombre, nivel_riesgo, dias_mora_desde, dias_mora_hasta, canales_permitidos, frecuencia_maxima, horario_inicio, horario_fin, activa, orden) VALUES
('Recordatorio preventivo', 'preventivo', 0, 0, '["SMS","Correo"]', '1 vez', '08:00:00', '18:00:00', 1, 1),
('Gestión S1 — Bajo riesgo', 'S1', 1, 30, '["SMS","Correo"]', '1 contacto / semana', '08:00:00', '18:00:00', 1, 2),
('Gestión S2 — Medio riesgo', 'S2', 31, 60, '["SMS","WhatsApp","Correo","Llamada"]', '3 contactos / semana', '08:00:00', '20:00:00', 1, 3),
('Gestión S3 — Alto riesgo', 'S3', 61, 90, '["SMS","WhatsApp","Correo","Llamada","Visita"]', 'Diario', '08:00:00', '20:00:00', 1, 4),
('Ruta jurídica', 'juridico', 91, NULL, '["Todos"]', 'Según caso', '08:00:00', '18:00:00', 1, 5);
