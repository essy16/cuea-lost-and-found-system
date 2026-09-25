-- MySQL dump 10.13  Distrib 8.0.44, for macos12.7 (arm64)
--
-- Host: 127.0.0.1    Database: cuea_lost_found
-- ------------------------------------------------------
-- Server version	8.0.44

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `actor_id` int DEFAULT NULL,
  `actor_role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actor_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'admin','System Admin','Logged in',NULL,NULL,'Administrator login successful.','::1','2026-09-03 10:16:41'),(2,1,'admin','System Admin','Added staff',NULL,NULL,'John Wekesa (01014)','::1','2026-09-03 10:17:42'),(3,1,'admin','System Admin','Edited staff',NULL,NULL,'John Wekesa (01014)','::1','2026-09-03 10:17:51'),(4,1,'admin','System Admin','Edited staff',NULL,NULL,'John Wekesa (01014)','::1','2026-09-03 10:18:13'),(5,13,'staff','John Wekesa','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-03 10:18:29'),(6,13,'staff','John Wekesa','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-03 10:18:48'),(7,13,'staff','John Wekesa','Reported lost item',NULL,NULL,'Item #12 cv','::1','2026-09-03 10:19:44'),(8,13,'staff','John Wekesa','Captured and approved onsite claim','claim',3,'Claim #3 for Item #11 — Cynthia Nderitu','::1','2026-09-03 10:21:53'),(9,13,'staff','John Wekesa','Issued Item','item',11,'hdjdh to Cynthia Nderitu','::1','2026-09-03 10:22:02'),(10,13,'staff','John Wekesa','Issued Item','item',4,'Kiaru id to Ivy Kairu','::1','2026-09-03 10:22:05'),(11,13,'staff','John Wekesa','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-03 10:23:31'),(12,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-03 10:23:40'),(13,1,'admin','System Admin','Logged in',NULL,NULL,'Administrator login successful.','::1','2026-09-04 07:20:43'),(14,1,'admin','System Admin','Edited staff',NULL,NULL,'Mathew Chacha (0101)','::1','2026-09-04 07:22:33'),(15,1,'admin','System Admin','Edited staff',NULL,NULL,'Mathew Chacha (0101)','::1','2026-09-04 07:22:34'),(16,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:23:01'),(17,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 07:24:36'),(18,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 07:25:29'),(19,5,'student','Ivy Kairu','Reported found item',NULL,NULL,'Item #13 student id','::1','2026-09-04 07:29:09'),(20,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:30:16'),(21,12,'staff','Mathew Chacha','Approved User Found Report','item',13,'Item #13','::1','2026-09-04 07:31:19'),(22,11,'staff','Jeremy Kinuthia','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:33:30'),(23,11,'staff','Jeremy Kinuthia','Captured and approved onsite claim','claim',4,'Claim #4 for Item #13 — Christopher','::1','2026-09-04 07:35:40'),(24,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:36:07'),(25,12,'staff','Mathew Chacha','Issued Item','item',13,'student id to Christopher','::1','2026-09-04 07:36:23'),(26,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:38:34'),(27,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:45:53'),(28,12,'staff','Mathew Chacha','Reported lost item',NULL,NULL,'Item #14 book','::1','2026-09-04 07:46:16'),(29,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 07:48:45'),(30,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:49:57'),(31,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 07:53:12'),(32,12,'staff','Mathew Chacha','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-04 14:16:07'),(33,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 14:16:19'),(34,5,'student','Ivy Kairu','Reported found item','item',15,'Item #15 Student id card','::1','2026-09-04 14:16:40'),(35,5,'student','Ivy Kairu','Reported lost item','item',16,'Item #16 book','::1','2026-09-04 14:16:55'),(36,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 14:35:55'),(37,14,'student','Peter Otieno','Created user account','user',14,'Student account registered.','::1','2026-09-04 14:37:00'),(38,14,'student','Peter Otieno','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 14:37:04'),(39,14,'student','Peter Otieno','Reported found item','item',17,'Item #17 book','::1','2026-09-04 14:41:36'),(40,14,'student','Peter Otieno','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 14:41:41'),(41,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 14:41:46'),(42,12,'staff','Mathew Chacha','Reported lost item','item',18,'Item #18 pen','::1','2026-09-04 14:42:09'),(43,12,'staff','Mathew Chacha','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-04 15:43:11'),(44,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 15:48:05'),(45,5,'student','Ivy Kairu','Reported Found Item','item',19,'Item #19 - book','::1','2026-09-04 15:49:36'),(46,5,'student','Ivy Kairu','Reported Found Item','item',20,'Item #20 - BOOJ','::1','2026-09-04 15:49:58'),(47,5,'student','Ivy Kairu','Reported Lost Item','item',21,'Item #21 - BOOK','::1','2026-09-04 15:50:21'),(48,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 15:50:23'),(49,14,'student','Peter Otieno','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 15:50:35'),(50,14,'student','Peter Otieno','Reported Found Item','item',22,'Item #22 - BOOK','::1','2026-09-04 15:50:43'),(51,14,'student','Peter Otieno','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 15:50:47'),(52,14,'student','Peter Otieno','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 15:50:52'),(53,14,'student','Peter Otieno','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 15:51:47'),(54,1,'admin','System Admin','Logged in',NULL,NULL,'Administrator login successful.','::1','2026-09-04 15:51:54'),(55,1,'admin','System Admin','Approved User Found Report','item',22,'Item #22','::1','2026-09-04 15:52:05'),(56,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 15:52:17'),(57,5,'student','Ivy Kairu','Reported Lost Item','item',23,'Item #23 - SILVER NECKLACE','::1','2026-09-04 15:52:53'),(58,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 15:52:56'),(59,14,'student','Peter Otieno','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 15:53:03'),(60,14,'student','Peter Otieno','Reported Found Item','item',24,'Item #24 - SILVER NECKLACE','::1','2026-09-04 15:53:09'),(61,14,'student','Peter Otieno','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 15:53:10'),(62,1,'admin','System Admin','Logged in',NULL,NULL,'Administrator login successful.','::1','2026-09-04 15:53:18'),(63,1,'admin','System Admin','Approved User Found Report','item',24,'Item #24','::1','2026-09-04 15:53:23'),(64,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 15:53:33'),(65,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 15:58:27'),(66,13,'staff','John Wekesa','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 15:58:32'),(67,13,'staff','John Wekesa','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-04 16:36:29'),(68,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 16:36:37'),(69,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 16:37:00'),(70,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 16:37:09'),(71,5,'student','Ivy Kairu','Reported Found Item','item',25,'Item #25 - BOOK','::1','2026-09-04 16:37:58'),(72,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 16:38:43'),(73,11,'staff','Jeremy Kinuthia','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 16:38:48'),(74,11,'staff','Jeremy Kinuthia','Reported Lost Item','item',26,'Item #26 - bata shoes','::1','2026-09-04 16:39:05'),(75,11,'staff','Jeremy Kinuthia','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-04 16:39:20'),(76,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-04 16:39:24'),(77,12,'staff','Mathew Chacha','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-04 16:40:13'),(78,15,'student','Mark Kipchumba','Created user account','user',15,'Student account registered.','::1','2026-09-04 16:42:44'),(79,15,'student','Mark Kipchumba','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 16:42:51'),(80,15,'student','Mark Kipchumba','Logged out',NULL,NULL,'Student logout.','::1','2026-09-04 16:43:16'),(81,16,'visitor','Belinda Moraa','Created user account','user',16,'Visitor account registered.','::1','2026-09-04 16:43:58'),(82,16,'visitor','Belinda Moraa','Logged in',NULL,NULL,'Visitor login successful.','::1','2026-09-04 16:44:02'),(83,16,'visitor','Belinda Moraa','Logged out',NULL,NULL,'Visitor logout.','::1','2026-09-04 16:44:18'),(84,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-04 16:44:29'),(85,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-07 13:28:51'),(86,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-07 13:28:58'),(87,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-07 13:29:53'),(88,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-07 13:30:01'),(89,12,'staff','Mathew Chacha','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-07 13:30:22'),(90,1,'admin','System Admin','Logged in',NULL,NULL,'Administrator login successful.','::1','2026-09-07 13:30:27'),(91,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-12 13:06:32'),(92,1,'admin','System Admin','Captured and approved onsite claim','claim',5,'Claim #5 for Item #26 — Cynthia Nderitu','::1','2026-09-14 14:51:55'),(93,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-14 15:03:17'),(94,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-14 15:03:25'),(95,12,'staff','Mathew Chacha','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-14 15:03:53'),(96,17,'visitor','Mark Juma','Created user account','user',17,'Visitor account registered.','::1','2026-09-14 15:04:45'),(97,17,'visitor','Mark Juma','Logged in',NULL,NULL,'Visitor login successful.','::1','2026-09-14 15:04:53'),(98,17,'visitor','Mark Juma','Logged out',NULL,NULL,'Visitor logout.','::1','2026-09-14 15:04:57'),(99,1,'admin','System Admin','Logged in',NULL,NULL,'Administrator login successful.','::1','2026-09-14 15:05:04'),(100,1,'admin','System Admin','Added staff',NULL,NULL,'Kelvin Letoo (01902)','::1','2026-09-14 15:05:45'),(101,1,'admin','System Admin','Removed Staff','staff',18,'Kelvin Letoo (01902)','::1','2026-09-14 15:05:52'),(102,1,'admin','System Admin','Added staff',NULL,NULL,'John Wekesa (9309)','::1','2026-09-14 15:06:15'),(103,1,'admin','System Admin','Removed Staff','staff',19,'John Wekesa (9309)','::1','2026-09-14 15:06:22'),(104,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-14 16:36:08'),(105,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-14 18:44:18'),(106,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-14 18:44:22'),(107,5,'student','Ivy Kairu','Reported Lost Item','item',27,'Item #27 - student id','::1','2026-09-14 18:48:21'),(108,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-14 18:48:38'),(109,3,'student','Cynthia Nderitu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-14 18:48:52'),(110,3,'student','Cynthia Nderitu','Reported Found Item','item',28,'Item #28 - student id','::1','2026-09-14 18:49:11'),(111,3,'student','Cynthia Nderitu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-14 18:49:20'),(112,12,'staff','Mathew Chacha','Logged in',NULL,NULL,'Staff login successful.','::1','2026-09-14 18:49:26'),(113,12,'staff','Mathew Chacha','Approved User Found Report','item',28,'Item #28 - student id','::1','2026-09-14 18:49:37'),(114,12,'staff','Mathew Chacha','Logged out',NULL,NULL,'Staff logout.','::1','2026-09-14 18:49:46'),(115,5,'student','Ivy Kairu','Logged in',NULL,NULL,'Student login successful.','::1','2026-09-14 18:49:52'),(116,5,'student','Ivy Kairu','Logged out',NULL,NULL,'Student logout.','::1','2026-09-14 18:50:18'),(117,1,'admin','System Admin','Logged in',NULL,NULL,'Administrator login successful.','::1','2026-09-14 18:50:35');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'System Admin','admin@cuea.edu','$2y$12$LUrV325bFLH8Q3T/I8UtAO4DEff8fvstC5zZxOQrXXFgtaJIX2P1.','2026-08-11 11:50:30');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `claims`
--

DROP TABLE IF EXISTS `claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `claims` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `claimant_type` enum('student','staff','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `claimant_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `claimant_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `claimant_phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_passport_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `police_abstract_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_notes` text COLLATE utf8mb4_unicode_ci,
  `verified_onsite` tinyint(1) NOT NULL DEFAULT '0',
  `captured_by_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `captured_by_id` int DEFAULT NULL,
  `captured_by_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','issued') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed_by_admin_id` int DEFAULT NULL,
  `reviewed_by_staff_id` int DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `issued_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `user_id` (`user_id`),
  KEY `fk_claims_reviewed_by_admin` (`reviewed_by_admin_id`),
  KEY `fk_claims_reviewed_by_staff` (`reviewed_by_staff_id`),
  CONSTRAINT `claims_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `claims_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_claims_reviewed_by_admin` FOREIGN KEY (`reviewed_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_claims_reviewed_by_staff` FOREIGN KEY (`reviewed_by_staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `claims`
--

LOCK TABLES `claims` WRITE;
/*!40000 ALTER TABLE `claims` DISABLE KEYS */;
INSERT INTO `claims` VALUES (1,4,5,NULL,'Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'has my id registration number','issued','2026-09-01 09:26:40',NULL,NULL,'2026-09-01 12:26:51','2026-09-03 13:22:05'),(2,9,NULL,'other','Matu','essyc14@gmail.com','082083004','39813176',NULL,NULL,'Male','dd','evidence/evidence_6a985eb6819468.61985651.jpg',NULL,1,'staff',11,'Jeremy Kinuthia','Onsite claim captured with required identity and Police Abstract evidence.','issued','2026-09-02 17:36:54',NULL,11,'2026-09-02 20:37:35','2026-09-02 20:55:04'),(3,11,NULL,'student','Cynthia Nderitu','essycynthia7@gmail.com','0717077768','39813176','1048008',NULL,'Female',NULL,'evidence/evidence_6a994a41149711.44643402.jpg',NULL,1,'staff',13,'John Wekesa','Onsite claim captured with required identity and Police Abstract evidence.','issued','2026-09-03 10:21:53',NULL,13,'2026-09-03 13:21:53','2026-09-03 13:22:02'),(4,13,NULL,'other','Christopher','essyc14@gmail.com','073873892','1898987',NULL,NULL,'Male','8383','evidence/evidence_6a9a74ccc30e07.86911819.jpg',NULL,1,'staff',11,'Jeremy Kinuthia','Onsite claim captured with required identity and Police Abstract evidence.','issued','2026-09-04 07:35:40',NULL,11,'2026-09-04 10:35:40','2026-09-04 10:36:23'),(5,26,NULL,'student','Cynthia Nderitu','essycynthia7@gmail.com','0717077768','39813176','1048008',NULL,'Female','dd','evidence/evidence_6aa80a0b691571.23944540.pdf',NULL,1,'admin',1,'System Admin','Onsite claim captured with required identity and Police Abstract evidence.','approved','2026-09-14 14:51:55',1,NULL,'2026-09-14 17:51:55',NULL);
/*!40000 ALTER TABLE `claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issued_items`
--

DROP TABLE IF EXISTS `issued_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `issued_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `claim_id` int NOT NULL,
  `issued_to_user_id` int DEFAULT NULL,
  `issued_to_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_by_staff_id` int DEFAULT NULL,
  `issued_by_admin_id` int DEFAULT NULL,
  `issued_by_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issuer_role` enum('staff','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_notes` text COLLATE utf8mb4_unicode_ci,
  `issued_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_claim_issue` (`claim_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issued_items`
--

LOCK TABLES `issued_items` WRITE;
/*!40000 ALTER TABLE `issued_items` DISABLE KEYS */;
INSERT INTO `issued_items` VALUES (1,9,2,NULL,'Matu',12,NULL,'Mathew Chacha','staff','n/a','2026-09-02 17:55:04'),(2,11,3,NULL,'Cynthia Nderitu',13,NULL,'John Wekesa','staff','n/a','2026-09-03 10:22:02'),(3,4,1,NULL,'Ivy Kairu',13,NULL,'John Wekesa','staff','n/a','2026-09-03 10:22:05'),(4,13,4,NULL,'Christopher',12,NULL,'Mathew Chacha','staff','issue apporved','2026-09-04 07:36:23');
/*!40000 ALTER TABLE `issued_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `item_type` enum('lost','found') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_reported` date NOT NULL,
  `reporter_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporter_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporter_phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporter_reg_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','claimed','rejected','issued') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed_by_admin_id` int DEFAULT NULL,
  `reviewed_by_staff_id` int DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `matched_lost_item_id` int DEFAULT NULL,
  `reporter_staff_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_matched_lost_item_id` (`matched_lost_item_id`),
  CONSTRAINT `fk_items_matched_lost` FOREIGN KEY (`matched_lost_item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,1,'lost','Student Card','student id','cuea student id','Other','2026-08-02','visitor','student@cuea.edu','0717077768',NULL,'item_6a8816210724c5.91645724.jpg',NULL,NULL,NULL,NULL,'approved','2026-08-21 09:10:57',NULL,NULL,NULL,NULL,NULL),(2,NULL,'lost','ID Card','Student id card','name :Cynthia Nderitu','Main Gate','2026-08-04','Cynthia Nderitu','essycynthia7@gmail.com','0717077768','1048008',NULL,NULL,NULL,NULL,NULL,'approved','2026-09-01 08:35:50',1,NULL,'2026-09-01 11:49:34',NULL,NULL),(3,5,'found','ID Card','Cynthia id card','had her nam','Main Gate','2026-09-01','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-01 09:13:13',NULL,7,NULL,NULL,NULL),(4,NULL,'found','ID Card','Kiaru id','kiaru','Library','2026-07-29','Essy Cynthia','essyc14@gmail.com','0717077768',NULL,NULL,NULL,NULL,NULL,NULL,'issued','2026-09-01 09:25:33',NULL,7,NULL,NULL,NULL),(5,NULL,'lost','Laptop','hp','hp','Hostels','2026-08-05','Nderitu','essyc14@gmail.com','0717077768',NULL,NULL,'hp','hp','hp','`82093898712','approved','2026-09-01 09:26:00',NULL,7,NULL,NULL,NULL),(6,NULL,'lost','ID Card','CUEA ID CARD','NAME : PHIL MUTISO','Library','2026-09-02','John Chacha','chacha@gmail.com','079797970',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-01 09:42:40',NULL,9,NULL,NULL,NULL),(7,5,'found','ID Card','id','id','Cafeteria','2026-09-01','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-01 09:53:52',1,NULL,'2026-09-01 12:56:08',NULL,NULL),(8,5,'found','Books','book','eih3','Main Gate','2026-08-31','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,'item_6a985c922ae8b4.04360326.jpg',NULL,NULL,NULL,NULL,'pending','2026-09-02 17:27:46',NULL,NULL,NULL,NULL,NULL),(9,11,'lost','Books','book','dbnjeb','Library','2026-09-01','Jeremy Kinuthia','jk@cuea.edu','0717078769',NULL,'item_6a985cf0a0bc77.02727499.jpg',NULL,NULL,NULL,NULL,'issued','2026-09-02 17:29:20',NULL,NULL,NULL,NULL,'01012'),(10,11,'lost','Books','ma xmnk','sadsf','Library','2026-08-31','Jeremy Kinuthia','jk@cuea.edu','0717078769',NULL,'item_6a985f38634c97.68829048.jpg',NULL,NULL,NULL,NULL,'approved','2026-09-02 17:39:04',NULL,NULL,NULL,NULL,'01012'),(11,12,'lost','Identification Documents','hdjdh','maseno','Library','2026-08-31','Mathew Chacha','chacha@gmail.com','0718077907',NULL,'item_6a986425af5a28.45144958.jpg',NULL,NULL,NULL,NULL,'issued','2026-09-02 18:00:05',NULL,NULL,NULL,NULL,'0101'),(12,13,'lost','Documents','cv','','Library','2026-09-01','John Wekesa','john@gmail.com','07873973',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-03 10:19:44',NULL,NULL,NULL,NULL,'01014'),(13,5,'found','Identification Documents','student id','','Library','2026-09-03','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,'item_6a9a7345a64328.81638638.jpg',NULL,NULL,NULL,NULL,'issued','2026-09-04 07:29:09',NULL,12,NULL,1,NULL),(14,12,'lost','Books','book','','Library','2026-09-02','Mathew Chacha','chacha@gmail.com','0718077907',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-04 07:46:16',NULL,NULL,NULL,NULL,'0101'),(15,5,'found','Identification Documents','Student id card','','Main Gate','2026-09-02','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'pending','2026-09-04 14:16:40',NULL,NULL,NULL,2,NULL),(16,5,'lost','Books','book','','Library','2026-09-03','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-04 14:16:55',NULL,NULL,NULL,NULL,NULL),(17,14,'found','Books','book','','Library','2026-09-01','Peter Otieno','petero@gmail.com','0717043765','1049839',NULL,NULL,NULL,NULL,NULL,'pending','2026-09-04 14:41:36',NULL,NULL,NULL,16,NULL),(18,12,'lost','Accessories','pen','','Library','2026-09-01','Mathew Chacha','chacha@gmail.com','0718077907',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-04 14:42:09',NULL,NULL,NULL,NULL,'0101'),(19,5,'found','Books','book',NULL,'Library','2026-09-02','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'pending','2026-09-04 15:49:36',NULL,NULL,NULL,16,NULL),(20,5,'found','Books','BOOJ',NULL,'Library','2026-09-02','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'pending','2026-09-04 15:49:58',NULL,NULL,NULL,NULL,NULL),(21,5,'lost','Books','BOOK',NULL,'Chapel','2026-09-02','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-04 15:50:21',NULL,NULL,NULL,NULL,NULL),(22,14,'found','Books','BOOK',NULL,'Hostels','2026-09-02','Peter Otieno','petero@gmail.com','0717043765','1049839',NULL,NULL,NULL,NULL,NULL,'approved','2026-09-04 15:50:43',1,NULL,NULL,21,NULL),(23,5,'lost','Accessories','SILVER NECKLACE',NULL,'Main Gate','2026-09-03','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-04 15:52:53',NULL,NULL,NULL,NULL,NULL),(24,14,'found','Accessories','SILVER NECKLACE',NULL,'Hostels','2026-09-03','Peter Otieno','petero@gmail.com','0717043765','1049839',NULL,NULL,NULL,NULL,NULL,'approved','2026-09-04 15:53:09',1,NULL,NULL,23,NULL),(25,5,'found','Books','BOOK',NULL,'Cafeteria','2026-09-03','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'pending','2026-09-04 16:37:58',NULL,NULL,NULL,21,NULL),(26,11,'lost','Clothing','bata shoes',NULL,'Main Gate','2026-09-02','Jeremy Kinuthia','jk@cuea.edu','0717078769',NULL,NULL,NULL,NULL,NULL,NULL,'claimed','2026-09-04 16:39:05',NULL,NULL,NULL,NULL,'01012'),(27,5,'lost','Identification Documents','student id',NULL,'Library','2026-09-12','Ivy Kairu','ivyk@gmail.com','0728995475',NULL,NULL,NULL,NULL,NULL,NULL,'approved','2026-09-14 18:48:21',NULL,NULL,NULL,NULL,NULL),(28,3,'found','Identification Documents','student id',NULL,'Library','2026-09-13','Cynthia Nderitu','essycynthia7@gmail.com','','1048008',NULL,NULL,NULL,NULL,NULL,'approved','2026-09-14 18:49:11',NULL,12,NULL,27,NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications_log`
--

DROP TABLE IF EXISTS `notifications_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipient_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_line` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_status` enum('queued','sent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'queued',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications_log`
--

LOCK TABLES `notifications_log` WRITE;
/*!40000 ALTER TABLE `notifications_log` DISABLE KEYS */;
INSERT INTO `notifications_log` VALUES (1,'essycynthia7@gmail.com','Welcome to CUEA Lost & Found','Your account has been created successfully.','queued','2026-08-11 11:51:56'),(2,'student@cuea.edu','Lost item report received','Your lost item report has been received by CUEA Lost & Found and is awaiting admin review.','queued','2026-08-21 09:10:57'),(3,'student@cuea.edu','Item report approved','Your report for \'student id\' has been approved and is now visible on the portal.','queued','2026-08-21 09:12:21'),(4,'essycynthia7@gmail.com','Item report received','Your lost item report has been registered and is awaiting review.','queued','2026-09-01 08:35:50'),(5,'ivyk@gmail.com','Item report received','Your found item report has been registered and is awaiting review.','queued','2026-09-01 09:13:13'),(6,'essyc14@gmail.com','Item report received','Your found item report has been registered and is awaiting review.','queued','2026-09-01 09:25:33'),(7,'essyc14@gmail.com','Item report received','Your lost item report has been registered and is awaiting review.','queued','2026-09-01 09:26:00'),(8,'ivyk@gmail.com','Claim request submitted','Your claim request for \'Kiaru id\' is pending review.','queued','2026-09-01 09:26:40'),(9,'ivyk@gmail.com','Claim approved','Your claim for \'Kiaru id\' has been approved. Please proceed to the issuing desk with identification.','queued','2026-09-01 09:26:51'),(10,'chacha@gmail.com','Item report received','Your lost item report has been registered and is awaiting review.','queued','2026-09-01 09:42:40'),(11,'ivyk@gmail.com','Item report received','Your found item report has been registered and is awaiting review.','queued','2026-09-01 09:53:52');
/*!40000 ALTER TABLE `notifications_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_passport_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('student','visitor','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_report_items` tinyint(1) NOT NULL DEFAULT '0',
  `can_issue_items` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `uq_users_staff_no` (`staff_no`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Demo Student','student@cuea.edu','CUEA/ST/001',NULL,NULL,NULL,NULL,NULL,'student','$2y$12$FPKF6KJTVivUw0mBOgoEeOaACm1s.X46B2dvjrqkQLspvhcQLPkX.',0,0,'2026-08-11 11:50:30'),(3,'Cynthia Nderitu','essycynthia7@gmail.com','1048008',NULL,NULL,NULL,NULL,NULL,'student','$2y$10$nVn/KLwsOkRADag8JZd8j.EuTFYEDdVYlOs3KTM/QvOd9eOVpbjO2',0,0,'2026-08-11 11:51:56'),(4,'Cynthia Nderitu','student2@cuea.edu','1048009',NULL,NULL,NULL,NULL,NULL,'student','$2y$10$PDyOmEYQ11Uci52yRqvT3eBx1eONN2hB7qjW3LqMebmVhZkleH3Ga',0,0,'2026-08-29 08:29:26'),(5,'Ivy Kairu','ivyk@gmail.com',NULL,NULL,'0728995475','Female','Doni','39813176','student','$2y$10$oz1c4PYqlkUoIwVKy6E7fON1sm223QPvoTrbwgOu2J34agw7VAXdy',0,0,'2026-09-01 08:29:39'),(11,'Jeremy Kinuthia','jk@cuea.edu',NULL,'01012','0717078769','Male','5490','39813176','staff','$2y$10$9U7Iki.VNdxhpYvLfdi/Be.6a9Ep3A6MZpdTKeka/sfGWpChOIxsW',1,0,'2026-09-02 17:12:26'),(12,'Mathew Chacha','chacha@gmail.com',NULL,'0101','0718077907','Male','5490','1898987','staff','$2y$10$80qLc5cya4jbH2DjKq/t1OmLKU0NPDPRsRT6hMhD85pqYChFVbG4m',1,1,'2026-09-02 17:52:51'),(13,'John Wekesa','john@gmail.com',NULL,'01014','07873973','Female','5490','13938023','staff','$2y$10$H9pzNIGmi4DiGlETlDEpgOX5kArbRcKDu9BRcLVlt4fXzgC/xjNTi',1,1,'2026-09-03 10:17:42'),(14,'Peter Otieno','petero@gmail.com','1049839',NULL,'0717043765','Male','5490','1898980','student','$2y$10$GAFcFeYo7/MNpcTzYeWBO.nwybNgRZxLecwf24BUJiBijob8mMHy.',0,0,'2026-09-04 14:37:00'),(15,'Mark Kipchumba','chumba@gmail.com','1035678',NULL,'0766767767','Male','doni','379987999','student','$2y$10$e2GQpTBCBhYDqjuOcrQLquH4dfdWTSYOAfupmIf5uy2jS2dr6RdGO',0,0,'2026-09-04 16:42:44'),(16,'Belinda Moraa','moraa@gmail.com',NULL,NULL,'0789009890','Female','kayole','28998990','visitor','$2y$10$IBX/J6j99aA0hWlDhbSS4epCfxZXcg70oMpkufn7ehaM6tCxf.QRK',0,0,'2026-09-04 16:43:58'),(17,'Mark Juma','juma@gmail.com',NULL,NULL,'0787329397','Prefer not to say','5490','20893088','visitor','$2y$10$Cbxh/K1T0CTYPcTTKZgDPO9pw1o0MjnFIATLtqbAXf0Y/s3mrt4bK',0,0,'2026-09-14 15:04:45');
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

-- Dump completed on 2026-09-25 13:35:00
