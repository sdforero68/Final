-- MySQL dump limpio para FreeSQLDatabase (sin comandos que requieren privilegios SUPER)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'panaderia','Panadería','2025-11-25 03:03:57'),(2,'amasijos','Amasijos','2025-11-25 03:03:57'),(3,'galleteria','Galletería','2025-11-25 03:03:57'),(4,'granola','Granola','2025-11-25 03:03:57'),(5,'frutos-secos','Frutos Secos y Semillas','2025-11-25 03:03:57'),(6,'envasados','Envasados','2025-11-25 03:03:57');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `status` enum('pendiente','confirmado','en_preparacion','enviado','entregado','cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `delivery_method` enum('delivery','pickup') COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ingredients` text COLLATE utf8mb4_unicode_ci,
  `benefits` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_category` (`category_id`),
  KEY `idx_code` (`code`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'pan-queso-grande','Pan de Queso y Cuajada Grande',1,12000.00,'Pan artesanal con queso y cuajada incorporados en la mezcla, tamaño grande 5 unds.','Harina integral, mantequilla, miel de caña, queso, quinua, yacón y linaza','Rico en proteínas y fibra, no contiene levadura.','Catálogo/CuajadaQuesoGrande.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(2,'pan-queso-pequeno','Pan de Queso y Cuajada Pequeño',1,10000.00,'Pan artesanal con queso y cuajada incorporados en la mezcla, tamaño pequeño 10 unds.','Harina integral, mantequilla, miel de caña, queso, quinua, yacón y linaza','Rico en proteínas y fibra, no contiene levadura.','Catálogo/CuajadaQuesoPeque.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(3,'pan-maiz-grande','Pan de Maíz Grande',1,12000.00,'Pan artesanal de maíz, con queso y cuajada, tamaño grande 5 unds.','Harina de maíz, mantequilla, miel de caña, queso, quinua, yacón y linaza','Sin gluten, rico en fibra','Catálogo/QuesoMaizGrande.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(4,'pan-maiz-pequeno','Pan de Maíz Pequeño',1,7000.00,'Pan artesanal de maíz, con queso y cuajada, tamaño pequeño 6 unds.','Harina de maíz, mantequilla, miel de caña, queso, quinua, yacón y linaza','Sin gluten, rico en fibra','Catálogo/QuesoMaizPeque.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(5,'masa-madre-centeno','Masa Madre de Centeno',1,16000.00,'Pan de masa madre (proceso de fermentación natural) con harina de centeno','Harina de centeno, masa madre natural y nueces','Digestión lenta, rico en minerales, libre de gluten, grasa, huevo, levadura y dulce.','Catálogo/AncestralCenteno.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(6,'masa-madre-ancestral-grande','Masa Madre Ancestral Grande',1,20000.00,'Pan de masa madre con harinas ancestrales, tamaño grande','Quinua, amaranto, sagú, masa madre','Superalimento, alto en proteínas','Catálogo/AncestralGrande.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(7,'masa-madre-ancestral-pequeno','Masa Madre Ancestral Pequeño',1,14000.00,'Pan de masa madre con harinas ancestrales, tamaño pequeño','Quinua, amaranto, sagú, masa madre','Superalimento, alto en proteínas','Catálogo/AncestralPeque.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(8,'paneton-grande','Panetón Grande',1,20000.00,'Panetón artesanal grande','Harinas integrales, frutos secos, especias','Endulzado naturalmente','Catálogo/PanetónGrande.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(9,'paneton-mediano','Panetón Mediano',1,14000.00,'Panetón artesanal mediano','Harinas integrales, frutos secos, especias','Endulzado naturalmente','Catálogo/PanetónMediano.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(10,'paneton-mini','Panetón Mini',1,6000.00,'Panetón artesanal mini','Harinas integrales, frutos secos, especias','Endulzado naturalmente','Catálogo/PanetónPeque.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(11,'relleno-surtido','Pan Relleno Surtido',1,16000.00,'Pan integral con relleno surtido','Harina integral, rellenos variados','Versátil y nutritivo','Catálogo/RellenoSurtido.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(12,'relleno-cacao','Pan Relleno de Cacao',1,18000.00,'Pan integral con relleno de cacao','Harina integral, cacao puro','Antioxidantes del cacao','Catálogo/RellenoCacao.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(13,'mogollas','Mogollas',1,10000.00,'Mogollas artesanales integrales','Harina integral, masa madre','Tradicional y saludable','Catálogo/Mogollas.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(14,'roscones','Roscones Integrales',1,6000.00,'Roscones integrales artesanales','Harina integral, especias','Crujientes y nutritivos','Catálogo/Roscones.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(15,'resobados','Resobados',2,7000.00,'Resobados artesanales','Harina, mantequilla','Textura única','Catálogo/Resobados.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(16,'achiras-18','Achiras (18 unidades)',2,7000.00,'Achiras tradicionales, paquete de 18','Almidón de achira, queso','Sin gluten','Catálogo/AchirasGrandes.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(17,'achiras-9','Achiras (9 unidades)',2,3500.00,'Achiras tradicionales, paquete de 9','Almidón de achira, queso','Sin gluten','Catálogo/AchirasPeque.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(18,'galletas-chip-cacao','Galletas Chip Cacao',3,7000.00,'Galletas con chips de cacao','Harina integral, chips de cacao puro','Antioxidantes','Catálogo/GalletasCacao.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(19,'galletas-cafe','Galletas de Café',3,7000.00,'Galletas con sabor a café','Café colombiano, harina integral','Energizantes','Catálogo/GalletasCafé.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(20,'galletas-jengibre','Galletas de Jengibre',3,7000.00,'Galletas con jengibre','Jengibre fresco, especias','Antiinflamatorio','Catálogo/GalletasJenjibre.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(21,'galletas-sal-grande','Galletas de Sal Grandes',3,16000.00,'Galletas saladas, presentación grande','Harina integral, sal rosada','Snack saludable','Catálogo/GalletasSal.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(22,'tostadas','Tostadas',3,7000.00,'Tostadas integrales crujientes','Harina integral, semillas','Perfectas para dips','Catálogo/Tostadas.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(23,'granola-datiles-lb','Granola con Dátiles (Libra)',4,16000.00,'Granola artesanal con dátiles, 1 libra','Avena, dátiles, frutos secos','Energía natural','Catálogo/LibraDátiles.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(24,'granola-datiles-media','Granola con Dátiles (Media Libra)',4,8000.00,'Granola artesanal con dátiles, media libra','Avena, dátiles, frutos secos','Energía natural','Catálogo/MediaDátiles.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(25,'granola-uvas-lb','Granola con Uvas (Libra)',4,16000.00,'Granola artesanal con uvas pasas, 1 libra','Avena, uvas pasas, almendras','Antioxidantes','Catálogo/LibraUvas.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(26,'granola-uvas-media','Granola con Uvas (Media Libra)',4,8000.00,'Granola artesanal con uvas pasas, media libra','Avena, uvas pasas, almendras','Antioxidantes','Catálogo/MediaUvas.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(27,'granola-arandanos-lb','Granola con Arándanos (Libra)',4,16000.00,'Granola artesanal con arándanos, 1 libra','Avena, arándanos secos, nueces','Superalimento','Catálogo/LibraArándanos.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(28,'granola-arandanos-media','Granola con Arándanos (Media Libra)',4,8000.00,'Granola artesanal con arándanos, media libra','Avena, arándanos secos, nueces','Superalimento','Catálogo/MediaArándanos.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(29,'granola-sin-dulce','Granola Sin Dulce (Para Diabéticos)',4,16000.00,'Granola especial sin azúcar','Avena, frutos secos, sin endulzantes','Apto para diabéticos','Catálogo/LibraDiabéticos.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(30,'pistachos-250','Pistachos (250g)',5,20000.00,'Pistachos naturales','Pistachos','Rico en proteínas','Catálogo/Pistachos250.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(31,'pistachos-125','Pistachos (125g)',5,10000.00,'Pistachos naturales','Pistachos','Rico en proteínas','Catálogo/Pistachos125.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(32,'maranon-250','Marañón (250g)',5,20000.00,'Marañón natural','Marañón','Alto en magnesio','Catálogo/Marañon250.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(33,'maranon-125','Marañón (125g)',5,10000.00,'Marañón natural','Marañón','Alto en magnesio','Catálogo/Marañon125.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(34,'macadamia-250','Macadamia (250g)',5,20000.00,'Nueces de macadamia premium','Macadamia','Grasas saludables','Catálogo/Macadamia250.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(35,'macadamia-125','Macadamia (125g)',5,10000.00,'Nueces de macadamia premium','Macadamia','Grasas saludables','Catálogo/Macadamia125.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(36,'almendras-250','Almendras (250g)',5,20000.00,'Almendras naturales','Almendras','Vitamina E','Catálogo/Almendra250.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(37,'almendras-125','Almendras (125g)',5,10000.00,'Almendras naturales','Almendras','Vitamina E','Catálogo/Almendra125.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(38,'datiles-fruto-250','Dátiles (250g)',5,20000.00,'Dátiles naturales','Dátiles','Energía natural','Catálogo/Dátiles250.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(39,'datiles-fruto-125','Dátiles (125g)',5,10000.00,'Dátiles naturales','Dátiles','Energía natural','Catálogo/Dátiles125.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(40,'nuez-brasil-250','Nuez de Brasil (250g)',5,20000.00,'Nueces de Brasil','Nuez de Brasil','Selenio natural','Catálogo/NuezBrasil.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(41,'nuez-brasil-125','Nuez de Brasil (125g)',5,10000.00,'Nueces de Brasil','Nuez de Brasil','Selenio natural','Catálogo/NuezBrasil.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(42,'nuez-nogal-250','Nuez de Nogal (250g)',5,20000.00,'Nueces de nogal','Nuez de nogal','Omega 3','Catálogo/NuezNogal.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(43,'nuez-nogal-125','Nuez de Nogal (125g)',5,10000.00,'Nueces de nogal','Nuez de nogal','Omega 3','Catálogo/NuezNogal.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(44,'albaricoque','Albaricoque',5,10000.00,'Albaricoques secos','Albaricoque deshidratado','Rico en fibra','Catálogo/Albaricoque.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(45,'ciruelas-pasas','Ciruelas Pasas',5,5000.00,'Ciruelas pasas naturales','Ciruelas deshidratadas','Digestión saludable','Catálogo/Ciruelas.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(46,'semillas-girasol','Semillas de Girasol',5,5000.00,'Semillas de girasol naturales','Semillas de girasol','Vitamina E','Catálogo/SemillasGirasol.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(47,'semillas-calabaza','Semillas de Calabaza',5,6000.00,'Semillas de calabaza','Semillas de calabaza','Magnesio y zinc','Catálogo/SemillasCalabaza.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(48,'semillas-chia','Semillas de Chía',5,5000.00,'Semillas de chía','Chía','Omega 3, fibra','Catálogo/SemillasChía.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(49,'linaza','Linaza',5,4000.00,'Semillas de linaza','Linaza','Omega 3','Catálogo/Linaza.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(50,'ajonjoli','Ajonjolí',5,12000.00,'Semillas de ajonjolí','Ajonjolí','Calcio natural','Catálogo/Ajonjolí.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(51,'quinua','Quinua',5,10000.00,'Quinua en grano','Quinua','Proteína completa','Catálogo/Quinua.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(52,'flor-jamaica','Flor de Jamaica',5,4000.00,'Flor de Jamaica deshidratada','Flor de Jamaica','Antioxidantes','Catálogo/FlorJamaica.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(53,'curcuma','Cúrcuma',5,5000.00,'Cúrcuma en polvo','Cúrcuma','Antiinflamatorio','Catálogo/Cúrcuma.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(54,'sal-rosada-grano','Sal Rosada en Grano',5,12000.00,'Sal rosada del Himalaya en grano','Sal rosada','Minerales naturales','Catálogo/SalEntera.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(55,'sal-rosada-molida','Sal Rosada Molida',5,12000.00,'Sal rosada del Himalaya molida','Sal rosada','Minerales naturales','Catálogo/SalMolida.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(56,'mix-maiz','Mix de Maíz',5,5000.00,'Mezcla de maíz tostado','Maíz variado','Snack natural','Catálogo/MixMaíz.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(57,'mantequilla-ghee-lb','Mantequilla Ghee (Libra)',6,36000.00,'Mantequilla clarificada ghee, 1 libra','Mantequilla clarificada','Sin lactosa, alto punto de humo','Catálogo/GueeLibra.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(58,'mantequilla-ghee-media','Mantequilla Ghee (Media Libra)',6,18000.00,'Mantequilla clarificada ghee, media libra','Mantequilla clarificada','Sin lactosa, alto punto de humo','Catálogo/GueeMedia.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25'),(59,'miel-abejas','Miel de Abejas (500g)',6,40000.00,'Miel pura de abejas, 500g','Miel 100% natural','Antibacteriana, energizante','Catálogo/Miel.jpg','2025-11-25 03:14:25','2025-11-25 03:14:25');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_token` (`token`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

