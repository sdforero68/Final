-- ============================================
-- Script SQL para insertar productos en la base de datos
-- Ejecuta este script en phpMyAdmin o MySQL Workbench
-- ============================================

USE anita_integrales;

-- Asegurar que las categorías existan primero
INSERT INTO categories (code, name) VALUES
('panaderia', 'Panadería'),
('amasijos', 'Amasijos'),
('galleteria', 'Galletería'),
('granola', 'Granola'),
('frutos-secos', 'Frutos Secos y Semillas'),
('envasados', 'Envasados')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insertar productos
-- El formato es: (code, name, category_id, price, description, ingredients, benefits, image)

INSERT INTO products (code, name, category_id, price, description, ingredients, benefits, image) VALUES

-- PANADERÍA
('ACH001', 'Achiras Grandes', (SELECT id FROM categories WHERE code = 'panaderia'), 15000.00, 'Deliciosas achiras artesanales de tamaño grande', 'Harina de achira, agua, sal', 'Fuente de carbohidratos naturales, sin conservantes', '/assets/images/Catálogo/AchirasGrandes.jpg'),
('ACH002', 'Achiras Pequeñas', (SELECT id FROM categories WHERE code = 'panaderia'), 12000.00, 'Achiras artesanales tamaño pequeño, perfectas para acompañar', 'Harina de achira, agua, sal', 'Snack saludable y natural', '/assets/images/Catálogo/AchirasPeque.jpg'),
('ANC001', 'Ancestral Grande', (SELECT id FROM categories WHERE code = 'panaderia'), 18000.00, 'Pan ancestral elaborado con técnicas tradicionales', 'Harina integral, levadura natural, agua', 'Alto contenido en fibra, digestión lenta', '/assets/images/Catálogo/AncestralGrande.jpg'),
('ANC002', 'Ancestral Pequeño', (SELECT id FROM categories WHERE code = 'panaderia'), 12000.00, 'Pan ancestral tamaño pequeño', 'Harina integral, levadura natural, agua', 'Alto contenido en fibra', '/assets/images/Catálogo/AncestralPeque.jpg'),
('ANC003', 'Ancestral Centeno', (SELECT id FROM categories WHERE code = 'panaderia'), 16000.00, 'Pan de centeno artesanal', 'Harina de centeno, agua, sal', 'Rico en fibra y minerales', '/assets/images/Catálogo/AncestralCenteno.jpg'),

-- AMASIJOS
('MOG001', 'Mogollas', (SELECT id FROM categories WHERE code = 'amasijos'), 8000.00, 'Mogollas tradicionales colombianas', 'Harina, manteca, azúcar', 'Delicioso acompañante para el desayuno', '/assets/images/Catálogo/Mogollas.jpg'),
('ROS001', 'Roscones', (SELECT id FROM categories WHERE code = 'amasijos'), 10000.00, 'Roscones artesanales rellenos', 'Harina, mantequilla, azúcar, relleno', 'Postre tradicional con sabor casero', '/assets/images/Catálogo/Roscones.jpg'),
('PAN001', 'Panetón Grande', (SELECT id FROM categories WHERE code = 'amasijos'), 35000.00, 'Panetón navideño tamaño grande', 'Harina, frutas confitadas, pasas, mantequilla', 'Tradicional postre navideño artesanal', '/assets/images/Catálogo/PanetónGrande.jpg'),
('PAN002', 'Panetón Mediano', (SELECT id FROM categories WHERE code = 'amasijos'), 25000.00, 'Panetón navideño tamaño mediano', 'Harina, frutas confitadas, pasas, mantequilla', 'Tradicional postre navideño', '/assets/images/Catálogo/PanetónMediano.jpg'),
('PAN003', 'Panetón Pequeño', (SELECT id FROM categories WHERE code = 'amasijos'), 15000.00, 'Panetón navideño tamaño pequeño', 'Harina, frutas confitadas, pasas', 'Porción individual de panetón', '/assets/images/Catálogo/PanetónPeque.jpg'),
('QUE001', 'Queso de Maíz Grande', (SELECT id FROM categories WHERE code = 'amasijos'), 14000.00, 'Queso de maíz artesanal tamaño grande', 'Maíz, queso, sal', 'Acompañante tradicional y delicioso', '/assets/images/Catálogo/QuesoMaizGrande.jpg'),
('QUE002', 'Queso de Maíz Pequeño', (SELECT id FROM categories WHERE code = 'amasijos'), 9000.00, 'Queso de maíz tamaño pequeño', 'Maíz, queso, sal', 'Snack tradicional', '/assets/images/Catálogo/QuesoMaizPeque.jpg'),
('CUA001', 'Cuajada de Queso Grande', (SELECT id FROM categories WHERE code = 'amasijos'), 16000.00, 'Cuajada de queso artesanal grande', 'Leche, cuajo, sal', 'Proteína de alta calidad', '/assets/images/Catálogo/CuajadaQuesoGrande.jpg'),
('CUA002', 'Cuajada de Queso Pequeña', (SELECT id FROM categories WHERE code = 'amasijos'), 10000.00, 'Cuajada de queso pequeña', 'Leche, cuajo, sal', 'Snack saludable con proteína', '/assets/images/Catálogo/CuajadaQuesoPeque.jpg'),

