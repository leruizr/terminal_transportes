-- database/schema.sql
-- Esquema para Terminal de Transportes

CREATE DATABASE IF NOT EXISTS terminal_transportes
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE terminal_transportes;

-- Empresas de transporte
CREATE TABLE IF NOT EXISTS empresas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  vehiculos_texto VARCHAR(255) NOT NULL,
  capacidad VARCHAR(50) NOT NULL
);

-- Ciudades / destinos
CREATE TABLE IF NOT EXISTS ciudades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE
);

-- Tipos de vehículo
CREATE TABLE IF NOT EXISTS tipos_vehiculo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  capacidad VARCHAR(50) NOT NULL,
  comodidades TEXT NOT NULL,
  ideal_para VARCHAR(255) NOT NULL
);

-- Rutas (empresa + destino + horario + precio + vehiculo + duracion)
CREATE TABLE IF NOT EXISTS rutas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ciudad_destino_id INT NOT NULL,
  empresa_id INT NOT NULL,
  horario VARCHAR(20) NOT NULL,
  precio INT NOT NULL,
  tipo_vehiculo_id INT NOT NULL,
  duracion VARCHAR(20) NOT NULL,
  FOREIGN KEY (ciudad_destino_id) REFERENCES ciudades(id) ON DELETE CASCADE,
  FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
  FOREIGN KEY (tipo_vehiculo_id) REFERENCES tipos_vehiculo(id) ON DELETE CASCADE
);

-- Tiquetes (compras de pasajeros)
CREATE TABLE IF NOT EXISTS tiquetes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_pasajero VARCHAR(100) NOT NULL,
  email_pasajero VARCHAR(150) NOT NULL,
  ruta_id INT NOT NULL,
  fecha_viaje DATE NOT NULL,
  personas INT NOT NULL DEFAULT 1,
  total INT NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ruta_id) REFERENCES rutas(id) ON DELETE CASCADE
);

-- =============================================
-- DATOS INICIALES
-- =============================================

-- Empresas
INSERT INTO empresas (nombre, vehiculos_texto, capacidad) VALUES
('Flota Occidental', 'Buses de Lujo, Buses Premium y Aerovan', '40 - 45 pasajeros'),
('Empresa Arauca', 'Buses de Lujo, Buses Premium y Busetas', '35 pasajeros'),
('Expreso Brasilia', 'Buses Premium y Gacelas', '40 pasajeros'),
('Copetran', 'Buses de Lujo', '20 - 40 pasajeros'),
('Expreso Bolivariano', 'Buses de Lujo, Buses Premium y Gacelas', '35 - 45 pasajeros'),
('Flota Magdalena', 'Buses de Lujo y Buses Premium', '30 - 40 pasajeros'),
('Rápido Ochoa', 'Buses Premium y Aerovan', '40 pasajeros');

-- Ciudades
INSERT INTO ciudades (nombre, slug) VALUES
('Armenia', 'armenia'),
('Barranquilla', 'barranquilla'),
('Bogotá', 'bogota'),
('Bucaramanga', 'bucaramanga'),
('Cali', 'cali'),
('Cartagena', 'cartagena'),
('Cúcuta', 'cucuta'),
('Ibagué', 'ibague'),
('Manizales', 'manizales'),
('Medellín', 'medellin'),
('Pereira', 'pereira'),
('Popayán', 'popayan'),
('Quibdó', 'quibdo'),
('Salento', 'salento'),
('Santa Marta', 'santamarta');

-- Tipos de vehículo
INSERT INTO tipos_vehiculo (nombre, capacidad, comodidades, ideal_para) VALUES
('Bus de Lujo', '40 - 45 pasajeros', 'Aire acondicionado, Asientos reclinables, Baño a bordo, WiFi disponible, Pantallas individuales', 'Viajes largos interdepartamentales'),
('Bus Premium', '35 - 40 pasajeros', 'Aire acondicionado, Asientos semi-cama, Tomas de corriente, Servicio a bordo, Entretenimiento', 'Rutas principales nacionales'),
('Aerovan', '10 - 15 pasajeros', 'Aire acondicionado, Asientos cómodos, Mayor rapidez, Equipaje incluido', 'Grupos pequeños y viajes rápidos'),
('Buseta', '19 - 25 pasajeros', 'Aire acondicionado, Asientos cómodos, Rutas frecuentes, Paradas intermedias', 'Distancias cortas y municipios cercanos'),
('Gacela', '30 - 35 pasajeros', 'Aire acondicionado, Buena relación precio-servicio, Salidas frecuentes, Rutas directas', 'Rutas intermedias y viajes económicos'),
('Taxi Intermunicipal', '4 pasajeros', 'Servicio puerta a puerta, Flexibilidad horaria, Equipaje personal, Aire acondicionado', 'Viajes express y personalizados'),
('Camión de Carga', 'Variable según tonelaje', 'Transporte de mercancías, Encomiendas y paquetes, Carga pesada, Cobertura nacional', 'Envío de carga y encomiendas');

-- Rutas (datos del script.js)
-- Armenia
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='armenia'), (SELECT id FROM empresas WHERE nombre='Flota Occidental'), '6:00 AM', 60000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '6h 30m'),
((SELECT id FROM ciudades WHERE slug='armenia'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '2:30 PM', 62000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '6h');

-- Barranquilla
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='barranquilla'), (SELECT id FROM empresas WHERE nombre='Expreso Brasilia'), '5:00 AM', 130000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '14h'),
((SELECT id FROM ciudades WHERE slug='barranquilla'), (SELECT id FROM empresas WHERE nombre='Copetran'), '9:00 PM', 130000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '14h');

