CREATE DATABASE IF NOT EXISTS arpynet_renovaciones CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE arpynet_renovaciones;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  rol_id INT NOT NULL,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL,
  CONSTRAINT fk_usuarios_rol FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo_cliente ENUM('Persona Natural','Persona Jurídica') NOT NULL,
  ruc VARCHAR(20) NOT NULL UNIQUE,
  razon_social VARCHAR(180) NOT NULL,
  direccion VARCHAR(200) NULL,
  telefono VARCHAR(30) NULL,
  correo VARCHAR(120) NULL,
  contacto VARCHAR(120) NULL,
  estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
  fecha_registro DATE NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL,
  INDEX idx_clientes_ruc (ruc),
  INDEX idx_clientes_estado (estado)
);

CREATE TABLE tipos_servicio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE proveedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(120) NULL,
  telefono VARCHAR(30) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE servicios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  tipo_servicio_id INT NOT NULL,
  codigo_servicio VARCHAR(40) NOT NULL UNIQUE,
  orden_servicio INT NOT NULL,
  nombre_servicio VARCHAR(180) NOT NULL,
  proveedor VARCHAR(120) NULL,
  fecha_inicio DATE NOT NULL,
  fecha_vencimiento DATE NOT NULL,
  periodo ENUM('Mensual','Trimestral','Semestral','Anual','Personalizado') NOT NULL,
  monto DECIMAL(12,2) NOT NULL,
  moneda VARCHAR(10) NOT NULL DEFAULT 'USD',
  estado ENUM('Activo','Próximo a vencer','Vencido','Suspendido','Cancelado') NOT NULL DEFAULT 'Activo',
  responsable VARCHAR(120) NULL,
  notas TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL,
  CONSTRAINT fk_servicios_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_servicios_tipo FOREIGN KEY (tipo_servicio_id) REFERENCES tipos_servicio(id),
  INDEX idx_servicios_vencimiento (fecha_vencimiento),
  INDEX idx_servicios_estado (estado),
  INDEX idx_servicios_codigo (codigo_servicio),
  INDEX idx_servicios_orden (orden_servicio)
);

CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servicio_id INT NOT NULL,
  estado_pago ENUM('Pendiente','Pagado','Parcial','Vencido') NOT NULL,
  fecha_pago DATE NULL,
  monto_facturado DECIMAL(12,2) NOT NULL,
  monto_pagado DECIMAL(12,2) NOT NULL DEFAULT 0,
  numero_factura VARCHAR(60) NULL,
  link_factura VARCHAR(255) NULL,
  archivo_factura VARCHAR(255) NULL,
  observaciones TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL,
  CONSTRAINT fk_pagos_servicio FOREIGN KEY (servicio_id) REFERENCES servicios(id),
  INDEX idx_pagos_estado (estado_pago)
);

CREATE TABLE facturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pago_id INT NOT NULL,
  numero VARCHAR(60) NOT NULL,
  ruta_archivo VARCHAR(255) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_facturas_pago FOREIGN KEY (pago_id) REFERENCES pagos(id)
);

CREATE TABLE renovaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servicio_id INT NOT NULL,
  fecha_anterior DATE NOT NULL,
  fecha_nueva DATE NOT NULL,
  pago_id INT NULL,
  notas TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_renovaciones_servicio FOREIGN KEY (servicio_id) REFERENCES servicios(id),
  CONSTRAINT fk_renovaciones_pago FOREIGN KEY (pago_id) REFERENCES pagos(id)
);

CREATE TABLE alertas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servicio_id INT NOT NULL,
  dias_antes INT NOT NULL,
  medio ENUM('dashboard','email') NOT NULL,
  enviado_en DATETIME NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_alertas_servicio FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);

CREATE TABLE documentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NULL,
  servicio_id INT NULL,
  nombre VARCHAR(180) NOT NULL,
  ruta VARCHAR(255) NOT NULL,
  tipo VARCHAR(60) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_documentos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_documentos_servicio FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);

CREATE TABLE logs_actividad (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NULL,
  accion VARCHAR(150) NOT NULL,
  entidad VARCHAR(80) NOT NULL,
  entidad_id INT NULL,
  metadata JSON NULL,
  created_at TIMESTAMP NULL,
  INDEX idx_logs_entidad (entidad, entidad_id),
  CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);


CREATE TABLE dominios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dominio VARCHAR(180) NOT NULL UNIQUE,
  cliente_id INT NOT NULL,
  servicio_id INT NULL,
  proveedor VARCHAR(120) NULL,
  fecha_inicio DATE NOT NULL,
  fecha_vencimiento DATE NOT NULL,
  monto DECIMAL(12,2) NULL,
  moneda VARCHAR(10) NOT NULL DEFAULT 'USD',
  estado ENUM('Activo','Próximo a vencer','Vencido') NOT NULL DEFAULT 'Activo',
  notas TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL,
  CONSTRAINT fk_dominios_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_dominios_servicio FOREIGN KEY (servicio_id) REFERENCES servicios(id),
  INDEX idx_dominios_venc (fecha_vencimiento)
);