-- GALLETERÍA
('GAL001', 'Galletas de Cacao', (SELECT id FROM categories WHERE code = 'galleteria'), 12000.00, 'Galletas artesanales con cacao puro', 'Harina integral, cacao, mantequilla', 'Antioxidantes naturales del cacao', '/assets/images/Catálogo/GalletasCacao.jpg'),
('GAL002', 'Galletas de Café', (SELECT id FROM categories WHERE code = 'galleteria'), 12000.00, 'Galletas con sabor a café colombiano', 'Harina, café molido, mantequilla', 'Energía natural del café', '/assets/images/Catálogo/GalletasCafé.jpg'),
('GAL003', 'Galletas de Jengibre', (SELECT id FROM categories WHERE code = 'galleteria'), 12000.00, 'Galletas con jengibre y especias', 'Harina, jengibre, especias', 'Propiedades digestivas del jengibre', '/assets/images/Catálogo/GalletasJenjibre.jpg'),
('GAL004', 'Galletas Saladas', (SELECT id FROM categories WHERE code = 'galleteria'), 10000.00, 'Galletas saladas artesanales', 'Harina, sal, aceite', 'Snack bajo en azúcar', '/assets/images/Catálogo/GalletasSal.jpg'),
('TOS001', 'Tostadas', (SELECT id FROM categories WHERE code = 'galleteria'), 8000.00, 'Tostadas artesanales crujientes', 'Harina, agua, sal', 'Bajo en grasa, crujiente', '/assets/images/Catálogo/Tostadas.jpg'),

-- GRANOLA
('LIB001', 'Libra Arándanos', (SELECT id FROM categories WHERE code = 'granola'), 25000.00, 'Granola con arándanos 1 libra', 'Avena, arándanos secos, miel, frutos secos', 'Rica en fibra y antioxidantes', '/assets/images/Catálogo/LibraArándanos.jpg'),
('MED001', 'Media Arándanos', (SELECT id FROM categories WHERE code = 'granola'), 15000.00, 'Granola con arándanos 1/2 libra', 'Avena, arándanos secos, miel', 'Desayuno nutritivo y energético', '/assets/images/Catálogo/MediaArándanos.jpg'),
('LIB002', 'Libra Dátiles', (SELECT id FROM categories WHERE code = 'granola'), 25000.00, 'Granola con dátiles 1 libra', 'Avena, dátiles, miel, frutos secos', 'Dulce natural y nutritivo', '/assets/images/Catálogo/LibraDátiles.jpg'),
('MED002', 'Media Dátiles', (SELECT id FROM categories WHERE code = 'granola'), 15000.00, 'Granola con dátiles 1/2 libra', 'Avena, dátiles, miel', 'Fuente de energía natural', '/assets/images/Catálogo/MediaDátiles.jpg'),
('LIB003', 'Libra Diabéticos', (SELECT id FROM categories WHERE code = 'granola'), 25000.00, 'Granola especial sin azúcar 1 libra', 'Avena, stevia, frutos secos', 'Ideal para personas con diabetes', '/assets/images/Catálogo/LibraDiabéticos.jpg'),
('LIB004', 'Libra Uvas', (SELECT id FROM categories WHERE code = 'granola'), 25000.00, 'Granola con uvas pasas 1 libra', 'Avena, uvas pasas, miel, frutos secos', 'Rica en hierro y fibra', '/assets/images/Catálogo/LibraUvas.jpg'),
('MED003', 'Media Uvas', (SELECT id FROM categories WHERE code = 'granola'), 15000.00, 'Granola con uvas pasas 1/2 libra', 'Avena, uvas pasas, miel', 'Snack saludable y dulce', '/assets/images/Catálogo/MediaUvas.jpg'),
('GUE001', 'Granola 1 Libra', (SELECT id FROM categories WHERE code = 'granola'), 25000.00, 'Granola artesanal 1 libra', 'Avena, miel, frutos secos, semillas', 'Desayuno completo y nutritivo', '/assets/images/Catálogo/GueeLibra.jpg'),
('GUE002', 'Granola 1/2 Libra', (SELECT id FROM categories WHERE code = 'granola'), 15000.00, 'Granola artesanal 1/2 libra', 'Avena, miel, frutos secos', 'Snack energético y saludable', '/assets/images/Catálogo/GueeMedia.jpg'),

