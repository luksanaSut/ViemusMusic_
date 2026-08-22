-- MariaDB dump 10.19  Distrib 10.4.21-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: viemus_music
-- ------------------------------------------------------
-- Server version	10.4.21-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `app_notifications`
--

DROP TABLE IF EXISTS `app_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `recipient_role` enum('admin','teacher','student','guardian') COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_notifications_recipient_role_recipient_id_is_read_index` (`recipient_role`,`recipient_id`,`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_notifications`
--

LOCK TABLES `app_notifications` WRITE;
/*!40000 ALTER TABLE `app_notifications` DISABLE KEYS */;
INSERT INTO `app_notifications` VALUES (1,'admin',NULL,'คำขอเรียนชดเชยใหม่ (รอ 2 ฝ่ายอนุมัติ)','กัญญารัตน์ ศรีสุข ขอเรียนชดเชยวันที่ 01/09/2026  (แจ้งโดย: กัญญารัตน์ ศรีสุข)','http://localhost:8000/makeup-requests/1',0,'2026-08-16 09:05:21','2026-08-16 09:05:21'),(2,'teacher',3,'คำขอสอนชดเชยรออนุมัติจากคุณ','กัญญารัตน์ ศรีสุข ขอเรียนชดเชยวันที่ 01/09/2026 14:02-15:02','http://localhost:8000/makeup-requests/1',0,'2026-08-16 09:05:21','2026-08-16 09:05:21'),(3,'student',4,'ความคืบหน้าคำขอเรียนชดเชย','อาจารย์ผู้สอนชดเชยอนุมัติคำขอเรียนชดเชยวันที่ 01/09/2026 แล้ว กำลังรออีกฝ่ายอนุมัติ','http://localhost:8000/my-leaves',0,'2026-08-16 09:06:56','2026-08-16 09:06:56'),(4,'guardian',5,'ความคืบหน้าคำขอเรียนชดเชย','อาจารย์ผู้สอนชดเชยอนุมัติคำขอเรียนชดเชยวันที่ 01/09/2026 แล้ว กำลังรออีกฝ่ายอนุมัติ','http://localhost:8000/my-leaves',0,'2026-08-16 09:06:56','2026-08-16 09:06:56'),(5,'admin',NULL,'จัดตารางเรียนชดเชยสำเร็จ','คำขอเรียนชดเชยของ กัญญารัตน์ ศรีสุข ได้รับอนุมัติครบ 2 ฝ่าย และจัดตารางเรียนให้แล้ว','http://localhost:8000/makeup-requests/1',0,'2026-08-16 09:07:26','2026-08-16 09:07:26'),(6,'student',4,'จัดตารางเรียนชดเชยให้แล้ว','คำขอเรียนชดเชยได้รับอนุมัติครบทุกฝ่ายแล้ว จัดตารางเรียนวันที่ 01/09/2026 14:02:00-15:02:00 ให้เรียบร้อย','http://localhost:8000/my-leaves',0,'2026-08-16 09:07:26','2026-08-16 09:07:26'),(7,'guardian',5,'จัดตารางเรียนชดเชยให้แล้ว','คำขอเรียนชดเชยได้รับอนุมัติครบทุกฝ่ายแล้ว จัดตารางเรียนวันที่ 01/09/2026 14:02:00-15:02:00 ให้เรียบร้อย','http://localhost:8000/my-leaves',0,'2026-08-16 09:07:26','2026-08-16 09:07:26'),(8,'student',4,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 16/08/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',0,'2026-08-16 09:57:06','2026-08-16 09:57:06'),(9,'guardian',5,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 16/08/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',0,'2026-08-16 09:57:06','2026-08-16 09:57:06');
/*!40000 ALTER TABLE `app_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897','i:10;',1786904665),('laravel-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer','i:1786904665;',1786904665),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:17;',1786907418),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1786907418;',1786907418),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:4;',1786905133),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1786905133;',1786905133),('laravel-cache-902ba3cda1883801594b6e1b452790cc53948fda','i:3;',1786782565),('laravel-cache-902ba3cda1883801594b6e1b452790cc53948fda:timer','i:1786782565;',1786782565),('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4','i:6;',1786807999),('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer','i:1786807999;',1786807999),('laravel-cache-b1d5781111d84f7b3fe45a0852e59758cd7a87e5','i:4;',1786905143),('laravel-cache-b1d5781111d84f7b3fe45a0852e59758cd7a87e5:timer','i:1786905143;',1786905143),('laravel-cache-fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f','i:6;',1786788477),('laravel-cache-fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f:timer','i:1786788477;',1786788477);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
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
-- Table structure for table `class_schedules`
--

DROP TABLE IF EXISTS `class_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `room_id` bigint(20) unsigned DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `delivery_mode` enum('onsite','online','hybrid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'onsite',
  `status` enum('scheduled','completed','cancelled','no_show') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_schedules_enrollment_id_foreign` (`enrollment_id`),
  KEY `class_schedules_schedule_date_status_index` (`schedule_date`,`status`),
  KEY `class_schedules_teacher_id_index` (`teacher_id`),
  KEY `class_schedules_room_id_index` (`room_id`),
  CONSTRAINT `class_schedules_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `class_schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_schedules`
--

LOCK TABLES `class_schedules` WRITE;
/*!40000 ALTER TABLE `class_schedules` DISABLE KEYS */;
INSERT INTO `class_schedules` VALUES (1,1,3,2,'2026-08-17','10:42:00','11:42:00','onsite','cancelled',NULL,'ผู้ดูแลระบบ','2026-08-15 08:43:03','2026-08-15 08:43:51'),(2,1,3,2,'2026-08-18','10:43:00','11:43:00','onsite','cancelled',NULL,'ผู้ดูแลระบบ','2026-08-15 08:43:36','2026-08-16 08:45:35'),(3,1,3,2,'2026-08-18','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(4,1,3,2,'2026-08-25','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(5,1,3,2,'2026-09-01','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(6,1,3,2,'2026-09-08','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(7,1,3,2,'2026-09-15','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(8,1,3,2,'2026-09-22','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(9,1,3,2,'2026-09-29','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(10,1,3,2,'2026-10-06','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(11,1,3,2,'2026-10-13','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(12,1,3,2,'2026-10-20','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(13,1,3,2,'2026-10-27','10:45:00','12:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 08:47:14'),(14,1,3,2,'2026-08-16','10:45:00','12:45:00','onsite','completed',NULL,'ผู้ดูแลระบบ','2026-08-16 08:47:14','2026-08-16 09:57:06'),(15,1,3,NULL,'2026-09-01','14:02:00','15:02:00','onsite','cancelled','คาบเรียนชดเชย (Makeup Class) จากคำขอ #1','ระบบ (อนุมัติเรียนชดเชยครบ 2 ฝ่าย)','2026-08-16 09:07:26','2026-08-16 09:46:36');
/*!40000 ALTER TABLE `class_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `discount_value` decimal(10,2) NOT NULL,
  `max_uses` int(10) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `applies_to_all_courses` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_coupon`
--

DROP TABLE IF EXISTS `course_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_coupon` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `coupon_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_coupon_course_id_coupon_id_unique` (`course_id`,`coupon_id`),
  KEY `course_coupon_coupon_id_foreign` (`coupon_id`),
  CONSTRAINT `course_coupon_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_coupon_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_coupon`
--

LOCK TABLES `course_coupon` WRITE;
/*!40000 ALTER TABLE `course_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_evaluation_items`
--

DROP TABLE IF EXISTS `course_evaluation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_evaluation_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_evaluation_id` bigint(20) unsigned NOT NULL,
  `evaluation_category_id` bigint(20) unsigned NOT NULL,
  `score` tinyint(3) unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_eval_items_unique` (`course_evaluation_id`,`evaluation_category_id`),
  KEY `course_evaluation_items_evaluation_category_id_foreign` (`evaluation_category_id`),
  CONSTRAINT `course_evaluation_items_course_evaluation_id_foreign` FOREIGN KEY (`course_evaluation_id`) REFERENCES `course_evaluations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_evaluation_items_evaluation_category_id_foreign` FOREIGN KEY (`evaluation_category_id`) REFERENCES `evaluation_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_evaluation_items`
--

LOCK TABLES `course_evaluation_items` WRITE;
/*!40000 ALTER TABLE `course_evaluation_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_evaluation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_evaluations`
--

DROP TABLE IF EXISTS `course_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint(20) unsigned NOT NULL,
  `overall_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `evaluated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evaluated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_evaluations_enrollment_id_unique` (`enrollment_id`),
  CONSTRAINT `course_evaluations_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_evaluations`
--

LOCK TABLES `course_evaluations` WRITE;
/*!40000 ALTER TABLE `course_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_evaluations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_teacher`
--

DROP TABLE IF EXISTS `course_teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_teacher` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_teacher_course_id_teacher_id_unique` (`course_id`,`teacher_id`),
  KEY `course_teacher_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `course_teacher_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_teacher_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_teacher`
--

LOCK TABLES `course_teacher` WRITE;
/*!40000 ALTER TABLE `course_teacher` DISABLE KEYS */;
INSERT INTO `course_teacher` VALUES (1,1,6,'2026-08-09 10:47:39','2026-08-09 10:47:39'),(2,1,2,'2026-08-09 10:47:39','2026-08-09 10:47:39'),(3,2,2,'2026-08-10 10:04:24','2026-08-10 10:04:24'),(4,2,1,'2026-08-10 10:04:24','2026-08-10 10:04:24'),(5,2,3,'2026-08-10 10:04:24','2026-08-10 10:04:24'),(6,3,6,'2026-08-10 10:46:46','2026-08-10 10:46:46'),(7,3,1,'2026-08-10 10:46:46','2026-08-10 10:46:46'),(8,4,3,'2026-08-10 10:48:08','2026-08-10 10:48:08'),(9,5,5,'2026-08-10 10:50:38','2026-08-10 10:50:38'),(10,6,6,'2026-08-10 10:52:39','2026-08-10 10:52:39'),(11,6,2,'2026-08-10 10:52:39','2026-08-10 10:52:39'),(12,6,1,'2026-08-10 10:52:39','2026-08-10 10:52:39'),(13,6,3,'2026-08-10 10:52:39','2026-08-10 10:52:39');
/*!40000 ALTER TABLE `course_teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_transfers`
--

DROP TABLE IF EXISTS `course_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transfer_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `old_enrollment_id` bigint(20) unsigned NOT NULL,
  `old_course_id` bigint(20) unsigned NOT NULL,
  `new_course_id` bigint(20) unsigned NOT NULL,
  `new_teacher_id` bigint(20) unsigned DEFAULT NULL,
  `old_course_remaining_value` decimal(10,2) NOT NULL,
  `new_course_price` decimal(10,2) NOT NULL,
  `teacher_change_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_difference` decimal(10,2) NOT NULL,
  `payment_status` enum('not_required','pending_payment','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_required',
  `credit_issued` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending_payment','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `new_enrollment_id` bigint(20) unsigned DEFAULT NULL,
  `payment_id` bigint(20) unsigned DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transferred_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_transfers_transfer_no_unique` (`transfer_no`),
  KEY `course_transfers_old_enrollment_id_foreign` (`old_enrollment_id`),
  KEY `course_transfers_old_course_id_foreign` (`old_course_id`),
  KEY `course_transfers_new_course_id_foreign` (`new_course_id`),
  KEY `course_transfers_new_teacher_id_foreign` (`new_teacher_id`),
  KEY `course_transfers_new_enrollment_id_foreign` (`new_enrollment_id`),
  KEY `course_transfers_payment_id_foreign` (`payment_id`),
  KEY `course_transfers_student_id_status_index` (`student_id`,`status`),
  CONSTRAINT `course_transfers_new_course_id_foreign` FOREIGN KEY (`new_course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_transfers_new_enrollment_id_foreign` FOREIGN KEY (`new_enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `course_transfers_new_teacher_id_foreign` FOREIGN KEY (`new_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `course_transfers_old_course_id_foreign` FOREIGN KEY (`old_course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_transfers_old_enrollment_id_foreign` FOREIGN KEY (`old_enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_transfers_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `course_transfers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_transfers`
--

LOCK TABLES `course_transfers` WRITE;
/*!40000 ALTER TABLE `course_transfers` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_transfers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `structure_type` enum('regular','special') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `class_type` enum('private','group','special_activity') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'private',
  `delivery_mode` enum('onsite','online','hybrid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'onsite',
  `activity_type` enum('camp','workshop','master_class') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instrument_id` bigint(20) unsigned DEFAULT NULL,
  `level_id` bigint(20) unsigned DEFAULT NULL,
  `total_sessions` int(10) unsigned DEFAULT NULL,
  `days_count` int(10) unsigned DEFAULT NULL,
  `hours_per_day` decimal(4,1) DEFAULT NULL,
  `duration_months` int(10) unsigned DEFAULT NULL,
  `course_start_date` date DEFAULT NULL,
  `course_end_date` date DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `learning_format` enum('individual','group','online','hybrid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'individual',
  `max_students` int(10) unsigned DEFAULT NULL,
  `allow_makeup_class` tinyint(1) NOT NULL DEFAULT 1,
  `emergency_leave_quota` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `is_adult_flexi` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_course_code_unique` (`course_code`),
  KEY `courses_instrument_id_foreign` (`instrument_id`),
  KEY `courses_level_id_foreign` (`level_id`),
  KEY `courses_is_active_learning_format_index` (`is_active`,`learning_format`),
  CONSTRAINT `courses_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `courses_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'WWW','regular','private','onsite',NULL,'wwww','ww',NULL,5,1,3,NULL,NULL,5,NULL,NULL,12333.00,'individual',NULL,1,1,0,1,'2026-08-09 10:47:39','2026-08-10 10:32:13','2026-08-10 10:32:13'),(2,'WWW1','regular','group','onsite',NULL,'dd',NULL,NULL,9,2,10,NULL,NULL,6,NULL,NULL,20000.00,'individual',2,1,1,0,1,'2026-08-10 10:04:24','2026-08-10 10:32:11','2026-08-10 10:32:11'),(3,'PV-PNO-001','regular','private','onsite','master_class','เปียโนพื้นฐานสำหรับผู้เริ่มต้น','เรียนเปียโนแบบตัวต่อตัว ตั้งแต่พื้นฐาน การอ่านโน้ต การวางนิ้ว และการเล่นเพลงเบื้องต้น เหมาะสำหรับผู้เริ่มต้น',NULL,1,1,12,NULL,NULL,3,NULL,NULL,14400.00,'individual',NULL,1,1,0,1,'2026-08-10 10:46:46','2026-08-10 10:46:46',NULL),(4,'GR-GTR-001','regular','group','onsite',NULL,'กีตาร์พื้นฐานสำหรับเด็ก','เรียนกีตาร์แบบกลุ่ม ฝึกการจับคอร์ด การตีคอร์ด จังหวะพื้นฐาน และเล่นเพลงร่วมกัน',NULL,2,1,12,NULL,NULL,1,NULL,NULL,6900.00,'individual',8,1,1,0,1,'2026-08-10 10:48:08','2026-08-10 10:48:08',NULL),(5,'SP-DRM-001','special','special_activity','onsite','workshop','กลอง Workshop สนุกกับจังหวะ','กิจกรรม Workshop ฝึกพื้นฐานกลองและจังหวะ ผ่านกิจกรรมและการเล่นร่วมกัน',NULL,5,1,NULL,2,3.0,NULL,'2026-08-17','2026-08-18',1500.00,'individual',15,1,1,0,1,'2026-08-10 10:50:38','2026-08-10 10:50:38',NULL),(6,'SP-CAMP-001','special','special_activity','onsite','camp','Music Summer Camp 2026','ค่ายดนตรีสำหรับเด็ก เรียนรู้การเล่นดนตรีและทำกิจกรรมร่วมกัน',NULL,14,2,NULL,5,6.0,NULL,'2026-08-24','2026-08-28',6500.00,'individual',30,1,1,0,1,'2026-08-10 10:52:39','2026-08-10 10:52:39',NULL);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enrollments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `enrolled_date` date NOT NULL,
  `expected_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `sessions_used` int(10) unsigned NOT NULL DEFAULT 0,
  `extension_months_used` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `status` enum('active','completed','cancelled','paused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enrollments_course_id_foreign` (`course_id`),
  KEY `enrollments_student_id_status_index` (`student_id`,`status`),
  KEY `enrollments_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
INSERT INTO `enrollments` VALUES (1,4,4,3,'2026-08-15','2026-09-15',NULL,1,0,'active','2026-08-15 08:41:39','2026-08-16 09:57:06');
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment_types`
--

DROP TABLE IF EXISTS `equipment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipment_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_types`
--

LOCK TABLES `equipment_types` WRITE;
/*!40000 ALTER TABLE `equipment_types` DISABLE KEYS */;
INSERT INTO `equipment_types` VALUES (1,'เปียนโน','2026-08-09 09:17:12','2026-08-09 09:17:12'),(2,'ลำโพง','2026-08-10 10:56:10','2026-08-10 10:56:10');
/*!40000 ALTER TABLE `equipment_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_categories`
--

DROP TABLE IF EXISTS `evaluation_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_categories`
--

LOCK TABLES `evaluation_categories` WRITE;
/*!40000 ALTER TABLE `evaluation_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam_results`
--

DROP TABLE IF EXISTS `exam_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `instrument_id` bigint(20) unsigned DEFAULT NULL,
  `exam_board` enum('abrsm','trinity') COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_date` date NOT NULL,
  `result` enum('distinction','merit','pass','fail') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_results_instrument_id_foreign` (`instrument_id`),
  KEY `exam_results_student_id_exam_board_index` (`student_id`,`exam_board`),
  CONSTRAINT `exam_results_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `exam_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_results`
--

LOCK TABLES `exam_results` WRITE;
/*!40000 ALTER TABLE `exam_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `exam_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `guardians`
--

DROP TABLE IF EXISTS `guardians`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guardians` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guardians_phone_index` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guardians`
--

LOCK TABLES `guardians` WRITE;
/*!40000 ALTER TABLE `guardians` DISABLE KEYS */;
INSERT INTO `guardians` VALUES (1,'test','0244454477',NULL,NULL,NULL,NULL,'2026-08-09 08:44:38','2026-08-10 10:32:36','2026-08-10 10:32:36'),(2,'สุภาวดี ใจดี','0823456789',NULL,NULL,NULL,NULL,'2026-08-10 10:36:18','2026-08-10 10:36:18',NULL),(3,'สมชาย ใจดี','0812345678',NULL,NULL,NULL,NULL,'2026-08-10 10:36:40','2026-08-10 10:39:04','2026-08-10 10:39:04'),(4,'สมชาย วัฒนชัย','0876543210','somchai@email.com',NULL,NULL,NULL,'2026-08-10 10:38:14','2026-08-15 01:27:22',NULL),(5,'อรทัย ศรีสุข','0845671230',NULL,NULL,NULL,NULL,'2026-08-10 10:38:54','2026-08-10 10:38:54',NULL),(6,'สมชาย ใจดี','0812345678',NULL,NULL,NULL,NULL,'2026-08-10 10:39:28','2026-08-10 10:39:28',NULL);
/*!40000 ALTER TABLE `guardians` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `homework_submission_files`
--

DROP TABLE IF EXISTS `homework_submission_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homework_submission_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `homework_submission_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `homework_submission_files_homework_submission_id_foreign` (`homework_submission_id`),
  CONSTRAINT `homework_submission_files_homework_submission_id_foreign` FOREIGN KEY (`homework_submission_id`) REFERENCES `homework_submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homework_submission_files`
--

LOCK TABLES `homework_submission_files` WRITE;
/*!40000 ALTER TABLE `homework_submission_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `homework_submission_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `homework_submissions`
--

DROP TABLE IF EXISTS `homework_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homework_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teaching_report_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT 1,
  `student_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('submitted','approved','needs_revision') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `feedback` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `submitted_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `homework_submissions_student_id_foreign` (`student_id`),
  KEY `homework_submissions_teaching_report_id_version_index` (`teaching_report_id`,`version`),
  CONSTRAINT `homework_submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homework_submissions_teaching_report_id_foreign` FOREIGN KEY (`teaching_report_id`) REFERENCES `teaching_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homework_submissions`
--

LOCK TABLES `homework_submissions` WRITE;
/*!40000 ALTER TABLE `homework_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `homework_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instruments`
--

DROP TABLE IF EXISTS `instruments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instruments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instruments`
--

LOCK TABLES `instruments` WRITE;
/*!40000 ALTER TABLE `instruments` DISABLE KEYS */;
INSERT INTO `instruments` VALUES (1,'เปียโน',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,'กีตาร์',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,'กีตาร์ไฟฟ้า',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,'เบส',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,'กลองชุด',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,'ไวโอลิน',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(7,'เชลโล',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(8,'ขับร้อง (Vocal)',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(9,'ขลุ่ย',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(10,'ซอ',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(11,'คีย์บอร์ด',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(12,'ยูคูเลเล่',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(14,'ดนตรีรวม',NULL,1,'2026-08-10 10:51:28','2026-08-10 10:51:28');
/*!40000 ALTER TABLE `instruments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
INSERT INTO `levels` VALUES (1,'เริ่มต้น (Beginner)',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,'ปานกลาง (Intermediate)',2,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,'ระดับสูง (Advanced)',3,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,'เตรียมสอบ / เตรียมแข่งขัน',4,'2026-08-03 09:22:54','2026-08-03 09:22:54');
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `makeup_requests`
--

DROP TABLE IF EXISTS `makeup_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `makeup_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_leave_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `enrollment_id` bigint(20) unsigned NOT NULL,
  `original_class_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `room_id` bigint(20) unsigned DEFAULT NULL,
  `makeup_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `delivery_mode` enum('onsite','online','hybrid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'onsite',
  `admin_approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_reviewed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_reviewed_at` timestamp NULL DEFAULT NULL,
  `instructor_approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `instructor_reviewed_at` timestamp NULL DEFAULT NULL,
  `overall_status` enum('pending','approved','rejected','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `class_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `is_overdue` tinyint(1) NOT NULL DEFAULT 0,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `makeup_requests_student_leave_id_unique` (`student_leave_id`),
  KEY `makeup_requests_student_id_foreign` (`student_id`),
  KEY `makeup_requests_enrollment_id_foreign` (`enrollment_id`),
  KEY `makeup_requests_original_class_schedule_id_foreign` (`original_class_schedule_id`),
  KEY `makeup_requests_teacher_id_foreign` (`teacher_id`),
  KEY `makeup_requests_room_id_foreign` (`room_id`),
  KEY `makeup_requests_class_schedule_id_foreign` (`class_schedule_id`),
  KEY `makeup_requests_overall_status_teacher_id_index` (`overall_status`,`teacher_id`),
  CONSTRAINT `makeup_requests_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `makeup_requests_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `makeup_requests_original_class_schedule_id_foreign` FOREIGN KEY (`original_class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `makeup_requests_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `makeup_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `makeup_requests_student_leave_id_foreign` FOREIGN KEY (`student_leave_id`) REFERENCES `student_leaves` (`id`) ON DELETE CASCADE,
  CONSTRAINT `makeup_requests_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `makeup_requests`
--

LOCK TABLES `makeup_requests` WRITE;
/*!40000 ALTER TABLE `makeup_requests` DISABLE KEYS */;
INSERT INTO `makeup_requests` VALUES (1,1,4,1,NULL,3,NULL,'2026-09-01','14:02:00','15:02:00','onsite','approved','ผู้ดูแลระบบ','2026-08-16 09:07:26','approved','2026-08-16 09:06:56','approved',15,0,NULL,NULL,'กัญญารัตน์ ศรีสุข','2026-08-16 09:05:21','2026-08-16 09:07:26');
/*!40000 ALTER TABLE `makeup_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_create_instruments_table',1),(5,'2024_01_01_000002_create_teaching_types_table',1),(6,'2024_01_01_000003_create_levels_table',1),(7,'2024_01_01_000004_create_teachers_table',1),(8,'2024_01_01_000005_create_teacher_instrument_table',1),(9,'2024_01_01_000006_create_teacher_teaching_type_table',1),(10,'2024_01_01_000007_create_teacher_level_table',1),(11,'2024_01_01_000008_create_teacher_rates_table',1),(12,'2024_01_01_000009_create_teacher_transport_fees_table',1),(13,'2024_01_01_000010_create_teacher_availabilities_table',1),(14,'2024_01_01_000011_create_teaching_sessions_table',1),(15,'2024_01_01_000012_add_branch_to_teachers_table',1),(16,'2024_01_01_000013_add_notes_to_teachers_table',1),(17,'2024_01_02_000001_create_courses_table',2),(18,'2024_01_02_000002_create_coupons_table',2),(19,'2024_01_02_000003_add_structure_fields_to_courses_table',3),(20,'2024_01_02_000004_add_delivery_mode_to_courses_table',4),(21,'2024_01_03_000001_create_students_table',5),(22,'2024_01_03_000002_create_enrollments_table',5),(23,'2024_01_03_000003_create_payments_table',5),(24,'2024_01_03_000004_create_student_credit_transactions_table',5),(25,'2024_01_03_000005_create_student_skill_levels_table',5),(26,'2024_01_03_000006_create_exam_results_table',5),(27,'2024_01_03_000007_create_student_leaves_table',5),(28,'2024_01_03_000008_create_guardians_table',6),(29,'2024_01_03_000009_create_student_guardian_table',6),(30,'2024_01_03_000010_drop_guardian_columns_from_students_table',6),(31,'2024_01_04_000001_create_rooms_table',7),(32,'2024_01_04_000002_create_equipment_types_table',7),(33,'2024_01_04_000003_create_room_equipment_table',7),(34,'2024_01_04_000004_create_room_bookings_table',7),(35,'2024_01_05_000001_create_sale_orders_table',8),(36,'2024_01_05_000002_create_tax_invoices_table',8),(37,'2024_01_05_000003_add_discount_fields_to_sale_orders_table',9),(38,'2024_01_05_000004_create_student_point_transactions_table',9),(39,'2024_01_05_000005_add_payment_reference_to_sale_orders_table',10),(40,'2024_01_06_000001_create_course_transfers_table',11),(41,'2024_01_07_000001_create_class_schedules_table',12),(42,'2024_01_07_000002_add_teacher_id_to_enrollments_table',13),(43,'2024_01_08_000001_upgrade_student_leaves_table',14),(44,'2024_01_08_000002_create_teacher_leaves_table',14),(45,'2024_01_08_000003_create_app_notifications_table',14),(46,'2024_01_09_000001_add_role_fields_to_users_table',15),(47,'2024_01_10_000001_create_makeup_requests_table',16),(48,'2024_01_11_000001_create_reschedule_requests_table',17),(49,'2024_01_11_000002_add_student_guardian_to_app_notifications_role_enum',18),(50,'2025_01_12_000001_create_teaching_logs_table',19),(52,'2025_01_13_000001_create_teaching_reports_tables',20),(53,'2025_01_13_000002_create_course_evaluation_tables',21),(54,'2025_01_14_000001_create_teaching_evidences_table',22),(55,'2025_01_15_000001_create_homework_submissions_table',23),(56,'2025_01_15_000002_create_run_throughs_table',23),(57,'2025_01_16_000001_add_percentage_to_teacher_rates_and_create_payroll_tables',24),(58,'2025_01_17_000001_add_km_and_create_transport_compensations',25);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `enrollment_id` bigint(20) unsigned DEFAULT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `method` enum('cash','transfer','credit_card','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('paid','partial','pending','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_invoice_no_unique` (`invoice_no`),
  KEY `payments_enrollment_id_foreign` (`enrollment_id`),
  KEY `payments_student_id_status_index` (`student_id`,`status`),
  CONSTRAINT `payments_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,4,1,'SO-20260815-0001',6900.00,6900.00,NULL,'2026-08-15','other','paid','ชำระผ่านระบบขายคอร์สเรียน (SO-20260815-0001)','2026-08-15 08:41:39','2026-08-15 08:41:39');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_run_items`
--

DROP TABLE IF EXISTS `payroll_run_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_run_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payroll_run_id` bigint(20) unsigned NOT NULL,
  `teaching_session_id` bigint(20) unsigned NOT NULL,
  `income_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payroll_run_items_unique` (`payroll_run_id`,`teaching_session_id`),
  KEY `payroll_run_items_teaching_session_id_foreign` (`teaching_session_id`),
  CONSTRAINT `payroll_run_items_payroll_run_id_foreign` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payroll_run_items_teaching_session_id_foreign` FOREIGN KEY (`teaching_session_id`) REFERENCES `teaching_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_run_items`
--

LOCK TABLES `payroll_run_items` WRITE;
/*!40000 ALTER TABLE `payroll_run_items` DISABLE KEYS */;
INSERT INTO `payroll_run_items` VALUES (1,2,1,-1000.00,'2026-08-16 11:46:28','2026-08-16 11:46:28');
/*!40000 ALTER TABLE `payroll_run_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_runs`
--

DROP TABLE IF EXISTS `payroll_runs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_runs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `teaching_income_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transport_fee_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `adjustment_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `adjustment_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','confirmed','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `paid_date` date DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adjusted_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payroll_runs_teacher_id_period_start_period_end_unique` (`teacher_id`,`period_start`,`period_end`),
  CONSTRAINT `payroll_runs_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_runs`
--

LOCK TABLES `payroll_runs` WRITE;
/*!40000 ALTER TABLE `payroll_runs` DISABLE KEYS */;
INSERT INTO `payroll_runs` VALUES (1,6,'2026-08-01','2026-08-31',0.00,0.00,0.00,NULL,0.00,'draft',NULL,NULL,'ผู้ดูแลระบบ',NULL,NULL,'2026-08-16 11:46:09','2026-08-16 11:46:09'),(2,3,'2026-08-01','2026-08-31',-1000.00,0.00,100.00,'s',-900.00,'paid','2026-08-16','ืืออ','ผู้ดูแลระบบ','ผู้ดูแลระบบ','ผู้ดูแลระบบ','2026-08-16 11:46:28','2026-08-16 12:09:57');
/*!40000 ALTER TABLE `payroll_runs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reschedule_requests`
--

DROP TABLE IF EXISTS `reschedule_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reschedule_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('change','swap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_schedule_id` bigint(20) unsigned NOT NULL,
  `swap_with_class_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `new_teacher_id` bigint(20) unsigned DEFAULT NULL,
  `new_room_id` bigint(20) unsigned DEFAULT NULL,
  `new_date` date DEFAULT NULL,
  `new_start_time` time DEFAULT NULL,
  `new_end_time` time DEFAULT NULL,
  `snapshot_before` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`snapshot_before`)),
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requested_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reschedule_requests_class_schedule_id_foreign` (`class_schedule_id`),
  KEY `reschedule_requests_swap_with_class_schedule_id_foreign` (`swap_with_class_schedule_id`),
  KEY `reschedule_requests_new_teacher_id_foreign` (`new_teacher_id`),
  KEY `reschedule_requests_new_room_id_foreign` (`new_room_id`),
  KEY `reschedule_requests_status_index` (`status`),
  CONSTRAINT `reschedule_requests_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reschedule_requests_new_room_id_foreign` FOREIGN KEY (`new_room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reschedule_requests_new_teacher_id_foreign` FOREIGN KEY (`new_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reschedule_requests_swap_with_class_schedule_id_foreign` FOREIGN KEY (`swap_with_class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reschedule_requests`
--

LOCK TABLES `reschedule_requests` WRITE;
/*!40000 ALTER TABLE `reschedule_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `reschedule_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_bookings`
--

DROP TABLE IF EXISTS `room_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendees_count` int(10) unsigned NOT NULL DEFAULT 1,
  `status` enum('confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `booked_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_bookings_teacher_id_foreign` (`teacher_id`),
  KEY `room_bookings_course_id_foreign` (`course_id`),
  KEY `room_bookings_room_id_booking_date_status_index` (`room_id`,`booking_date`,`status`),
  CONSTRAINT `room_bookings_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `room_bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_bookings_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_bookings`
--

LOCK TABLES `room_bookings` WRITE;
/*!40000 ALTER TABLE `room_bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_equipment`
--

DROP TABLE IF EXISTS `room_equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_equipment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `equipment_type_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_equipment_room_id_equipment_type_id_unique` (`room_id`,`equipment_type_id`),
  KEY `room_equipment_equipment_type_id_foreign` (`equipment_type_id`),
  CONSTRAINT `room_equipment_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_equipment_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_equipment`
--

LOCK TABLES `room_equipment` WRITE;
/*!40000 ALTER TABLE `room_equipment` DISABLE KEYS */;
INSERT INTO `room_equipment` VALUES (1,1,1,1,NULL,NULL,'2026-08-09 09:17:16','2026-08-09 09:17:16'),(2,2,1,1,NULL,NULL,'2026-08-10 10:54:18','2026-08-10 10:54:18'),(3,4,2,1,NULL,NULL,'2026-08-10 10:56:12','2026-08-10 10:56:12');
/*!40000 ALTER TABLE `room_equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int(10) unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_under_maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `maintenance_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_from` date DEFAULT NULL,
  `maintenance_to` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_room_code_unique` (`room_code`),
  KEY `rooms_is_active_is_under_maintenance_index` (`is_active`,`is_under_maintenance`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'CONF-001','s','2',2,NULL,1,0,NULL,NULL,NULL,'2026-08-09 09:17:16','2026-08-10 10:32:23','2026-08-10 10:32:23'),(2,'R001','ห้องเปียโน 1','ชั้น 2',2,'ห้องสำหรับเรียนเปียโนแบบ Private มีฉนวนกันเสียง',1,0,NULL,NULL,NULL,'2026-08-10 10:54:18','2026-08-10 10:54:18',NULL),(3,'R004','ห้องซ้อมรวม','ชั้น 3',12,'ห้องสำหรับซ้อมวงและเรียนแบบ Group',1,0,NULL,NULL,NULL,'2026-08-10 10:55:17','2026-08-10 10:55:17',NULL),(4,'R005','ห้องกิจกรรม','ชั้น 1',30,'ห้องขนาดใหญ่สำหรับ Workshop, Master Class และกิจกรรมพิเศษ',1,0,NULL,NULL,NULL,'2026-08-10 10:56:12','2026-08-10 10:56:12',NULL);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `run_through_attachments`
--

DROP TABLE IF EXISTS `run_through_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `run_through_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `run_through_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `run_through_attachments_run_through_id_foreign` (`run_through_id`),
  CONSTRAINT `run_through_attachments_run_through_id_foreign` FOREIGN KEY (`run_through_id`) REFERENCES `run_throughs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `run_through_attachments`
--

LOCK TABLES `run_through_attachments` WRITE;
/*!40000 ALTER TABLE `run_through_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `run_through_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `run_throughs`
--

DROP TABLE IF EXISTS `run_throughs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `run_throughs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `practice_result` enum('excellent','good','needs_practice') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `areas_to_improve` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_recorded_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `run_throughs_teacher_id_foreign` (`teacher_id`),
  KEY `run_throughs_enrollment_id_index` (`enrollment_id`),
  CONSTRAINT `run_throughs_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `run_throughs_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `run_throughs`
--

LOCK TABLES `run_throughs` WRITE;
/*!40000 ALTER TABLE `run_throughs` DISABLE KEYS */;
/*!40000 ALTER TABLE `run_throughs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_orders`
--

DROP TABLE IF EXISTS `sale_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_mode` enum('onsite','online','hybrid') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_day_of_week` tinyint(4) DEFAULT NULL,
  `preferred_start_time` time DEFAULT NULL,
  `preferred_end_time` time DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `credit_used` decimal(10,2) NOT NULL DEFAULT 0.00,
  `points_used` int(10) unsigned NOT NULL DEFAULT 0,
  `points_discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `vat_rate` decimal(5,2) NOT NULL DEFAULT 7.00,
  `vat_amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `net_payable` decimal(10,2) DEFAULT NULL,
  `payment_proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` enum('cash','transfer','credit_card','promptpay','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending_payment','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `enrollment_id` bigint(20) unsigned DEFAULT NULL,
  `payment_id` bigint(20) unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sold_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sale_orders_order_no_unique` (`order_no`),
  KEY `sale_orders_course_id_foreign` (`course_id`),
  KEY `sale_orders_teacher_id_foreign` (`teacher_id`),
  KEY `sale_orders_enrollment_id_foreign` (`enrollment_id`),
  KEY `sale_orders_payment_id_foreign` (`payment_id`),
  KEY `sale_orders_student_id_status_index` (`student_id`,`status`),
  KEY `sale_orders_coupon_id_foreign` (`coupon_id`),
  CONSTRAINT `sale_orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_orders_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_orders_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_orders_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_orders_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_orders_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_orders`
--

LOCK TABLES `sale_orders` WRITE;
/*!40000 ALTER TABLE `sale_orders` DISABLE KEYS */;
INSERT INTO `sale_orders` VALUES (1,'SO-20260815-0001',4,4,NULL,NULL,3,'Cloud 11','onsite',2,'10:40:00','11:41:00',6448.60,0.00,0.00,0,0.00,7.00,451.40,6900.00,6900.00,NULL,'promptpay',NULL,'paid',1,1,NULL,'ผู้ดูแลระบบ','2026-08-15 08:41:24','2026-08-15 08:41:39');
/*!40000 ALTER TABLE `sale_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES ('Xw9VUIfkAoAQqcDiKtZw6lrUoLHcyXEm4MYFNoAZ',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJkNnRFMGRtRkJmY3NVdjhBQzd3THVSR0tJeFg2SGlpbDVCdFRQRHBWIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL3BheXJvbGwiLCJyb3V0ZSI6InBheXJvbGwuaW5kZXgifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1786907404);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_credit_transactions`
--

DROP TABLE IF EXISTS `student_credit_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_credit_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `type` enum('topup','use','refund','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_credit_transactions_student_id_index` (`student_id`),
  CONSTRAINT `student_credit_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_credit_transactions`
--

LOCK TABLES `student_credit_transactions` WRITE;
/*!40000 ALTER TABLE `student_credit_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_credit_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_guardian`
--

DROP TABLE IF EXISTS `student_guardian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_guardian` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `guardian_id` bigint(20) unsigned NOT NULL,
  `relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_guardian_student_id_guardian_id_unique` (`student_id`,`guardian_id`),
  KEY `student_guardian_guardian_id_foreign` (`guardian_id`),
  CONSTRAINT `student_guardian_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_guardian_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_guardian`
--

LOCK TABLES `student_guardian` WRITE;
/*!40000 ALTER TABLE `student_guardian` DISABLE KEYS */;
INSERT INTO `student_guardian` VALUES (1,1,1,'มารดา',0,'2026-08-09 08:44:38','2026-08-09 08:44:38'),(2,2,2,'มารดา',0,'2026-08-10 10:36:18','2026-08-10 10:39:28'),(3,2,3,'บิดา',0,'2026-08-10 10:36:40','2026-08-10 10:36:40'),(4,3,4,'บิดา',1,'2026-08-10 10:38:14','2026-08-10 10:38:14'),(5,4,5,'มารดา',1,'2026-08-10 10:38:54','2026-08-10 10:38:54'),(6,2,6,'บิดา',1,'2026-08-10 10:39:28','2026-08-10 10:39:28');
/*!40000 ALTER TABLE `student_guardian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_leaves`
--

DROP TABLE IF EXISTS `student_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_leaves` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `enrollment_id` bigint(20) unsigned NOT NULL,
  `class_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `leave_type` enum('emergency','normal','no_makeup') COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `is_makeup_required` tinyint(1) NOT NULL DEFAULT 1,
  `makeup_date` date DEFAULT NULL,
  `makeup_status` enum('pending','scheduled','completed','not_required') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_leaves_student_id_foreign` (`student_id`),
  KEY `student_leaves_enrollment_id_leave_type_index` (`enrollment_id`,`leave_type`),
  KEY `student_leaves_class_schedule_id_foreign` (`class_schedule_id`),
  CONSTRAINT `student_leaves_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_leaves_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_leaves_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_leaves`
--

LOCK TABLES `student_leaves` WRITE;
/*!40000 ALTER TABLE `student_leaves` DISABLE KEYS */;
INSERT INTO `student_leaves` VALUES (1,4,1,NULL,'normal','2026-08-25',NULL,'pending',NULL,NULL,1,'2026-09-01','scheduled','2026-08-16 09:05:21','2026-08-16 09:07:26');
/*!40000 ALTER TABLE `student_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_point_transactions`
--

DROP TABLE IF EXISTS `student_point_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_point_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `sale_order_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('earn','redeem','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_point_transactions_sale_order_id_foreign` (`sale_order_id`),
  KEY `student_point_transactions_student_id_index` (`student_id`),
  CONSTRAINT `student_point_transactions_sale_order_id_foreign` FOREIGN KEY (`sale_order_id`) REFERENCES `sale_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_point_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_point_transactions`
--

LOCK TABLES `student_point_transactions` WRITE;
/*!40000 ALTER TABLE `student_point_transactions` DISABLE KEYS */;
INSERT INTO `student_point_transactions` VALUES (1,4,1,'earn',69,69,'สะสมแต้มจากการซื้อคอร์ส SO-20260815-0001','2026-08-15 08:41:39','2026-08-15 08:41:39');
/*!40000 ALTER TABLE `student_point_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_skill_levels`
--

DROP TABLE IF EXISTS `student_skill_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_skill_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `instrument_id` bigint(20) unsigned NOT NULL,
  `level_id` bigint(20) unsigned NOT NULL,
  `assessed_date` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_skill_levels_student_id_instrument_id_unique` (`student_id`,`instrument_id`),
  KEY `student_skill_levels_instrument_id_foreign` (`instrument_id`),
  KEY `student_skill_levels_level_id_foreign` (`level_id`),
  CONSTRAINT `student_skill_levels_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_skill_levels_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_skill_levels_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_skill_levels`
--

LOCK TABLES `student_skill_levels` WRITE;
/*!40000 ALTER TABLE `student_skill_levels` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_skill_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','paused','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_student_code_unique` (`student_code`),
  KEY `students_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'6812345678','luk','xxxx','2020-01-09','female','0812345678','nan@gmail.com','qqq','749/32',NULL,'active',NULL,'2026-08-09 08:36:24','2026-08-10 10:27:53','2026-08-10 10:27:53'),(2,'STD0001','พิมพ์ชนก ใจดี','มิ้นท์','2017-02-05','female','0812345678','pimchanok@gmail.com','mint.student01','99/9 หมู่ 5 ตำบลบางแก้ว อำเภอเมือง จังหวัดสมุทรปราการ 10270',NULL,'active',NULL,'2026-08-10 10:26:43','2026-08-10 10:35:57',NULL),(3,'STD0002','ธนกฤต วัฒนชัย','นนท์','2021-02-11','male','0897654321','thanakrit@gmail.com','non.student02','88/12 ถนนสุขุมวิท ตำบลเสม็ด อำเภอเมือง จังหวัดชลบุรี 20000',NULL,'active',NULL,'2026-08-10 10:31:05','2026-08-10 10:31:05',NULL),(4,'STD0003','กัญญารัตน์ ศรีสุข','ฟ้า','2019-06-11','female','0861234567','kanyarat@gmail.com','fah.student03','45/7 ถนนราษฎร์บำรุง ตำบลเนินพระ อำเภอเมือง จังหวัดระยอง 21000',NULL,'active',NULL,'2026-08-10 10:35:15','2026-08-10 10:35:15',NULL);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_invoices`
--

DROP TABLE IF EXISTS `tax_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_order_id` bigint(20) unsigned NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` enum('receipt','tax_invoice') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'receipt',
  `is_company` tinyint(1) NOT NULL DEFAULT 0,
  `buyer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_tax_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `vat_rate` decimal(5,2) NOT NULL,
  `vat_amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `issued_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_invoices_invoice_no_unique` (`invoice_no`),
  KEY `tax_invoices_sale_order_id_foreign` (`sale_order_id`),
  CONSTRAINT `tax_invoices_sale_order_id_foreign` FOREIGN KEY (`sale_order_id`) REFERENCES `sale_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_invoices`
--

LOCK TABLES `tax_invoices` WRITE;
/*!40000 ALTER TABLE `tax_invoices` DISABLE KEYS */;
INSERT INTO `tax_invoices` VALUES (1,1,'RCP-20260815-0002','receipt',0,'test',NULL,'xxxx','0421212121',6448.60,7.00,451.40,6900.00,'2026-08-15','2026-08-15 08:41:24','2026-08-15 08:41:39');
/*!40000 ALTER TABLE `tax_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_availabilities`
--

DROP TABLE IF EXISTS `teacher_availabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_availabilities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_availabilities_teacher_id_day_of_week_index` (`teacher_id`,`day_of_week`),
  CONSTRAINT `teacher_availabilities_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_availabilities`
--

LOCK TABLES `teacher_availabilities` WRITE;
/*!40000 ALTER TABLE `teacher_availabilities` DISABLE KEYS */;
INSERT INTO `teacher_availabilities` VALUES (1,1,1,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,1,2,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,1,3,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,1,4,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,1,5,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,1,6,'09:00:00','16:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(7,2,1,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(8,2,2,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(9,2,3,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(10,2,4,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(11,2,5,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(12,2,6,'09:00:00','16:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(13,3,1,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(14,3,2,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(15,3,3,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(16,3,4,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(17,3,5,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(18,3,6,'09:00:00','16:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(19,4,1,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(20,4,2,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(21,4,3,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(22,4,4,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(23,4,5,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(24,4,6,'09:00:00','16:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(25,5,1,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(26,5,2,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(27,5,3,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(28,5,4,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(29,5,5,'09:00:00','18:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(30,5,6,'09:00:00','16:00:00',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(37,6,0,'09:00:00','18:00:00',1,'2026-08-10 09:06:33','2026-08-10 09:06:33'),(38,6,1,'09:00:00','18:00:00',0,'2026-08-10 09:06:33','2026-08-10 09:06:33'),(39,6,2,'09:00:00','18:00:00',0,'2026-08-10 09:06:33','2026-08-10 09:06:33'),(40,6,3,'09:00:00','18:00:00',0,'2026-08-10 09:06:33','2026-08-10 09:06:33'),(41,6,4,'09:00:00','18:00:00',0,'2026-08-10 09:06:33','2026-08-10 09:06:33'),(42,6,5,'09:00:00','18:00:00',1,'2026-08-10 09:06:33','2026-08-10 09:06:33'),(43,6,6,'09:00:00','16:00:00',1,'2026-08-10 09:06:33','2026-08-10 09:06:33');
/*!40000 ALTER TABLE `teacher_availabilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_instrument`
--

DROP TABLE IF EXISTS `teacher_instrument`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_instrument` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `instrument_id` bigint(20) unsigned NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_instrument_teacher_id_instrument_id_unique` (`teacher_id`,`instrument_id`),
  KEY `teacher_instrument_instrument_id_foreign` (`instrument_id`),
  CONSTRAINT `teacher_instrument_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_instrument_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_instrument`
--

LOCK TABLES `teacher_instrument` WRITE;
/*!40000 ALTER TABLE `teacher_instrument` DISABLE KEYS */;
INSERT INTO `teacher_instrument` VALUES (1,1,1,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,1,11,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,2,8,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,3,2,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,3,3,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,3,12,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(7,4,6,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(8,4,10,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(9,5,5,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(10,6,1,1,'2026-08-03 09:22:54','2026-08-03 09:22:54');
/*!40000 ALTER TABLE `teacher_instrument` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_leaves`
--

DROP TABLE IF EXISTS `teacher_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_leaves` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `leave_date_from` date NOT NULL,
  `leave_date_to` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_leaves_teacher_id_status_index` (`teacher_id`,`status`),
  CONSTRAINT `teacher_leaves_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_leaves`
--

LOCK TABLES `teacher_leaves` WRITE;
/*!40000 ALTER TABLE `teacher_leaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `teacher_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_level`
--

DROP TABLE IF EXISTS `teacher_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_level` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `level_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_level_teacher_id_level_id_unique` (`teacher_id`,`level_id`),
  KEY `teacher_level_level_id_foreign` (`level_id`),
  CONSTRAINT `teacher_level_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_level_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_level`
--

LOCK TABLES `teacher_level` WRITE;
/*!40000 ALTER TABLE `teacher_level` DISABLE KEYS */;
INSERT INTO `teacher_level` VALUES (1,1,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,1,2,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,1,3,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,1,4,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,2,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,2,2,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(7,2,3,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(8,3,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(9,3,2,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(10,3,3,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(11,4,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(12,4,2,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(13,5,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(14,5,2,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(15,5,3,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(16,6,1,'2026-08-03 09:22:54','2026-08-03 09:22:54');
/*!40000 ALTER TABLE `teacher_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_rates`
--

DROP TABLE IF EXISTS `teacher_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `teaching_type_id` bigint(20) unsigned DEFAULT NULL,
  `instrument_id` bigint(20) unsigned DEFAULT NULL,
  `rate_type` enum('per_hour','per_session','monthly_fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_amount` decimal(10,2) NOT NULL,
  `effective_from` date DEFAULT NULL,
  `effective_to` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_rates_teaching_type_id_foreign` (`teaching_type_id`),
  KEY `teacher_rates_instrument_id_foreign` (`instrument_id`),
  KEY `teacher_rates_teacher_id_is_active_index` (`teacher_id`,`is_active`),
  CONSTRAINT `teacher_rates_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `teacher_rates_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_rates_teaching_type_id_foreign` FOREIGN KEY (`teaching_type_id`) REFERENCES `teaching_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_rates`
--

LOCK TABLES `teacher_rates` WRITE;
/*!40000 ALTER TABLE `teacher_rates` DISABLE KEYS */;
INSERT INTO `teacher_rates` VALUES (1,1,NULL,NULL,'per_hour',600.00,'2026-05-03',NULL,1,NULL,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,2,NULL,NULL,'per_hour',650.00,'2026-05-03',NULL,1,NULL,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,3,NULL,NULL,'per_session',500.00,'2026-05-03',NULL,1,NULL,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,4,NULL,NULL,'per_hour',700.00,'2026-05-03',NULL,1,NULL,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,5,NULL,NULL,'per_hour',600.00,'2026-05-03',NULL,1,NULL,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,6,NULL,NULL,'per_hour',550.00,'2026-05-03',NULL,1,NULL,'2026-08-03 09:22:54','2026-08-03 09:22:54');
/*!40000 ALTER TABLE `teacher_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_teaching_type`
--

DROP TABLE IF EXISTS `teacher_teaching_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_teaching_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `teaching_type_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_type_unique` (`teacher_id`,`teaching_type_id`),
  KEY `teacher_teaching_type_teaching_type_id_foreign` (`teaching_type_id`),
  CONSTRAINT `teacher_teaching_type_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_teaching_type_teaching_type_id_foreign` FOREIGN KEY (`teaching_type_id`) REFERENCES `teaching_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_teaching_type`
--

LOCK TABLES `teacher_teaching_type` WRITE;
/*!40000 ALTER TABLE `teacher_teaching_type` DISABLE KEYS */;
INSERT INTO `teacher_teaching_type` VALUES (1,1,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,2,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,3,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,3,3,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,4,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,5,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(7,5,2,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(8,6,1,'2026-08-03 09:22:54','2026-08-03 09:22:54');
/*!40000 ALTER TABLE `teacher_teaching_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_transport_fees`
--

DROP TABLE IF EXISTS `teacher_transport_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_transport_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `fee_type` enum('fixed_per_day','per_km') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed_per_day',
  `fee_amount` decimal(10,2) NOT NULL,
  `effective_from` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_transport_fees_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `teacher_transport_fees_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_transport_fees`
--

LOCK TABLES `teacher_transport_fees` WRITE;
/*!40000 ALTER TABLE `teacher_transport_fees` DISABLE KEYS */;
INSERT INTO `teacher_transport_fees` VALUES (1,3,'fixed_per_day',150.00,'2026-05-03',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,4,'fixed_per_day',150.00,'2026-05-03',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,6,'fixed_per_day',150.00,'2026-05-03',1,'2026-08-03 09:22:54','2026-08-03 09:22:54');
/*!40000 ALTER TABLE `teacher_transport_fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teachers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_type` enum('full_time','freelance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'freelance',
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_teacher_code_unique` (`teacher_code`),
  UNIQUE KEY `teachers_email_unique` (`email`),
  KEY `teachers_is_active_employment_type_index` (`is_active`,`employment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (1,'T0001','ภานุพงศ์ เจริญสุข','พีท','peat.piano@viemus.school','081-234-5671',NULL,NULL,NULL,'full_time','Cloud 11','จบเปียโนคลาสสิกจากสถาบันดนตรี มีประสบการณ์สอนมากกว่า 8 ปี เชี่ยวชาญเตรียมสอบ Grade',NULL,1,'2024-08-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(2,'T0002','ณัฐริกา ปานทอง','เมย์','may.vocal@viemus.school','081-234-5672',NULL,NULL,NULL,'full_time','Cloud 11','นักร้องมืออาชีพ ปัจจุบันสอนขับร้องเพลงไทยสากลและสากล',NULL,1,'2025-06-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(3,'T0003','สรวิชญ์ พิทักษ์ธรรม','เต้ย','toey.guitar@viemus.school','081-234-5673',NULL,NULL,NULL,'freelance','Astra Academy','มือกีตาร์วง Session รับสอนตั้งแต่พื้นฐานถึงระดับ Advanced และเปิด Workshop เป็นครั้งคราว',NULL,1,'2026-02-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(4,'T0004','ปวีณา แซ่ตั้ง','แพร','prae.violin@viemus.school','081-234-5674',NULL,NULL,NULL,'freelance','Cloud 11','จบไวโอลินเกียรตินิยม ปัจจุบันเป็นนักไวโอลินอิสระและติวเตอร์',NULL,0,'2025-12-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(5,'T0005','ธนกร อินทรวิเศษ','บอส','boss.drums@viemus.school','081-234-5675',NULL,NULL,NULL,'full_time','Cloud 11','มือกลองประจำวงดนตรีสด รับงาน Accompaniment ควบคู่กับการสอนประจำ',NULL,1,'2025-07-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(6,'T0006','ชนิดาภา วงศ์สุวรรณ','เฟิร์น','fern.piano@viemus.school','081-234-5676',NULL,NULL,NULL,'freelance','Astra Academy','ครูเปียโนสำหรับเด็กเล็ก เชี่ยวชาญหลักสูตรปูพื้นฐานสำหรับผู้เริ่มต้น',NULL,1,'2024-11-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL);
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaching_evidences`
--

DROP TABLE IF EXISTS `teaching_evidences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teaching_evidences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teaching_log_id` bigint(20) unsigned NOT NULL,
  `file_type` enum('image','video','document') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `uploaded_by_user_id` bigint(20) unsigned DEFAULT NULL,
  `uploaded_by_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teaching_evidences_uploaded_by_user_id_foreign` (`uploaded_by_user_id`),
  KEY `teaching_evidences_teaching_log_id_file_type_index` (`teaching_log_id`,`file_type`),
  CONSTRAINT `teaching_evidences_teaching_log_id_foreign` FOREIGN KEY (`teaching_log_id`) REFERENCES `teaching_logs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaching_evidences_uploaded_by_user_id_foreign` FOREIGN KEY (`uploaded_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_evidences`
--

LOCK TABLES `teaching_evidences` WRITE;
/*!40000 ALTER TABLE `teaching_evidences` DISABLE KEYS */;
/*!40000 ALTER TABLE `teaching_evidences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaching_logs`
--

DROP TABLE IF EXISTS `teaching_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teaching_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_schedule_id` bigint(20) unsigned NOT NULL,
  `enrollment_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `attendance_status` enum('present','late','absent','excused_leave') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_in_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_duration_minutes` smallint(5) unsigned DEFAULT NULL,
  `is_extra_time` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `confirmed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_leave_id` bigint(20) unsigned DEFAULT NULL,
  `teaching_session_id` bigint(20) unsigned DEFAULT NULL,
  `session_deducted` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teaching_logs_class_schedule_id_unique` (`class_schedule_id`),
  KEY `teaching_logs_enrollment_id_foreign` (`enrollment_id`),
  KEY `teaching_logs_student_leave_id_foreign` (`student_leave_id`),
  KEY `teaching_logs_teaching_session_id_foreign` (`teaching_session_id`),
  KEY `teaching_logs_teacher_id_attendance_status_index` (`teacher_id`,`attendance_status`),
  KEY `teaching_logs_student_id_attendance_status_index` (`student_id`,`attendance_status`),
  CONSTRAINT `teaching_logs_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaching_logs_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaching_logs_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaching_logs_student_leave_id_foreign` FOREIGN KEY (`student_leave_id`) REFERENCES `student_leaves` (`id`) ON DELETE SET NULL,
  CONSTRAINT `teaching_logs_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaching_logs_teaching_session_id_foreign` FOREIGN KEY (`teaching_session_id`) REFERENCES `teaching_sessions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_logs`
--

LOCK TABLES `teaching_logs` WRITE;
/*!40000 ALTER TABLE `teaching_logs` DISABLE KEYS */;
INSERT INTO `teaching_logs` VALUES (1,14,1,3,4,'present','2026-08-16 09:56:55','สรวิชญ์ พิทักษ์ธรรม',45,0,'2026-08-16 09:57:06','สรวิชญ์ พิทักษ์ธรรม',NULL,1,1,NULL,'2026-08-16 09:56:32','2026-08-16 09:57:06');
/*!40000 ALTER TABLE `teaching_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaching_report_attachments`
--

DROP TABLE IF EXISTS `teaching_report_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teaching_report_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teaching_report_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teaching_report_attachments_teaching_report_id_foreign` (`teaching_report_id`),
  CONSTRAINT `teaching_report_attachments_teaching_report_id_foreign` FOREIGN KEY (`teaching_report_id`) REFERENCES `teaching_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_report_attachments`
--

LOCK TABLES `teaching_report_attachments` WRITE;
/*!40000 ALTER TABLE `teaching_report_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `teaching_report_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaching_reports`
--

DROP TABLE IF EXISTS `teaching_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teaching_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teaching_log_id` bigint(20) unsigned NOT NULL,
  `content_taught` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `homework` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progress_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teaching_reports_teaching_log_id_unique` (`teaching_log_id`),
  CONSTRAINT `teaching_reports_teaching_log_id_foreign` FOREIGN KEY (`teaching_log_id`) REFERENCES `teaching_logs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_reports`
--

LOCK TABLES `teaching_reports` WRITE;
/*!40000 ALTER TABLE `teaching_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `teaching_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaching_sessions`
--

DROP TABLE IF EXISTS `teaching_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teaching_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `instrument_id` bigint(20) unsigned DEFAULT NULL,
  `teaching_type_id` bigint(20) unsigned DEFAULT NULL,
  `level_id` bigint(20) unsigned DEFAULT NULL,
  `student_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `rate_applied` decimal(10,2) DEFAULT NULL,
  `transport_fee_applied` decimal(10,2) NOT NULL DEFAULT 0.00,
  `km_traveled` decimal(8,2) DEFAULT NULL,
  `income_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('scheduled','completed','cancelled','no_show') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teaching_sessions_instrument_id_foreign` (`instrument_id`),
  KEY `teaching_sessions_teaching_type_id_foreign` (`teaching_type_id`),
  KEY `teaching_sessions_level_id_foreign` (`level_id`),
  KEY `teaching_sessions_teacher_id_session_date_index` (`teacher_id`,`session_date`),
  CONSTRAINT `teaching_sessions_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `teaching_sessions_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `teaching_sessions_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaching_sessions_teaching_type_id_foreign` FOREIGN KEY (`teaching_type_id`) REFERENCES `teaching_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_sessions`
--

LOCK TABLES `teaching_sessions` WRITE;
/*!40000 ALTER TABLE `teaching_sessions` DISABLE KEYS */;
INSERT INTO `teaching_sessions` VALUES (1,3,2,NULL,NULL,'กัญญารัตน์ ศรีสุข','2026-08-16','10:45:00','12:45:00',-2.00,500.00,0.00,NULL,-1000.00,'completed',NULL,'2026-08-16 09:57:06','2026-08-16 09:57:06');
/*!40000 ALTER TABLE `teaching_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaching_types`
--

DROP TABLE IF EXISTS `teaching_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teaching_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teaching_types_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_types`
--

LOCK TABLES `teaching_types` WRITE;
/*!40000 ALTER TABLE `teaching_types` DISABLE KEYS */;
INSERT INTO `teaching_types` VALUES (1,'สอนประจำ','regular','2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,'Accompaniment','accompaniment','2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,'Workshop','workshop','2026-08-03 09:22:54','2026-08-03 09:22:54');
/*!40000 ALTER TABLE `teaching_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transport_compensations`
--

DROP TABLE IF EXISTS `transport_compensations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_compensations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `compensation_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transport_compensations_teacher_id_compensation_date_index` (`teacher_id`,`compensation_date`),
  CONSTRAINT `transport_compensations_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transport_compensations`
--

LOCK TABLES `transport_compensations` WRITE;
/*!40000 ALTER TABLE `transport_compensations` DISABLE KEYS */;
INSERT INTO `transport_compensations` VALUES (1,3,'2026-08-16',200.00,'ค่ารถ','ผู้ดูแลระบบ','2026-08-16 12:07:33','2026-08-16 12:07:33');
/*!40000 ALTER TABLE `transport_compensations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','teacher','student','guardian') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `guardian_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_teacher_id_unique` (`teacher_id`),
  UNIQUE KEY `users_student_id_unique` (`student_id`),
  UNIQUE KEY `users_guardian_id_unique` (`guardian_id`),
  CONSTRAINT `users_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ผู้ดูแลระบบ','admin@viemus.school','admin',NULL,NULL,NULL,1,0,NULL,'$2y$12$lkUq/qQyJudbMV7moggJu.DgJY4GDe/GibYowTi34Z0Dyj9dO70T6',NULL,'2026-08-15 01:10:20','2026-08-15 01:41:11'),(3,'นายแอดมิน คนแรก','admin@gmail.com','admin',NULL,NULL,NULL,1,1,NULL,'$2y$12$G1/zebVNcVLuYnxuQCQpIu4pmvap9wrr00yxmoK6UHIgKrMScUM7u','rXT9t5TEHJlFh9h7ec5SGnuBwTxDId80dZF1LrKvyCVzorpUNsANe6SxTM99','2026-08-15 01:12:53','2026-08-15 01:12:57'),(9,'สรวิชญ์ พิทักษ์ธรรม','toey.guitar@viemus.school','teacher',3,NULL,NULL,1,0,NULL,'$2y$12$Wf6pT8m7DGvDucwZHObfF.EwtSH6HlN3ktwZouE.0CeCGywoqF4EG',NULL,'2026-08-16 08:56:31','2026-08-16 08:58:15'),(10,'กัญญารัตน์ ศรีสุข','kanyarat@gmail.com','student',NULL,4,NULL,1,0,NULL,'$2y$12$e8HYSaSPAOzUVBe7Lx1ZpuYpZe1c5Buy60dBTafmNlkYMcnAXw3f.',NULL,'2026-08-16 08:57:19','2026-08-16 09:00:00');
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

-- Dump completed on 2026-08-17  2:13:42
