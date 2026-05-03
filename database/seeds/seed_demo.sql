INSERT INTO roles (nombre, descripcion) VALUES
('administrador_general','Control total de la plataforma'),
('analista_cartera','Gestión operativa de clientes y cobranza'),
('coordinador_gerencia','Consulta ejecutiva y reportes'),
('juridico','Revisión documental y jurídica');

INSERT INTO clientes (tipo_documento, numero_documento, nit, nombre_completo, razon_social_referencia, placa_principal, estado_localizacion, habeas_data_flag) VALUES
('NIT','9001234567','900.123.456-7','Transportes del Valle S.A.S.','Transportes del Valle S.A.S.','ABC123','contactable', true),
('NIT','8304567891','830.456.789-1','Logística Andina Ltda.','Logística Andina Ltda.','XYZ987','contactable', true),
('NIT','9012345678','901.234.567-8','Constructora Horizonte','Constructora Horizonte','HOR456','contacto_incompleto', false);

INSERT INTO obligaciones (cliente_id, codigo_interno, tipo_obligacion, concepto, valor_inicial, saldo_actual, estado_obligacion, nivel_riesgo, fecha_ultimo_abono, valor_ultimo_abono, antiguedad_dias) VALUES
(1,'OBL-2024-00123','Pagaré','Compra de llantas',320450000,165760000,'critica','S3','2024-05-15',12450000,91),
(2,'OBL-2024-00124','Factura','Mantenimiento flota',265330000,132480000,'critica','S3','2024-05-02',8500000,61),
(3,'OBL-2024-00125','Letra de cambio','Servicio técnico',198730000,98340000,'vencida','S2','2024-04-18',5700000,46);
