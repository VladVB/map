-- MySQL dump 10.13  Distrib 5.6.13, for Win64 (x86_64)
--
-- Host: localhost    Database: objonmap
-- ------------------------------------------------------
-- Server version	5.6.13

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
-- Current Database: `objonmap`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `objonmap` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `objonmap`;

--
-- Table structure for table `dic_cities`
--

DROP TABLE IF EXISTS `dic_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dic_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dic_cities`
--

LOCK TABLES `dic_cities` WRITE;
/*!40000 ALTER TABLE `dic_cities` DISABLE KEYS */;
INSERT INTO `dic_cities` VALUES (1,'Киев'),(2,'Львов'),(3,'Одесса'),(4,'Харьков'),(5,'Ковель'),(8,'Житомир'),(9,'Тернополь'),(10,'Николаев'),(11,'Херсон'),(12,'Умань'),(13,'Хмельницкий');
/*!40000 ALTER TABLE `dic_cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dic_house`
--

DROP TABLE IF EXISTS `dic_house`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dic_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(15) DEFAULT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `onmap` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_street_idx` (`id_parent`),
  CONSTRAINT `fk_street` FOREIGN KEY (`id_parent`) REFERENCES `dic_street` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dic_house`
--

LOCK TABLES `dic_house` WRITE;
/*!40000 ALTER TABLE `dic_house` DISABLE KEYS */;
INSERT INTO `dic_house` VALUES (21,'4а',10,'поликлиника №1'),(22,'104',11,'2'),(23,'121',12,'3'),(24,'32б',13,'4'),(25,'26',14,'5'),(26,'26',15,'6'),(27,'3а',16,'7'),(28,'29',17,'8'),(29,'53',18,'9');
/*!40000 ALTER TABLE `dic_house` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dic_street`
--

DROP TABLE IF EXISTS `dic_street`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dic_street` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(45) DEFAULT NULL,
  `id_parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cities_idx` (`id_parent`),
  CONSTRAINT `fk_cities` FOREIGN KEY (`id_parent`) REFERENCES `dic_cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dic_street`
--

LOCK TABLES `dic_street` WRITE;
/*!40000 ALTER TABLE `dic_street` DISABLE KEYS */;
INSERT INTO `dic_street` VALUES (10,'Подвысоцкого',1),(11,'Большая Васильковская',1),(12,'Харьковское шоссе',1),(13,'Владимира Маяковского',1),(14,'Петра Запорожца',1),(15,'Лайоша Гавро',1),(16,'Балакирева',4),(17,'23 Августа',4),(18,' Победы',4);
/*!40000 ALTER TABLE `dic_street` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `role` tinyint(4) DEFAULT '1',
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_user`
--

LOCK TABLES `tbl_user` WRITE;
/*!40000 ALTER TABLE `tbl_user` DISABLE KEYS */;
INSERT INTO `tbl_user` VALUES (23,'вв','202cb962ac59075b964b07152d234b70',1,'123'),(24,'qq','$2y$13$N7rMkTLw8g1c5bwymlGO0e/ZkmJyW6SbFjJga9978x/eqGR1Ii9Hq',2,'123');
/*!40000 ALTER TABLE `tbl_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-02 20:58:38
