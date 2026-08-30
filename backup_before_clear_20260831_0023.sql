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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_notifications`
--

LOCK TABLES `app_notifications` WRITE;
/*!40000 ALTER TABLE `app_notifications` DISABLE KEYS */;
INSERT INTO `app_notifications` VALUES (1,'student',4,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 24/08/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',1,'2026-08-24 12:57:14','2026-08-25 08:47:05'),(2,'guardian',5,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 24/08/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',0,'2026-08-24 12:57:14','2026-08-24 12:57:14'),(3,'student',4,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 24/08/2026 แล้ว','http://localhost:8000/my-teaching-reports',1,'2026-08-24 12:59:29','2026-08-25 08:47:05'),(4,'guardian',5,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 24/08/2026 แล้ว','http://localhost:8000/my-teaching-reports',0,'2026-08-24 12:59:29','2026-08-24 12:59:29'),(5,'student',4,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 24/08/2026 แล้ว','http://localhost:8000/my-teaching-reports',1,'2026-08-24 13:10:26','2026-08-25 08:47:05'),(6,'guardian',5,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 24/08/2026 แล้ว','http://localhost:8000/my-teaching-reports',0,'2026-08-24 13:10:26','2026-08-24 13:10:26'),(7,'teacher',3,'นักเรียนส่งการบ้านแล้ว','กัญญารัตน์ ศรีสุข ส่งการบ้าน (ครั้งที่ 1)','http://localhost:8000/homework-submissions',1,'2026-08-24 13:11:52','2026-08-25 07:58:58'),(8,'student',4,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 24/08/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',1,'2026-08-25 08:32:06','2026-08-25 08:47:05'),(9,'guardian',5,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 24/08/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',0,'2026-08-25 08:32:06','2026-08-25 08:32:06'),(10,'student',4,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 24/08/2026 แล้ว','http://localhost:8000/my-teaching-reports',1,'2026-08-25 08:40:24','2026-08-25 08:47:05'),(11,'guardian',5,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 24/08/2026 แล้ว','http://localhost:8000/my-teaching-reports',0,'2026-08-25 08:40:24','2026-08-25 08:40:24'),(12,'teacher',3,'นักเรียนส่งการบ้านแล้ว','กัญญารัตน์ ศรีสุข ส่งการบ้าน (ครั้งที่ 1)','http://localhost:8000/homework-submissions',1,'2026-08-25 08:59:11','2026-08-29 07:39:27'),(13,'student',4,'การบ้านผ่านแล้ว!','อาจารย์ตรวจการบ้านของคุณผ่านแล้ว','http://localhost:8000/my-homework',1,'2026-08-25 09:12:41','2026-08-25 18:43:34'),(14,'guardian',5,'การบ้านผ่านแล้ว!','อาจารย์ตรวจการบ้านของคุณผ่านแล้ว','http://localhost:8000/my-homework',0,'2026-08-25 09:12:41','2026-08-25 09:12:41'),(15,'student',10,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 07/09/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',0,'2026-08-25 09:14:29','2026-08-25 09:14:29'),(16,'student',10,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 07/09/2026 แล้ว','http://localhost:8000/my-teaching-reports',0,'2026-08-25 09:14:59','2026-08-25 09:14:59'),(17,'student',4,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 09/09/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',1,'2026-08-25 09:18:02','2026-08-25 18:43:29'),(18,'guardian',5,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 09/09/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',0,'2026-08-25 09:18:02','2026-08-25 09:18:02'),(19,'student',4,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 09/09/2026 แล้ว','http://localhost:8000/my-teaching-reports',1,'2026-08-25 09:18:21','2026-08-25 18:43:24'),(20,'guardian',5,'มีผลการสอนใหม่','อาจารย์บันทึกผลการสอนคาบวันที่ 09/09/2026 แล้ว','http://localhost:8000/my-teaching-reports',0,'2026-08-25 09:18:21','2026-08-25 09:18:21'),(21,'student',10,'บันทึกการสอนคาบนี้เสร็จสิ้น','คาบเรียนวันที่ 14/09/2026 บันทึกผล: เข้าเรียน','http://localhost:8000/teaching-logs',0,'2026-08-26 08:03:27','2026-08-26 08:03:27'),(22,'admin',NULL,'คำขอเปลี่ยนแปลงตารางเรียนใหม่ (รออนุมัติ)','เปลี่ยนแปลงตารางเรียน สำหรับ กัญญารัตน์ ศรีสุข คอร์ส เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/reschedule-requests',1,'2026-08-26 08:41:36','2026-08-26 08:42:28'),(23,'teacher',3,'คำขอเปลี่ยนแปลงตารางเรียนใหม่ (รออนุมัติ)','เปลี่ยนแปลงตารางเรียน สำหรับ กัญญารัตน์ ศรีสุข คอร์ส เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/reschedule-requests',1,'2026-08-26 08:41:36','2026-08-26 08:41:51'),(24,'student',4,'คำขอเปลี่ยนแปลงตารางเรียนใหม่ (รออนุมัติ)','เปลี่ยนแปลงตารางเรียน สำหรับ กัญญารัตน์ ศรีสุข คอร์ส เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/my-leaves',0,'2026-08-26 08:41:36','2026-08-26 08:41:36'),(25,'guardian',5,'คำขอเปลี่ยนแปลงตารางเรียนใหม่ (รออนุมัติ)','เปลี่ยนแปลงตารางเรียน สำหรับ กัญญารัตน์ ศรีสุข คอร์ส เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/my-leaves',0,'2026-08-26 08:41:36','2026-08-26 08:41:36'),(26,'teacher',3,'ปรับตารางเรียนสำเร็จ','เปลี่ยนแปลงตารางเรียน สำหรับ กัญญารัตน์ ศรีสุข คอร์ส เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/reschedule-requests',1,'2026-08-26 08:42:45','2026-08-26 08:43:09'),(27,'student',4,'ปรับตารางเรียนสำเร็จ','เปลี่ยนแปลงตารางเรียน สำหรับ กัญญารัตน์ ศรีสุข คอร์ส เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/my-leaves',0,'2026-08-26 08:42:45','2026-08-26 08:42:45'),(28,'guardian',5,'ปรับตารางเรียนสำเร็จ','เปลี่ยนแปลงตารางเรียน สำหรับ กัญญารัตน์ ศรีสุข คอร์ส เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/my-leaves',0,'2026-08-26 08:42:45','2026-08-26 08:42:45'),(29,'student',4,'มีแบบฝึกหัดทบทวนใหม่ (Run Through)','d — เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/my-run-throughs',0,'2026-08-29 07:21:14','2026-08-29 07:21:14'),(30,'guardian',5,'มีแบบฝึกหัดทบทวนใหม่ (Run Through)','d — เปียโนออนไลน์ตัวต่อตัว','http://localhost:8000/my-run-throughs',0,'2026-08-29 07:21:14','2026-08-29 07:21:14'),(31,'student',4,'บันทึกผลการฝึกซ้อม Run Through แล้ว','d — ผล: ดีเยี่ยม','http://localhost:8000/my-run-throughs',0,'2026-08-29 07:21:34','2026-08-29 07:21:34'),(32,'guardian',5,'บันทึกผลการฝึกซ้อม Run Through แล้ว','d — ผล: ดีเยี่ยม','http://localhost:8000/my-run-throughs',0,'2026-08-29 07:21:34','2026-08-29 07:21:34'),(33,'teacher',3,'มีนัดทดลองเรียนใหม่','test · 31/08/2026 10:46','http://localhost:8000/my-trial-leads/1',0,'2026-08-30 09:21:52','2026-08-30 09:21:52'),(34,'teacher',3,'มีนัดทดลองเรียนใหม่','test · 31/08/2026 10:46','http://localhost:8000/my-trial-leads/1',0,'2026-08-30 09:21:55','2026-08-30 09:21:55'),(35,'teacher',3,'มีนัดทดลองเรียนใหม่','test · 31/08/2026 10:46','http://localhost:8000/my-trial-leads/1',0,'2026-08-30 09:21:57','2026-08-30 09:21:57'),(36,'teacher',3,'มีนัดทดลองเรียนใหม่','test · 31/08/2026 10:46','http://localhost:8000/my-trial-leads/1',0,'2026-08-30 09:22:37','2026-08-30 09:22:37'),(37,'teacher',3,'มีนัดทดลองเรียนใหม่','test · 31/08/2026 10:26','http://localhost:8000/my-trial-leads/3',1,'2026-08-30 09:28:11','2026-08-30 09:35:41');
/*!40000 ALTER TABLE `app_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_code` smallint(5) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_route_name_index` (`route_name`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'ผู้ดูแลระบบ','admin','POST','students','students.store',302,'127.0.0.1','{\"student_code\":\"STD004\",\"full_name\":\"\\u0e01\\u0e34\\u0e15\\u0e15\\u0e34\\u0e1e\\u0e07\\u0e28\\u0e4c \\u0e27\\u0e31\\u0e12\\u0e19\\u0e32\\u0e01\\u0e38\\u0e25\",\"nickname\":\"\\u0e21\\u0e34\\u0e49\\u0e19\\u0e19\\u0e35\\u0e48\",\"date_of_birth\":\"2008-02-15\",\"gender\":\"male\",\"phone\":\"089-123-4567\",\"status\":\"active\",\"email\":\"kittipong.student@example.com\",\"line_id\":\"non_music01\",\"address\":\"99\\/15 \\u0e41\\u0e02\\u0e27\\u0e07\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e40\\u0e02\\u0e15\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e01\\u0e23\\u0e38\\u0e07\\u0e40\\u0e17\\u0e1e\\u0e21\\u0e2b\\u0e32\\u0e19\\u0e04\\u0e23 10230\",\"notes\":\"\\u0e2a\\u0e19\\u0e43\\u0e08\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e01\\u0e35\\u0e15\\u0e32\\u0e23\\u0e4c \\u0e15\\u0e49\\u0e2d\\u0e07\\u0e01\\u0e32\\u0e23\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e0a\\u0e48\\u0e27\\u0e07\\u0e40\\u0e22\\u0e47\\u0e19\\u0e27\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\"}','2026-08-24 17:14:12'),(2,1,'ผู้ดูแลระบบ','admin','POST','students','students.store',302,'127.0.0.1','{\"student_code\":\"STD004\",\"full_name\":\"\\u0e01\\u0e34\\u0e15\\u0e15\\u0e34\\u0e1e\\u0e07\\u0e28\\u0e4c \\u0e27\\u0e31\\u0e12\\u0e19\\u0e32\\u0e01\\u0e38\\u0e25\",\"nickname\":\"\\u0e21\\u0e34\\u0e49\\u0e19\\u0e19\\u0e35\\u0e48\",\"date_of_birth\":\"2008-02-15\",\"gender\":\"male\",\"phone\":\"089-123-4567\",\"status\":\"active\",\"email\":\"kittipong.student@gmail.com\",\"line_id\":\"non_music01\",\"address\":\"99\\/15 \\u0e41\\u0e02\\u0e27\\u0e07\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e40\\u0e02\\u0e15\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e01\\u0e23\\u0e38\\u0e07\\u0e40\\u0e17\\u0e1e\\u0e21\\u0e2b\\u0e32\\u0e19\\u0e04\\u0e23 10230\",\"notes\":\"\\u0e2a\\u0e19\\u0e43\\u0e08\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e01\\u0e35\\u0e15\\u0e32\\u0e23\\u0e4c \\u0e15\\u0e49\\u0e2d\\u0e07\\u0e01\\u0e32\\u0e23\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e0a\\u0e48\\u0e27\\u0e07\\u0e40\\u0e22\\u0e47\\u0e19\\u0e27\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\"}','2026-08-24 17:14:25'),(3,1,'ผู้ดูแลระบบ','admin','PUT','students/8','students.update',302,'127.0.0.1','{\"student_code\":\"STD004\",\"full_name\":\"\\u0e01\\u0e34\\u0e15\\u0e15\\u0e34\\u0e1e\\u0e07\\u0e28\\u0e4c \\u0e27\\u0e31\\u0e12\\u0e19\\u0e32\\u0e01\\u0e38\\u0e25\",\"nickname\":\"\\u0e21\\u0e34\\u0e49\\u0e19\\u0e19\\u0e35\\u0e48\",\"date_of_birth\":\"2008-02-15\",\"gender\":\"male\",\"phone\":\"0891234567\",\"status\":\"active\",\"email\":\"kittipong.student@gmail.com\",\"line_id\":\"non_music01\",\"address\":\"99\\/15 \\u0e41\\u0e02\\u0e27\\u0e07\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e40\\u0e02\\u0e15\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e01\\u0e23\\u0e38\\u0e07\\u0e40\\u0e17\\u0e1e\\u0e21\\u0e2b\\u0e32\\u0e19\\u0e04\\u0e23 10230\",\"notes\":\"\\u0e2a\\u0e19\\u0e43\\u0e08\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e01\\u0e35\\u0e15\\u0e32\\u0e23\\u0e4c \\u0e15\\u0e49\\u0e2d\\u0e07\\u0e01\\u0e32\\u0e23\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e0a\\u0e48\\u0e27\\u0e07\\u0e40\\u0e22\\u0e47\\u0e19\\u0e27\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"photo\":{}}','2026-08-24 17:15:25'),(4,1,'ผู้ดูแลระบบ','admin','PUT','students/8','students.update',302,'127.0.0.1','{\"student_code\":\"STD0004\",\"full_name\":\"\\u0e01\\u0e34\\u0e15\\u0e15\\u0e34\\u0e1e\\u0e07\\u0e28\\u0e4c \\u0e27\\u0e31\\u0e12\\u0e19\\u0e32\\u0e01\\u0e38\\u0e25\",\"nickname\":\"\\u0e21\\u0e34\\u0e49\\u0e19\\u0e19\\u0e35\\u0e48\",\"date_of_birth\":\"2008-02-15\",\"gender\":\"male\",\"phone\":\"0891234567\",\"status\":\"active\",\"email\":\"kittipong.student@gmail.com\",\"line_id\":\"non_music01\",\"address\":\"99\\/15 \\u0e41\\u0e02\\u0e27\\u0e07\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e40\\u0e02\\u0e15\\u0e25\\u0e32\\u0e14\\u0e1e\\u0e23\\u0e49\\u0e32\\u0e27 \\u0e01\\u0e23\\u0e38\\u0e07\\u0e40\\u0e17\\u0e1e\\u0e21\\u0e2b\\u0e32\\u0e19\\u0e04\\u0e23 10230\",\"notes\":\"\\u0e2a\\u0e19\\u0e43\\u0e08\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e01\\u0e35\\u0e15\\u0e32\\u0e23\\u0e4c \\u0e15\\u0e49\\u0e2d\\u0e07\\u0e01\\u0e32\\u0e23\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e0a\\u0e48\\u0e27\\u0e07\\u0e40\\u0e22\\u0e47\\u0e19\\u0e27\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\"}','2026-08-24 17:15:41'),(5,1,'ผู้ดูแลระบบ','admin','PUT','students/4','students.update',302,'127.0.0.1','{\"student_code\":\"STD0003\",\"full_name\":\"\\u0e01\\u0e31\\u0e0d\\u0e0d\\u0e32\\u0e23\\u0e31\\u0e15\\u0e19\\u0e4c \\u0e28\\u0e23\\u0e35\\u0e2a\\u0e38\\u0e02\",\"nickname\":\"\\u0e1f\\u0e49\\u0e32\",\"date_of_birth\":\"2019-06-11\",\"gender\":\"female\",\"phone\":\"0861234567\",\"status\":\"active\",\"email\":\"kanyarat@gmail.com\",\"line_id\":\"fah.student03\",\"address\":\"45\\/7 \\u0e16\\u0e19\\u0e19\\u0e23\\u0e32\\u0e29\\u0e0e\\u0e23\\u0e4c\\u0e1a\\u0e33\\u0e23\\u0e38\\u0e07 \\u0e15\\u0e33\\u0e1a\\u0e25\\u0e40\\u0e19\\u0e34\\u0e19\\u0e1e\\u0e23\\u0e30 \\u0e2d\\u0e33\\u0e40\\u0e20\\u0e2d\\u0e40\\u0e21\\u0e37\\u0e2d\\u0e07 \\u0e08\\u0e31\\u0e07\\u0e2b\\u0e27\\u0e31\\u0e14\\u0e23\\u0e30\\u0e22\\u0e2d\\u0e07 21000\",\"notes\":null,\"photo\":{}}','2026-08-24 17:15:57'),(6,1,'ผู้ดูแลระบบ','admin','PUT','students/2','students.update',302,'127.0.0.1','{\"student_code\":\"STD0001\",\"full_name\":\"\\u0e1e\\u0e34\\u0e21\\u0e1e\\u0e4c\\u0e0a\\u0e19\\u0e01 \\u0e43\\u0e08\\u0e14\\u0e35\",\"nickname\":\"\\u0e21\\u0e34\\u0e49\\u0e19\\u0e17\\u0e4c\",\"date_of_birth\":\"2017-02-05\",\"gender\":\"female\",\"phone\":\"0812345678\",\"status\":\"active\",\"email\":\"pimchanok@gmail.com\",\"line_id\":\"mint.student01\",\"address\":\"99\\/9 \\u0e2b\\u0e21\\u0e39\\u0e48 5 \\u0e15\\u0e33\\u0e1a\\u0e25\\u0e1a\\u0e32\\u0e07\\u0e41\\u0e01\\u0e49\\u0e27 \\u0e2d\\u0e33\\u0e40\\u0e20\\u0e2d\\u0e40\\u0e21\\u0e37\\u0e2d\\u0e07 \\u0e08\\u0e31\\u0e07\\u0e2b\\u0e27\\u0e31\\u0e14\\u0e2a\\u0e21\\u0e38\\u0e17\\u0e23\\u0e1b\\u0e23\\u0e32\\u0e01\\u0e32\\u0e23 10270\",\"notes\":null,\"photo\":{}}','2026-08-24 17:16:41'),(7,1,'ผู้ดูแลระบบ','admin','PUT','students/3','students.update',302,'127.0.0.1','{\"student_code\":\"STD0002\",\"full_name\":\"\\u0e18\\u0e19\\u0e01\\u0e24\\u0e15 \\u0e27\\u0e31\\u0e12\\u0e19\\u0e0a\\u0e31\\u0e22\",\"nickname\":\"\\u0e19\\u0e19\\u0e17\\u0e4c\",\"date_of_birth\":\"2021-02-11\",\"gender\":\"male\",\"phone\":\"0897654321\",\"status\":\"active\",\"email\":\"thanakrit@gmail.com\",\"line_id\":\"non.student02\",\"address\":\"88\\/12 \\u0e16\\u0e19\\u0e19\\u0e2a\\u0e38\\u0e02\\u0e38\\u0e21\\u0e27\\u0e34\\u0e17 \\u0e15\\u0e33\\u0e1a\\u0e25\\u0e40\\u0e2a\\u0e21\\u0e47\\u0e14 \\u0e2d\\u0e33\\u0e40\\u0e20\\u0e2d\\u0e40\\u0e21\\u0e37\\u0e2d\\u0e07 \\u0e08\\u0e31\\u0e07\\u0e2b\\u0e27\\u0e31\\u0e14\\u0e0a\\u0e25\\u0e1a\\u0e38\\u0e23\\u0e35 20000\",\"notes\":null,\"photo\":{}}','2026-08-24 17:16:51'),(8,1,'ผู้ดูแลระบบ','admin','POST','students/8/guardians','students.guardians.store',302,'127.0.0.1','{\"guardian_id\":\"6\",\"full_name\":\"\\u0e2a\\u0e21\\u0e0a\\u0e32\\u0e22 \\u0e43\\u0e08\\u0e14\\u0e35\",\"phone\":\"0812345678\",\"relation\":\"\\u0e21\\u0e32\\u0e23\\u0e14\\u0e32\"}','2026-08-24 17:17:24'),(9,1,'ผู้ดูแลระบบ','admin','POST','guardians/6/create-account','guardians.create-account',302,'127.0.0.1',NULL,'2026-08-24 17:17:48'),(10,1,'ผู้ดูแลระบบ','admin','POST','students','students.store',302,'127.0.0.1','{\"student_code\":\"STD0005\",\"full_name\":\"\\u0e13\\u0e31\\u0e10\\u0e13\\u0e34\\u0e0a\\u0e32 \\u0e27\\u0e31\\u0e12\\u0e19\\u0e30\",\"nickname\":\"\\u0e1e\\u0e23\\u0e35\\u0e21\",\"date_of_birth\":\"2000-06-13\",\"gender\":\"female\",\"phone\":\"092-456-7810\",\"status\":\"active\",\"email\":\"natchanicha.student@gmail.com\",\"line_id\":\"preem_music10\",\"address\":\"88\\/24 \\u0e16\\u0e19\\u0e19\\u0e23\\u0e32\\u0e21\\u0e04\\u0e33\\u0e41\\u0e2b\\u0e07 \\u0e41\\u0e02\\u0e27\\u0e07\\u0e2b\\u0e31\\u0e27\\u0e2b\\u0e21\\u0e32\\u0e01 \\u0e40\\u0e02\\u0e15\\u0e1a\\u0e32\\u0e07\\u0e01\\u0e30\\u0e1b\\u0e34 \\u0e01\\u0e23\\u0e38\\u0e07\\u0e40\\u0e17\\u0e1e\\u0e21\\u0e2b\\u0e32\\u0e19\\u0e04\\u0e23 10240\",\"notes\":\"\\u0e2a\\u0e19\\u0e43\\u0e08\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19 \\u0e21\\u0e35\\u0e1e\\u0e37\\u0e49\\u0e19\\u0e10\\u0e32\\u0e19\\u0e40\\u0e1a\\u0e37\\u0e49\\u0e2d\\u0e07\\u0e15\\u0e49\\u0e19 \\u0e15\\u0e49\\u0e2d\\u0e07\\u0e01\\u0e32\\u0e23\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e27\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\\u0e0a\\u0e48\\u0e27\\u0e07\\u0e1a\\u0e48\\u0e32\\u0e22\"}','2026-08-24 17:20:05'),(11,1,'ผู้ดูแลระบบ','admin','PUT','students/9','students.update',302,'127.0.0.1','{\"student_code\":\"STD0005\",\"full_name\":\"\\u0e13\\u0e31\\u0e10\\u0e13\\u0e34\\u0e0a\\u0e32 \\u0e27\\u0e31\\u0e12\\u0e19\\u0e30\",\"nickname\":\"\\u0e1e\\u0e23\\u0e35\\u0e21\",\"date_of_birth\":\"2000-06-13\",\"gender\":\"female\",\"phone\":\"0924567810\",\"status\":\"active\",\"email\":\"natchanicha.student@gmail.com\",\"line_id\":\"preem_music10\",\"address\":\"88\\/24 \\u0e16\\u0e19\\u0e19\\u0e23\\u0e32\\u0e21\\u0e04\\u0e33\\u0e41\\u0e2b\\u0e07 \\u0e41\\u0e02\\u0e27\\u0e07\\u0e2b\\u0e31\\u0e27\\u0e2b\\u0e21\\u0e32\\u0e01 \\u0e40\\u0e02\\u0e15\\u0e1a\\u0e32\\u0e07\\u0e01\\u0e30\\u0e1b\\u0e34 \\u0e01\\u0e23\\u0e38\\u0e07\\u0e40\\u0e17\\u0e1e\\u0e21\\u0e2b\\u0e32\\u0e19\\u0e04\\u0e23 10240\",\"notes\":\"\\u0e2a\\u0e19\\u0e43\\u0e08\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19 \\u0e21\\u0e35\\u0e1e\\u0e37\\u0e49\\u0e19\\u0e10\\u0e32\\u0e19\\u0e40\\u0e1a\\u0e37\\u0e49\\u0e2d\\u0e07\\u0e15\\u0e49\\u0e19 \\u0e15\\u0e49\\u0e2d\\u0e07\\u0e01\\u0e32\\u0e23\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e27\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\\u0e0a\\u0e48\\u0e27\\u0e07\\u0e1a\\u0e48\\u0e32\\u0e22\",\"photo\":{}}','2026-08-24 17:20:29'),(12,1,'ผู้ดูแลระบบ','admin','POST','students/9/guardians','students.guardians.store',302,'127.0.0.1','{\"guardian_id\":\"6\",\"full_name\":\"\\u0e2a\\u0e21\\u0e0a\\u0e32\\u0e22 \\u0e43\\u0e08\\u0e14\\u0e35\",\"phone\":\"0812345678\",\"relation\":\"\\u0e21\\u0e32\\u0e23\\u0e14\\u0e32\"}','2026-08-24 17:20:43'),(13,1,'ผู้ดูแลระบบ','admin','PUT','guardians/6','guardians.update',302,'127.0.0.1','{\"full_name\":\"\\u0e2a\\u0e21\\u0e0a\\u0e32\\u0e22 \\u0e43\\u0e08\\u0e14\\u0e35\",\"phone\":\"0812345678\",\"email\":\"somjai@email.com\",\"line_id\":\"somjai\"}','2026-08-24 17:21:22'),(14,1,'ผู้ดูแลระบบ','admin','POST','guardians/6/create-account','guardians.create-account',302,'127.0.0.1',NULL,'2026-08-24 17:21:25'),(15,1,'ผู้ดูแลระบบ','admin','PUT','teachers/6','teachers.update',302,'127.0.0.1','{\"teacher_code\":\"T0006\",\"full_name\":\"\\u0e0a\\u0e19\\u0e34\\u0e14\\u0e32\\u0e20\\u0e32 \\u0e27\\u0e07\\u0e28\\u0e4c\\u0e2a\\u0e38\\u0e27\\u0e23\\u0e23\\u0e13\",\"nickname\":\"\\u0e40\\u0e1f\\u0e34\\u0e23\\u0e4c\\u0e19\",\"email\":\"fern.piano@viemus.school\",\"phone\":\"081-234-5676\",\"line_id\":null,\"address\":null,\"employment_type\":\"freelance\",\"branch\":\"Astra Academy\",\"start_date\":\"2024-11-03\",\"is_active\":\"1\",\"teaching_type_ids\":[\"1\"],\"instrument_ids\":[\"1\"],\"primary_instrument_id\":\"1\",\"level_ids\":[\"1\"],\"bio\":\"\\u0e04\\u0e23\\u0e39\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e40\\u0e14\\u0e47\\u0e01\\u0e40\\u0e25\\u0e47\\u0e01 \\u0e40\\u0e0a\\u0e35\\u0e48\\u0e22\\u0e27\\u0e0a\\u0e32\\u0e0d\\u0e2b\\u0e25\\u0e31\\u0e01\\u0e2a\\u0e39\\u0e15\\u0e23\\u0e1b\\u0e39\\u0e1e\\u0e37\\u0e49\\u0e19\\u0e10\\u0e32\\u0e19\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e39\\u0e49\\u0e40\\u0e23\\u0e34\\u0e48\\u0e21\\u0e15\\u0e49\\u0e19\",\"notes\":null,\"photo\":{}}','2026-08-24 17:22:15'),(16,1,'ผู้ดูแลระบบ','admin','PUT','teachers/6','teachers.update',302,'127.0.0.1','{\"teacher_code\":\"T0006\",\"full_name\":\"\\u0e0a\\u0e19\\u0e34\\u0e14\\u0e32\\u0e20\\u0e32 \\u0e27\\u0e07\\u0e28\\u0e4c\\u0e2a\\u0e38\\u0e27\\u0e23\\u0e23\\u0e13\",\"nickname\":\"\\u0e40\\u0e1f\\u0e34\\u0e23\\u0e4c\\u0e19\",\"email\":\"fern.piano@viemus.school\",\"phone\":\"081-234-5676\",\"line_id\":null,\"address\":null,\"employment_type\":\"freelance\",\"branch\":\"Astra Academy\",\"start_date\":\"2024-11-03\",\"is_active\":\"1\",\"teaching_type_ids\":[\"1\"],\"instrument_ids\":[\"1\"],\"primary_instrument_id\":\"1\",\"level_ids\":[\"1\"],\"bio\":\"\\u0e04\\u0e23\\u0e39\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e40\\u0e14\\u0e47\\u0e01\\u0e40\\u0e25\\u0e47\\u0e01 \\u0e40\\u0e0a\\u0e35\\u0e48\\u0e22\\u0e27\\u0e0a\\u0e32\\u0e0d\\u0e2b\\u0e25\\u0e31\\u0e01\\u0e2a\\u0e39\\u0e15\\u0e23\\u0e1b\\u0e39\\u0e1e\\u0e37\\u0e49\\u0e19\\u0e10\\u0e32\\u0e19\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e39\\u0e49\\u0e40\\u0e23\\u0e34\\u0e48\\u0e21\\u0e15\\u0e49\\u0e19\",\"notes\":null,\"photo\":{}}','2026-08-24 17:22:15'),(17,1,'ผู้ดูแลระบบ','admin','PUT','teachers/6','teachers.update',302,'127.0.0.1','{\"teacher_code\":\"T0006\",\"full_name\":\"\\u0e0a\\u0e19\\u0e34\\u0e14\\u0e32\\u0e20\\u0e32 \\u0e27\\u0e07\\u0e28\\u0e4c\\u0e2a\\u0e38\\u0e27\\u0e23\\u0e23\\u0e13\",\"nickname\":\"\\u0e40\\u0e1f\\u0e34\\u0e23\\u0e4c\\u0e19\",\"email\":\"fern.piano@gmail.com\",\"phone\":\"081-234-5676\",\"line_id\":null,\"address\":null,\"employment_type\":\"freelance\",\"branch\":\"Astra Academy\",\"start_date\":\"2024-11-03\",\"is_active\":\"1\",\"teaching_type_ids\":[\"1\"],\"instrument_ids\":[\"1\"],\"primary_instrument_id\":\"1\",\"level_ids\":[\"1\"],\"bio\":\"\\u0e04\\u0e23\\u0e39\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e40\\u0e14\\u0e47\\u0e01\\u0e40\\u0e25\\u0e47\\u0e01 \\u0e40\\u0e0a\\u0e35\\u0e48\\u0e22\\u0e27\\u0e0a\\u0e32\\u0e0d\\u0e2b\\u0e25\\u0e31\\u0e01\\u0e2a\\u0e39\\u0e15\\u0e23\\u0e1b\\u0e39\\u0e1e\\u0e37\\u0e49\\u0e19\\u0e10\\u0e32\\u0e19\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e39\\u0e49\\u0e40\\u0e23\\u0e34\\u0e48\\u0e21\\u0e15\\u0e49\\u0e19\",\"notes\":null,\"photo\":{}}','2026-08-24 17:22:40'),(18,1,'ผู้ดูแลระบบ','admin','PUT','teachers/2','teachers.update',302,'127.0.0.1','{\"teacher_code\":\"T0002\",\"full_name\":\"\\u0e13\\u0e31\\u0e10\\u0e23\\u0e34\\u0e01\\u0e32 \\u0e1b\\u0e32\\u0e19\\u0e17\\u0e2d\\u0e07\",\"nickname\":\"\\u0e40\\u0e21\\u0e22\\u0e4c\",\"email\":\"may.vocal@viemus.school\",\"phone\":\"081-234-5672\",\"line_id\":null,\"address\":null,\"employment_type\":\"full_time\",\"branch\":\"Cloud 11\",\"start_date\":\"2025-06-03\",\"is_active\":\"1\",\"teaching_type_ids\":[\"1\"],\"instrument_ids\":[\"8\"],\"primary_instrument_id\":\"8\",\"level_ids\":[\"1\",\"2\",\"3\"],\"bio\":\"\\u0e19\\u0e31\\u0e01\\u0e23\\u0e49\\u0e2d\\u0e07\\u0e21\\u0e37\\u0e2d\\u0e2d\\u0e32\\u0e0a\\u0e35\\u0e1e \\u0e1b\\u0e31\\u0e08\\u0e08\\u0e38\\u0e1a\\u0e31\\u0e19\\u0e2a\\u0e2d\\u0e19\\u0e02\\u0e31\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e07\\u0e40\\u0e1e\\u0e25\\u0e07\\u0e44\\u0e17\\u0e22\\u0e2a\\u0e32\\u0e01\\u0e25\\u0e41\\u0e25\\u0e30\\u0e2a\\u0e32\\u0e01\\u0e25\",\"notes\":null,\"photo\":{}}','2026-08-24 17:22:57'),(19,1,'ผู้ดูแลระบบ','admin','PUT','teachers/2','teachers.update',302,'127.0.0.1','{\"teacher_code\":\"T0002\",\"full_name\":\"\\u0e13\\u0e31\\u0e10\\u0e23\\u0e34\\u0e01\\u0e32 \\u0e1b\\u0e32\\u0e19\\u0e17\\u0e2d\\u0e07\",\"nickname\":\"\\u0e40\\u0e21\\u0e22\\u0e4c\",\"email\":\"may.vocal@gmail.com\",\"phone\":\"081-234-5672\",\"line_id\":null,\"address\":null,\"employment_type\":\"full_time\",\"branch\":\"Cloud 11\",\"start_date\":\"2025-06-03\",\"is_active\":\"1\",\"teaching_type_ids\":[\"1\"],\"instrument_ids\":[\"8\"],\"primary_instrument_id\":\"8\",\"level_ids\":[\"1\",\"2\",\"3\"],\"bio\":\"\\u0e19\\u0e31\\u0e01\\u0e23\\u0e49\\u0e2d\\u0e07\\u0e21\\u0e37\\u0e2d\\u0e2d\\u0e32\\u0e0a\\u0e35\\u0e1e \\u0e1b\\u0e31\\u0e08\\u0e08\\u0e38\\u0e1a\\u0e31\\u0e19\\u0e2a\\u0e2d\\u0e19\\u0e02\\u0e31\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e07\\u0e40\\u0e1e\\u0e25\\u0e07\\u0e44\\u0e17\\u0e22\\u0e2a\\u0e32\\u0e01\\u0e25\\u0e41\\u0e25\\u0e30\\u0e2a\\u0e32\\u0e01\\u0e25\",\"notes\":null,\"photo\":{}}','2026-08-24 17:23:13'),(20,1,'ผู้ดูแลระบบ','admin','DELETE','courses/6','courses.destroy',302,'127.0.0.1',NULL,'2026-08-24 17:27:16'),(21,1,'ผู้ดูแลระบบ','admin','DELETE','courses/5','courses.destroy',302,'127.0.0.1',NULL,'2026-08-24 17:27:19'),(22,1,'ผู้ดูแลระบบ','admin','DELETE','courses/4','courses.destroy',302,'127.0.0.1',NULL,'2026-08-24 17:27:22'),(23,1,'ผู้ดูแลระบบ','admin','DELETE','courses/3','courses.destroy',302,'127.0.0.1',NULL,'2026-08-24 17:27:25'),(24,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"PV-VIO-001\",\"name\":\"\\u0e44\\u0e27\\u0e42\\u0e2d\\u0e25\\u0e34\\u0e19\\u0e1e\\u0e37\\u0e49\\u0e19\\u0e10\\u0e32\\u0e19\\u0e15\\u0e31\\u0e27\\u0e15\\u0e48\\u0e2d\\u0e15\\u0e31\\u0e27\",\"description\":null,\"instrument_id\":\"6\",\"level_id\":\"1\",\"structure_type\":\"regular\",\"class_type\":\"private\",\"delivery_mode\":\"onsite\",\"days_count\":null,\"hours_per_day\":null,\"course_start_date\":null,\"course_end_date\":null,\"total_sessions\":\"12\",\"duration_months\":\"3\",\"price\":\"14400\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"0\",\"teacher_ids\":[\"6\",\"2\"]}','2026-08-24 17:29:07'),(25,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"PV-PNO-ON1\",\"name\":\"\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\\u0e2d\\u0e2d\\u0e19\\u0e44\\u0e25\\u0e19\\u0e4c\\u0e15\\u0e31\\u0e27\\u0e15\\u0e48\\u0e2d\\u0e15\\u0e31\\u0e27\",\"description\":null,\"instrument_id\":null,\"level_id\":\"2\",\"structure_type\":\"regular\",\"class_type\":\"private\",\"delivery_mode\":\"online\",\"days_count\":null,\"hours_per_day\":null,\"course_start_date\":null,\"course_end_date\":null,\"total_sessions\":\"8\",\"duration_months\":\"2\",\"price\":\"9600\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"0\",\"teacher_ids\":[\"1\",\"3\"]}','2026-08-24 17:30:03'),(26,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"GR-GTR-HY1\",\"name\":\"\\u0e01\\u0e35\\u0e15\\u0e32\\u0e23\\u0e4c\\u0e01\\u0e25\\u0e38\\u0e48\\u0e21\\u0e44\\u0e2e\\u0e1a\\u0e23\\u0e34\\u0e14 (\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e17\\u0e35\\u0e48\\u0e42\\u0e23\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19+\\u0e2d\\u0e2d\\u0e19\\u0e44\\u0e25\\u0e19\\u0e4c\\u0e2a\\u0e25\\u0e31\\u0e1a)\",\"description\":null,\"instrument_id\":\"2\",\"level_id\":\"1\",\"structure_type\":\"regular\",\"class_type\":\"group\",\"delivery_mode\":\"hybrid\",\"days_count\":null,\"hours_per_day\":null,\"course_start_date\":null,\"course_end_date\":null,\"total_sessions\":\"12\",\"duration_months\":\"6\",\"price\":\"7200\",\"max_students\":\"2\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"0\",\"teacher_ids\":[\"2\",\"3\"]}','2026-08-24 17:31:40'),(27,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"PV-PNO-AD1\",\"name\":\"\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\\u0e1c\\u0e39\\u0e49\\u0e43\\u0e2b\\u0e0d\\u0e48 (\\u0e40\\u0e27\\u0e25\\u0e32\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e22\\u0e37\\u0e14\\u0e2b\\u0e22\\u0e38\\u0e48\\u0e19)\",\"description\":null,\"instrument_id\":\"1\",\"level_id\":\"1\",\"structure_type\":\"regular\",\"class_type\":\"private\",\"delivery_mode\":\"onsite\",\"days_count\":null,\"hours_per_day\":null,\"course_start_date\":null,\"course_end_date\":null,\"total_sessions\":null,\"duration_months\":\"12\",\"price\":\"18000\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"1\",\"teacher_ids\":[\"2\",\"5\"]}','2026-08-24 17:33:53'),(28,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"PV-PNO-AD1\",\"name\":\"\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\\u0e1c\\u0e39\\u0e49\\u0e43\\u0e2b\\u0e0d\\u0e48 (\\u0e40\\u0e27\\u0e25\\u0e32\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e22\\u0e37\\u0e14\\u0e2b\\u0e22\\u0e38\\u0e48\\u0e19)\",\"description\":null,\"instrument_id\":\"1\",\"level_id\":\"1\",\"structure_type\":\"regular\",\"class_type\":\"private\",\"delivery_mode\":\"onsite\",\"days_count\":null,\"hours_per_day\":null,\"course_start_date\":null,\"course_end_date\":null,\"total_sessions\":\"12\",\"duration_months\":\"12\",\"price\":\"18000\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"1\",\"teacher_ids\":[\"2\",\"5\"]}','2026-08-24 17:34:31'),(29,1,'ผู้ดูแลระบบ','admin','POST','instruments','instruments.store',201,'127.0.0.1','{\"name\":\"\\u0e44\\u0e21\\u0e48\\u0e23\\u0e30\\u0e1a\\u0e38\"}','2026-08-24 17:35:03'),(30,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"SP-CAMP-002\",\"name\":\"Winter Music Camp 2026\",\"description\":null,\"instrument_id\":\"15\",\"level_id\":\"2\",\"structure_type\":\"special\",\"class_type\":\"special_activity\",\"delivery_mode\":\"onsite\",\"activity_type\":\"camp\",\"days_count\":\"5\",\"hours_per_day\":\"6\",\"course_start_date\":\"2026-10-20\",\"course_end_date\":\"2026-10-24\",\"total_sessions\":null,\"duration_months\":null,\"price\":\"6900\",\"max_students\":\"25\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"0\",\"teacher_ids\":[\"6\",\"2\",\"5\"]}','2026-08-24 17:36:31'),(31,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"SP-VOC-001\",\"name\":\"Workshop \\u0e23\\u0e49\\u0e2d\\u0e07\\u0e40\\u0e1e\\u0e25\\u0e07\\u0e1b\\u0e4a\\u0e2d\\u0e1b 1 \\u0e27\\u0e31\\u0e19\",\"description\":null,\"instrument_id\":\"15\",\"level_id\":\"2\",\"structure_type\":\"special\",\"class_type\":\"special_activity\",\"delivery_mode\":\"onsite\",\"activity_type\":\"workshop\",\"days_count\":\"1\",\"hours_per_day\":\"4\",\"course_start_date\":\"2026-09-22\",\"course_end_date\":\"2026-09-22\",\"total_sessions\":null,\"duration_months\":null,\"price\":\"990\",\"max_students\":\"20\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"0\",\"teacher_ids\":[\"5\",\"1\",\"3\"]}','2026-08-24 17:38:11'),(32,1,'ผู้ดูแลระบบ','admin','POST','courses','courses.store',302,'127.0.0.1','{\"course_code\":\"SP-VLN-MC1\",\"name\":\"Master Class \\u0e44\\u0e27\\u0e42\\u0e2d\\u0e25\\u0e34\\u0e19\\u0e01\\u0e31\\u0e1a\\u0e2d\\u0e32\\u0e08\\u0e32\\u0e23\\u0e22\\u0e4c\\u0e23\\u0e31\\u0e1a\\u0e40\\u0e0a\\u0e34\\u0e0d\",\"description\":null,\"instrument_id\":\"6\",\"level_id\":\"2\",\"structure_type\":\"special\",\"class_type\":\"special_activity\",\"delivery_mode\":\"onsite\",\"activity_type\":\"master_class\",\"days_count\":\"1\",\"hours_per_day\":\"3\",\"course_start_date\":\"2026-08-31\",\"course_end_date\":\"2026-08-31\",\"total_sessions\":null,\"duration_months\":null,\"price\":\"1500\",\"max_students\":\"12\",\"is_active\":\"1\",\"emergency_leave_quota\":\"1\",\"allow_makeup_class\":\"1\",\"is_adult_flexi\":\"0\",\"teacher_ids\":[\"2\",\"1\",\"3\"]}','2026-08-24 17:39:31'),(33,1,'ผู้ดูแลระบบ','admin','DELETE','rooms/4','rooms.destroy',302,'127.0.0.1',NULL,'2026-08-24 17:43:20'),(34,1,'ผู้ดูแลระบบ','admin','DELETE','rooms/3','rooms.destroy',302,'127.0.0.1',NULL,'2026-08-24 17:43:22'),(35,1,'ผู้ดูแลระบบ','admin','DELETE','rooms/2','rooms.destroy',302,'127.0.0.1',NULL,'2026-08-24 17:43:25'),(36,1,'ผู้ดูแลระบบ','admin','POST','equipment-types','equipment-types.store',201,'127.0.0.1','{\"name\":\"\\u0e01\\u0e23\\u0e30\\u0e08\\u0e01\\u0e1d\\u0e36\\u0e01\\u0e17\\u0e48\\u0e32\\u0e17\\u0e32\\u0e07 x1\"}','2026-08-24 17:44:16'),(37,1,'ผู้ดูแลระบบ','admin','POST','rooms','rooms.store',302,'127.0.0.1','{\"room_code\":\"R002\",\"name\":\"\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e44\\u0e27\\u0e42\\u0e2d\\u0e25\\u0e34\\u0e19 1\",\"location\":\"\\u0e0a\\u0e31\\u0e49\\u0e19 2\",\"capacity\":\"2\",\"is_active\":\"1\",\"description\":\"\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e44\\u0e27\\u0e42\\u0e2d\\u0e25\\u0e34\\u0e19\\u0e41\\u0e1a\\u0e1a Private \\u0e21\\u0e35\\u0e09\\u0e19\\u0e27\\u0e19\\u0e01\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e35\\u0e22\\u0e07\",\"equipment\":[{\"equipment_type_id\":\"3\",\"quantity\":\"1\"}]}','2026-08-24 17:44:21'),(38,1,'ผู้ดูแลระบบ','admin','POST','rooms','rooms.store',302,'127.0.0.1','{\"room_code\":\"R006\",\"name\":\"\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e0b\\u0e49\\u0e2d\\u0e21\\u0e27\\u0e07\\u0e40\\u0e04\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07\\u0e2a\\u0e32\\u0e22\",\"location\":\"\\u0e0a\\u0e31\\u0e49\\u0e19 3\",\"capacity\":\"10\",\"is_active\":\"1\",\"description\":\"\\u0e40\\u0e2b\\u0e21\\u0e32\\u0e30\\u0e2a\\u0e33\\u0e2b\\u0e23\\u0e31\\u0e1a\\u0e2a\\u0e2d\\u0e19\\u0e01\\u0e25\\u0e38\\u0e48\\u0e21\\u0e40\\u0e25\\u0e47\\u0e01 \\u0e21\\u0e35\\u0e40\\u0e01\\u0e49\\u0e32\\u0e2d\\u0e35\\u0e49 + \\u0e02\\u0e32\\u0e15\\u0e31\\u0e49\\u0e07\\u0e42\\u0e19\\u0e49\\u0e15\"}','2026-08-24 17:45:22'),(39,1,'ผู้ดูแลระบบ','admin','POST','rooms','rooms.store',302,'127.0.0.1','{\"room_code\":\"R007\",\"name\":\"\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e41\\u0e2a\\u0e14\\u0e07\\u0e14\\u0e19\\u0e15\\u0e23\\u0e35\",\"location\":\"\\u0e0a\\u0e31\\u0e49\\u0e19 1 \\u0e15\\u0e36\\u0e01 A\",\"capacity\":\"50\",\"is_active\":\"1\",\"description\":\"\\u0e43\\u0e0a\\u0e49\\u0e08\\u0e31\\u0e14 Camp, Workshop, Master Class \\u0e41\\u0e25\\u0e30\\u0e07\\u0e32\\u0e19\\u0e41\\u0e2a\\u0e14\\u0e07\\u0e14\\u0e19\\u0e15\\u0e23\\u0e35\\u0e1b\\u0e25\\u0e32\\u0e22\\u0e1b\\u0e35\",\"equipment\":[{\"equipment_type_id\":\"2\",\"quantity\":\"2\"}]}','2026-08-24 17:46:07'),(40,1,'ผู้ดูแลระบบ','admin','POST','rooms','rooms.store',302,'127.0.0.1','{\"room_code\":\"R008\",\"name\":\"\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e01\\u0e25\\u0e2d\\u0e07 1\",\"location\":null,\"capacity\":\"3\",\"is_active\":\"1\",\"description\":null}','2026-08-24 17:46:52'),(41,1,'ผู้ดูแลระบบ','admin','PATCH','rooms/8/maintenance','rooms.maintenance',302,'127.0.0.1','{\"maintenance_reason\":\"\\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e09\\u0e19\\u0e27\\u0e19\\u0e01\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e35\\u0e22\\u0e07\",\"maintenance_from\":\"2026-09-01\",\"maintenance_to\":\"2026-09-15\"}','2026-08-24 17:47:14'),(42,1,'ผู้ดูแลระบบ','admin','POST','rooms','rooms.store',302,'127.0.0.1','{\"room_code\":\"R009\",\"name\":\"\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e40\\u0e01\\u0e48\\u0e32 (\\u0e22\\u0e01\\u0e40\\u0e25\\u0e34\\u0e01\\u0e43\\u0e0a\\u0e49\\u0e07\\u0e32\\u0e19)\",\"location\":null,\"capacity\":\"5\",\"is_active\":\"0\",\"description\":null}','2026-08-24 17:47:56'),(43,1,'ผู้ดูแลระบบ','admin','POST','promotions','promotions.store',302,'127.0.0.1','{\"code\":\"WELCOME100\",\"name\":\"WELCOME\",\"discount_type\":\"percent\",\"discount_value\":\"10\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":null,\"valid_from\":\"2026-08-24\",\"valid_to\":\"2026-09-05\",\"is_active\":\"1\",\"scope\":\"course\",\"applies_to_all\":\"1\"}','2026-08-24 17:57:34'),(44,1,'ผู้ดูแลระบบ','admin','POST','sales/quick-student','sales.quick-student',201,'127.0.0.1','{\"full_name\":\"\\u0e14.\\u0e0d.\\u0e20\\u0e31\\u0e17\\u0e23\\u0e27\\u0e14\\u0e35 \\u0e2a\\u0e34\\u0e19\\u0e17\\u0e27\\u0e35\",\"nickname\":\"\\u0e19\\u0e49\\u0e33\\u0e2b\\u0e27\\u0e32\\u0e19\",\"phone\":\"0891234567\",\"date_of_birth\":\"2009-03-15\"}','2026-08-24 17:58:19'),(45,1,'ผู้ดูแลระบบ','admin','POST','sales','sales.store',302,'127.0.0.1','{\"student_id\":\"10\",\"course_id\":\"9\",\"teacher_id\":\"3\",\"branch\":\"Cloud 11\",\"delivery_mode\":\"hybrid\",\"preferred_day_of_week\":\"1\",\"preferred_start_time\":\"17:00\",\"preferred_end_time\":\"18:00\",\"invoice_type\":\"receipt\",\"is_company\":\"0\",\"buyer_name\":\"\\u0e04\\u0e38\\u0e13\\u0e41\\u0e21\\u0e48\\u0e20\\u0e31\\u0e17\\u0e23\\u0e27\\u0e14\\u0e35\",\"buyer_tax_id\":null,\"buyer_phone\":\"0891234567\",\"buyer_address\":null,\"notes\":null}','2026-08-24 17:59:42'),(46,1,'ผู้ดูแลระบบ','admin','POST','sales/1/apply-discount','sales.apply-discount',302,'127.0.0.1','{\"coupon_code\":\"WELCOME100\",\"use_points\":\"0\",\"use_credit\":\"0\"}','2026-08-24 17:59:48'),(47,1,'ผู้ดูแลระบบ','admin','POST','sales/1/confirm-payment','sales.confirm-payment',302,'127.0.0.1','{\"payment_method\":\"transfer\",\"payment_reference\":null}','2026-08-24 18:00:10'),(48,1,'ผู้ดูแลระบบ','admin','POST','sales','sales.store',302,'127.0.0.1','{\"student_id\":\"4\",\"course_id\":\"8\",\"teacher_id\":null,\"branch\":\"Cloud 11\",\"delivery_mode\":\"online\",\"preferred_day_of_week\":\"3\",\"preferred_start_time\":\"16:00\",\"preferred_end_time\":\"17:00\",\"invoice_type\":\"none\",\"is_company\":\"0\",\"buyer_name\":null,\"buyer_tax_id\":null,\"buyer_phone\":null,\"buyer_address\":null,\"notes\":null}','2026-08-24 18:02:16'),(49,1,'ผู้ดูแลระบบ','admin','POST','sales/2/confirm-payment','sales.confirm-payment',302,'127.0.0.1','{\"payment_method\":\"credit_card\",\"payment_reference\":\"AUTH-889021\"}','2026-08-24 18:02:30'),(50,1,'ผู้ดูแลระบบ','admin','POST','sales','sales.store',302,'127.0.0.1','{\"student_id\":\"3\",\"course_id\":\"11\",\"teacher_id\":null,\"branch\":\"Cloud 11\",\"delivery_mode\":\"onsite\",\"preferred_day_of_week\":null,\"preferred_start_time\":null,\"preferred_end_time\":null,\"invoice_type\":\"tax_invoice\",\"is_company\":\"1\",\"buyer_name\":\"\\u0e1a\\u0e23\\u0e34\\u0e29\\u0e31\\u0e17 \\u0e21\\u0e34\\u0e27\\u0e2a\\u0e34\\u0e04\\u0e40\\u0e25\\u0e34\\u0e23\\u0e4c\\u0e19\\u0e19\\u0e34\\u0e48\\u0e07 \\u0e08\\u0e33\\u0e01\\u0e31\\u0e14\",\"buyer_tax_id\":\"0105566001234\",\"buyer_phone\":\"0891234567\",\"buyer_address\":\"123 \\u0e16.\\u0e2a\\u0e38\\u0e02\\u0e38\\u0e21\\u0e27\\u0e34\\u0e17 \\u0e01\\u0e23\\u0e38\\u0e07\\u0e40\\u0e17\\u0e1e\\u0e2f\",\"notes\":null}','2026-08-24 18:03:50'),(51,1,'ผู้ดูแลระบบ','admin','POST','sales/3/confirm-payment','sales.confirm-payment',302,'127.0.0.1','{\"payment_method\":\"promptpay\",\"payment_reference\":null}','2026-08-24 18:04:01'),(52,1,'ผู้ดูแลระบบ','admin','POST','promotions','promotions.store',302,'127.0.0.1','{\"code\":null,\"name\":\"\\u0e42\\u0e1b\\u0e23\\u0e42\\u0e21\\u0e0a\\u0e31\\u0e19\\u0e15\\u0e49\\u0e2d\\u0e19\\u0e23\\u0e31\\u0e1a\\u0e40\\u0e1b\\u0e34\\u0e14\\u0e40\\u0e17\\u0e2d\\u0e21 \\u0e25\\u0e14 10%\",\"discount_type\":\"percent\",\"discount_value\":\"10\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":null,\"valid_from\":\"2026-09-01\",\"valid_to\":\"2026-09-05\",\"is_active\":\"1\",\"scope\":\"course\",\"applies_to_all\":\"1\"}','2026-08-24 18:06:36'),(53,1,'ผู้ดูแลระบบ','admin','PUT','promotions/1','promotions.update',302,'127.0.0.1','{\"code\":\"WELCOME100\",\"name\":\"WELCOME\",\"discount_type\":\"fixed\",\"discount_value\":\"100\",\"min_spend\":null,\"max_uses\":\"50\",\"per_customer_limit\":\"1\",\"valid_from\":\"2026-08-24\",\"valid_to\":\"2026-09-05\",\"is_active\":\"1\",\"scope\":\"course\",\"course_ids\":[\"8\",\"7\"]}','2026-08-24 18:08:04'),(54,1,'ผู้ดูแลระบบ','admin','POST','promotions','promotions.store',302,'127.0.0.1','{\"code\":\"SPEND1000\",\"name\":\"\\u0e0b\\u0e37\\u0e49\\u0e2d\\u0e04\\u0e23\\u0e1a 1,000 \\u0e25\\u0e14 150\",\"discount_type\":\"spend_get\",\"discount_value\":\"150\",\"min_spend\":\"1000\",\"max_uses\":null,\"per_customer_limit\":null,\"valid_from\":null,\"valid_to\":null,\"is_active\":\"1\",\"scope\":\"both\",\"applies_to_all\":\"1\"}','2026-08-24 18:09:16'),(55,1,'ผู้ดูแลระบบ','admin','POST','promotions','promotions.store',302,'127.0.0.1','{\"code\":null,\"name\":\"\\u0e25\\u0e49\\u0e32\\u0e07\\u0e2a\\u0e15\\u0e4a\\u0e2d\\u0e01\\u0e2d\\u0e38\\u0e1b\\u0e01\\u0e23\\u0e13\\u0e4c\\u0e14\\u0e19\\u0e15\\u0e23\\u0e35 \\u0e25\\u0e14 20%\",\"discount_type\":\"percent\",\"discount_value\":\"20\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":null,\"valid_from\":null,\"valid_to\":null,\"is_active\":\"1\",\"scope\":\"product\",\"applies_to_all\":\"1\"}','2026-08-24 18:09:51'),(56,1,'ผู้ดูแลระบบ','admin','POST','promotions','promotions.store',302,'127.0.0.1','{\"code\":\"VIP2026\",\"name\":\"\\u0e2a\\u0e34\\u0e17\\u0e18\\u0e34\\u0e1e\\u0e34\\u0e40\\u0e28\\u0e29\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 VIP \\u0e25\\u0e14 500 \\u0e1a\\u0e32\\u0e17\",\"discount_type\":\"fixed\",\"discount_value\":\"500\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":\"1\",\"valid_from\":null,\"valid_to\":null,\"scope\":\"course\",\"applies_to_all\":\"1\"}','2026-08-24 18:10:47'),(57,1,'ผู้ดูแลระบบ','admin','PUT','promotions/5','promotions.update',302,'127.0.0.1','{\"code\":\"VIP2026\",\"name\":\"\\u0e2a\\u0e34\\u0e17\\u0e18\\u0e34\\u0e1e\\u0e34\\u0e40\\u0e28\\u0e29\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 VIP \\u0e25\\u0e14 500 \\u0e1a\\u0e32\\u0e17\",\"discount_type\":\"fixed\",\"discount_value\":\"500.00\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":\"1\",\"valid_from\":null,\"valid_to\":null,\"scope\":\"course\",\"applies_to_all\":\"1\"}','2026-08-24 18:10:56'),(58,1,'ผู้ดูแลระบบ','admin','PATCH','promotions/5/toggle-status','promotions.toggle-status',302,'127.0.0.1',NULL,'2026-08-24 18:11:00'),(59,1,'ผู้ดูแลระบบ','admin','PUT','promotions/5','promotions.update',302,'127.0.0.1','{\"code\":\"VIP2026\",\"name\":\"\\u0e2a\\u0e34\\u0e17\\u0e18\\u0e34\\u0e1e\\u0e34\\u0e40\\u0e28\\u0e29\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 VIP \\u0e25\\u0e14 500 \\u0e1a\\u0e32\\u0e17\",\"discount_type\":\"fixed\",\"discount_value\":\"500.00\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":\"1\",\"valid_from\":null,\"valid_to\":null,\"is_active\":\"1\",\"scope\":\"course\",\"applies_to_all\":\"1\"}','2026-08-24 18:11:04'),(60,1,'ผู้ดูแลระบบ','admin','PUT','promotions/5','promotions.update',302,'127.0.0.1','{\"code\":\"VIP2026\",\"name\":\"\\u0e2a\\u0e34\\u0e17\\u0e18\\u0e34\\u0e1e\\u0e34\\u0e40\\u0e28\\u0e29\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 VIP \\u0e25\\u0e14 500 \\u0e1a\\u0e32\\u0e17\",\"discount_type\":\"fixed\",\"discount_value\":\"500.00\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":\"1\",\"valid_from\":null,\"valid_to\":null,\"is_active\":\"1\",\"scope\":\"course\",\"applies_to_all\":\"1\"}','2026-08-24 18:15:39'),(61,1,'ผู้ดูแลระบบ','admin','PUT','promotions/5','promotions.update',302,'127.0.0.1','{\"code\":\"VIP2026\",\"name\":\"\\u0e2a\\u0e34\\u0e17\\u0e18\\u0e34\\u0e1e\\u0e34\\u0e40\\u0e28\\u0e29\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 VIP \\u0e25\\u0e14 500 \\u0e1a\\u0e32\\u0e17\",\"discount_type\":\"fixed\",\"discount_value\":\"500.00\",\"min_spend\":null,\"max_uses\":null,\"per_customer_limit\":\"1\",\"valid_from\":null,\"valid_to\":null,\"scope\":\"course\",\"applies_to_all\":\"1\"}','2026-08-24 18:15:44'),(62,1,'ผู้ดูแลระบบ','admin','POST','sales','sales.store',302,'127.0.0.1','{\"student_id\":\"9\",\"course_id\":\"13\",\"teacher_id\":\"2\",\"branch\":\"Cloud 11\",\"delivery_mode\":\"onsite\",\"preferred_day_of_week\":null,\"preferred_start_time\":null,\"preferred_end_time\":null,\"invoice_type\":\"none\",\"is_company\":\"0\",\"buyer_name\":null,\"buyer_tax_id\":null,\"buyer_phone\":null,\"buyer_address\":null,\"notes\":null}','2026-08-24 18:16:08'),(63,1,'ผู้ดูแลระบบ','admin','PATCH','sales/4/cancel','sales.cancel',302,'127.0.0.1',NULL,'2026-08-24 18:20:06'),(64,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-preview','schedules.bulk-preview',200,'127.0.0.1','{\"enrollment_id\":\"2\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\",\"mode\":\"weekly\",\"days_of_week\":[\"3\"],\"start_date\":\"2026-09-02\",\"session_count\":\"8\",\"start_time\":\"16:00\",\"end_time\":\"17:00\"}','2026-08-24 18:43:47'),(65,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-confirm','schedules.bulk-confirm',302,'127.0.0.1','{\"enrollment_id\":\"2\",\"notes\":null,\"rows\":[{\"date\":\"2026-09-02\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"},{\"date\":\"2026-09-09\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"},{\"date\":\"2026-09-16\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"},{\"date\":\"2026-09-23\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"},{\"date\":\"2026-09-30\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"},{\"date\":\"2026-10-07\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"},{\"date\":\"2026-10-14\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"},{\"date\":\"2026-10-21\",\"start_time\":\"16:00\",\"end_time\":\"17:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"online\"}]}','2026-08-24 18:43:52'),(66,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-preview','schedules.bulk-preview',200,'127.0.0.1','{\"enrollment_id\":\"1\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\",\"mode\":\"weekly\",\"days_of_week\":[\"1\"],\"start_date\":\"2026-09-07\",\"session_count\":\"12\",\"start_time\":\"17:00\",\"end_time\":\"18:00\"}','2026-08-24 18:44:31'),(67,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-confirm','schedules.bulk-confirm',302,'127.0.0.1','{\"enrollment_id\":\"1\",\"notes\":null,\"rows\":[{\"date\":\"2026-09-07\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-09-14\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-09-21\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-09-28\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-10-05\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-10-12\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-10-19\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-10-26\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-11-02\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-11-09\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-11-16\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"},{\"date\":\"2026-11-23\",\"start_time\":\"17:00\",\"end_time\":\"18:00\",\"teacher_id\":\"3\",\"room_id\":null,\"delivery_mode\":\"hybrid\"}]}','2026-08-24 18:44:35'),(68,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-preview','schedules.bulk-preview',200,'127.0.0.1','{\"enrollment_id\":\"3\",\"teacher_id\":\"6\",\"room_id\":null,\"delivery_mode\":\"onsite\",\"mode\":\"daily_range\",\"days_of_week\":[],\"start_date\":\"2026-08-24\",\"session_count\":null,\"start_time\":\"10:45\",\"end_time\":\"14:45\"}','2026-08-24 18:45:30'),(69,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-confirm','schedules.bulk-confirm',302,'127.0.0.1','{\"enrollment_id\":\"3\",\"notes\":null,\"rows\":[{\"date\":\"2026-10-20\",\"start_time\":\"10:45\",\"end_time\":\"14:45\",\"teacher_id\":\"6\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-21\",\"start_time\":\"10:45\",\"end_time\":\"14:45\",\"teacher_id\":\"6\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-22\",\"start_time\":\"10:45\",\"end_time\":\"14:45\",\"teacher_id\":\"6\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-23\",\"start_time\":\"10:45\",\"end_time\":\"14:45\",\"teacher_id\":\"6\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-24\",\"start_time\":\"10:45\",\"end_time\":\"14:45\",\"teacher_id\":\"6\",\"room_id\":null,\"delivery_mode\":\"onsite\"}]}','2026-08-24 18:45:33'),(70,1,'ผู้ดูแลระบบ','admin','PATCH','rooms/8/maintenance','rooms.maintenance',302,'127.0.0.1',NULL,'2026-08-24 18:50:43'),(71,1,'ผู้ดูแลระบบ','admin','PATCH','rooms/8/maintenance','rooms.maintenance',302,'127.0.0.1','{\"maintenance_reason\":\"\\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e09\\u0e19\\u0e27\\u0e19\\u0e01\\u0e31\\u0e19\\u0e40\\u0e2a\\u0e35\\u0e22\\u0e07\",\"maintenance_from\":\"2026-08-24\",\"maintenance_to\":\"2026-09-05\"}','2026-08-24 18:50:53'),(72,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-24 19:14:54'),(73,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-24 19:15:00'),(74,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-24 19:30:21'),(75,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\"}','2026-08-24 19:30:31'),(76,1,'ผู้ดูแลระบบ','admin','PUT','schedules/1','schedules.update',302,'127.0.0.1','{\"enrollment_id\":\"2\",\"teacher_id\":\"3\",\"room_id\":null,\"schedule_date\":\"2026-08-25\",\"start_time\":\"16:00:00\",\"end_time\":\"17:00:00\",\"delivery_mode\":\"online\",\"status\":\"scheduled\",\"notes\":null}','2026-08-24 19:31:45'),(77,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-24 19:31:48'),(78,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-24 19:31:56'),(79,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-24 19:32:05'),(80,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\"}','2026-08-24 19:32:12'),(81,1,'ผู้ดูแลระบบ','admin','PUT','schedules/1','schedules.update',302,'127.0.0.1','{\"enrollment_id\":\"2\",\"teacher_id\":\"3\",\"room_id\":null,\"schedule_date\":\"2026-08-24\",\"start_time\":\"16:00:00\",\"end_time\":\"17:00:00\",\"delivery_mode\":\"online\",\"status\":\"scheduled\",\"notes\":null}','2026-08-24 19:32:21'),(82,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-24 19:32:23'),(83,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-24 19:32:29'),(84,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/3/check-in','teaching-logs.check-in',302,'127.0.0.1','{\"attendance_status\":\"present\",\"notes\":null}','2026-08-24 19:57:14'),(85,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/3/confirm-duration','teaching-logs.confirm-duration',302,'127.0.0.1','{\"duration_minutes\":\"45\",\"is_extra_time\":false,\"km_traveled\":null}','2026-08-24 19:57:14'),(86,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/3/report','teaching-reports.store',302,'127.0.0.1','{\"content_taught\":\"\\u0e2a\\u0e2d\\u0e19\\u0e1d\\u0e36\\u0e01\\u0e19\\u0e34\\u0e49\\u0e27\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\",\"homework\":\"\\u0e1d\\u0e36\\u0e01\\u0e19\\u0e34\\u0e49\\u0e27\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\",\"progress_notes\":\"\\u0e02\\u0e22\\u0e31\\u0e1a\\u0e19\\u0e34\\u0e49\\u0e27\\u0e44\\u0e14\\u0e49\\u0e14\\u0e35\\u0e02\\u0e36\\u0e49\\u0e19\",\"notes\":null}','2026-08-24 19:59:29'),(87,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/3/report','teaching-reports.store',302,'127.0.0.1','{\"content_taught\":\"\\u0e2a\\u0e2d\\u0e19\\u0e1d\\u0e36\\u0e01\\u0e19\\u0e34\\u0e49\\u0e27\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\",\"homework\":\"\\u0e1d\\u0e36\\u0e01\\u0e19\\u0e34\\u0e49\\u0e27\\u0e40\\u0e1b\\u0e35\\u0e22\\u0e42\\u0e19\",\"progress_notes\":\"\\u0e02\\u0e22\\u0e31\\u0e1a\\u0e19\\u0e34\\u0e49\\u0e27\\u0e44\\u0e14\\u0e49\\u0e14\\u0e35\\u0e02\\u0e36\\u0e49\\u0e19\",\"notes\":null}','2026-08-24 20:10:26'),(88,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-24 20:11:28'),(89,10,'กัญญารัตน์ ศรีสุข','student','POST','login',NULL,302,'127.0.0.1','{\"email\":\"kanyarat@gmail.com\"}','2026-08-24 20:11:37'),(90,10,'กัญญารัตน์ ศรีสุข','student','POST','teaching-reports/1/homework-submissions','homework-submissions.store',302,'127.0.0.1','{\"student_note\":null,\"files\":[{}]}','2026-08-24 20:11:52'),(91,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-24 20:12:05'),(92,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-24 20:12:17'),(93,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-25 14:46:00'),(94,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/4/check-in','teaching-logs.check-in',302,'127.0.0.1','{\"attendance_status\":\"present\",\"notes\":null}','2026-08-25 15:32:06'),(95,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/4/confirm-duration','teaching-logs.confirm-duration',302,'127.0.0.1','{\"duration_minutes\":\"60\",\"is_extra_time\":false,\"km_traveled\":null}','2026-08-25 15:32:06'),(96,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/4/report','teaching-reports.store',302,'127.0.0.1','{\"content_taught\":null,\"progress_notes\":null,\"homework\":\"\\u0e17\\u0e14\\u0e2a\\u0e2d\\u0e1a\",\"notes\":null}','2026-08-25 15:40:24'),(97,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 15:40:45'),(98,10,'กัญญารัตน์ ศรีสุข','student','POST','login',NULL,302,'127.0.0.1','{\"email\":\"kanyarat@gmail.com\"}','2026-08-25 15:40:53'),(99,10,'กัญญารัตน์ ศรีสุข','student','POST','notifications/mark-all-read','notifications.mark-all-read',302,'127.0.0.1',NULL,'2026-08-25 15:47:05'),(100,10,'กัญญารัตน์ ศรีสุข','student','POST','teaching-reports/2/homework-submissions','homework-submissions.store',302,'127.0.0.1','{\"student_note\":\"\\u0e1b\\u0e1b\"}','2026-08-25 15:59:11'),(101,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 15:59:16'),(102,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-25 15:59:26'),(103,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 16:00:30'),(104,10,'กัญญารัตน์ ศรีสุข','student','POST','login',NULL,302,'127.0.0.1','{\"email\":\"kanyarat@gmail.com\"}','2026-08-25 16:00:36'),(105,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 16:09:04'),(106,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-25 16:09:15'),(107,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','homework-submissions/2/review','homework-submissions.review',302,'127.0.0.1','{\"feedback\":null,\"status\":\"approved\"}','2026-08-25 16:12:41'),(108,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 16:12:50'),(109,10,'กัญญารัตน์ ศรีสุข','student','POST','login',NULL,302,'127.0.0.1','{\"email\":\"kanyarat@gmail.com\"}','2026-08-25 16:12:59'),(110,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 16:13:40'),(111,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-25 16:13:48'),(112,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-25 16:13:49'),(113,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/5/check-in','teaching-logs.check-in',302,'127.0.0.1','{\"attendance_status\":\"present\",\"notes\":null}','2026-08-25 16:14:29'),(114,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/5/confirm-duration','teaching-logs.confirm-duration',302,'127.0.0.1','{\"duration_minutes\":\"60\",\"is_extra_time\":false,\"km_traveled\":null}','2026-08-25 16:14:29'),(115,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/5/report','teaching-reports.store',302,'127.0.0.1','{\"content_taught\":\"xxxxx\",\"progress_notes\":\"xxxxx\",\"homework\":\"xxxxxxxxxxxx\",\"notes\":null,\"attachments\":[{}]}','2026-08-25 16:14:59'),(116,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/5/evidences','teaching-evidences.store',302,'127.0.0.1','{\"files\":[{}]}','2026-08-25 16:15:06'),(117,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 16:15:16'),(118,10,'กัญญารัตน์ ศรีสุข','student','POST','login',NULL,302,'127.0.0.1','{\"email\":\"kanyarat@gmail.com\"}','2026-08-25 16:15:22'),(119,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 16:15:34'),(120,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-25 16:15:41'),(121,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/6/check-in','teaching-logs.check-in',302,'127.0.0.1','{\"attendance_status\":\"present\",\"notes\":null}','2026-08-25 16:18:02'),(122,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/6/confirm-duration','teaching-logs.confirm-duration',302,'127.0.0.1','{\"duration_minutes\":\"60\",\"is_extra_time\":false,\"km_traveled\":null}','2026-08-25 16:18:02'),(123,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/6/report','teaching-reports.store',302,'127.0.0.1','{\"content_taught\":\"xxxxxxxxxxxxxx\",\"progress_notes\":\"xxxxxxxxxxxxxx\",\"homework\":\"xxxxxxxxxxxxxx\",\"notes\":\"xxxxxxxxxxxxxx\"}','2026-08-25 16:18:21'),(124,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/6/evidences','teaching-evidences.store',302,'127.0.0.1','{\"files\":[{}]}','2026-08-25 16:18:38'),(125,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-25 16:19:08'),(126,10,'กัญญารัตน์ ศรีสุข','student','POST','login',NULL,302,'127.0.0.1','{\"email\":\"kanyarat@gmail.com\"}','2026-08-25 16:19:15'),(127,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 01:40:44'),(128,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 01:40:52'),(129,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 01:42:51'),(130,10,'กัญญารัตน์ ศรีสุข','student','POST','login',NULL,302,'127.0.0.1','{\"email\":\"kanyarat@gmail.com\"}','2026-08-26 01:42:57'),(131,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 02:53:28'),(132,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 02:53:36'),(133,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 14:18:49'),(134,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 14:34:03'),(135,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 14:34:10'),(136,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 14:34:55'),(137,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\"}','2026-08-26 14:35:03'),(138,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 14:39:05'),(139,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login','generated::9YkyC4R776jC752z',302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 14:39:12'),(140,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 14:53:05'),(141,1,'ผู้ดูแลระบบ','admin','POST','login','generated::25RaDqFpEQ6nxV63',302,'127.0.0.1','{\"email\":\"admin@viemus.school\"}','2026-08-26 14:53:14'),(142,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 14:53:46'),(143,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login','generated::25RaDqFpEQ6nxV63',302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 14:53:55'),(144,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/10/check-in','teaching-logs.check-in',302,'127.0.0.1','{\"attendance_status\":\"present\",\"notes\":null}','2026-08-26 15:03:26'),(145,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','teaching-logs/10/confirm-duration','teaching-logs.confirm-duration',302,'127.0.0.1','{\"duration_minutes\":\"45\",\"is_extra_time\":false,\"km_traveled\":null}','2026-08-26 15:03:27'),(146,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','reschedule-requests','reschedule-requests.store',302,'127.0.0.1','{\"type\":\"change\",\"class_schedule_id\":\"8\",\"new_teacher_id\":null,\"new_room_id\":null,\"new_date\":\"2026-10-22\",\"new_start_time\":\"15:41\",\"new_end_time\":\"16:41\",\"swap_with_class_schedule_id\":null,\"reason\":null}','2026-08-26 15:41:36'),(147,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 15:41:56'),(148,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\"}','2026-08-26 15:42:22'),(149,1,'ผู้ดูแลระบบ','admin','POST','reschedule-requests/1/approve','reschedule-requests.approve',302,'127.0.0.1',NULL,'2026-08-26 15:42:45'),(150,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-26 15:42:56'),(151,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\"}','2026-08-26 15:43:06'),(152,1,'ผู้ดูแลระบบ','admin','POST','login','generated::20e6Ojbx1o9vCNvO',302,'127.0.0.1','{\"email\":\"admin@viemus.school\"}','2026-08-29 08:47:53'),(153,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-29 13:20:38'),(154,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\"}','2026-08-29 14:02:08'),(155,1,'ผู้ดูแลระบบ','admin','POST','enrollments/2/run-throughs','run-throughs.store',500,'127.0.0.1','{\"title\":\"xxx\",\"description\":\"xxx\"}','2026-08-29 14:02:26'),(156,1,'ผู้ดูแลระบบ','admin','POST','enrollments/2/run-throughs','run-throughs.store',500,'127.0.0.1','{\"title\":\"d\",\"description\":\"d\"}','2026-08-29 14:18:22'),(157,1,'ผู้ดูแลระบบ','admin','POST','enrollments/2/run-throughs','run-throughs.store',302,'127.0.0.1','{\"title\":\"d\",\"description\":\"d\"}','2026-08-29 14:21:14'),(158,1,'ผู้ดูแลระบบ','admin','POST','run-throughs/1/record-result','run-throughs.record-result',302,'127.0.0.1','{\"practice_result\":\"excellent\",\"areas_to_improve\":\"xx\",\"teacher_comment\":\"xx\"}','2026-08-29 14:21:34'),(159,1,'ผู้ดูแลระบบ','admin','POST','sales','sales.store',302,'127.0.0.1','{\"student_id\":\"3\",\"course_id\":\"7\",\"teacher_id\":\"2\",\"branch\":\"Cloud 11\",\"delivery_mode\":\"onsite\",\"preferred_day_of_week\":\"2\",\"preferred_start_time\":\"10:24\",\"preferred_end_time\":\"15:24\",\"invoice_type\":\"none\",\"is_company\":\"0\",\"buyer_name\":null,\"buyer_tax_id\":null,\"buyer_phone\":null,\"buyer_address\":null,\"notes\":null}','2026-08-29 14:24:22'),(160,1,'ผู้ดูแลระบบ','admin','POST','sales/5/confirm-payment','sales.confirm-payment',302,'127.0.0.1','{\"payment_method\":\"transfer\",\"payment_reference\":null}','2026-08-29 14:24:38'),(161,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-preview','schedules.bulk-preview',200,'127.0.0.1','{\"enrollment_id\":\"4\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\",\"mode\":\"weekly\",\"days_of_week\":[\"2\"],\"start_date\":\"2026-08-29\",\"session_count\":\"12\",\"start_time\":\"10:25\",\"end_time\":\"15:25\"}','2026-08-29 14:25:15'),(162,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-confirm','schedules.bulk-confirm',302,'127.0.0.1','{\"enrollment_id\":\"4\",\"notes\":null,\"rows\":[{\"date\":\"2026-09-01\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-09-08\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-09-15\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-09-22\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-09-29\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-06\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-13\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-11-24\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-27\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-11-03\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-11-10\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"},{\"date\":\"2026-11-17\",\"start_time\":\"10:25\",\"end_time\":\"15:25\",\"teacher_id\":\"2\",\"room_id\":\"5\",\"delivery_mode\":\"onsite\"}]}','2026-08-29 14:25:38'),(163,1,'ผู้ดูแลระบบ','admin','POST','sales','sales.store',302,'127.0.0.1','{\"student_id\":\"2\",\"course_id\":\"8\",\"teacher_id\":\"1\",\"branch\":\"Cloud 11\",\"delivery_mode\":\"onsite\",\"preferred_day_of_week\":\"5\",\"preferred_start_time\":\"11:26\",\"preferred_end_time\":\"15:26\",\"invoice_type\":\"none\",\"is_company\":\"0\",\"buyer_name\":null,\"buyer_tax_id\":null,\"buyer_phone\":null,\"buyer_address\":null,\"notes\":null}','2026-08-29 14:26:52'),(164,1,'ผู้ดูแลระบบ','admin','POST','sales/6/confirm-payment','sales.confirm-payment',302,'127.0.0.1','{\"payment_method\":\"credit_card\",\"payment_reference\":\"1212\"}','2026-08-29 14:27:04'),(165,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-preview','schedules.bulk-preview',200,'127.0.0.1','{\"enrollment_id\":\"5\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\",\"mode\":\"weekly\",\"days_of_week\":[\"5\"],\"start_date\":\"2026-08-29\",\"session_count\":\"8\",\"start_time\":\"10:27\",\"end_time\":\"15:27\"}','2026-08-29 14:27:53'),(166,1,'ผู้ดูแลระบบ','admin','POST','schedules/bulk-confirm','schedules.bulk-confirm',302,'127.0.0.1','{\"enrollment_id\":\"5\",\"notes\":null,\"rows\":[{\"date\":\"2026-09-04\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-09-11\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-09-18\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-09-25\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-02\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-09\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-16\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"},{\"date\":\"2026-10-23\",\"start_time\":\"10:27\",\"end_time\":\"15:27\",\"teacher_id\":\"1\",\"room_id\":null,\"delivery_mode\":\"onsite\"}]}','2026-08-29 14:27:56'),(167,1,'ผู้ดูแลระบบ','admin','POST','course-transfers','course-transfers.store',302,'127.0.0.1','{\"old_enrollment_id\":\"3\",\"new_course_id\":\"12\",\"new_teacher_id\":\"1\",\"teacher_change_fee\":\"0\",\"reason\":null,\"notes\":null}','2026-08-29 14:29:56'),(168,1,'ผู้ดูแลระบบ','admin','POST','course-transfers/1/confirm-payment','course-transfers.confirm-payment',302,'127.0.0.1','{\"payment_method\":\"transfer\",\"payment_reference\":null}','2026-08-29 14:30:04'),(169,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-29 14:31:04'),(170,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-29 14:35:43'),(171,1,'ผู้ดูแลระบบ','admin','PATCH','students/3/enrollments/3/status','students.enrollments.status',302,'127.0.0.1','{\"status\":\"active\"}','2026-08-29 14:38:38'),(172,1,'ผู้ดูแลระบบ','admin','PATCH','students/3/enrollments/3/status','students.enrollments.status',302,'127.0.0.1','{\"status\":\"cancelled\"}','2026-08-29 14:38:45'),(173,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-29 14:38:53'),(174,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-29 14:39:02'),(175,NULL,NULL,NULL,'POST','login',NULL,419,'127.0.0.1','{\"email\":\"instructor@viemus.com\"}','2026-08-29 14:47:50'),(176,NULL,NULL,NULL,'POST','login',NULL,302,'127.0.0.1','{\"email\":\"instructor@viemus.com\"}','2026-08-29 14:48:27'),(177,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 13:37:18'),(178,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-30 13:46:09'),(179,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 13:59:55'),(180,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-30 14:00:02'),(181,1,'ผู้ดูแลระบบ','admin','POST','trial-leads','trial-leads.store',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"2\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500\",\"payment_status\":\"unpaid\",\"next_follow_up_date\":\"2026-09-04\",\"notes\":null}','2026-08-30 14:46:56'),(182,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"payment_status\":\"unpaid\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"scheduled\",\"trial_result\":null,\"teacher_feedback\":null,\"notes\":null}','2026-08-30 14:47:45'),(183,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 14:47:51'),(184,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-30 14:47:59'),(185,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 14:48:43'),(186,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-30 14:48:50'),(187,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"contacted\",\"trial_result\":null,\"teacher_feedback\":null,\"notes\":null}','2026-08-30 15:15:47'),(188,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"new\",\"trial_result\":null,\"teacher_feedback\":null,\"notes\":null}','2026-08-30 15:15:55'),(189,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"scheduled\",\"trial_result\":null,\"teacher_feedback\":null,\"notes\":null}','2026-08-30 15:15:58'),(190,1,'ผู้ดูแลระบบ','admin','POST','trial-leads/1/payments','trial-payments.store',302,'127.0.0.1','{\"payment_method\":\"promptpay\",\"amount\":\"2500\",\"transaction_at\":\"2026-08-30T15:17\",\"reference_no\":null,\"notes\":null}','2026-08-30 15:17:58'),(191,1,'ผู้ดูแลระบบ','admin','POST','trial-payments/1/confirm','trial-payments.confirm',302,'127.0.0.1',NULL,'2026-08-30 15:18:04'),(192,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:19:31'),(193,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:19:45'),(194,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','my-trial-leads/1/confirm','trial-leads.teacher-confirm',302,'127.0.0.1',NULL,'2026-08-30 16:19:53'),(195,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:19:57'),(196,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:20:04'),(197,1,'ผู้ดูแลระบบ','admin','POST','trial-leads/1/confirmation-status','trial-leads.confirmation-status',302,'127.0.0.1','{\"confirmation_status\":\"guardian_confirmed\",\"confirmation_notes\":null}','2026-08-30 16:20:23'),(198,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:20:35'),(199,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:20:41'),(200,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:20:50'),(201,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:20:57'),(202,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"lost\",\"trial_result\":\"considering\",\"teacher_feedback\":\"\\u0e19\\u0e31\\u0e01\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e41\\u0e08\\u0e49\\u0e07\\u0e02\\u0e2d\\u0e1e\\u0e34\\u0e08\\u0e32\\u0e23\\u0e13\\u0e32\\u0e2d\\u0e35\\u0e01\\u0e04\\u0e23\\u0e31\\u0e49\\u0e07\",\"notes\":null}','2026-08-30 16:21:52'),(203,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"scheduled\",\"trial_result\":\"considering\",\"teacher_feedback\":\"\\u0e19\\u0e31\\u0e01\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e41\\u0e08\\u0e49\\u0e07\\u0e02\\u0e2d\\u0e1e\\u0e34\\u0e08\\u0e32\\u0e23\\u0e13\\u0e32\\u0e2d\\u0e35\\u0e01\\u0e04\\u0e23\\u0e31\\u0e49\\u0e07\",\"notes\":null}','2026-08-30 16:21:55'),(204,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"completed\",\"trial_result\":\"considering\",\"teacher_feedback\":\"\\u0e19\\u0e31\\u0e01\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e41\\u0e08\\u0e49\\u0e07\\u0e02\\u0e2d\\u0e1e\\u0e34\\u0e08\\u0e32\\u0e23\\u0e13\\u0e32\\u0e2d\\u0e35\\u0e01\\u0e04\\u0e23\\u0e31\\u0e49\\u0e07\",\"notes\":null}','2026-08-30 16:21:57'),(205,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:22:02'),(206,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:22:12'),(207,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:22:20'),(208,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:22:28'),(209,1,'ผู้ดูแลระบบ','admin','PUT','trial-leads/1','trial-leads.update',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"12\",\"date_of_birth\":\"2004-02-05\",\"guardian_name\":\"test\",\"phone\":\"0423398321\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"7\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:46\",\"trial_end_time\":\"16:46\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"2500.00\",\"next_follow_up_date\":\"2026-09-04\",\"status\":\"completed\",\"trial_result\":\"considering\",\"teacher_feedback\":\"\\u0e19\\u0e31\\u0e01\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\u0e41\\u0e08\\u0e49\\u0e07\\u0e02\\u0e2d\\u0e1e\\u0e34\\u0e08\\u0e32\\u0e23\\u0e13\\u0e32\\u0e2d\\u0e35\\u0e01\\u0e04\\u0e23\\u0e31\\u0e49\\u0e07\",\"notes\":null}','2026-08-30 16:22:37'),(210,1,'ผู้ดูแลระบบ','admin','POST','trial-leads','trial-leads.store',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":null,\"date_of_birth\":\"2026-08-30\",\"guardian_name\":\"test\",\"phone\":\"081-234-5678\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"5\",\"trial_date\":\"2026-08-30\",\"trial_start_time\":\"23:26\",\"trial_end_time\":\"00:26\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"1500\",\"next_follow_up_date\":\"2026-08-30\",\"payment_method\":\"promptpay\",\"payment_amount\":\"1500\",\"payment_reference_no\":null,\"payment_notes\":null,\"notes\":null}','2026-08-30 16:27:05'),(211,1,'ผู้ดูแลระบบ','admin','POST','trial-leads','trial-leads.store',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"31\",\"date_of_birth\":\"2014-02-28\",\"guardian_name\":\"test\",\"phone\":\"081-234-5678\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"5\",\"trial_date\":\"2026-08-30\",\"trial_start_time\":\"23:26\",\"trial_end_time\":\"00:26\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"1500\",\"next_follow_up_date\":\"2026-08-30\",\"payment_method\":\"promptpay\",\"payment_amount\":\"1500\",\"payment_reference_no\":null,\"payment_notes\":null,\"notes\":null}','2026-08-30 16:27:40'),(212,1,'ผู้ดูแลระบบ','admin','POST','trial-leads','trial-leads.store',302,'127.0.0.1','{\"student_name\":\"test\",\"nickname\":\"test\",\"age\":\"31\",\"date_of_birth\":\"2014-02-28\",\"guardian_name\":\"test\",\"phone\":\"081-234-5678\",\"email\":\"test2@gmail.com\",\"line_id\":\"qqq\",\"source\":null,\"interest\":\"test\",\"preferred_schedule\":\"\\u0e40\\u0e2a\\u0e32\\u0e23\\u0e4c\",\"course_id\":\"8\",\"teacher_id\":\"3\",\"room_id\":\"5\",\"trial_date\":\"2026-08-31\",\"trial_start_time\":\"10:26\",\"trial_end_time\":\"13:26\",\"delivery_mode\":\"onsite\",\"trial_fee\":\"1500\",\"next_follow_up_date\":\"2026-08-30\",\"payment_method\":\"promptpay\",\"payment_amount\":\"1500\",\"payment_reference_no\":null,\"payment_notes\":null,\"notes\":null}','2026-08-30 16:28:11'),(213,1,'ผู้ดูแลระบบ','admin','POST','trial-payments/2/confirm','trial-payments.confirm',302,'127.0.0.1',NULL,'2026-08-30 16:28:23'),(214,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:28:42'),(215,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:28:51'),(216,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','my-trial-leads/3/confirm','trial-leads.teacher-confirm',302,'127.0.0.1',NULL,'2026-08-30 16:28:58'),(217,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:29:01'),(218,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:29:14'),(219,1,'ผู้ดูแลระบบ','admin','POST','trial-leads/3/confirmation-status','trial-leads.confirmation-status',302,'127.0.0.1','{\"confirmation_status\":\"guardian_confirmed\",\"confirmation_notes\":null}','2026-08-30 16:29:22'),(220,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:29:30'),(221,9,'สรวิชญ์ พิทักษ์ธรรม','teacher','POST','login',NULL,302,'127.0.0.1','{\"email\":\"toey.guitar@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:29:38'),(222,NULL,NULL,NULL,'POST','logout','logout',302,'127.0.0.1',NULL,'2026-08-30 16:35:43'),(223,1,'ผู้ดูแลระบบ','admin','POST','login',NULL,302,'127.0.0.1','{\"email\":\"admin@viemus.school\",\"remember\":\"on\"}','2026-08-30 16:35:53');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
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
INSERT INTO `cache` VALUES ('laravel-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897','i:2;',1788107791),('laravel-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer','i:1788107791;',1788107791),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:6;',1788110087),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1788110087;',1788110087),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:2;',1788107803),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1788107803;',1788107803);
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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_schedules`
--

LOCK TABLES `class_schedules` WRITE;
/*!40000 ALTER TABLE `class_schedules` DISABLE KEYS */;
INSERT INTO `class_schedules` VALUES (1,2,3,NULL,'2026-08-24','16:00:00','17:00:00','online','completed',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-25 08:32:06'),(2,2,3,NULL,'2026-09-09','16:00:00','17:00:00','online','completed',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-25 09:18:02'),(3,2,3,NULL,'2026-09-16','16:00:00','17:00:00','online','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-24 11:43:52'),(4,2,3,NULL,'2026-09-23','16:00:00','17:00:00','online','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-24 11:43:52'),(5,2,3,NULL,'2026-09-30','16:00:00','17:00:00','online','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-24 11:43:52'),(6,2,3,NULL,'2026-10-07','16:00:00','17:00:00','online','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-24 11:43:52'),(7,2,3,NULL,'2026-10-14','16:00:00','17:00:00','online','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-24 11:43:52'),(8,2,3,NULL,'2026-10-22','15:41:00','16:41:00','online','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:43:52','2026-08-26 08:42:45'),(9,1,3,NULL,'2026-09-07','17:00:00','18:00:00','hybrid','completed',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-25 09:14:29'),(10,1,3,NULL,'2026-09-14','17:00:00','18:00:00','hybrid','completed',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-26 08:03:27'),(11,1,3,NULL,'2026-09-21','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(12,1,3,NULL,'2026-09-28','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(13,1,3,NULL,'2026-10-05','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(14,1,3,NULL,'2026-10-12','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(15,1,3,NULL,'2026-10-19','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(16,1,3,NULL,'2026-10-26','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(17,1,3,NULL,'2026-11-02','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(18,1,3,NULL,'2026-11-09','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(19,1,3,NULL,'2026-11-16','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(20,1,3,NULL,'2026-11-23','17:00:00','18:00:00','hybrid','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:44:35','2026-08-24 11:44:35'),(21,3,6,NULL,'2026-10-20','10:45:00','14:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:45:33','2026-08-24 11:45:33'),(22,3,6,NULL,'2026-10-21','10:45:00','14:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:45:33','2026-08-24 11:45:33'),(23,3,6,NULL,'2026-10-22','10:45:00','14:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:45:33','2026-08-24 11:45:33'),(24,3,6,NULL,'2026-10-23','10:45:00','14:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:45:33','2026-08-24 11:45:33'),(25,3,6,NULL,'2026-10-24','10:45:00','14:45:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-24 11:45:33','2026-08-24 11:45:33'),(26,4,2,5,'2026-09-01','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(27,4,2,5,'2026-09-08','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(28,4,2,5,'2026-09-15','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(29,4,2,5,'2026-09-22','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(30,4,2,5,'2026-09-29','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(31,4,2,5,'2026-10-06','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(32,4,2,5,'2026-10-13','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(33,4,2,5,'2026-11-24','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(34,4,2,5,'2026-10-27','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(35,4,2,5,'2026-11-03','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(36,4,2,5,'2026-11-10','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(37,4,2,5,'2026-11-17','10:25:00','15:25:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:25:38','2026-08-29 07:25:38'),(38,5,1,NULL,'2026-09-04','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56'),(39,5,1,NULL,'2026-09-11','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56'),(40,5,1,NULL,'2026-09-18','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56'),(41,5,1,NULL,'2026-09-25','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56'),(42,5,1,NULL,'2026-10-02','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56'),(43,5,1,NULL,'2026-10-09','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56'),(44,5,1,NULL,'2026-10-16','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56'),(45,5,1,NULL,'2026-10-23','10:27:00','15:27:00','onsite','scheduled',NULL,'ผู้ดูแลระบบ','2026-08-29 07:27:56','2026-08-29 07:27:56');
/*!40000 ALTER TABLE `class_schedules` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_teacher`
--