-- FRUTOS SECOS Y SEMILLAS
('ALM001', 'Almendras 125g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 25000.00, 'Almendras naturales 125 gramos', 'Almendras 100% naturales', 'Fuente de proteína y grasas saludables', '/assets/images/Catálogo/Almendra125.jpg'),
('ALM002', 'Almendras 250g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 45000.00, 'Almendras naturales 250 gramos', 'Almendras 100% naturales', 'Alto contenido de vitamina E', '/assets/images/Catálogo/Almendra250.jpg'),
('MAC001', 'Macadamia 125g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 35000.00, 'Nueces de macadamia 125 gramos', 'Macadamia 100% natural', 'Grasas saludables y minerales', '/assets/images/Catálogo/Macadamia125.jpg'),
('MAC002', 'Macadamia 250g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 65000.00, 'Nueces de macadamia 250 gramos', 'Macadamia 100% natural', 'Alto valor nutricional', '/assets/images/Catálogo/Macadamia250.jpg'),
('PIS001', 'Pistachos 125g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 30000.00, 'Pistachos tostados 125 gramos', 'Pistachos 100% naturales', 'Rico en proteína y fibra', '/assets/images/Catálogo/Pistachos125.jpg'),
('PIS002', 'Pistachos 250g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 55000.00, 'Pistachos tostados 250 gramos', 'Pistachos 100% naturales', 'Antioxidantes y minerales', '/assets/images/Catálogo/Pistachos250.jpg'),
('MAR001', 'Marañón 125g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 28000.00, 'Marañón tostado 125 gramos', 'Marañón 100% natural', 'Fuente de magnesio y zinc', '/assets/images/Catálogo/Marañon125.jpg'),
('MAR002', 'Marañón 250g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 50000.00, 'Marañón tostado 250 gramos', 'Marañón 100% natural', 'Alto contenido de selenio', '/assets/images/Catálogo/Marañon250.jpg'),
('DAT001', 'Dátiles 125g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 18000.00, 'Dátiles naturales 125 gramos', 'Dátiles 100% naturales', 'Dulce natural, rico en fibra', '/assets/images/Catálogo/Dátiles125.jpg'),
('DAT002', 'Dátiles 250g', (SELECT id FROM categories WHERE code = 'frutos-secos'), 32000.00, 'Dátiles naturales 250 gramos', 'Dátiles 100% naturales', 'Energía natural y minerales', '/assets/images/Catálogo/Dátiles250.jpg'),
('NOZ001', 'Nuez de Nogal', (SELECT id FROM categories WHERE code = 'frutos-secos'), 28000.00, 'Nueces de nogal naturales', 'Nueces 100% naturales', 'Omega-3 y antioxidantes', '/assets/images/Catálogo/NuezNogal.jpg'),
('NOZ002', 'Nuez de Brasil', (SELECT id FROM categories WHERE code = 'frutos-secos'), 32000.00, 'Nueces de Brasil', 'Nueces de Brasil 100% naturales', 'Rico en selenio', '/assets/images/Catálogo/NuezBrasil.jpg'),

-- SEMILLAS
('SEM001', 'Semillas de Chía', (SELECT id FROM categories WHERE code = 'frutos-secos'), 15000.00, 'Semillas de chía orgánicas', 'Semillas de chía 100% orgánicas', 'Omega-3, fibra y proteína', '/assets/images/Catálogo/SemillasChía.jpg'),
('SEM002', 'Semillas de Girasol', (SELECT id FROM categories WHERE code = 'frutos-secos'), 12000.00, 'Semillas de girasol tostadas', 'Semillas de girasol 100% naturales', 'Vitamina E y magnesio', '/assets/images/Catálogo/SemillasGirasol.jpg'),
('SEM003', 'Semillas de Calabaza', (SELECT id FROM categories WHERE code = 'frutos-secos'), 14000.00, 'Semillas de calabaza tostadas', 'Semillas de calabaza 100% naturales', 'Zinc y magnesio', '/assets/images/Catálogo/SemillasCalabaza.jpg'),
('LIN001', 'Linaza', (SELECT id FROM categories WHERE code = 'frutos-secos'), 10000.00, 'Semillas de linaza', 'Linaza 100% natural', 'Omega-3 y fibra soluble', '/assets/images/Catálogo/Linaza.jpg'),
('AJON001', 'Ajonjolí', (SELECT id FROM categories WHERE code = 'frutos-secos'), 11000.00, 'Semillas de ajonjolí tostadas', 'Ajonjolí 100% natural', 'Calcio y proteína', '/assets/images/Catálogo/Ajonjolí.jpg'),

-- ENVASADOS
('MIEL001', 'Miel de Abejas', (SELECT id FROM categories WHERE code = 'envasados'), 18000.00, 'Miel pura de abejas artesanal', 'Miel 100% pura', 'Endulzante natural, propiedades antibacterianas', '/assets/images/Catálogo/Miel.jpg'),
('POL001', 'Polen', (SELECT id FROM categories WHERE code = 'envasados'), 25000.00, 'Polen de abejas puro', 'Polen 100% natural', 'Alto contenido de proteína y vitaminas', '/assets/images/Catálogo/Polen.jpg'),
('SAL001', 'Sal Entera', (SELECT id FROM categories WHERE code = 'envasados'), 5000.00, 'Sal marina entera', 'Sal marina 100% natural', 'Minerales naturales', '/assets/images/Catálogo/SalEntera.jpg'),
('SAL002', 'Sal Molida', (SELECT id FROM categories WHERE code = 'envasados'), 5000.00, 'Sal marina molida', 'Sal marina 100% natural', 'Lista para usar', '/assets/images/Catálogo/SalMolida.jpg'),

-- OTROS PRODUCTOS
('QUIN001', 'Quinua', (SELECT id FROM categories WHERE code = 'frutos-secos'), 22000.00, 'Quinua orgánica lista para consumir', 'Quinua orgánica 100%', 'Super alimento, proteína completa', '/assets/images/Catálogo/Quinua.jpg'),
('AVEN001', 'Avena', (SELECT id FROM categories WHERE code = 'frutos-secos'), 8000.00, 'Avena en hojuelas', 'Avena 100% natural', 'Alta en fibra, reduce colesterol', '/assets/images/Catálogo/Avena.jpg'),
('CUR001', 'Cúrcuma', (SELECT id FROM categories WHERE code = 'envasados'), 15000.00, 'Cúrcuma en polvo orgánica', 'Cúrcuma 100% orgánica', 'Propiedades antiinflamatorias', '/assets/images/Catálogo/Cúrcuma.jpg'),
('FLO001', 'Flor de Jamaica', (SELECT id FROM categories WHERE code = 'envasados'), 12000.00, 'Flor de Jamaica seca', 'Flor de Jamaica 100% natural', 'Rica en antioxidantes, diurética', '/assets/images/Catálogo/FlorJamaica.jpg'),
('ALB001', 'Albaricoque', (SELECT id FROM categories WHERE code = 'frutos-secos'), 20000.00, 'Albaricoques secos naturales', 'Albaricoques 100% naturales', 'Rico en vitamina A y fibra', '/assets/images/Catálogo/Albaricoque.jpg'),
('CIR001', 'Ciruelas', (SELECT id FROM categories WHERE code = 'frutos-secos'), 18000.00, 'Ciruelas pasas naturales', 'Ciruelas 100% naturales', 'Alto contenido de fibra y hierro', '/assets/images/Catálogo/Ciruelas.jpg'),
('MIX001', 'Mix de Maíz', (SELECT id FROM categories WHERE code = 'frutos-secos'), 14000.00, 'Mezcla de maíz tostado', 'Maíz, sal', 'Snack crocante y saludable', '/assets/images/Catálogo/MixMaíz.jpg'),
('REL001', 'Relleno de Cacao', (SELECT id FROM categories WHERE code = 'galleteria'), 14000.00, 'Galletas rellenas de cacao', 'Harina, cacao, mantequilla', 'Deliciosas galletas rellenas', '/assets/images/Catálogo/RellenoCacao.jpg'),
('REL002', 'Relleno Surtido', (SELECT id FROM categories WHERE code = 'galleteria'), 14000.00, 'Galletas rellenas surtidas', 'Harina, rellenos variados', 'Variedad de sabores', '/assets/images/Catálogo/RellenoSurtido.jpg'),
('RES001', 'Resobados', (SELECT id FROM categories WHERE code = 'galleteria'), 10000.00, 'Galletas resobadas artesanales', 'Harina, mantequilla, azúcar', 'Textura suave y dulce', '/assets/images/Catálogo/Resobados.jpg')

ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    price = VALUES(price),
    description = VALUES(description),
    ingredients = VALUES(ingredients),
    benefits = VALUES(benefits);

-- Verificar productos insertados
SELECT COUNT(*) as total_productos FROM products;
SELECT c.name as categoria, COUNT(p.id) as cantidad_productos 
FROM categories c 
LEFT JOIN products p ON c.id = p.category_id 
GROUP BY c.id, c.name 
ORDER BY c.name;

