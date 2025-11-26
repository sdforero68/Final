-- ============================================
-- Plantilla para agregar un producto nuevo
-- Copia y modifica este ejemplo para agregar productos
-- ============================================

USE anita_integrales;

-- Ejemplo: Agregar un producto nuevo
-- Reemplaza los valores según tu producto

INSERT INTO products (code, name, category_id, price, description, ingredients, benefits, image) VALUES
(
    'CODIGO001',                                    -- Código único del producto (ej: ACH001, PAN001)
    'Nombre del Producto',                          -- Nombre del producto
    (SELECT id FROM categories WHERE code = 'panaderia'),  -- Código de categoría (panaderia, amasijos, galleteria, granola, frutos-secos, envasados)
    15000.00,                                       -- Precio (número decimal)
    'Descripción del producto',                     -- Descripción breve
    'Ingredientes principales',                     -- Lista de ingredientes
    'Beneficios para la salud',                     -- Beneficios nutricionales
    '/assets/images/Catálogo/NombreImagen.jpg'     -- Ruta de la imagen
);

-- Si quieres agregar múltiples productos a la vez, usa este formato:

INSERT INTO products (code, name, category_id, price, description, ingredients, benefits, image) VALUES
('PROD001', 'Producto 1', (SELECT id FROM categories WHERE code = 'panaderia'), 15000.00, 'Descripción 1', 'Ingredientes 1', 'Beneficios 1', '/assets/images/Catálogo/Imagen1.jpg'),
('PROD002', 'Producto 2', (SELECT id FROM categories WHERE code = 'amasijos'), 18000.00, 'Descripción 2', 'Ingredientes 2', 'Beneficios 2', '/assets/images/Catálogo/Imagen2.jpg');

-- Verificar que se insertó correctamente
SELECT * FROM products WHERE code = 'CODIGO001';

-- Categorías disponibles:
-- 'panaderia'      - Panadería
-- 'amasijos'       - Amasijos  
-- 'galleteria'     - Galletería
-- 'granola'        - Granola
-- 'frutos-secos'   - Frutos Secos y Semillas
-- 'envasados'      - Envasados