LOCK TABLES `course_teacher` WRITE;
/*!40000 ALTER TABLE `course_teacher` DISABLE KEYS */;
INSERT INTO `course_teacher` VALUES (1,1,6,'2026-08-09 10:47:39','2026-08-09 10:47:39'),(2,1,2,'2026-08-09 10:47:39','2026-08-09 10:47:39'),(3,2,2,'2026-08-10 10:04:24','2026-08-10 10:04:24'),(4,2,1,'2026-08-10 10:04:24','2026-08-10 10:04:24'),(5,2,3,'2026-08-10 10:04:24','2026-08-10 10:04:24'),(6,3,6,'2026-08-10 10:46:46','2026-08-10 10:46:46'),(7,3,1,'2026-08-10 10:46:46','2026-08-10 10:46:46'),(8,4,3,'2026-08-10 10:48:08','2026-08-10 10:48:08'),(9,5,5,'2026-08-10 10:50:38','2026-08-10 10:50:38'),(10,6,6,'2026-08-10 10:52:39','2026-08-10 10:52:39'),(11,6,2,'2026-08-10 10:52:39','2026-08-10 10:52:39'),(12,6,1,'2026-08-10 10:52:39','2026-08-10 10:52:39'),(13,6,3,'2026-08-10 10:52:39','2026-08-10 10:52:39'),(14,7,6,'2026-08-24 10:29:07','2026-08-24 10:29:07'),(15,7,2,'2026-08-24 10:29:07','2026-08-24 10:29:07'),(16,8,1,'2026-08-24 10:30:03','2026-08-24 10:30:03'),(17,8,3,'2026-08-24 10:30:03','2026-08-24 10:30:03'),(18,9,2,'2026-08-24 10:31:40','2026-08-24 10:31:40'),(19,9,3,'2026-08-24 10:31:40','2026-08-24 10:31:40'),(20,10,2,'2026-08-24 10:34:31','2026-08-24 10:34:31'),(21,10,5,'2026-08-24 10:34:31','2026-08-24 10:34:31'),(22,11,6,'2026-08-24 10:36:31','2026-08-24 10:36:31'),(23,11,2,'2026-08-24 10:36:31','2026-08-24 10:36:31'),(24,11,5,'2026-08-24 10:36:31','2026-08-24 10:36:31'),(25,12,5,'2026-08-24 10:38:11','2026-08-24 10:38:11'),(26,12,1,'2026-08-24 10:38:11','2026-08-24 10:38:11'),(27,12,3,'2026-08-24 10:38:11','2026-08-24 10:38:11'),(28,13,2,'2026-08-24 10:39:31','2026-08-24 10:39:31'),(29,13,1,'2026-08-24 10:39:31','2026-08-24 10:39:31'),(30,13,3,'2026-08-24 10:39:31','2026-08-24 10:39:31');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_transfers`
--

LOCK TABLES `course_transfers` WRITE;
/*!40000 ALTER TABLE `course_transfers` DISABLE KEYS */;
INSERT INTO `course_transfers` VALUES (1,'CT-20260829-0001',3,3,11,12,1,0.00,990.00,0.00,990.00,'paid',0.00,'completed',6,6,'transfer',NULL,NULL,NULL,NULL,'ผู้ดูแลระบบ','2026-08-29 07:30:04','2026-08-29 07:29:56','2026-08-29 07:30:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'WWW','regular','private','onsite',NULL,'wwww','ww',NULL,5,1,3,NULL,NULL,5,NULL,NULL,12333.00,'individual',NULL,1,1,0,1,'2026-08-09 10:47:39','2026-08-10 10:32:13','2026-08-10 10:32:13'),(2,'WWW1','regular','group','onsite',NULL,'dd',NULL,NULL,9,2,10,NULL,NULL,6,NULL,NULL,20000.00,'individual',2,1,1,0,1,'2026-08-10 10:04:24','2026-08-10 10:32:11','2026-08-10 10:32:11'),(3,'PV-PNO-001','regular','private','onsite','master_class','เปียโนพื้นฐานสำหรับผู้เริ่มต้น','เรียนเปียโนแบบตัวต่อตัว ตั้งแต่พื้นฐาน การอ่านโน้ต การวางนิ้ว และการเล่นเพลงเบื้องต้น เหมาะสำหรับผู้เริ่มต้น',NULL,1,1,12,NULL,NULL,3,NULL,NULL,14400.00,'individual',NULL,1,1,0,1,'2026-08-10 10:46:46','2026-08-24 10:27:25','2026-08-24 10:27:25'),(4,'GR-GTR-001','regular','group','onsite',NULL,'กีตาร์พื้นฐานสำหรับเด็ก','เรียนกีตาร์แบบกลุ่ม ฝึกการจับคอร์ด การตีคอร์ด จังหวะพื้นฐาน และเล่นเพลงร่วมกัน',NULL,2,1,12,NULL,NULL,1,NULL,NULL,6900.00,'individual',8,1,1,0,1,'2026-08-10 10:48:08','2026-08-24 10:27:22','2026-08-24 10:27:22'),(5,'SP-DRM-001','special','special_activity','onsite','workshop','กลอง Workshop สนุกกับจังหวะ','กิจกรรม Workshop ฝึกพื้นฐานกลองและจังหวะ ผ่านกิจกรรมและการเล่นร่วมกัน',NULL,5,1,NULL,2,3.0,NULL,'2026-08-17','2026-08-18',1500.00,'individual',15,1,1,0,1,'2026-08-10 10:50:38','2026-08-24 10:27:19','2026-08-24 10:27:19'),(6,'SP-CAMP-001','special','special_activity','onsite','camp','Music Summer Camp 2026','ค่ายดนตรีสำหรับเด็ก เรียนรู้การเล่นดนตรีและทำกิจกรรมร่วมกัน',NULL,14,2,NULL,5,6.0,NULL,'2026-08-24','2026-08-28',6500.00,'individual',30,1,1,0,1,'2026-08-10 10:52:39','2026-08-24 10:27:16','2026-08-24 10:27:16'),(7,'PV-VIO-001','regular','private','onsite',NULL,'ไวโอลินพื้นฐานตัวต่อตัว',NULL,NULL,6,1,12,NULL,NULL,3,NULL,NULL,14400.00,'individual',NULL,1,1,0,1,'2026-08-24 10:29:07','2026-08-24 10:29:07',NULL),(8,'PV-PNO-ON1','regular','private','online',NULL,'เปียโนออนไลน์ตัวต่อตัว',NULL,NULL,NULL,2,8,NULL,NULL,2,NULL,NULL,9600.00,'individual',NULL,1,1,0,1,'2026-08-24 10:30:03','2026-08-24 10:30:03',NULL),(9,'GR-GTR-HY1','regular','group','hybrid',NULL,'กีตาร์กลุ่มไฮบริด (เรียนที่โรงเรียน+ออนไลน์สลับ)',NULL,NULL,2,1,12,NULL,NULL,6,NULL,NULL,7200.00,'individual',2,1,1,0,1,'2026-08-24 10:31:40','2026-08-24 10:31:40',NULL),(10,'PV-PNO-AD1','regular','private','onsite',NULL,'เปียโนผู้ใหญ่ (เวลาเรียนยืดหยุ่น)',NULL,NULL,1,1,12,NULL,NULL,12,NULL,NULL,18000.00,'individual',NULL,1,1,1,1,'2026-08-24 10:34:31','2026-08-24 10:34:31',NULL),(11,'SP-CAMP-002','special','special_activity','onsite','camp','Winter Music Camp 2026',NULL,NULL,15,2,NULL,5,6.0,NULL,'2026-10-20','2026-10-24',6900.00,'individual',25,1,1,0,1,'2026-08-24 10:36:31','2026-08-24 10:36:31',NULL),(12,'SP-VOC-001','special','special_activity','onsite','workshop','Workshop ร้องเพลงป๊อป 1 วัน',NULL,NULL,15,2,NULL,1,4.0,NULL,'2026-09-22','2026-09-22',990.00,'individual',20,1,1,0,1,'2026-08-24 10:38:11','2026-08-24 10:38:11',NULL),(13,'SP-VLN-MC1','special','special_activity','onsite','master_class','Master Class ไวโอลินกับอาจารย์รับเชิญ',NULL,NULL,6,2,NULL,1,3.0,NULL,'2026-08-31','2026-08-31',1500.00,'individual',12,1,1,0,1,'2026-08-24 10:39:31','2026-08-24 10:39:31',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
INSERT INTO `enrollments` VALUES (1,10,9,3,'2026-08-24','2027-02-24',NULL,2,0,'active','2026-08-24 11:00:10','2026-08-26 08:03:27'),(2,4,8,NULL,'2026-08-24','2026-10-24',NULL,2,0,'active','2026-08-24 11:02:30','2026-08-25 09:18:02'),(3,3,11,NULL,'2026-08-24',NULL,'2026-08-29',0,0,'cancelled','2026-08-24 11:04:01','2026-08-29 07:38:45'),(4,3,7,2,'2026-08-29','2026-11-29',NULL,0,0,'active','2026-08-29 07:24:38','2026-08-29 07:24:38'),(5,2,8,1,'2026-08-29','2026-10-29',NULL,0,0,'active','2026-08-29 07:27:04','2026-08-29 07:27:04'),(6,3,12,1,'2026-08-29',NULL,NULL,0,0,'active','2026-08-29 07:30:04','2026-08-29 07:30:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_types`
--

