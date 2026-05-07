USE arpynet_renovaciones;

INSERT INTO roles (nombre, created_at, updated_at)
VALUES
('Administrador', NOW(), NOW()),
('Operador', NOW(), NOW()),
('Solo lectura', NOW(), NOW());

INSERT INTO usuarios (rol_id, nombre, email, password_hash, estado, created_at, updated_at)
VALUES (1, 'Administrador Inicial', 'admin@arpynet.com', '$2y$12$/B1kuJsiYBIj7fzv7hmFQOmzHG/N01mGIy9A246D9Yf.Il/PzMIgK', 'activo', NOW(), NOW());

INSERT INTO tipos_servicio (nombre, created_at, updated_at)
VALUES
('Dominio', NOW(), NOW()),
('Hosting', NOW(), NOW()),
('Adobe', NOW(), NOW()),
('Kaspersky', NOW(), NOW()),
('Sophos', NOW(), NOW()),
('Google Workspace', NOW(), NOW()),
('Microsoft 365', NOW(), NOW()),
('Otro', NOW(), NOW());

INSERT INTO clientes (tipo_cliente, ruc, razon_social, direccion, telefono, correo, contacto, estado, fecha_registro, created_at, updated_at)
VALUES ('Persona Jurídica', '20123456789', 'Cliente Demo SAC', 'Lima, Perú', '999999999', 'contacto@demo.pe', 'Ana Torres', 'activo', CURDATE(), NOW(), NOW());

INSERT INTO servicios (cliente_id, tipo_servicio_id, codigo_servicio, orden_servicio, nombre_servicio, proveedor, fecha_inicio, fecha_vencimiento, periodo, monto, moneda, estado, responsable, notas, created_at, updated_at)
VALUES (1, 1, 'SRV-DEMO-0001', 1, 'demo.com', 'Namecheap', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 20 DAY), 'Anual', 18.00, 'USD', 'Activo', 'Equipo TI', 'Dominio principal', NOW(), NOW());

INSERT INTO dominios (dominio, cliente_id, servicio_id, proveedor, fecha_inicio, fecha_vencimiento, monto, moneda, estado, notas, created_at, updated_at)
VALUES ('demo.com', 1, 1, 'Namecheap', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 18.00, 'USD', 'Activo', 'Dominio anual demo', NOW(), NOW());
