-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: proethos2
-- ------------------------------------------------------
-- Server version	5.5.47-0ubuntu0.14.04.1

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
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'2016-05-12 17:27:13','2016-05-12 17:27:13',0,'dsasdasda','sadsaddsa'),(2,'2016-05-12 17:28:26','2016-05-12 17:28:26',0,'Questão de teste?','É sim, é uma questão de teste'),(3,'2016-05-12 17:28:40','2016-05-12 17:28:40',1,'Questão de teste?????','É sim, é uma questão de teste'),(7,'2016-05-12 17:50:02','2016-05-12 17:50:02',1,'Teste de questão?','Resposta');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `language`
--

LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
INSERT INTO `language` VALUES (1,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-us','English - United States'),(2,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-es','Spanish - Spain (Traditional)'),(3,'2022-04-16 10:48:00','2022-04-16 10:48:00','pt-br','Portuguese - Brazil'),(4,'2022-04-16 10:48:00','2022-04-16 10:48:00','fr-fr','French - France'),(5,'2022-04-16 10:48:00','2022-04-16 10:48:00','af','Afrikaans'),(6,'2022-04-16 10:48:00','2022-04-16 10:48:00','sq','Albanian'),(7,'2022-04-16 10:48:00','2022-04-16 10:48:00','am','Amharic'),(8,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-dz','Arabic - Algeria'),(9,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-bh','Arabic - Bahrain'),(10,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-eg','Arabic - Egypt'),(11,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-iq','Arabic - Iraq'),(12,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-jo','Arabic - Jordan'),(13,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-kw','Arabic - Kuwait'),(14,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-lb','Arabic - Lebanon'),(15,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-ly','Arabic - Libya'),(16,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-ma','Arabic - Morocco'),(17,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-om','Arabic - Oman'),(18,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-qa','Arabic - Qatar'),(19,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-sa','Arabic - Saudi Arabia'),(20,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-sy','Arabic - Syria'),(21,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-tn','Arabic - Tunisia'),(22,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-ae','Arabic - United Arab Emirates'),(23,'2022-04-16 10:48:00','2022-04-16 10:48:00','ar-ye','Arabic - Yemen'),(24,'2022-04-16 10:48:00','2022-04-16 10:48:00','hy','Armenian'),(25,'2022-04-16 10:48:00','2022-04-16 10:48:00','as','Assamese'),(26,'2022-04-16 10:48:00','2022-04-16 10:48:00','az-az','Azeri - Cyrillic'),(27,'2022-04-16 10:48:00','2022-04-16 10:48:00','az-az','Azeri - Latin'),(28,'2022-04-16 10:48:00','2022-04-16 10:48:00','eu','Basque'),(29,'2022-04-16 10:48:00','2022-04-16 10:48:00','be','Belarusian'),(30,'2022-04-16 10:48:00','2022-04-16 10:48:00','bn','Bengali - Bangladesh'),(31,'2022-04-16 10:48:00','2022-04-16 10:48:00','bn','Bengali - India'),(32,'2022-04-16 10:48:00','2022-04-16 10:48:00','bs','Bosnian'),(33,'2022-04-16 10:48:00','2022-04-16 10:48:00','bg','Bulgarian'),(34,'2022-04-16 10:48:00','2022-04-16 10:48:00','my','Burmese'),(35,'2022-04-16 10:48:00','2022-04-16 10:48:00','ca','Catalan'),(36,'2022-04-16 10:48:00','2022-04-16 10:48:00','zh-cn','Chinese - China'),(37,'2022-04-16 10:48:00','2022-04-16 10:48:00','zh-hk','Chinese - Hong Kong SAR'),(38,'2022-04-16 10:48:00','2022-04-16 10:48:00','zh-mo','Chinese - Macau SAR'),(39,'2022-04-16 10:48:00','2022-04-16 10:48:00','zh-sg','Chinese - Singapore'),(40,'2022-04-16 10:48:00','2022-04-16 10:48:00','zh-tw','Chinese - Taiwan'),(41,'2022-04-16 10:48:00','2022-04-16 10:48:00','hr','Croatian'),(42,'2022-04-16 10:48:00','2022-04-16 10:48:00','cs','Czech'),(43,'2022-04-16 10:48:00','2022-04-16 10:48:00','da','Danish'),(44,'2022-04-16 10:48:00','2022-04-16 10:48:00','dv','Divehi; Dhivehi; Maldivian'),(45,'2022-04-16 10:48:00','2022-04-16 10:48:00','nl-be','Dutch - Belgium'),(46,'2022-04-16 10:48:00','2022-04-16 10:48:00','nl-nl','Dutch - Netherlands'),(47,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Edo'),(48,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-au','English - Australia'),(49,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-bz','English - Belize'),(50,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-ca','English - Canada'),(51,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-cb','English - Caribbean'),(52,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-gb','English - Great Britain'),(53,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-in','English - India'),(54,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-ie','English - Ireland'),(55,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-jm','English - Jamaica'),(56,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-nz','English - New Zealand'),(57,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-ph','English - Phillippines'),(58,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-za','English - Southern Africa'),(59,'2022-04-16 10:48:00','2022-04-16 10:48:00','en-tt','English - Trinidad'),(60,'2022-04-16 10:48:00','2022-04-16 10:48:00','','English - Zimbabwe'),(61,'2022-04-16 10:48:00','2022-04-16 10:48:00','et','Estonian'),(62,'2022-04-16 10:48:00','2022-04-16 10:48:00','mk','FYRO Macedonia'),(63,'2022-04-16 10:48:00','2022-04-16 10:48:00','fo','Faroese'),(64,'2022-04-16 10:48:00','2022-04-16 10:48:00','fa','Farsi - Persian'),(65,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Filipino'),(66,'2022-04-16 10:48:00','2022-04-16 10:48:00','fi','Finnish'),(67,'2022-04-16 10:48:00','2022-04-16 10:48:00','fr-be','French - Belgium'),(68,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - Cameroon'),(69,'2022-04-16 10:48:00','2022-04-16 10:48:00','fr-ca','French - Canada'),(70,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - Congo'),(71,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - Cote d\'Ivoire'),(72,'2022-04-16 10:48:00','2022-04-16 10:48:00','fr-lu','French - Luxembourg'),(73,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - Mali'),(74,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - Monaco'),(75,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - Morocco'),(76,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - Senegal'),(77,'2022-04-16 10:48:00','2022-04-16 10:48:00','fr-ch','French - Switzerland'),(78,'2022-04-16 10:48:00','2022-04-16 10:48:00','','French - West Indies'),(79,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Frisian - Netherlands'),(80,'2022-04-16 10:48:00','2022-04-16 10:48:00','gd-ie','Gaelic - Ireland'),(81,'2022-04-16 10:48:00','2022-04-16 10:48:00','gd','Gaelic - Scotland'),(82,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Galician'),(83,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Georgian'),(84,'2022-04-16 10:48:00','2022-04-16 10:48:00','de-at','German - Austria'),(85,'2022-04-16 10:48:00','2022-04-16 10:48:00','de-de','German - Germany'),(86,'2022-04-16 10:48:00','2022-04-16 10:48:00','de-li','German - Liechtenstein'),(87,'2022-04-16 10:48:00','2022-04-16 10:48:00','de-lu','German - Luxembourg'),(88,'2022-04-16 10:48:00','2022-04-16 10:48:00','de-ch','German - Switzerland'),(89,'2022-04-16 10:48:00','2022-04-16 10:48:00','el','Greek'),(90,'2022-04-16 10:48:00','2022-04-16 10:48:00','gn','Guarani - Paraguay'),(91,'2022-04-16 10:48:00','2022-04-16 10:48:00','gu','Gujarati'),(92,'2022-04-16 10:48:00','2022-04-16 10:48:00','','HID (Human Interface Device)'),(93,'2022-04-16 10:48:00','2022-04-16 10:48:00','he','Hebrew'),(94,'2022-04-16 10:48:00','2022-04-16 10:48:00','hi','Hindi'),(95,'2022-04-16 10:48:00','2022-04-16 10:48:00','hu','Hungarian'),(96,'2022-04-16 10:48:00','2022-04-16 10:48:00','is','Icelandic'),(97,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Igbo - Nigeria'),(98,'2022-04-16 10:48:00','2022-04-16 10:48:00','id','Indonesian'),(99,'2022-04-16 10:48:00','2022-04-16 10:48:00','it-it','Italian - Italy'),(100,'2022-04-16 10:48:00','2022-04-16 10:48:00','it-ch','Italian - Switzerland'),(101,'2022-04-16 10:48:00','2022-04-16 10:48:00','ja','Japanese'),(102,'2022-04-16 10:48:00','2022-04-16 10:48:00','kn','Kannada'),(103,'2022-04-16 10:48:00','2022-04-16 10:48:00','ks','Kashmiri'),(104,'2022-04-16 10:48:00','2022-04-16 10:48:00','kk','Kazakh'),(105,'2022-04-16 10:48:00','2022-04-16 10:48:00','km','Khmer'),(106,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Konkani'),(107,'2022-04-16 10:48:00','2022-04-16 10:48:00','ko','Korean'),(108,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Kyrgyz - Cyrillic'),(109,'2022-04-16 10:48:00','2022-04-16 10:48:00','lo','Lao'),(110,'2022-04-16 10:48:00','2022-04-16 10:48:00','la','Latin'),(111,'2022-04-16 10:48:00','2022-04-16 10:48:00','lv','Latvian'),(112,'2022-04-16 10:48:00','2022-04-16 10:48:00','lt','Lithuanian'),(113,'2022-04-16 10:48:00','2022-04-16 10:48:00','ms-bn','Malay - Brunei'),(114,'2022-04-16 10:48:00','2022-04-16 10:48:00','ms-my','Malay - Malaysia'),(115,'2022-04-16 10:48:00','2022-04-16 10:48:00','ml','Malayalam'),(116,'2022-04-16 10:48:00','2022-04-16 10:48:00','mt','Maltese'),(117,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Manipuri'),(118,'2022-04-16 10:48:00','2022-04-16 10:48:00','mi','Maori'),(119,'2022-04-16 10:48:00','2022-04-16 10:48:00','mr','Marathi'),(120,'2022-04-16 10:48:00','2022-04-16 10:48:00','mn','Mongolian'),(121,'2022-04-16 10:48:00','2022-04-16 10:48:00','mn','Mongolian'),(122,'2022-04-16 10:48:00','2022-04-16 10:48:00','ne','Nepali'),(123,'2022-04-16 10:48:00','2022-04-16 10:48:00','no-no','Norwegian - Bokml'),(124,'2022-04-16 10:48:00','2022-04-16 10:48:00','no-no','Norwegian - Nynorsk'),(125,'2022-04-16 10:48:00','2022-04-16 10:48:00','or','Oriya'),(126,'2022-04-16 10:48:00','2022-04-16 10:48:00','pl','Polish'),(127,'2022-04-16 10:48:00','2022-04-16 10:48:00','pt-pt','Portuguese - Portugal'),(128,'2022-04-16 10:48:00','2022-04-16 10:48:00','pa','Punjabi'),(129,'2022-04-16 10:48:00','2022-04-16 10:48:00','rm','Raeto-Romance'),(130,'2022-04-16 10:48:00','2022-04-16 10:48:00','ro-mo','Romanian - Moldova'),(131,'2022-04-16 10:48:00','2022-04-16 10:48:00','ro','Romanian - Romania'),(132,'2022-04-16 10:48:00','2022-04-16 10:48:00','ru','Russian'),(133,'2022-04-16 10:48:00','2022-04-16 10:48:00','ru-mo','Russian - Moldova'),(134,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Sami Lappish'),(135,'2022-04-16 10:48:00','2022-04-16 10:48:00','sa','Sanskrit'),(136,'2022-04-16 10:48:00','2022-04-16 10:48:00','sr-sp','Serbian - Cyrillic'),(137,'2022-04-16 10:48:00','2022-04-16 10:48:00','sr-sp','Serbian - Latin'),(138,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Sesotho (Sutu)'),(139,'2022-04-16 10:48:00','2022-04-16 10:48:00','tn','Setsuana'),(140,'2022-04-16 10:48:00','2022-04-16 10:48:00','sd','Sindhi'),(141,'2022-04-16 10:48:00','2022-04-16 10:48:00','si','Sinhala; Sinhalese'),(142,'2022-04-16 10:48:00','2022-04-16 10:48:00','sk','Slovak'),(143,'2022-04-16 10:48:00','2022-04-16 10:48:00','sl','Slovenian'),(144,'2022-04-16 10:48:00','2022-04-16 10:48:00','so','Somali'),(145,'2022-04-16 10:48:00','2022-04-16 10:48:00','sb','Sorbian'),(146,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-ar','Spanish - Argentina'),(147,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-bo','Spanish - Bolivia'),(148,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-cl','Spanish - Chile'),(149,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-co','Spanish - Colombia'),(150,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-cr','Spanish - Costa Rica'),(151,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-do','Spanish - Dominican Republic'),(152,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-ec','Spanish - Ecuador'),(153,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-sv','Spanish - El Salvador'),(154,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-gt','Spanish - Guatemala'),(155,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-hn','Spanish - Honduras'),(156,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-mx','Spanish - Mexico'),(157,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-ni','Spanish - Nicaragua'),(158,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-pa','Spanish - Panama'),(159,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-py','Spanish - Paraguay'),(160,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-pe','Spanish - Peru'),(161,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-pr','Spanish - Puerto Rico'),(162,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-uy','Spanish - Uruguay'),(163,'2022-04-16 10:48:00','2022-04-16 10:48:00','es-ve','Spanish - Venezuela'),(164,'2022-04-16 10:48:00','2022-04-16 10:48:00','sw','Swahili'),(165,'2022-04-16 10:48:00','2022-04-16 10:48:00','sv-fi','Swedish - Finland'),(166,'2022-04-16 10:48:00','2022-04-16 10:48:00','sv-se','Swedish - Sweden'),(167,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Syriac'),(168,'2022-04-16 10:48:00','2022-04-16 10:48:00','tg','Tajik'),(169,'2022-04-16 10:48:00','2022-04-16 10:48:00','ta','Tamil'),(170,'2022-04-16 10:48:00','2022-04-16 10:48:00','tt','Tatar'),(171,'2022-04-16 10:48:00','2022-04-16 10:48:00','te','Telugu'),(172,'2022-04-16 10:48:00','2022-04-16 10:48:00','th','Thai'),(173,'2022-04-16 10:48:00','2022-04-16 10:48:00','bo','Tibetan'),(174,'2022-04-16 10:48:00','2022-04-16 10:48:00','ts','Tsonga'),(175,'2022-04-16 10:48:00','2022-04-16 10:48:00','tr','Turkish'),(176,'2022-04-16 10:48:00','2022-04-16 10:48:00','tk','Turkmen'),(177,'2022-04-16 10:48:00','2022-04-16 10:48:00','uk','Ukrainian'),(178,'2022-04-16 10:48:00','2022-04-16 10:48:00','UTF-8','Unicode'),(179,'2022-04-16 10:48:00','2022-04-16 10:48:00','ur','Urdu'),(180,'2022-04-16 10:48:00','2022-04-16 10:48:00','uz-uz','Uzbek - Cyrillic'),(181,'2022-04-16 10:48:00','2022-04-16 10:48:00','uz-uz','Uzbek - Latin'),(182,'2022-04-16 10:48:00','2022-04-16 10:48:00','','Venda'),(183,'2022-04-16 10:48:00','2022-04-16 10:48:00','vi','Vietnamese'),(184,'2022-04-16 10:48:00','2022-04-16 10:48:00','cy','Welsh'),(185,'2022-04-16 10:48:00','2022-04-16 10:48:00','xh','Xhosa'),(186,'2022-04-16 10:48:00','2022-04-16 10:48:00','yi','Yiddish'),(187,'2022-04-16 10:48:00','2022-04-16 10:48:00','zu','Zulu');
/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `list_clinical_trial_name`
--

LOCK TABLES `list_clinical_trial_name` WRITE;
/*!40000 ALTER TABLE `list_clinical_trial_name` DISABLE KEYS */;
INSERT INTO `list_clinical_trial_name` VALUES (1,'0000-00-00 00:00:00','0000-00-00 00:00:00','Australian New Zealand Clinical Trials Registry','ANZCTR'),(2,'0000-00-00 00:00:00','0000-00-00 00:00:00','Chinese Clinical Trial Registry','ChiCTR'),(3,'0000-00-00 00:00:00','0000-00-00 00:00:00','Clinical Trials Registry - India','CTRI'),(4,'0000-00-00 00:00:00','0000-00-00 00:00:00','Clinical Trials.gov',''),(5,'0000-00-00 00:00:00','0000-00-00 00:00:00','Cuban Public Registry of Clinical Trials','RPCEC'),(6,'0000-00-00 00:00:00','0000-00-00 00:00:00','EU Clinical Trials Register',''),(7,'0000-00-00 00:00:00','0000-00-00 00:00:00','German Clinical Trials Register','DRKS'),(8,'0000-00-00 00:00:00','0000-00-00 00:00:00','International Standard Randomised Controlled Trial Number','ISRCTN.org'),(9,'0000-00-00 00:00:00','0000-00-00 00:00:00','Iranian Registry of Clinical Trials','IRCT'),(10,'0000-00-00 00:00:00','0000-00-00 00:00:00','Japan Primary Registries Network','JPRN'),(11,'0000-00-00 00:00:00','0000-00-00 00:00:00','Netherlands National Trial Register','NTR'),(12,'0000-00-00 00:00:00','0000-00-00 00:00:00','Pan African Clinical Trial Registry','PACTR'),(13,'0000-00-00 00:00:00','0000-00-00 00:00:00','Registro Brasileiro de Ensaios Clínicos','Rebec'),(14,'0000-00-00 00:00:00','0000-00-00 00:00:00','Registro Peruano de Ensayos Clínicos','REPEC'),(15,'0000-00-00 00:00:00','0000-00-00 00:00:00','Sri Lanka Clinical Trials Registry','SLCTR'),(16,'0000-00-00 00:00:00','0000-00-00 00:00:00','Universal Trial Number','UTN');
/*!40000 ALTER TABLE `list_clinical_trial_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `list_gender`
--

LOCK TABLES `list_gender` WRITE;
/*!40000 ALTER TABLE `list_gender` DISABLE KEYS */;
INSERT INTO `list_gender` VALUES (1,'2016-04-27 13:44:00','2016-04-27 13:44:00','N/A'),(2,'2016-04-27 13:44:00','2016-04-27 13:44:00','Male'),(3,'2016-04-27 13:44:00','2016-04-27 13:44:00','Female'),(4,'2016-04-27 13:44:00','2016-04-27 13:44:00','Both');
/*!40000 ALTER TABLE `list_gender` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `list_recruitment_status`
--

LOCK TABLES `list_recruitment_status` WRITE;
/*!40000 ALTER TABLE `list_recruitment_status` DISABLE KEYS */;
INSERT INTO `list_recruitment_status` VALUES (1,'0000-00-00 00:00:00','0000-00-00 00:00:00','Recruiting'),(2,'0000-00-00 00:00:00','0000-00-00 00:00:00','Suspended'),(3,'0000-00-00 00:00:00','0000-00-00 00:00:00','Completed'),(4,'0000-00-00 00:00:00','0000-00-00 00:00:00','Other');
/*!40000 ALTER TABLE `list_recruitment_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `list_role`
--

LOCK TABLES `list_role` WRITE;
/*!40000 ALTER TABLE `list_role` DISABLE KEYS */;
INSERT INTO `list_role` VALUES (1,'2016-04-28 17:44:32','2016-04-28 17:44:32','Investigator','investigator'),(2,'2016-04-28 17:44:32','2016-04-28 17:44:32','Secretary','secretary'),(3,'2016-04-28 17:44:32','2016-04-28 17:44:32','Member of Committee','member-of-committee'),(4,'2016-04-28 17:44:32','2016-04-28 17:44:32','Member ad-hoc','member-ad-hoc');
/*!40000 ALTER TABLE `list_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `list_upload_type`
--

LOCK TABLES `list_upload_type` WRITE;
/*!40000 ALTER TABLE `list_upload_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_upload_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `meeting`
--

LOCK TABLES `meeting` WRITE;
/*!40000 ALTER TABLE `meeting` DISABLE KEYS */;
INSERT INTO `meeting` VALUES (2,'2016-05-09 17:50:12','2016-05-09 17:50:12','2016-07-15','Assunto ','- Item 1\r\n- Item 2'),(5,'2016-05-13 08:40:12','2016-05-13 08:40:12','2016-05-22','Assunto','Conteú\ndo'),(18,'2016-05-13 08:53:01','2016-05-13 08:53:01','2016-05-28','Assuntoaa','Conteú\ndoaa'),(21,'2016-05-13 09:06:34','2016-05-13 09:06:34','2016-05-28','Assuntoaa','Conteú\ndoaa');
/*!40000 ALTER TABLE `meeting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `protocol`
--

LOCK TABLES `protocol` WRITE;
/*!40000 ALTER TABLE `protocol` DISABLE KEYS */;
INSERT INTO `protocol` VALUES (46,'2016-05-13 09:00:30','2016-05-13 09:00:30',1,61,'E','2016-05-13 09:01:08',NULL,'PAHO.0046.01','fsdfsd',2,'2016-05-13 09:01:08',NULL),(49,'2016-05-13 09:08:04','2016-05-13 09:08:04',1,64,'S','2016-05-13 09:08:06',NULL,NULL,NULL,0,'2016-05-13 09:08:06',NULL);
/*!40000 ALTER TABLE `protocol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `protocol_comment`
--

LOCK TABLES `protocol_comment` WRITE;
/*!40000 ALTER TABLE `protocol_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `protocol_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `protocol_history`
--

LOCK TABLES `protocol_history` WRITE;
/*!40000 ALTER TABLE `protocol_history` DISABLE KEYS */;
INSERT INTO `protocol_history` VALUES (73,46,'2016-05-13 09:01:08','2016-05-13 09:01:08','Submission of protocol.'),(74,46,'2016-05-13 09:01:21','2016-05-13 09:01:21','Protocol was send to comittee to initial analysis.'),(75,46,'2016-05-13 09:01:25','2016-05-13 09:01:25','Protocol was accepted.'),(78,49,'2016-05-13 09:08:06','2016-05-13 09:08:06','Submission of protocol.');
/*!40000 ALTER TABLE `protocol_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `protocol_revision`
--

LOCK TABLES `protocol_revision` WRITE;
/*!40000 ALTER TABLE `protocol_revision` DISABLE KEYS */;
INSERT INTO `protocol_revision` VALUES (8,'2016-05-13 09:01:45','2016-05-13 09:01:45',0,46,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,'2016-05-13 09:01:45','2016-05-13 09:01:45',0,46,2,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `protocol_revision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `submission`
--

LOCK TABLES `submission` WRITE;
/*!40000 ALTER TABLE `submission` DISABLE KEYS */;
INSERT INTO `submission` VALUES (61,46,1,'2016-05-13 09:00:30','2016-05-13 09:00:30','rweerw','rewrwe','',1,'rwewer','wre','wer','wer','wer','rew','',4,44,44,'dsf','dsf','2016-05-13','fsd','fsd','','','','','fsd','sd','dfs','fsd','fds',1,2,1,NULL,1),(64,49,1,'2016-05-13 09:08:04','2016-05-13 09:08:04','Public Title','Cientitif Title','Title Acronymous',0,'Abstract','Keywords','Introduction','Justify','Goals','Study design','Health Condition or Problem Studied ',2,4,6,'Inclusion criteria','Exclusion criteria','2017-05-31','Interventions','Primary outcomes ','Secondary outcomes ','General Procedures','Analysis Plan','Ethical Considerations','Funding source','Primary Sponsor','Secondary Sponsor','Bibliography','Scientific Contact',1,2,1,NULL,1);
/*!40000 ALTER TABLE `submission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `submission_clinical_trial`
--

LOCK TABLES `submission_clinical_trial` WRITE;
/*!40000 ALTER TABLE `submission_clinical_trial` DISABLE KEYS */;
/*!40000 ALTER TABLE `submission_clinical_trial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `submission_cost`
--

LOCK TABLES `submission_cost` WRITE;
/*!40000 ALTER TABLE `submission_cost` DISABLE KEYS */;
/*!40000 ALTER TABLE `submission_cost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `submission_country`
--

LOCK TABLES `submission_country` WRITE;
/*!40000 ALTER TABLE `submission_country` DISABLE KEYS */;
/*!40000 ALTER TABLE `submission_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `submission_task`
--

LOCK TABLES `submission_task` WRITE;
/*!40000 ALTER TABLE `submission_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `submission_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `submission_upload`
--

LOCK TABLES `submission_upload` WRITE;
/*!40000 ALTER TABLE `submission_upload` DISABLE KEYS */;
INSERT INTO `submission_upload` VALUES (56,61,NULL,'2016-05-13 09:01:08','2016-05-13 09:01:08','2016-05-13-submission.pdf','/home/moa/project/proethos2/git/symphony/src/Proethos2/ModelBundle/Entity/../../../../uploads/00061/2016-05-13-submission.pdf'),(59,64,NULL,'2016-05-13 09:08:06','2016-05-13 09:08:06','2016-05-13-submission.pdf','/home/moa/project/proethos2/git/symphony/src/Proethos2/ModelBundle/Entity/../../../../uploads/00064/2016-05-13-submission.pdf');
/*!40000 ALTER TABLE `submission_upload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `submission_user`
--

LOCK TABLES `submission_user` WRITE;
/*!40000 ALTER TABLE `submission_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `submission_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `upload_type`
--

LOCK TABLES `upload_type` WRITE;
/*!40000 ALTER TABLE `upload_type` DISABLE KEYS */;
INSERT INTO `upload_type` VALUES (1,'2016-04-22 00:00:00','2016-04-22 00:00:00','Protocol','protocol'),(2,'2016-04-22 00:00:00','2016-04-22 00:00:00','Others','others'),(3,'2016-05-12 00:00:00','2016-05-12 00:00:00','Draft Opinion','draft-opinion');
/*!40000 ALTER TABLE `upload_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `upload_type_extension`
--

LOCK TABLES `upload_type_extension` WRITE;
/*!40000 ALTER TABLE `upload_type_extension` DISABLE KEYS */;
INSERT INTO `upload_type_extension` VALUES (1,'2016-04-22 00:00:00','2016-04-22 00:00:00','pdf'),(2,'0000-00-00 00:00:00','0000-00-00 00:00:00','doc'),(3,'0000-00-00 00:00:00','0000-00-00 00:00:00','docx');
/*!40000 ALTER TABLE `upload_type_extension` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `upload_type_translation`
--

LOCK TABLES `upload_type_translation` WRITE;
/*!40000 ALTER TABLE `upload_type_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `upload_type_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `upload_type_upload_type_extension`
--

LOCK TABLES `upload_type_upload_type_extension` WRITE;
/*!40000 ALTER TABLE `upload_type_upload_type_extension` DISABLE KEYS */;
INSERT INTO `upload_type_upload_type_extension` VALUES (1,1),(1,2),(1,3),(2,1),(2,2),(2,3);
/*!40000 ALTER TABLE `upload_type_upload_type_extension` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'moa.moda@gmail.com','$2a$08$jHZj/wJfcVKlIwr5AvR78euJxYK7Ku5kURNhNx.7.CSIJ3Pq6LEPC','admin',1,'Moacir Mod','BR','0000-00-00 00:00:00','0000-00-00 00:00:00','PAHO'),(2,'murasaki@paho.org','$2a$08$jHZj/wJfcVKlIwr5AvR78euJxYK7Ku5kURNhNx.7.CSIJ3Pq6LEPC','murasaki',1,'Renato Murasaki','BR','0000-00-00 00:00:00','0000-00-00 00:00:00','BIREME');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (1,1),(1,2),(1,3),(1,4),(2,1),(2,2),(2,3),(2,4);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-13  9:10:02