LOCK TABLES `equipment_types` WRITE;
/*!40000 ALTER TABLE `equipment_types` DISABLE KEYS */;
INSERT INTO `equipment_types` VALUES (1,'เปียนโน','2026-08-09 09:17:12','2026-08-09 09:17:12'),(2,'ลำโพง','2026-08-10 10:56:10','2026-08-10 10:56:10'),(3,'กระจกฝึกท่าทาง x1','2026-08-24 10:44:16','2026-08-24 10:44:16');
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
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` enum('course','product_cost','rent','staff','maintenance','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorded_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_category_expense_date_index` (`category`,`expense_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guardians`
--

LOCK TABLES `guardians` WRITE;
/*!40000 ALTER TABLE `guardians` DISABLE KEYS */;
INSERT INTO `guardians` VALUES (1,'test','0244454477',NULL,NULL,NULL,NULL,'2026-08-09 08:44:38','2026-08-10 10:32:36','2026-08-10 10:32:36'),(2,'สุภาวดี ใจดี','0823456789',NULL,NULL,NULL,NULL,'2026-08-10 10:36:18','2026-08-10 10:36:18',NULL),(3,'สมชาย ใจดี','0812345678',NULL,NULL,NULL,NULL,'2026-08-10 10:36:40','2026-08-10 10:39:04','2026-08-10 10:39:04'),(4,'สมชาย วัฒนชัย','0876543210','somchai@email.com',NULL,NULL,NULL,'2026-08-10 10:38:14','2026-08-15 01:27:22',NULL),(5,'อรทัย ศรีสุข','0845671230',NULL,NULL,NULL,NULL,'2026-08-10 10:38:54','2026-08-10 10:38:54',NULL),(6,'สมชาย ใจดี','0812345678','somjai@email.com','somjai',NULL,NULL,'2026-08-10 10:39:28','2026-08-24 10:21:22',NULL),(7,'QA Test Guardian','0800000000',NULL,NULL,NULL,NULL,'2026-08-24 08:58:29','2026-08-24 09:04:25','2026-08-24 09:04:25');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homework_submissions`
--

LOCK TABLES `homework_submissions` WRITE;
/*!40000 ALTER TABLE `homework_submissions` DISABLE KEYS */;
INSERT INTO `homework_submissions` VALUES (2,2,4,1,'ปป','approved',NULL,'สรวิชญ์ พิทักษ์ธรรม','2026-08-25 09:12:41','กัญญารัตน์ ศรีสุข','2026-08-25 08:59:11','2026-08-25 09:12:41');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instruments`
--

LOCK TABLES `instruments` WRITE;
/*!40000 ALTER TABLE `instruments` DISABLE KEYS */;
INSERT INTO `instruments` VALUES (1,'เปียโน',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,'กีตาร์',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,'กีตาร์ไฟฟ้า',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,'เบส',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,'กลองชุด',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,'ไวโอลิน',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(7,'เชลโล',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(8,'ขับร้อง (Vocal)',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(9,'ขลุ่ย',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(10,'ซอ',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(11,'คีย์บอร์ด',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(12,'ยูคูเลเล่',NULL,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(14,'ดนตรีรวม',NULL,1,'2026-08-10 10:51:28','2026-08-10 10:51:28'),(15,'ไม่ระบุ',NULL,1,'2026-08-24 10:35:03','2026-08-24 10:35:03');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `makeup_requests`
--

LOCK TABLES `makeup_requests` WRITE;
/*!40000 ALTER TABLE `makeup_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `makeup_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_tiers`
--

DROP TABLE IF EXISTS `membership_tiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_tiers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `min_spend` decimal(12,2) NOT NULL DEFAULT 0.00,
  `benefits` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'secondary',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_tiers`
--

LOCK TABLES `membership_tiers` WRITE;
/*!40000 ALTER TABLE `membership_tiers` DISABLE KEYS */;
/*!40000 ALTER TABLE `membership_tiers` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_create_instruments_table',1),(5,'2024_01_01_000002_create_teaching_types_table',1),(6,'2024_01_01_000003_create_levels_table',1),(7,'2024_01_01_000004_create_teachers_table',1),(8,'2024_01_01_000005_create_teacher_instrument_table',1),(9,'2024_01_01_000006_create_teacher_teaching_type_table',1),(10,'2024_01_01_000007_create_teacher_level_table',1),(11,'2024_01_01_000008_create_teacher_rates_table',1),(12,'2024_01_01_000009_create_teacher_transport_fees_table',1),(13,'2024_01_01_000010_create_teacher_availabilities_table',1),(14,'2024_01_01_000011_create_teaching_sessions_table',1),(15,'2024_01_01_000012_add_branch_to_teachers_table',1),(16,'2024_01_01_000013_add_notes_to_teachers_table',1),(17,'2024_01_02_000001_create_courses_table',2),(18,'2024_01_02_000002_create_coupons_table',2),(19,'2024_01_02_000003_add_structure_fields_to_courses_table',3),(20,'2024_01_02_000004_add_delivery_mode_to_courses_table',4),(21,'2024_01_03_000001_create_students_table',5),(22,'2024_01_03_000002_create_enrollments_table',5),(23,'2024_01_03_000003_create_payments_table',5),(24,'2024_01_03_000004_create_student_credit_transactions_table',5),(25,'2024_01_03_000005_create_student_skill_levels_table',5),(26,'2024_01_03_000006_create_exam_results_table',5),(27,'2024_01_03_000007_create_student_leaves_table',5),(28,'2024_01_03_000008_create_guardians_table',6),(29,'2024_01_03_000009_create_student_guardian_table',6),(30,'2024_01_03_000010_drop_guardian_columns_from_students_table',6),(31,'2024_01_04_000001_create_rooms_table',7),(32,'2024_01_04_000002_create_equipment_types_table',7),(33,'2024_01_04_000003_create_room_equipment_table',7),(34,'2024_01_04_000004_create_room_bookings_table',7),(35,'2024_01_05_000001_create_sale_orders_table',8),(36,'2024_01_05_000002_create_tax_invoices_table',8),(37,'2024_01_05_000003_add_discount_fields_to_sale_orders_table',9),(38,'2024_01_05_000004_create_student_point_transactions_table',9),(39,'2024_01_05_000005_add_payment_reference_to_sale_orders_table',10),(40,'2024_01_06_000001_create_course_transfers_table',11),(41,'2024_01_07_000001_create_class_schedules_table',12),(42,'2024_01_07_000002_add_teacher_id_to_enrollments_table',13),(43,'2024_01_08_000001_upgrade_student_leaves_table',14),(44,'2024_01_08_000002_create_teacher_leaves_table',14),(45,'2024_01_08_000003_create_app_notifications_table',14),(46,'2024_01_09_000001_add_role_fields_to_users_table',15),(47,'2024_01_10_000001_create_makeup_requests_table',16),(48,'2024_01_11_000001_create_reschedule_requests_table',17),(49,'2024_01_11_000002_add_student_guardian_to_app_notifications_role_enum',18),(50,'2025_01_12_000001_create_teaching_logs_table',19),(52,'2025_01_13_000001_create_teaching_reports_tables',20),(53,'2025_01_13_000002_create_course_evaluation_tables',21),(54,'2025_01_14_000001_create_teaching_evidences_table',22),(55,'2025_01_15_000001_create_homework_submissions_table',23),(56,'2025_01_15_000002_create_run_throughs_table',23),(57,'2025_01_16_000001_add_percentage_to_teacher_rates_and_create_payroll_tables',24),(58,'2025_01_17_000001_add_km_and_create_transport_compensations',25),(59,'2024_01_18_000001_create_music_store_tables',26),(60,'2024_01_19_000001_add_self_checkout_fields_to_store_sales_table',27),(61,'2024_01_20_000001_add_delivery_fields_to_store_sales_table',28),(62,'2026_08_23_000001_rename_coupons_to_promotions_table',29),(63,'2026_08_23_000002_create_promotion_product_table',29),(64,'2026_08_23_000003_create_promotion_usages_table',29),(65,'2026_08_23_000004_rename_coupon_fields_on_sale_orders_table',29),(66,'2026_08_23_000005_add_discount_fields_to_store_sales_table',29),(67,'2026_08_23_000006_create_membership_tiers_table',30),(68,'2026_08_23_000007_create_student_memberships_table',30),(69,'2026_08_23_000008_add_expiry_fields_to_student_point_transactions_table',30),(70,'2026_08_23_000009_add_points_credit_fields_to_store_sales_table',30),(71,'2026_08_23_000010_create_expenses_table',31),(72,'2026_08_24_000001_add_staff_to_users_role_enum',32),(73,'2026_08_24_000002_create_permissions_table',32),(74,'2026_08_24_000003_create_role_permissions_table',32),(75,'2026_08_24_000004_create_audit_logs_table',32),(76,'2026_08_26_000001_create_teacher_leave_attachments_table',33),(77,'2026_08_30_000001_create_trial_leads_table',34),(78,'2026_08_30_000002_create_trial_payments_table',35),(79,'2026_08_30_000003_add_confirmation_fields_to_trial_leads_table',36),(80,'2026_08_30_000004_add_result_audit_fields_to_trial_leads_table',37);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,10,1,'SO-20260824-0001',6480.00,6480.00,NULL,'2026-08-24','transfer','paid','ชำระผ่านระบบขายคอร์สเรียน (SO-20260824-0001)','2026-08-24 11:00:10','2026-08-24 11:00:10'),(2,4,2,'SO-20260824-0002',9600.00,9600.00,NULL,'2026-08-24','credit_card','paid','ชำระผ่านระบบขายคอร์สเรียน (SO-20260824-0002)','2026-08-24 11:02:30','2026-08-24 11:02:30'),(3,3,3,'SO-20260824-0003',6900.00,6900.00,NULL,'2026-08-24','other','paid','ชำระผ่านระบบขายคอร์สเรียน (SO-20260824-0003)','2026-08-24 11:04:01','2026-08-24 11:04:01'),(4,3,4,'SO-20260829-0001',14400.00,14400.00,NULL,'2026-08-29','transfer','paid','ชำระผ่านระบบขายคอร์สเรียน (SO-20260829-0001)','2026-08-29 07:24:38','2026-08-29 07:24:38'),(5,2,5,'SO-20260829-0002',9600.00,9600.00,NULL,'2026-08-29','credit_card','paid','ชำระผ่านระบบขายคอร์สเรียน (SO-20260829-0002)','2026-08-29 07:27:04','2026-08-29 07:27:04'),(6,3,NULL,'CT-20260829-0001',990.00,990.00,NULL,'2026-08-29','transfer','paid','ชำระส่วนต่างค่าเปลี่ยนคอร์ส (CT-20260829-0001)','2026-08-29 07:30:04','2026-08-29 07:30:04');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_run_items`
--

LOCK TABLES `payroll_run_items` WRITE;
/*!40000 ALTER TABLE `payroll_run_items` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_runs`
--

LOCK TABLES `payroll_runs` WRITE;
/*!40000 ALTER TABLE `payroll_runs` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_runs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'teachers.manage','จัดการอาจารย์','อาจารย์',0,1,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(2,'courses.manage','จัดการคอร์สเรียน','วิชาการ',0,2,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(3,'promotions.manage','โปรโมชัน/คูปอง','งานขาย',0,3,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(4,'membership.manage','ระดับสมาชิก','งานขาย',0,4,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(5,'students.manage','จัดการนักเรียน','นักเรียน',0,5,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(6,'student_leaves.manage','ลาเรียนนักเรียน','นักเรียน',0,6,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(7,'guardians.manage','ผู้ปกครอง','นักเรียน',0,7,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(8,'rooms.manage','ห้องเรียน','ตารางเรียน',0,8,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(9,'sales.manage','ขายคอร์สเรียน','งานขาย',0,9,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(10,'course_transfers.manage','เปลี่ยนคอร์ส','งานขาย',0,10,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(11,'schedules.manage','ตารางเรียน','ตารางเรียน',0,11,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(12,'teacher_leaves.manage','อนุมัติลาอาจารย์','อาจารย์',0,12,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(13,'users.manage','จัดการผู้ใช้งาน','ระบบ',1,13,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(14,'makeup_reschedule.manage','เรียนชดเชย/สลับคลาส','ตารางเรียน',0,14,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(15,'payroll.manage','เงินเดือนอาจารย์','การเงิน',0,15,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(16,'transport_fees.manage','ค่ารถอาจารย์','การเงิน',0,16,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(17,'finance.manage','การเงิน','การเงิน',0,17,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(18,'reports.view','รายงาน','รายงาน',0,18,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(19,'products.manage','สินค้า/สต็อก','Music Store',0,19,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(20,'store_sales.manage','ขายสินค้า','Music Store',0,20,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(21,'role_permissions.manage','จัดการสิทธิ์','ระบบ',1,21,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(22,'audit_logs.view','ดูประวัติการใช้งาน','ระบบ',0,22,'2026-08-24 07:29:00','2026-08-24 07:29:00'),(23,'trial_leads.manage','ผู้สนใจและทดลองเรียน','งานขาย',0,9,'2026-08-30 07:45:03','2026-08-30 07:45:03');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) NOT NULL DEFAULT 5,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_status_index` (`status`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotion_course`
--

DROP TABLE IF EXISTS `promotion_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion_course` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `promotion_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_coupon_course_id_coupon_id_unique` (`course_id`,`promotion_id`),
  KEY `course_coupon_coupon_id_foreign` (`promotion_id`),
  CONSTRAINT `course_coupon_coupon_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_coupon_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotion_course`
--

LOCK TABLES `promotion_course` WRITE;
/*!40000 ALTER TABLE `promotion_course` DISABLE KEYS */;
INSERT INTO `promotion_course` VALUES (1,8,1,'2026-08-24 11:08:04','2026-08-24 11:08:04'),(2,7,1,'2026-08-24 11:08:04','2026-08-24 11:08:04');
/*!40000 ALTER TABLE `promotion_course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotion_product`
--

DROP TABLE IF EXISTS `promotion_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `promotion_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promotion_product_promotion_id_product_id_unique` (`promotion_id`,`product_id`),
  KEY `promotion_product_product_id_foreign` (`product_id`),
  CONSTRAINT `promotion_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `promotion_product_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotion_product`
--

LOCK TABLES `promotion_product` WRITE;
/*!40000 ALTER TABLE `promotion_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotion_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotion_usages`
--

DROP TABLE IF EXISTS `promotion_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `promotion_id` bigint(20) unsigned NOT NULL,
  `sale_order_id` bigint(20) unsigned DEFAULT NULL,
  `store_sale_id` bigint(20) unsigned DEFAULT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `buyer_identifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promotion_usages_sale_order_id_foreign` (`sale_order_id`),
  KEY `promotion_usages_store_sale_id_foreign` (`store_sale_id`),
  KEY `promotion_usages_student_id_foreign` (`student_id`),
  KEY `promotion_usages_promotion_id_student_id_index` (`promotion_id`,`student_id`),
  CONSTRAINT `promotion_usages_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `promotion_usages_sale_order_id_foreign` FOREIGN KEY (`sale_order_id`) REFERENCES `sale_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `promotion_usages_store_sale_id_foreign` FOREIGN KEY (`store_sale_id`) REFERENCES `store_sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `promotion_usages_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotion_usages`
--

LOCK TABLES `promotion_usages` WRITE;
/*!40000 ALTER TABLE `promotion_usages` DISABLE KEYS */;
INSERT INTO `promotion_usages` VALUES (1,1,1,NULL,10,NULL,720.00,'2026-08-24 11:00:10','2026-08-24 11:00:10','2026-08-24 11:00:10');
/*!40000 ALTER TABLE `promotion_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` enum('course','product','both') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'course',
  `discount_type` enum('percent','fixed','spend_get') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `discount_value` decimal(10,2) NOT NULL,
  `min_spend` decimal(10,2) DEFAULT NULL,
  `max_uses` int(10) unsigned DEFAULT NULL,
  `per_customer_limit` int(10) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `applies_to_all` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` VALUES (1,'WELCOME100','WELCOME','course','fixed',100.00,NULL,50,1,1,'2026-08-24','2026-09-05',0,1,'2026-08-24 10:57:34','2026-08-24 11:08:04'),(2,NULL,'โปรโมชันต้อนรับเปิดเทอม ลด 10%','course','percent',10.00,NULL,NULL,NULL,0,'2026-09-01','2026-09-05',1,1,'2026-08-24 11:06:36','2026-08-24 11:06:36'),(3,'SPEND1000','ซื้อครบ 1,000 ลด 150','both','spend_get',150.00,1000.00,NULL,NULL,0,NULL,NULL,1,1,'2026-08-24 11:09:16','2026-08-24 11:09:16'),(4,NULL,'ล้างสต๊อกอุปกรณ์ดนตรี ลด 20%','product','percent',20.00,NULL,NULL,NULL,0,NULL,NULL,1,1,'2026-08-24 11:09:51','2026-08-24 11:09:51'),(5,'VIP2026','สิทธิพิเศษลูกค้า VIP ลด 500 บาท','course','fixed',500.00,NULL,NULL,1,0,NULL,NULL,1,0,'2026-08-24 11:10:47','2026-08-24 11:15:44');
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reschedule_requests`
--

LOCK TABLES `reschedule_requests` WRITE;
/*!40000 ALTER TABLE `reschedule_requests` DISABLE KEYS */;
INSERT INTO `reschedule_requests` VALUES (1,'change',8,NULL,NULL,NULL,'2026-10-22','15:41:00','16:41:00','{\"teacher_id\":3,\"room_id\":null,\"schedule_date\":\"2026-10-21\",\"start_time\":\"16:00:00\",\"end_time\":\"17:00:00\"}','approved',NULL,NULL,'สรวิชญ์ พิทักษ์ธรรม','ผู้ดูแลระบบ','2026-08-26 08:42:45','2026-08-26 08:41:36','2026-08-26 08:42:45');
/*!40000 ALTER TABLE `reschedule_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_permission_id_unique` (`role`,`permission_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (42,'staff',1,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(43,'staff',2,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(44,'staff',5,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(45,'staff',6,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(46,'staff',7,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(47,'staff',8,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(48,'staff',9,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(49,'staff',10,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(50,'staff',11,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(51,'staff',12,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(52,'staff',14,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(53,'staff',19,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(54,'staff',20,'2026-08-24 09:27:23','2026-08-24 09:27:23'),(55,'staff',23,'2026-08-30 07:45:03','2026-08-30 07:45:03');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_equipment`
--

LOCK TABLES `room_equipment` WRITE;
/*!40000 ALTER TABLE `room_equipment` DISABLE KEYS */;
INSERT INTO `room_equipment` VALUES (1,1,1,1,NULL,NULL,'2026-08-09 09:17:16','2026-08-09 09:17:16'),(2,2,1,1,NULL,NULL,'2026-08-10 10:54:18','2026-08-10 10:54:18'),(3,4,2,1,NULL,NULL,'2026-08-10 10:56:12','2026-08-10 10:56:12'),(4,5,3,1,NULL,NULL,'2026-08-24 10:44:21','2026-08-24 10:44:21'),(5,7,2,2,NULL,NULL,'2026-08-24 10:46:07','2026-08-24 10:46:07');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'CONF-001','s','2',2,NULL,1,0,NULL,NULL,NULL,'2026-08-09 09:17:16','2026-08-10 10:32:23','2026-08-10 10:32:23'),(2,'R001','ห้องเปียโน 1','ชั้น 2',2,'ห้องสำหรับเรียนเปียโนแบบ Private มีฉนวนกันเสียง',1,0,NULL,NULL,NULL,'2026-08-10 10:54:18','2026-08-24 10:43:25','2026-08-24 10:43:25'),(3,'R004','ห้องซ้อมรวม','ชั้น 3',12,'ห้องสำหรับซ้อมวงและเรียนแบบ Group',1,0,NULL,NULL,NULL,'2026-08-10 10:55:17','2026-08-24 10:43:22','2026-08-24 10:43:22'),(4,'R005','ห้องกิจกรรม','ชั้น 1',30,'ห้องขนาดใหญ่สำหรับ Workshop, Master Class และกิจกรรมพิเศษ',1,0,NULL,NULL,NULL,'2026-08-10 10:56:12','2026-08-24 10:43:20','2026-08-24 10:43:20'),(5,'R002','ห้องไวโอลิน 1','ชั้น 2',2,'ห้องสำหรับเรียนไวโอลินแบบ Private มีฉนวนกันเสียง',1,0,NULL,NULL,NULL,'2026-08-24 10:44:21','2026-08-24 10:44:21',NULL),(6,'R006','ห้องซ้อมวงเครื่องสาย','ชั้น 3',10,'เหมาะสำหรับสอนกลุ่มเล็ก มีเก้าอี้ + ขาตั้งโน้ต',1,0,NULL,NULL,NULL,'2026-08-24 10:45:22','2026-08-24 10:45:22',NULL),(7,'R007','ห้องแสดงดนตรี','ชั้น 1 ตึก A',50,'ใช้จัด Camp, Workshop, Master Class และงานแสดงดนตรีปลายปี',1,0,NULL,NULL,NULL,'2026-08-24 10:46:07','2026-08-24 10:46:07',NULL),(8,'R008','ห้องกลอง 1',NULL,3,NULL,1,1,'เปลี่ยนฉนวนกันเสียง','2026-08-24','2026-09-05','2026-08-24 10:46:52','2026-08-24 11:50:53',NULL),(9,'R009','ห้องเก่า (ยกเลิกใช้งาน)',NULL,5,NULL,0,0,NULL,NULL,NULL,'2026-08-24 10:47:56','2026-08-24 10:47:56',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `run_throughs`
--

LOCK TABLES `run_throughs` WRITE;
/*!40000 ALTER TABLE `run_throughs` DISABLE KEYS */;
INSERT INTO `run_throughs` VALUES (1,2,3,'d','d','excellent','xx','xx','2026-08-29 07:21:34','ผู้ดูแลระบบ','2026-08-29 07:21:14','2026-08-29 07:21:34');
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
  `promotion_id` bigint(20) unsigned DEFAULT NULL,
  `promotion_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_promotion_id` bigint(20) unsigned DEFAULT NULL,
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_mode` enum('onsite','online','hybrid') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_day_of_week` tinyint(4) DEFAULT NULL,
  `preferred_start_time` time DEFAULT NULL,
  `preferred_end_time` time DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `auto_discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
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
  KEY `sale_orders_coupon_id_foreign` (`promotion_id`),
  KEY `sale_orders_auto_promotion_id_foreign` (`auto_promotion_id`),
  CONSTRAINT `sale_orders_auto_promotion_id_foreign` FOREIGN KEY (`auto_promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_orders_coupon_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_orders_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_orders_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_orders_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_orders_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_orders_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_orders`
--

LOCK TABLES `sale_orders` WRITE;
/*!40000 ALTER TABLE `sale_orders` DISABLE KEYS */;
INSERT INTO `sale_orders` VALUES (1,'SO-20260824-0001',10,9,1,'WELCOME100',NULL,3,'Cloud 11','hybrid',1,'17:00:00','18:00:00',6728.97,720.00,0.00,0.00,0,0.00,7.00,471.03,7200.00,6480.00,'payment-proofs/zap3F4yqymG9bd0uM5PwmB1Cc2h9wCMhT310hqGR.png','transfer',NULL,'paid',1,1,NULL,'ผู้ดูแลระบบ','2026-08-24 10:59:42','2026-08-24 11:00:10'),(2,'SO-20260824-0002',4,8,NULL,NULL,NULL,NULL,'Cloud 11','online',3,'16:00:00','17:00:00',8971.96,0.00,0.00,0.00,0,0.00,7.00,628.04,9600.00,9600.00,NULL,'credit_card','AUTH-889021','paid',2,2,NULL,'ผู้ดูแลระบบ','2026-08-24 11:02:16','2026-08-24 11:02:30'),(3,'SO-20260824-0003',3,11,NULL,NULL,NULL,NULL,'Cloud 11','onsite',NULL,NULL,NULL,6448.60,0.00,0.00,0.00,0,0.00,7.00,451.40,6900.00,6900.00,NULL,'promptpay',NULL,'paid',3,3,NULL,'ผู้ดูแลระบบ','2026-08-24 11:03:50','2026-08-24 11:04:01'),(4,'SO-20260824-0004',9,13,NULL,NULL,NULL,2,'Cloud 11','onsite',NULL,NULL,NULL,1401.87,0.00,0.00,0.00,0,0.00,7.00,98.13,1500.00,1500.00,NULL,NULL,NULL,'cancelled',NULL,NULL,NULL,'ผู้ดูแลระบบ','2026-08-24 11:16:08','2026-08-24 11:20:06'),(5,'SO-20260829-0001',3,7,NULL,NULL,NULL,2,'Cloud 11','onsite',2,'10:24:00','15:24:00',13457.94,0.00,0.00,0.00,0,0.00,7.00,942.06,14400.00,14400.00,'payment-proofs/IR84OieFMLshnjScYw2GHD4tPgc2HfV3sgYZ6fl8.png','transfer',NULL,'paid',4,4,NULL,'ผู้ดูแลระบบ','2026-08-29 07:24:22','2026-08-29 07:24:38'),(6,'SO-20260829-0002',2,8,NULL,NULL,NULL,1,'Cloud 11','onsite',5,'11:26:00','15:26:00',8971.96,0.00,0.00,0.00,0,0.00,7.00,628.04,9600.00,9600.00,'payment-proofs/tCcth3MdQGja14fFmvzfYlab6cIRBS6p8YoHveAk.png','credit_card','1212','paid',5,5,NULL,'ผู้ดูแลระบบ','2026-08-29 07:26:52','2026-08-29 07:27:04');
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
INSERT INTO `sessions` VALUES ('UhMtCIkpnTiWQ0XpBkQMinzi09cyTeYt4MS2S1ry',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJoRHVmaTlEakFvbzJia0tWTVhWUzhTdllMSmxIYjBBQWZ0czhvTEtKIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL3Jvb21zIiwicm91dGUiOiJyb29tcy5pbmRleCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1788110034);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `type` enum('in','out','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_sale_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_store_sale_id_foreign` (`store_sale_id`),
  KEY `stock_movements_product_id_created_at_index` (`product_id`,`created_at`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_store_sale_id_foreign` FOREIGN KEY (`store_sale_id`) REFERENCES `store_sales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store_sale_items`
--

DROP TABLE IF EXISTS `store_sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_sale_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_sale_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_sale_items_store_sale_id_foreign` (`store_sale_id`),
  KEY `store_sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `store_sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `store_sale_items_store_sale_id_foreign` FOREIGN KEY (`store_sale_id`) REFERENCES `store_sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_sale_items`
--

LOCK TABLES `store_sale_items` WRITE;
/*!40000 ALTER TABLE `store_sale_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store_sales`
--

DROP TABLE IF EXISTS `store_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `promotion_id` bigint(20) unsigned DEFAULT NULL,
  `promotion_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_promotion_id` bigint(20) unsigned DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `auto_discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_payable` decimal(10,2) DEFAULT NULL,
  `points_used` int(10) unsigned NOT NULL DEFAULT 0,
  `points_discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `credit_used` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_method` enum('pickup','delivery') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pickup',
  `delivery_recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_tracking_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_status` enum('not_applicable','preparing','shipped','ready_for_pickup','picked_up','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_applicable',
  `payment_method` enum('cash','transfer','credit_card','promptpay') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending_payment','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `sold_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordered_by_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_sales_sale_no_unique` (`sale_no`),
  KEY `store_sales_student_id_foreign` (`student_id`),
  KEY `store_sales_ordered_by_user_id_foreign` (`ordered_by_user_id`),
  KEY `store_sales_promotion_id_foreign` (`promotion_id`),
  KEY `store_sales_auto_promotion_id_foreign` (`auto_promotion_id`),
  CONSTRAINT `store_sales_auto_promotion_id_foreign` FOREIGN KEY (`auto_promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_sales_ordered_by_user_id_foreign` FOREIGN KEY (`ordered_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_sales_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_sales_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_sales`
--

LOCK TABLES `store_sales` WRITE;
/*!40000 ALTER TABLE `store_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_sales` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_guardian`
--

LOCK TABLES `student_guardian` WRITE;
/*!40000 ALTER TABLE `student_guardian` DISABLE KEYS */;
INSERT INTO `student_guardian` VALUES (1,1,1,'มารดา',0,'2026-08-09 08:44:38','2026-08-09 08:44:38'),(2,2,2,'มารดา',0,'2026-08-10 10:36:18','2026-08-10 10:39:28'),(3,2,3,'บิดา',0,'2026-08-10 10:36:40','2026-08-10 10:36:40'),(4,3,4,'บิดา',1,'2026-08-10 10:38:14','2026-08-10 10:38:14'),(5,4,5,'มารดา',1,'2026-08-10 10:38:54','2026-08-10 10:38:54'),(6,2,6,'บิดา',1,'2026-08-10 10:39:28','2026-08-10 10:39:28'),(9,8,6,'มารดา',0,'2026-08-24 10:17:24','2026-08-24 10:17:24'),(10,9,6,'มารดา',0,'2026-08-24 10:20:43','2026-08-24 10:20:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_leaves`
--

LOCK TABLES `student_leaves` WRITE;
/*!40000 ALTER TABLE `student_leaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_memberships`
--

DROP TABLE IF EXISTS `student_memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_memberships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `membership_tier_id` bigint(20) unsigned DEFAULT NULL,
  `total_spend_12m` decimal(12,2) NOT NULL DEFAULT 0.00,
  `lifetime_spend` decimal(12,2) NOT NULL DEFAULT 0.00,
  `renewed_at` timestamp NULL DEFAULT NULL,
  `next_review_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_memberships_student_id_unique` (`student_id`),
  KEY `student_memberships_membership_tier_id_foreign` (`membership_tier_id`),
  CONSTRAINT `student_memberships_membership_tier_id_foreign` FOREIGN KEY (`membership_tier_id`) REFERENCES `membership_tiers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_memberships_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_memberships`
--

LOCK TABLES `student_memberships` WRITE;
/*!40000 ALTER TABLE `student_memberships` DISABLE KEYS */;
INSERT INTO `student_memberships` VALUES (1,10,NULL,6480.00,6480.00,'2026-08-24 11:00:10','2027-08-24 11:00:10','2026-08-24 11:00:10','2026-08-24 11:00:10'),(2,4,NULL,9600.00,9600.00,'2026-08-24 11:02:30','2027-08-24 11:02:30','2026-08-24 11:02:30','2026-08-24 11:02:30'),(3,3,NULL,21300.00,21300.00,'2026-08-29 07:24:38','2027-08-29 07:24:38','2026-08-24 11:04:01','2026-08-29 07:24:38'),(4,2,NULL,9600.00,9600.00,'2026-08-29 07:27:04','2027-08-29 07:27:04','2026-08-29 07:27:04','2026-08-29 07:27:04');
/*!40000 ALTER TABLE `student_memberships` ENABLE KEYS */;
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
  `store_sale_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('earn','redeem','adjustment','expire') COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `remaining_points` int(11) DEFAULT NULL,
  `expiring_notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_point_transactions_sale_order_id_foreign` (`sale_order_id`),
  KEY `student_point_transactions_student_id_index` (`student_id`),
  KEY `student_point_transactions_store_sale_id_foreign` (`store_sale_id`),
  CONSTRAINT `student_point_transactions_sale_order_id_foreign` FOREIGN KEY (`sale_order_id`) REFERENCES `sale_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_point_transactions_store_sale_id_foreign` FOREIGN KEY (`store_sale_id`) REFERENCES `store_sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_point_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_point_transactions`
--

LOCK TABLES `student_point_transactions` WRITE;
/*!40000 ALTER TABLE `student_point_transactions` DISABLE KEYS */;
INSERT INTO `student_point_transactions` VALUES (1,10,1,NULL,'earn',64,64,'สะสมแต้มจากการซื้อคอร์ส SO-20260824-0001','2027-08-24 11:00:10',64,NULL,'2026-08-24 11:00:10','2026-08-24 11:00:10'),(2,4,2,NULL,'earn',96,96,'สะสมแต้มจากการซื้อคอร์ส SO-20260824-0002','2027-08-24 11:02:30',96,NULL,'2026-08-24 11:02:30','2026-08-24 11:02:30'),(3,3,3,NULL,'earn',69,69,'สะสมแต้มจากการซื้อคอร์ส SO-20260824-0003','2027-08-24 11:04:01',69,NULL,'2026-08-24 11:04:01','2026-08-24 11:04:01'),(4,3,5,NULL,'earn',144,213,'สะสมแต้มจากการซื้อคอร์ส SO-20260829-0001','2027-08-29 07:24:38',144,NULL,'2026-08-29 07:24:38','2026-08-29 07:24:38'),(5,2,6,NULL,'earn',96,96,'สะสมแต้มจากการซื้อคอร์ส SO-20260829-0002','2027-08-29 07:27:04',96,NULL,'2026-08-29 07:27:04','2026-08-29 07:27:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'6812345678','luk','xxxx','2020-01-09','female','0812345678','nan@gmail.com','qqq','749/32',NULL,'active',NULL,'2026-08-09 08:36:24','2026-08-10 10:27:53','2026-08-10 10:27:53'),(2,'STD0001','พิมพ์ชนก ใจดี','มิ้นท์','2017-02-05','female','0812345678','pimchanok@gmail.com','mint.student01','99/9 หมู่ 5 ตำบลบางแก้ว อำเภอเมือง จังหวัดสมุทรปราการ 10270','students/GFfPc2DS59HN2g3wzS4jZbp2rX8NppUOHZQTIWpA.png','active',NULL,'2026-08-10 10:26:43','2026-08-24 10:16:41',NULL),(3,'STD0002','ธนกฤต วัฒนชัย','นนท์','2021-02-11','male','0897654321','thanakrit@gmail.com','non.student02','88/12 ถนนสุขุมวิท ตำบลเสม็ด อำเภอเมือง จังหวัดชลบุรี 20000','students/THK4Edq4zkzoKTKntfdaPKLqIIORQJyFapy9hEP0.png','active',NULL,'2026-08-10 10:31:05','2026-08-24 10:16:51',NULL),(4,'STD0003','กัญญารัตน์ ศรีสุข','ฟ้า','2019-06-11','female','0861234567','kanyarat@gmail.com','fah.student03','45/7 ถนนราษฎร์บำรุง ตำบลเนินพระ อำเภอเมือง จังหวัดระยอง 21000','students/WZrKyZszz5uTF6O5GJiMna135B3NVzOsHVOEDp9g.png','active',NULL,'2026-08-10 10:35:15','2026-08-24 10:15:57',NULL),(5,'QA-TEST-0001','QA Test Student',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'2026-08-22 22:06:12','2026-08-22 22:17:44','2026-08-22 22:17:44'),(6,'QA-TEST-0002','QA Loyalty Student',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'2026-08-22 22:53:55','2026-08-22 23:10:57','2026-08-22 23:10:57'),(7,'QA-TEST-0003','QA Test Nav Student',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'2026-08-24 08:51:04','2026-08-24 09:04:25','2026-08-24 09:04:25'),(8,'STD0004','กิตติพงศ์ วัฒนากุล','มิ้นนี่','2008-02-15','male','0891234567','kittipong.student@gmail.com','non_music01','99/15 แขวงลาดพร้าว เขตลาดพร้าว กรุงเทพมหานคร 10230','students/MK7FTFi8F0atu9inMiXCnDfp06K05EmywOLjJ4Ei.png','active','สนใจเรียนกีตาร์ ต้องการเรียนช่วงเย็นวันเสาร์','2026-08-24 10:14:25','2026-08-24 10:15:41',NULL),(9,'STD0005','ณัฐณิชา วัฒนะ','พรีม','2000-06-13','female','0924567810','natchanicha.student@gmail.com','preem_music10','88/24 ถนนรามคำแหง แขวงหัวหมาก เขตบางกะปิ กรุงเทพมหานคร 10240','students/vu54axO4QnJl4I5Tp269P5wIu1AcppEBARX7iyjU.png','active','สนใจเรียนเปียโน มีพื้นฐานเบื้องต้น ต้องการเรียนวันเสาร์ช่วงบ่าย','2026-08-24 10:20:05','2026-08-24 10:20:29',NULL),(10,'ST-2026-0006','ด.ญ.ภัทรวดี สินทวี','น้ำหวาน','2009-03-15',NULL,'0891234567',NULL,NULL,NULL,NULL,'active',NULL,'2026-08-24 10:58:19','2026-08-24 10:58:19',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_invoices`
--

LOCK TABLES `tax_invoices` WRITE;
/*!40000 ALTER TABLE `tax_invoices` DISABLE KEYS */;
INSERT INTO `tax_invoices` VALUES (1,1,'RCP-20260824-0002','receipt',0,'คุณแม่ภัทรวดี',NULL,NULL,'0891234567',6728.97,7.00,471.03,6480.00,'2026-08-24','2026-08-24 10:59:42','2026-08-24 11:00:10'),(2,3,'TAX-20260824-0003','tax_invoice',1,'บริษัท มิวสิคเลิร์นนิ่ง จำกัด','0105566001234','123 ถ.สุขุมวิท กรุงเทพฯ','0891234567',6448.60,7.00,451.40,6900.00,'2026-08-24','2026-08-24 11:03:50','2026-08-24 11:04:01');
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
INSERT INTO `teacher_instrument` VALUES (1,1,1,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,1,11,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,2,8,1,'2026-08-03 09:22:54','2026-08-24 10:23:13'),(4,3,2,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(5,3,3,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(6,3,12,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(7,4,6,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(8,4,10,0,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(9,5,5,1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(10,6,1,1,'2026-08-03 09:22:54','2026-08-24 10:22:40');
/*!40000 ALTER TABLE `teacher_instrument` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_leave_attachments`
--

DROP TABLE IF EXISTS `teacher_leave_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_leave_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_leave_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint(20) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_leave_attachments_teacher_leave_id_foreign` (`teacher_leave_id`),
  CONSTRAINT `teacher_leave_attachments_teacher_leave_id_foreign` FOREIGN KEY (`teacher_leave_id`) REFERENCES `teacher_leaves` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_leave_attachments`
--

LOCK TABLES `teacher_leave_attachments` WRITE;
/*!40000 ALTER TABLE `teacher_leave_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `teacher_leave_attachments` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_transport_fees`
--

LOCK TABLES `teacher_transport_fees` WRITE;
/*!40000 ALTER TABLE `teacher_transport_fees` DISABLE KEYS */;
INSERT INTO `teacher_transport_fees` VALUES (1,3,'fixed_per_day',150.00,'2026-05-03',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(2,4,'fixed_per_day',150.00,'2026-05-03',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(3,6,'fixed_per_day',150.00,'2026-05-03',1,'2026-08-03 09:22:54','2026-08-03 09:22:54'),(4,5,'fixed_per_day',100.00,'2026-08-17',1,'2026-08-17 11:52:28','2026-08-17 11:52:28');
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
INSERT INTO `teachers` VALUES (1,'T0001','ภานุพงศ์ เจริญสุข','พีท','peat.piano@viemus.school','081-234-5671',NULL,NULL,NULL,'full_time','Cloud 11','จบเปียโนคลาสสิกจากสถาบันดนตรี มีประสบการณ์สอนมากกว่า 8 ปี เชี่ยวชาญเตรียมสอบ Grade',NULL,1,'2024-08-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(2,'T0002','ณัฐริกา ปานทอง','เมย์','may.vocal@gmail.com','0812345672',NULL,NULL,'teachers/1QSjMKJJfb2iqu5qyKKKKwKJpK8UVKOfLT09kRKq.png','full_time','Cloud 11','นักร้องมืออาชีพ ปัจจุบันสอนขับร้องเพลงไทยสากลและสากล',NULL,1,'2025-06-03','2026-08-03 09:22:54','2026-08-24 10:23:13',NULL),(3,'T0003','สรวิชญ์ พิทักษ์ธรรม','เต้ย','toey.guitar@viemus.school','081-234-5673',NULL,NULL,NULL,'freelance','Astra Academy','มือกีตาร์วง Session รับสอนตั้งแต่พื้นฐานถึงระดับ Advanced และเปิด Workshop เป็นครั้งคราว',NULL,1,'2026-02-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(4,'T0004','ปวีณา แซ่ตั้ง','แพร','prae.violin@viemus.school','081-234-5674',NULL,NULL,NULL,'freelance','Cloud 11','จบไวโอลินเกียรตินิยม ปัจจุบันเป็นนักไวโอลินอิสระและติวเตอร์',NULL,0,'2025-12-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(5,'T0005','ธนกร อินทรวิเศษ','บอส','boss.drums@viemus.school','081-234-5675',NULL,NULL,NULL,'full_time','Cloud 11','มือกลองประจำวงดนตรีสด รับงาน Accompaniment ควบคู่กับการสอนประจำ',NULL,1,'2025-07-03','2026-08-03 09:22:54','2026-08-03 09:22:54',NULL),(6,'T0006','ชนิดาภา วงศ์สุวรรณ','เฟิร์น','fern.piano@gmail.com','0812345676',NULL,NULL,'teachers/McJnW9YOAWqD4WMO3OGQHZc8IJQmS5nlOYP9FjD1.png','freelance','Astra Academy','ครูเปียโนสำหรับเด็กเล็ก เชี่ยวชาญหลักสูตรปูพื้นฐานสำหรับผู้เริ่มต้น',NULL,1,'2024-11-03','2026-08-03 09:22:54','2026-08-24 10:22:40',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_evidences`
--

LOCK TABLES `teaching_evidences` WRITE;
/*!40000 ALTER TABLE `teaching_evidences` DISABLE KEYS */;
INSERT INTO `teaching_evidences` VALUES (1,5,'document','teaching-evidences/document/zmFivVFfSzAMczE9aVw0EyOLPRru8P7YCbOAaYDs.pdf','RCP-20260824-0002 (1).pdf','application/pdf',110220,9,'สรวิชญ์ พิทักษ์ธรรม','2026-08-25 09:15:06','2026-08-25 09:15:06'),(2,6,'document','teaching-evidences/document/aHP0UfZhSthGS1o9lvYIqa8HjhTXqxdnHFjG8IYd.pdf','RCP-20260824-0002 (2).pdf','application/pdf',110220,9,'สรวิชญ์ พิทักษ์ธรรม','2026-08-25 09:18:38','2026-08-25 09:18:38');
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_logs`
--

LOCK TABLES `teaching_logs` WRITE;
/*!40000 ALTER TABLE `teaching_logs` DISABLE KEYS */;
INSERT INTO `teaching_logs` VALUES (4,1,2,3,4,'present','2026-08-25 08:32:06','สรวิชญ์ พิทักษ์ธรรม',60,0,'2026-08-25 08:32:06','สรวิชญ์ พิทักษ์ธรรม',NULL,2,1,NULL,'2026-08-25 08:02:45','2026-08-25 08:32:06'),(5,9,1,3,10,'present','2026-08-25 09:14:29','สรวิชญ์ พิทักษ์ธรรม',60,0,'2026-08-25 09:14:29','สรวิชญ์ พิทักษ์ธรรม',NULL,3,1,NULL,'2026-08-25 09:14:24','2026-08-25 09:14:29'),(6,2,2,3,4,'present','2026-08-25 09:18:02','สรวิชญ์ พิทักษ์ธรรม',60,0,'2026-08-25 09:18:02','สรวิชญ์ พิทักษ์ธรรม',NULL,4,1,NULL,'2026-08-25 09:16:12','2026-08-25 09:18:02'),(7,11,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-25 09:16:30','2026-08-25 09:16:30'),(8,3,2,3,4,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-25 09:16:38','2026-08-25 09:16:38'),(9,5,2,3,4,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-25 09:17:00','2026-08-25 09:17:00'),(10,10,1,3,10,'present','2026-08-26 08:03:26','สรวิชญ์ พิทักษ์ธรรม',45,0,'2026-08-26 08:03:26','สรวิชญ์ พิทักษ์ธรรม',NULL,5,1,NULL,'2026-08-26 07:45:01','2026-08-26 08:03:27'),(11,12,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-26 07:52:35','2026-08-26 07:52:35'),(12,4,2,3,4,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(13,6,2,3,4,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(14,7,2,3,4,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(15,8,2,3,4,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(16,13,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(17,14,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(18,15,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(19,16,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(20,17,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(21,18,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(22,19,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26'),(23,20,1,3,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,'2026-08-29 08:04:26','2026-08-29 08:04:26');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_report_attachments`
--

LOCK TABLES `teaching_report_attachments` WRITE;
/*!40000 ALTER TABLE `teaching_report_attachments` DISABLE KEYS */;
INSERT INTO `teaching_report_attachments` VALUES (1,3,'teaching-report-attachments/p44Hl8AfXNLdl3AmWrfCrD0LZhkhPfwIixfm5GfJ.pdf','RCP-20260824-0002 (2).pdf','2026-08-25 09:14:59','2026-08-25 09:14:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_reports`
--

LOCK TABLES `teaching_reports` WRITE;
/*!40000 ALTER TABLE `teaching_reports` DISABLE KEYS */;
INSERT INTO `teaching_reports` VALUES (2,4,NULL,'ทดสอบ',NULL,NULL,'สรวิชญ์ พิทักษ์ธรรม','2026-08-25 08:40:24','2026-08-25 08:40:24'),(3,5,'xxxxx','xxxxxxxxxxxx','xxxxx',NULL,'สรวิชญ์ พิทักษ์ธรรม','2026-08-25 09:14:59','2026-08-25 09:14:59'),(4,6,'xxxxxxxxxxxxxx','xxxxxxxxxxxxxx','xxxxxxxxxxxxxx','xxxxxxxxxxxxxx','สรวิชญ์ พิทักษ์ธรรม','2026-08-25 09:18:21','2026-08-25 09:18:21');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaching_sessions`
--

LOCK TABLES `teaching_sessions` WRITE;
/*!40000 ALTER TABLE `teaching_sessions` DISABLE KEYS */;
INSERT INTO `teaching_sessions` VALUES (2,3,NULL,NULL,NULL,'กัญญารัตน์ ศรีสุข','2026-08-24','16:00:00','17:00:00',1.00,500.00,150.00,NULL,500.00,'completed',NULL,'2026-08-25 08:32:06','2026-08-25 08:32:06'),(3,3,2,NULL,NULL,'ด.ญ.ภัทรวดี สินทวี','2026-09-07','17:00:00','18:00:00',1.00,500.00,150.00,NULL,500.00,'completed',NULL,'2026-08-25 09:14:29','2026-08-25 09:14:29'),(4,3,NULL,NULL,NULL,'กัญญารัตน์ ศรีสุข','2026-09-09','16:00:00','17:00:00',1.00,500.00,150.00,NULL,500.00,'completed',NULL,'2026-08-25 09:18:02','2026-08-25 09:18:02'),(5,3,2,NULL,NULL,'ด.ญ.ภัทรวดี สินทวี','2026-09-14','17:00:00','18:00:00',0.75,500.00,150.00,NULL,500.00,'completed',NULL,'2026-08-26 08:03:27','2026-08-26 08:03:27');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transport_compensations`
--

LOCK TABLES `transport_compensations` WRITE;
/*!40000 ALTER TABLE `transport_compensations` DISABLE KEYS */;
/*!40000 ALTER TABLE `transport_compensations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trial_leads`
--

DROP TABLE IF EXISTS `trial_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trial_leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` tinyint(3) unsigned DEFAULT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `room_id` bigint(20) unsigned DEFAULT NULL,
  `interest` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_schedule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_date` date DEFAULT NULL,
  `trial_start_time` time DEFAULT NULL,
  `trial_end_time` time DEFAULT NULL,
  `delivery_mode` enum('onsite','online') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'onsite',
  `trial_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','paid','waived','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL,
  `status` enum('new','contacted','scheduled','completed','converted','lost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `trial_result` enum('interested','considering','not_interested','no_show') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmation_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `guardian_confirmed_at` timestamp NULL DEFAULT NULL,
  `guardian_confirmed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_confirmed_at` timestamp NULL DEFAULT NULL,
  `teacher_confirmed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmation_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_in_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_feedback` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_recorded_at` timestamp NULL DEFAULT NULL,
  `result_recorded_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `converted_student_id` bigint(20) unsigned DEFAULT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_leads_lead_no_unique` (`lead_no`),
  KEY `trial_leads_course_id_foreign` (`course_id`),
  KEY `trial_leads_teacher_id_foreign` (`teacher_id`),
  KEY `trial_leads_room_id_foreign` (`room_id`),
  KEY `trial_leads_converted_student_id_foreign` (`converted_student_id`),
  KEY `trial_leads_status_next_follow_up_date_index` (`status`,`next_follow_up_date`),
  KEY `trial_leads_trial_date_teacher_id_index` (`trial_date`,`teacher_id`),
  CONSTRAINT `trial_leads_converted_student_id_foreign` FOREIGN KEY (`converted_student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trial_leads_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trial_leads_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trial_leads_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trial_leads`
--

LOCK TABLES `trial_leads` WRITE;
/*!40000 ALTER TABLE `trial_leads` DISABLE KEYS */;
INSERT INTO `trial_leads` VALUES (1,'TL-20260830-0001','test','test','2004-02-05',12,'test','0423398321','test2@gmail.com','qqq',8,3,7,'test','เสาร์','2026-08-31','10:46:00','16:46:00','onsite',2500.00,'paid','2026-08-30 08:18:04','completed','considering','fully_confirmed','2026-08-30 09:20:23','ผู้ดูแลระบบ','2026-08-30 09:19:53','สรวิชญ์ พิทักษ์ธรรม',NULL,NULL,NULL,'นักเรียนแจ้งขอพิจารณาอีกครั้ง',NULL,NULL,'2026-09-04',NULL,NULL,NULL,NULL,'ผู้ดูแลระบบ','2026-08-30 07:46:56','2026-08-30 09:22:37'),(3,'TL-20260830-0002','test','test','2014-02-28',31,'test','0812345678','test2@gmail.com','qqq',8,3,5,'test','เสาร์','2026-08-31','10:26:00','13:26:00','onsite',1500.00,'paid','2026-08-30 09:28:23','scheduled',NULL,'fully_confirmed','2026-08-30 09:29:22','ผู้ดูแลระบบ','2026-08-30 09:28:58','สรวิชญ์ พิทักษ์ธรรม',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30',NULL,NULL,NULL,NULL,'ผู้ดูแลระบบ','2026-08-30 09:28:11','2026-08-30 09:29:22');
/*!40000 ALTER TABLE `trial_leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trial_payments`
--

DROP TABLE IF EXISTS `trial_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trial_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trial_lead_id` bigint(20) unsigned NOT NULL,
  `parent_payment_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('payment','refund') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'payment',
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','transfer','promptpay','credit_card','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_at` datetime NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_payments_transaction_no_unique` (`transaction_no`),
  KEY `trial_payments_parent_payment_id_foreign` (`parent_payment_id`),
  KEY `trial_payments_status_transaction_at_index` (`status`,`transaction_at`),
  KEY `trial_payments_trial_lead_id_type_index` (`trial_lead_id`,`type`),
  CONSTRAINT `trial_payments_parent_payment_id_foreign` FOREIGN KEY (`parent_payment_id`) REFERENCES `trial_payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trial_payments_trial_lead_id_foreign` FOREIGN KEY (`trial_lead_id`) REFERENCES `trial_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trial_payments`
--

LOCK TABLES `trial_payments` WRITE;
/*!40000 ALTER TABLE `trial_payments` DISABLE KEYS */;
INSERT INTO `trial_payments` VALUES (1,'TP-20260830-0001',1,NULL,'payment',2500.00,'promptpay','confirmed','2026-08-30 15:17:00',NULL,'trial-payment-proofs/HvOcwKBATVyA4CVfNitArXjI7FJdCPj0swF9jb8e.png','girl (1).png',NULL,'ผู้ดูแลระบบ','ผู้ดูแลระบบ','2026-08-30 08:18:04','2026-08-30 08:17:58','2026-08-30 08:18:04'),(2,'TP-20260830-0002',3,NULL,'payment',1500.00,'promptpay','confirmed','2026-08-30 16:28:11',NULL,'trial-payment-proofs/OSPW6GLdq3z3lX2uWnOOVqloG3SGOvmdD2x3tJmt.png','boy (1).png',NULL,'ผู้ดูแลระบบ','ผู้ดูแลระบบ','2026-08-30 09:28:23','2026-08-30 09:28:11','2026-08-30 09:28:23');
/*!40000 ALTER TABLE `trial_payments` ENABLE KEYS */;
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
  `role` enum('admin','staff','teacher','student','guardian') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ผู้ดูแลระบบ','admin@viemus.school','admin',NULL,NULL,NULL,1,0,NULL,'$2y$12$lkUq/qQyJudbMV7moggJu.DgJY4GDe/GibYowTi34Z0Dyj9dO70T6','ZkpVtTY5W9ofYnzku9xpRiX2soJaTSwyjWYVQkl6UbPCvl6g9UpAEbYnh8pK','2026-08-15 01:10:20','2026-08-15 01:41:11'),(3,'นายแอดมิน คนแรก','admin@gmail.com','admin',NULL,NULL,NULL,1,1,NULL,'$2y$12$G1/zebVNcVLuYnxuQCQpIu4pmvap9wrr00yxmoK6UHIgKrMScUM7u','rXT9t5TEHJlFh9h7ec5SGnuBwTxDId80dZF1LrKvyCVzorpUNsANe6SxTM99','2026-08-15 01:12:53','2026-08-15 01:12:57'),(9,'สรวิชญ์ พิทักษ์ธรรม','toey.guitar@viemus.school','teacher',3,NULL,NULL,1,0,NULL,'$2y$12$Wf6pT8m7DGvDucwZHObfF.EwtSH6HlN3ktwZouE.0CeCGywoqF4EG','WxnhNdsTLGeclelivFcArjTDdz0UfEDyN1J1hk2ojQUJdgDTMbp5YNAILVB2','2026-08-16 08:56:31','2026-08-16 08:58:15'),(10,'กัญญารัตน์ ศรีสุข','kanyarat@gmail.com','student',NULL,4,NULL,1,0,NULL,'$2y$12$e8HYSaSPAOzUVBe7Lx1ZpuYpZe1c5Buy60dBTafmNlkYMcnAXw3f.',NULL,'2026-08-16 08:57:19','2026-08-25 09:25:58'),(11,'ธนกร อินทรวิเศษ','boss.drums@viemus.school','teacher',5,NULL,NULL,1,0,NULL,'$2y$12$RN6RvGQKUwL/yOK4lheWWuNyZ7Lkm/DRStD/9qUCfyqV.6u0qo5oW',NULL,'2026-08-17 11:03:53','2026-08-17 11:04:44'),(12,'พิมพ์ชนก ใจดี','pimchanok@gmail.com','student',NULL,2,NULL,1,0,NULL,'$2y$12$2K1Mrs.octle6kMuLQfYy.ShpGYU6sog7LIjTYnw8UICUvI3l2w9C',NULL,'2026-08-17 11:05:37','2026-08-17 11:06:07'),(13,'สมชาย วัฒนชัย','somchai@email.com','guardian',NULL,NULL,4,1,0,NULL,'$2y$12$5LU/2N9ot6sU49m6pU9NDu6CN.H8rqtzR0w5dH6IphnSTs5UoRKQK',NULL,'2026-08-22 09:06:28','2026-08-22 09:07:04'),(22,'staff@viemus.school','staff@viemus.school','staff',NULL,NULL,NULL,1,0,NULL,'$2y$12$Fkge2Pb9Xrpd7bPJ5Dzjxex9/QasSQT75OUDNyTrmN8NWVOIBz6NG',NULL,'2026-08-24 08:27:47','2026-08-24 08:28:41'),(25,'สมชาย ใจดี','somjai@email.com','guardian',NULL,NULL,6,1,1,NULL,'$2y$12$ieIYaT17.wpF9.Au6uDkiuV/6ULHUMGy33cmO.Bz93MDSKSbZCFU6',NULL,'2026-08-24 10:21:25','2026-08-24 10:21:25');
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

-- Dump completed on 2026-08-31  0:23:50
