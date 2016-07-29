-- MySQL dump 10.13  Distrib 5.5.49, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: proethos2
-- ------------------------------------------------------
-- Server version	5.5.49-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `list_clinical_trial_name`
--

LOCK TABLES `list_clinical_trial_name` WRITE;
/*!40000 ALTER TABLE `list_clinical_trial_name` DISABLE KEYS */;
REPLACE INTO `list_clinical_trial_name` (`id`, `created`, `updated`, `name`, `code`, `slug`, `status`) VALUES (1,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','ANZCTR','gerado-no-teste',1),(2,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','ChiCTR','gerado-no-teste',1),(3,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','CTRI','gerado-no-teste',1),(4,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','','gerado-no-teste',0),(5,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','RPCEC','gerado-no-teste',0),(6,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','','gerado-no-teste',0),(7,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','DRKS','gerado-no-teste',0),(8,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','ISRCTN.org','gerado-no-teste',0),(9,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','IRCT','gerado-no-teste',0),(10,'0000-00-00 00:00:00','0000-00-00 00:00:00','Gerado no Teste','JPRN','gerado-no-teste',0),(11,'0000-00-00 00:00:00','0000-00-00 00:00:00','Netherlands National Trial Register','NTR','',0),(12,'0000-00-00 00:00:00','0000-00-00 00:00:00','Pan African Clinical Trial Registry','PACTR','',0),(13,'0000-00-00 00:00:00','0000-00-00 00:00:00','Registro Brasileiro de Ensaios Clínicos','Rebec','',0),(14,'0000-00-00 00:00:00','0000-00-00 00:00:00','Registro Peruano de Ensayos Clínicos','REPEC','',0),(15,'0000-00-00 00:00:00','0000-00-00 00:00:00','Sri Lanka Clinical Trials Registry','SLCTR','',0),(16,'0000-00-00 00:00:00','0000-00-00 00:00:00','Universal Trial Number','UTN','',0),(17,'2016-06-21 15:44:50','2016-06-21 15:44:50','Test',NULL,'test',1),(18,'2016-06-21 15:46:34','2016-06-21 15:46:34','Gerado no Teste',NULL,'gerado-no-teste',1),(19,'2016-06-21 15:51:15','2016-06-21 15:51:15','Gerado no Teste',NULL,'gerado-no-teste',1),(20,'2016-06-21 16:34:15','2016-06-21 16:34:15','Gerado no Teste',NULL,'gerado-no-teste',1),(21,'2016-06-24 11:20:27','2016-06-24 11:20:27','Gerado no Teste',NULL,'gerado-no-teste',1),(22,'2016-06-24 11:27:07','2016-06-24 11:27:07','Gerado no Teste',NULL,'gerado-no-teste',1),(23,'2016-06-24 11:30:49','2016-06-24 11:30:49','Gerado no Teste',NULL,'gerado-no-teste',1),(24,'2016-07-08 11:42:12','2016-07-08 11:42:12','Gerado no Teste',NULL,'gerado-no-teste',1),(25,'2016-07-08 11:57:27','2016-07-08 11:57:27','Gerado no Teste',NULL,'gerado-no-teste',1),(26,'2016-07-08 12:01:32','2016-07-08 12:01:32','Gerado no Teste',NULL,'gerado-no-teste',1),(27,'2016-07-08 12:03:46','2016-07-08 12:03:46','Gerado no Teste',NULL,'gerado-no-teste',1),(28,'2016-07-08 12:07:43','2016-07-08 12:07:43','Gerado no Teste',NULL,'gerado-no-teste',1),(29,'2016-07-08 12:18:34','2016-07-08 12:18:34','Gerado no Teste',NULL,'gerado-no-teste',1),(30,'2016-07-08 12:24:53','2016-07-08 12:24:53','Gerado no Teste',NULL,'gerado-no-teste',1),(31,'2016-07-08 12:43:32','2016-07-08 12:43:32','Gerado no Teste',NULL,'gerado-no-teste',1),(32,'2016-07-08 13:04:02','2016-07-08 13:04:02','Gerado no Teste',NULL,'gerado-no-teste',1),(33,'2016-07-08 13:21:42','2016-07-08 13:21:42','Gerado no Teste',NULL,'gerado-no-teste',1),(34,'2016-07-08 14:53:22','2016-07-08 14:53:22','Gerado no Teste',NULL,'gerado-no-teste',1),(35,'2016-07-08 15:02:48','2016-07-08 15:02:48','Gerado no Teste',NULL,'gerado-no-teste',1),(36,'2016-07-08 15:34:29','2016-07-08 15:34:29','Gerado no Teste',NULL,'gerado-no-teste',1),(37,'2016-07-09 14:31:40','2016-07-09 14:31:40','Gerado no Teste',NULL,'gerado-no-teste',1),(38,'2016-07-13 16:25:16','2016-07-13 16:25:16','Gerado no Teste',NULL,'gerado-no-teste',1),(39,'2016-07-13 16:29:38','2016-07-13 16:29:38','Gerado no Teste',NULL,'gerado-no-teste',1),(40,'2016-07-18 13:47:46','2016-07-18 13:47:46','Gerado no Teste',NULL,'gerado-no-teste',1),(41,'2016-07-18 13:51:42','2016-07-18 13:51:42','Gerado no Teste',NULL,'gerado-no-teste',1),(42,'2016-07-18 13:53:35','2016-07-18 13:53:35','Gerado no Teste',NULL,'gerado-no-teste',1),(43,'2016-07-28 19:44:01','2016-07-28 19:44:01','Gerado no Teste',NULL,'gerado-no-teste',1),(44,'2016-07-29 15:03:27','2016-07-29 15:03:27','Gerado no Teste',NULL,'gerado-no-teste',1),(45,'2016-07-29 15:13:59','2016-07-29 15:13:59','Gerado no Teste',NULL,'gerado-no-teste',1);
/*!40000 ALTER TABLE `list_clinical_trial_name` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-29 15:19:52
