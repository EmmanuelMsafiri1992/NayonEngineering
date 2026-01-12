-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: elton_laravel
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategories` json DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Audio & Visual Alarms','audio-visual-alarms','[\"Beacons\", \"Combination Sounder/Beacon\", \"Fire Detection\", \"Signal Towers\", \"Sounders\", \"Speakers & PA Systems\", \"Traffic Lights\"]','fas fa-bell',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(2,'Automation Products','automation-products','[\"Contactors\", \"Counters\", \"Hour Meters\", \"Level/Pump Control\", \"Power Monitors\", \"Power Supplies\", \"Process Control\", \"Relays\", \"Smart Control\", \"Temperature Control\", \"Timers\"]','fas fa-cogs',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(3,'Circuit Breakers & Switchgear','circuit-breakers-switchgear','[\"Changeover Switches\", \"Contactors\", \"Fuses\", \"Isolators\", \"MCBs\", \"RCDs\", \"Surge Protection\"]','fas fa-bolt',2,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(4,'Enclosures & Fittings','enclosures-fittings','[\"Distribution Boards\", \"Electronic Enclosures\", \"Floor/Wall Mount\", \"Meter Boxes\", \"Pushbutton Stations\", \"Weather Proof Boxes\"]','fas fa-box',3,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(5,'Lighting','lighting','[\"LED Bulbs\", \"LED Tubes\", \"Flood Lights\", \"Down Lights\", \"Commercial Lighting\", \"Decorative Lighting\", \"Solar Lighting\"]','fas fa-lightbulb',4,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(6,'Power Supplies & Transformers','power-supplies-transformers','[\"Back-Up Power/UPS\", \"Batteries\", \"Battery Chargers\", \"DC Converters\", \"Power Supplies\", \"Transformers\", \"Voltage Regulators\"]','fas fa-car-battery',5,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(7,'Solar','solar','[\"Solar Panels\", \"Inverters\", \"Batteries\", \"Charge Controllers\", \"Mounting Systems\", \"Solar Lighting\", \"Solar Pumping\"]','fas fa-solar-panel',6,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(8,'Installation & Wiring','installation-wiring','[\"Switches & Sockets\", \"Plug Tops\", \"Cable & Wire\", \"Conduit\", \"Terminals\", \"Electrical Tape\"]','fas fa-plug',7,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(9,'Test Instruments & Tools','test-instruments-tools','[\"Multimeters\", \"Clamp Meters\", \"Hand Tools\", \"Power Tools\", \"Safety Equipment\"]','fas fa-tools',8,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(10,'Level Control & Pumps','level-control-pumps','[\"Float Switches\", \"Flow Switches\", \"Level Sensors\", \"Pumps\", \"Pump Accessories\"]','fas fa-water',9,1,'2026-01-11 15:13:55','2026-01-11 15:13:55');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_11_000001_create_categories_table',1),(5,'2024_01_11_000002_create_products_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `subcategory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACDC',
  `list_price` decimal(10,2) NOT NULL,
  `net_price` decimal(10,2) NOT NULL,
  `discount` int NOT NULL DEFAULT '0',
  `stock` int NOT NULL DEFAULT '0',
  `warranty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_is_active_index` (`category_id`,`is_active`),
  KEY `products_brand_index` (`brand`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'RA300BK','16A Black Std Plug Top Rubber','16a-black-std-plug-top-rubber',8,'Plug Tops','ACDC',24.00,13.04,46,93568,NULL,NULL,'16A Black Standard Plug Top with Rubber construction for durability and safety.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(2,'LEDT8-A4FR-DL','230VAC 18W Daylight Frosted 1200mm LED T8 Tube','230vac-18w-daylight-frosted-1200mm-led-t8-tube',5,'LED Tubes','ACDC',68.00,25.22,63,73581,'2 Years',NULL,'Energy efficient 18W LED T8 tube with daylight frosted finish. 1200mm length.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(3,'LEDT8-A5FR-DL','230VAC 22W Daylight Frosted 1500mm LED T8 Tube','230vac-22w-daylight-frosted-1500mm-led-t8-tube',5,'LED Tubes','ACDC',80.00,31.30,61,81108,'2 Years',NULL,'Energy efficient 22W LED T8 tube with daylight frosted finish. 1500mm length.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(4,'3M-74712','1710 Black General Purpose PVC Electrical Tape','1710-black-general-purpose-pvc-electrical-tape',8,'Electrical Tape','3M Electrical',41.00,21.74,47,6772,NULL,NULL,'3M 1710 Black PVC Electrical Tape for general purpose electrical insulation.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(5,'B7402','2x16A Switched Socket Outlet with White Cover','2x16a-switched-socket-outlet-with-white-cover',8,'Switches & Sockets','ACDC',104.00,51.30,51,40166,NULL,NULL,'Double 16A switched socket outlet with white cover plate.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(6,'HS-E27-10W-DL','230VAC 9W Daylight E27 LED Bulb','230vac-9w-daylight-e27-led-bulb',5,'LED Bulbs','ACDC',30.00,11.30,62,45000,NULL,NULL,'9W Daylight LED bulb with E27 screw base. Energy efficient replacement.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(7,'HS-B22-10W-DL','230VAC 9W Daylight B22 LED Bulb','230vac-9w-daylight-b22-led-bulb',5,'LED Bulbs','ACDC',34.00,11.30,67,52000,NULL,NULL,'9W Daylight LED bulb with B22 bayonet base. Energy efficient replacement.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(8,'SMD-GU10-6W-DL','6W GU10 Daylight Down Light','6w-gu10-daylight-down-light',5,'Down Lights','ACDC',30.00,8.70,71,38000,NULL,NULL,'6W GU10 Daylight LED down light. Perfect for recessed lighting.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(9,'RA310BK','Janus Coupler Double Black 15A','janus-coupler-double-black-15a',8,'Plug Tops','ACDC',41.00,21.74,47,28000,NULL,NULL,'15A Janus double coupler in black finish.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(10,'FL-100W-CW','220-240V 100W Cool White LED Aluminium Flood Light IP65','220-240v-100w-cool-white-led-aluminium-flood-light-ip65',5,'Flood Lights','ACDC',369.00,199.13,46,5200,NULL,NULL,'100W LED Flood Light with aluminium body. IP65 rated for outdoor use.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(11,'HS-B22-10W-CW','230VAC 9W Cool White LED Lamp B22','230vac-9w-cool-white-led-lamp-b22',5,'LED Bulbs','ACDC',34.00,11.30,67,48000,NULL,NULL,'9W Cool White LED lamp with B22 bayonet base.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(12,'SA1-20','20A 1-Pole 4.5kA C Curve Mini Rail MCB','20a-1-pole-45ka-c-curve-mini-rail-mcb',3,'MCBs','ACDC',104.00,56.52,46,32000,NULL,NULL,'20A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(13,'LEDT8-A2FR-DL','230VAC 9W Daylight LED T8 Tube 550mm','230vac-9w-daylight-led-t8-tube-550mm',5,'LED Tubes','ACDC',49.00,18.26,63,42000,NULL,NULL,'9W Daylight LED T8 tube. 550mm length for compact fittings.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(14,'LEDT8-A5FR-CW','230VAC 22W Cool White LED T8 Tube 1500mm','230vac-22w-cool-white-led-t8-tube-1500mm',5,'LED Tubes','ACDC',80.00,31.30,61,55000,NULL,NULL,'22W Cool White LED T8 tube. 1500mm length.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(15,'SMD-GU10-6W-WW','230VAC 6W GU10 Warm White Down Light','230vac-6w-gu10-warm-white-down-light',5,'Down Lights','ACDC',30.00,8.70,71,35000,NULL,NULL,'6W GU10 Warm White LED down light.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(16,'LED-GU10-6W-DL/5','230VAC 6W GU10 Daylight Down Light - 5 Pack','230vac-6w-gu10-daylight-down-light-5-pack',5,'Down Lights','ACDC',148.00,42.61,71,12000,NULL,NULL,'Pack of 5 x 6W GU10 Daylight LED down lights. Great value.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(17,'3M-74715','1710 White General Purpose PVC Electrical Tape','1710-white-general-purpose-pvc-electrical-tape',8,'Electrical Tape','3M Electrical',41.00,21.74,47,5500,NULL,NULL,'3M 1710 White PVC Electrical Tape for general purpose electrical insulation.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(18,'FL-20W-CW','220-240VAC 20W LED Cool White Mini Flood Light','220-240vac-20w-led-cool-white-mini-flood-light',5,'Flood Lights','ACDC',129.00,68.70,47,8500,NULL,NULL,'Compact 20W LED mini flood light. Cool white output.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(19,'HS-B22-15W-DL','230VAC 15W B22 Daylight LED Bulb','230vac-15w-b22-daylight-led-bulb',5,'LED Bulbs','ACDC',39.00,13.04,67,42000,NULL,NULL,'15W Daylight LED bulb with B22 bayonet base. High brightness.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(20,'SA1-10','10A 1-Pole 4.5kA C Curve Mini Rail MCB','10a-1-pole-45ka-c-curve-mini-rail-mcb',3,'MCBs','ACDC',104.00,56.52,46,38000,NULL,NULL,'10A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(21,'T-LED-7W-E27-CW','230VAC 7W Cool White LED Bulb E27 4200k','230vac-7w-cool-white-led-bulb-e27-4200k',5,'LED Bulbs','ACDC',28.00,10.43,63,55000,NULL,NULL,'7W Cool White LED bulb with E27 screw base. 4200K colour temperature.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(22,'B7002','2-Lever 1-Way Switch 2x4 with White Cover Plate','2-lever-1-way-switch-2x4-with-white-cover-plate',8,'Switches & Sockets','ACDC',45.00,24.35,46,28000,NULL,NULL,'2-Lever 1-way light switch. 2x4 module with white cover plate.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(23,'HS-E27-15W-WW','230VAC 15W Warm White LED Lamp E27','230vac-15w-warm-white-led-lamp-e27',5,'LED Bulbs','ACDC',39.00,13.04,67,35000,NULL,NULL,'15W Warm White LED bulb with E27 screw base.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(24,'SAE2A-63','63A 30mA 2P RCD No Overload Type A','63a-30ma-2p-rcd-no-overload-type-a',3,'RCDs','ACDC',622.00,346.96,44,8500,NULL,NULL,'63A 2-Pole Residual Current Device. 30mA sensitivity, Type A.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(25,'CP-FD-18W-DL','90-260VAC 18W LED Magnetic Retrofit Module Daylight','90-260vac-18w-led-magnetic-retrofit-module-daylight',5,'LED Bulbs','ACDC',122.00,60.00,51,15000,NULL,NULL,'18W LED Magnetic retrofit module. Daylight colour. Wide voltage input.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(26,'CON-20/25','PVC Conduit Pipe 20mm - Bundle of 25 x 4M','pvc-conduit-pipe-20mm-bundle-of-25-x-4m',8,'Conduit','ACDC',716.00,251.00,65,2500,NULL,NULL,'Bundle of 25 x 4 metre 20mm PVC conduit pipes.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(27,'LED-A60-7W-B22-DL/2','230VAC 7W Daylight A60 B22 LED Lamp - 2 Pack','230vac-7w-daylight-a60-b22-led-lamp-2-pack',5,'LED Bulbs','ACDC',55.00,20.87,62,22000,NULL,NULL,'Pack of 2 x 7W Daylight LED lamps with B22 bayonet base.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(28,'HS-B22-10W-WW','230VAC 9W Warm White LED Lamp B22','230vac-9w-warm-white-led-lamp-b22',5,'LED Bulbs','ACDC',34.00,11.30,67,42000,NULL,NULL,'9W Warm White LED lamp with B22 bayonet base.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(29,'FL-150W-CW','220-240V 150W Cool White LED Flood Light IP65','220-240v-150w-cool-white-led-flood-light-ip65',5,'Flood Lights','ACDC',668.00,346.96,48,3200,NULL,NULL,'150W LED Flood Light. IP65 rated for outdoor use. Cool white output.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(30,'CP-C018','CLOU 80A CITIQ Prepaid Electricity Sub Meter','clou-80a-citiq-prepaid-electricity-sub-meter',2,'Power Monitors','Citiq Prepaid',122.00,94.78,22,5500,NULL,NULL,'80A Prepaid electricity sub meter by CITIQ.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(31,'SDBEDIN-12','White DIN DB 12-Way Surface with Door','white-din-db-12-way-surface-with-door',4,'Distribution Boards','ACDC',194.00,129.57,33,8500,NULL,NULL,'12-Way DIN rail distribution board. Surface mount with door.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(32,'SA1-32','32A 1-Pole 4.5kA C Curve Mini Rail MCB','32a-1-pole-45ka-c-curve-mini-rail-mcb',3,'MCBs','ACDC',104.00,56.52,46,28000,NULL,NULL,'32A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(33,'LED-GU10-6W-WW/5','230VAC 6W GU10 Warm White Down Light - 5 Pack','230vac-6w-gu10-warm-white-down-light-5-pack',5,'Down Lights','ACDC',148.00,42.61,71,10000,NULL,NULL,'Pack of 5 x 6W GU10 Warm White LED down lights.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(34,'T-LED-7W-B22-WW','230VAC 7W Warm White LED Bulb B22 2700k','230vac-7w-warm-white-led-bulb-b22-2700k',5,'LED Bulbs','ACDC',28.00,10.43,63,48000,NULL,NULL,'7W Warm White LED bulb with B22 bayonet base. 2700K colour temperature.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(35,'HS-B22-15W-CW','230VAC 15W Cool White LED Lamp B22','230vac-15w-cool-white-led-lamp-b22',5,'LED Bulbs','ACDC',39.00,13.04,67,38000,NULL,NULL,'15W Cool White LED lamp with B22 bayonet base.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(36,'SA1-15','15A 1-Pole 4.5kA C Curve Mini Rail MCB','15a-1-pole-45ka-c-curve-mini-rail-mcb',3,'MCBs','ACDC',104.00,56.52,46,32000,NULL,NULL,'15A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(37,'W901 WH','1.5mm x 2 Core + E Flat White Cable - 100m','15mm-x-2-core-e-flat-white-cable-100m',8,'Cable & Wire','ACDC',1712.00,1018.00,41,850,NULL,NULL,'100 metre roll of 1.5mm 2 core + earth flat white cable.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(38,'LEDT8-A2FR-CW','230VAC 9W Cool White LED T8 Tube 550mm','230vac-9w-cool-white-led-t8-tube-550mm',5,'LED Tubes','ACDC',49.00,18.26,63,38000,NULL,NULL,'9W Cool White LED T8 tube. 550mm length for compact fittings.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(39,'NB250-PCL','6 Inch PVC Ceiling Light Fitting - Clear','6-inch-pvc-ceiling-light-fitting-clear',5,'Commercial Lighting','ACDC',38.00,25.22,34,18000,NULL,NULL,'6 inch PVC ceiling light fitting with clear cover.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(40,'ES-CR2032-BP2','Energizer Lithium Coin 2032 Blister - 2 Pack','energizer-lithium-coin-2032-blister-2-pack',6,'Batteries','Energizer',46.00,37.95,18,12000,NULL,NULL,'Pack of 2 Energizer CR2032 lithium coin batteries.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(41,'T-LED-7W-E27-WW','230VAC 7W Warm White LED Bulb E27 2700k','230vac-7w-warm-white-led-bulb-e27-2700k',5,'LED Bulbs','ACDC',28.00,10.43,63,52000,NULL,NULL,'7W Warm White LED bulb with E27 screw base. 2700K colour temperature.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(42,'DIM-GU10-5W-WW-5Y','230VAC 5W GU10 Warm White Dimmable LED - 5 Year Warranty','230vac-5w-gu10-warm-white-dimmable-led-5-year-warranty',5,'Down Lights','ACDC',66.00,27.83,58,15000,'5 Years',NULL,'5W Dimmable GU10 LED down light. Warm white. 5 year warranty.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(43,'BH-01','Bulk Head Fitting B22 Holder','bulk-head-fitting-b22-holder',5,'Commercial Lighting','ACDC',46.00,33.91,26,22000,NULL,NULL,'Bulk head light fitting with B22 holder.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(44,'B7406','1x16A + 1X New SA Switch Socket','1x16a-1x-new-sa-switch-socket',8,'Switches & Sockets','ACDC',95.00,51.30,46,25000,NULL,NULL,'Combination 16A socket and SA switch socket outlet.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(45,'SDBEDIN-8','White DIN DB 8-Way Surface','white-din-db-8-way-surface',4,'Distribution Boards','ACDC',133.00,86.09,35,12000,NULL,NULL,'8-Way DIN rail distribution board. Surface mount.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(46,'TK-03-20W','Solar 20W LED Floodlight Kit','solar-20w-led-floodlight-kit',7,'Solar Lighting','ACDC',542.00,381.74,30,3500,NULL,NULL,'Complete solar flood light kit with 20W LED light and solar panel.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(47,'E91BP4-MAX','Energizer Max Battery AA - 4 Pack','energizer-max-battery-aa-4-pack',6,'Batteries','Energizer',101.00,83.33,18,8500,NULL,NULL,'Pack of 4 Energizer Max AA batteries.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(48,'LEDWPT8-218','2X18W LED Fitting 4FT IP65','2x18w-led-fitting-4ft-ip65',5,'Commercial Lighting','ACDC',368.00,207.83,44,6500,NULL,NULL,'Dual 18W LED fitting. 4 foot length. IP65 waterproof rated.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(49,'3M-74713','1710 Yellow General Purpose PVC Electrical Tape','1710-yellow-general-purpose-pvc-electrical-tape',8,'Electrical Tape','3M Electrical',41.00,21.74,47,4800,NULL,NULL,'3M 1710 Yellow PVC Electrical Tape for phase identification.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(50,'B7400','16A Switched Socket Outlet','16a-switched-socket-outlet',8,'Switches & Sockets','ACDC',87.00,42.61,51,35000,NULL,NULL,'Single 16A switched socket outlet.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(51,'LEDWPT8-222','2X22W LED Fitting 5FT IP65','2x22w-led-fitting-5ft-ip65',5,'Commercial Lighting','ACDC',446.00,251.30,44,5200,NULL,NULL,'Dual 22W LED fitting. 5 foot length. IP65 waterproof rated.',0,1,'2026-01-11 15:13:55','2026-01-11 15:13:55'),(52,'T-LED-5W-B22-DL','5W Daylight LED Bulb B22','5w-daylight-led-bulb-b22',5,'LED Bulbs','ACDC',28.00,9.57,66,55000,NULL,NULL,'5W Daylight LED bulb with B22 bayonet base.',1,1,'2026-01-11 15:13:55','2026-01-11 15:13:55');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2026-01-11 15:13:54','$2y$12$HShyzQcYqkrKrKSHt91lDu8syTXYn489uiqrSGjNFEZXh.YCxiR5e','gcgOYruDI4','2026-01-11 15:13:55','2026-01-11 15:13:55');
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

-- Dump completed on 2026-01-12 10:14:15
