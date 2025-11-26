-- ============================================
-- Script para poblar productos en la base de datos
-- Ejecuta este archivo después de crear las tablas
-- ============================================

USE anita_integrales;

-- Asegurar que las categorías existan
INSERT INTO categories (code, name) VALUES
('panaderia', 'Panadería'),
('amasijos', 'Amasijos'),
('galleteria', 'Galletería'),
('granola', 'Granola'),
('frutos-secos', 'Frutos Secos y Semillas'),
('envasados', 'Envasados')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insertar productos de ejemplo
-- Puedes agregar más productos siguiendo este formato

INSERT INTO products (code, name, category_id, price, description, ingredients, benefits, image) VALUES
('ACH001', 'Achiras Grandes', (SELECT id FROM categories WHERE code = 'panaderia'), 15000.00, 'Deliciosas achiras artesanales de tamaño grande', 'Harina de achira, agua, sal', 'Fuente de carbohidratos naturales', '/assets/images/Catálogo/AchirasGrandes.jpg'),
('ACH002', 'Achiras Pequeñas', (SELECT id FROM categories WHERE code = 'panaderia'), 12000.00, 'Achiras artesanales tamaño pequeño', 'Harina de achira, agua, sal', 'Snack saludable y natural', '/assets/images/Catálogo/AchirasPeque.jpg'),
('ALM001', 'Almendras 125g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 25000.00, 'Almendras naturales en presentación de 125 gramos', 'Almendras 100% naturales', 'Fuente de proteína y grasas saludables', '/assets/images/Catálogo/Almendra125.jpg'),
('ALM002', 'Almendras 250g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 45000.00, 'Almendras naturales en presentación de 250 gramos', 'Almendras 100% naturales', 'Fuente de proteína y grasas saludables', '/assets/images/Catálogo/Almendra250.jpg'),
('MIEL001', 'Miel de Abejas', (SELECT id FROM categories WHERE code = 'envasados'), 18000.00, 'Miel pura de abejas artesanal', 'Miel 100% pura', 'Endulzante natural con propiedades antibacterianas', '/assets/images/Catálogo/Miel.jpg'),
('QUIN001', 'Quinua', (SELECT id FROM categories WHERE code = 'frutos-secos'), 22000.00, 'Quinua orgánica lista para consumir', 'Quinua orgánica', 'Super alimento rico en proteínas completas', '/assets/images/Catálogo/Quinua.jpg')
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    price = VALUES(price),
    description = VALUES(description);

-- Nota: Puedes agregar más productos ejecutando más INSERT statements
-- O puedes cargar productos desde la aplicación una vez que esté funcionando