-- Bogotá
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='bogota'), (SELECT id FROM empresas WHERE nombre='Expreso Bolivariano'), '6:00 AM', 90000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '9h'),
((SELECT id FROM ciudades WHERE slug='bogota'), (SELECT id FROM empresas WHERE nombre='Copetran'), '10:00 AM', 90000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '9h 30m'),
((SELECT id FROM ciudades WHERE slug='bogota'), (SELECT id FROM empresas WHERE nombre='Flota Magdalena'), '4:00 PM', 90000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '8h 45m');

-- Bucaramanga
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='bucaramanga'), (SELECT id FROM empresas WHERE nombre='Copetran'), '7:00 AM', 95000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '10h');

-- Cali
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='cali'), (SELECT id FROM empresas WHERE nombre='Flota Occidental'), '7:00 AM', 80000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '8h 30m'),
((SELECT id FROM ciudades WHERE slug='cali'), (SELECT id FROM empresas WHERE nombre='Expreso Brasilia'), '2:00 PM', 80000, (SELECT id FROM tipos_vehiculo WHERE nombre='Gacela'), '8h 30m'),
((SELECT id FROM ciudades WHERE slug='cali'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '6:00 PM', 82000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '8h 15m');

-- Cartagena
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='cartagena'), (SELECT id FROM empresas WHERE nombre='Flota Magdalena'), '5:00 AM', 120000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '13h'),
((SELECT id FROM ciudades WHERE slug='cartagena'), (SELECT id FROM empresas WHERE nombre='Expreso Brasilia'), '8:00 PM', 120000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '13h'),
((SELECT id FROM ciudades WHERE slug='cartagena'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '10:00 PM', 125000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '13h');

-- Cúcuta
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='cucuta'), (SELECT id FROM empresas WHERE nombre='Copetran'), '6:00 AM', 110000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '15h'),
((SELECT id FROM ciudades WHERE slug='cucuta'), (SELECT id FROM empresas WHERE nombre='Expreso Bolivariano'), '5:00 PM', 110000, (SELECT id FROM tipos_vehiculo WHERE nombre='Gacela'), '14h 30m');

-- Ibagué
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='ibague'), (SELECT id FROM empresas WHERE nombre='Expreso Bolivariano'), '8:00 AM', 85000, (SELECT id FROM tipos_vehiculo WHERE nombre='Gacela'), '7h'),
((SELECT id FROM ciudades WHERE slug='ibague'), (SELECT id FROM empresas WHERE nombre='Expreso Bolivariano'), '4:00 PM', 85000, (SELECT id FROM tipos_vehiculo WHERE nombre='Gacela'), '7h');

-- Manizales
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='manizales'), (SELECT id FROM empresas WHERE nombre='Rápido Ochoa'), '6:30 AM', 70000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '5h'),
((SELECT id FROM ciudades WHERE slug='manizales'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '10:30 AM', 72000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '4h 45m'),
((SELECT id FROM ciudades WHERE slug='manizales'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '4:30 PM', 70000, (SELECT id FROM tipos_vehiculo WHERE nombre='Buseta'), '5h');

-- Medellín
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='medellin'), (SELECT id FROM empresas WHERE nombre='Flota Occidental'), '07:45 AM', 65000, (SELECT id FROM tipos_vehiculo WHERE nombre='Buseta'), '5h 45m'),
((SELECT id FROM ciudades WHERE slug='medellin'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '06:30 PM', 65000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '5h 30m');

-- Pereira
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='pereira'), (SELECT id FROM empresas WHERE nombre='Rápido Ochoa'), '6:30 AM', 65000, (SELECT id FROM tipos_vehiculo WHERE nombre='Aerovan'), '5h 30m'),
((SELECT id FROM ciudades WHERE slug='pereira'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '11:45 AM', 67000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '5h 45m'),
((SELECT id FROM ciudades WHERE slug='pereira'), (SELECT id FROM empresas WHERE nombre='Flota Occidental'), '3:30 PM', 65000, (SELECT id FROM tipos_vehiculo WHERE nombre='Buseta'), '6h');

-- Popayán
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='popayan'), (SELECT id FROM empresas WHERE nombre='Expreso Bolivariano'), '7:00 AM', 95000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '11h'),
((SELECT id FROM ciudades WHERE slug='popayan'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '8:00 PM', 98000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '10h 30m');

-- Quibdó
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='quibdo'), (SELECT id FROM empresas WHERE nombre='Flota Occidental'), '8:00 AM', 75000, (SELECT id FROM tipos_vehiculo WHERE nombre='Buseta'), '12h'),
((SELECT id FROM ciudades WHERE slug='quibdo'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '5:00 AM', 78000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '11h 30m');

-- Salento
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='salento'), (SELECT id FROM empresas WHERE nombre='Flota Occidental'), '9:00 AM', 60000, (SELECT id FROM tipos_vehiculo WHERE nombre='Aerovan'), '7h 30m'),
((SELECT id FROM ciudades WHERE slug='salento'), (SELECT id FROM empresas WHERE nombre='Empresa Arauca'), '1:00 PM', 65000, (SELECT id FROM tipos_vehiculo WHERE nombre='Buseta'), '7h');

-- Santa Marta
INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES
((SELECT id FROM ciudades WHERE slug='santamarta'), (SELECT id FROM empresas WHERE nombre='Flota Magdalena'), '8:00 AM', 125000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus de Lujo'), '15h'),
((SELECT id FROM ciudades WHERE slug='santamarta'), (SELECT id FROM empresas WHERE nombre='Expreso Brasilia'), '6:00 PM', 125000, (SELECT id FROM tipos_vehiculo WHERE nombre='Bus Premium'), '15h');
