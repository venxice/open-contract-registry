-- MySQL dump 10.13  Distrib 8.0.46, for macos14.8 (arm64)
--
-- Host: localhost    Database: dbm_db
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `tblbidding`
--

DROP TABLE IF EXISTS `tblbidding`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblbidding` (
  `bidding_id` int NOT NULL AUTO_INCREMENT,
  `contractor` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `notice_date` date DEFAULT NULL,
  `contract_number` varchar(100) DEFAULT NULL,
  `contract_date` date DEFAULT NULL,
  `notice_proceed` date DEFAULT NULL,
  PRIMARY KEY (`bidding_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbidding`
--

LOCK TABLES `tblbidding` WRITE;
/*!40000 ALTER TABLE `tblbidding` DISABLE KEYS */;
INSERT INTO `tblbidding` VALUES (2,'Contractor',1222.00,'2026-07-31','12231','2026-08-05','2026-08-18'),(5,'sd',12.00,'0333-12-23','12231','2026-07-31','2026-08-21'),(6,'hsbchs',33.00,'2026-08-05','099','2026-08-02','2026-08-21'),(7,'11',11.00,'2026-08-26','11','2026-08-18','2026-08-15'),(8,'22',22.00,'2026-08-13','22','2026-08-12','2026-08-24'),(9,'11',11.00,'2026-08-03','11','2026-08-20','2026-08-08'),(10,'Test Corp',50000.00,'2026-08-25','TEST-001','2026-08-25','2026-09-01'),(11,'trialll',100.00,'2026-07-29','097388723','2026-08-07','2026-08-13'),(12,'new',1.00,'2026-08-31','1','2026-08-04','2026-08-25'),(13,'1',2.00,'2026-08-08','1','2026-08-13','2026-08-19'),(14,'Try 1',2936.00,'2026-09-11','09836261772','2026-09-11','2026-09-02');
/*!40000 ALTER TABLE `tblbidding` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbidding_project`
--

DROP TABLE IF EXISTS `tblbidding_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblbidding_project` (
  `project_id` int NOT NULL AUTO_INCREMENT,
  `bidding_id` int NOT NULL,
  `project_title` varchar(500) NOT NULL,
  PRIMARY KEY (`project_id`),
  KEY `fk_project_bidding` (`bidding_id`),
  CONSTRAINT `fk_project_bidding` FOREIGN KEY (`bidding_id`) REFERENCES `tblbidding` (`bidding_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbidding_project`
--

LOCK TABLES `tblbidding_project` WRITE;
/*!40000 ALTER TABLE `tblbidding_project` DISABLE KEYS */;
INSERT INTO `tblbidding_project` VALUES (2,2,'dbchjds'),(5,5,' dcd'),(6,6,'jdcjds'),(9,7,'11'),(10,8,'22'),(11,9,'212'),(12,10,'Test Project'),(14,11,'hello'),(15,12,'new'),(16,13,'1'),(19,14,'Try project');
/*!40000 ALTER TABLE `tblbidding_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbidding_project_description`
--

DROP TABLE IF EXISTS `tblbidding_project_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblbidding_project_description` (
  `description_id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `project_description` text NOT NULL,
  `date_posted` date DEFAULT NULL,
  `project_attachment` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`description_id`),
  KEY `fk_description_project` (`project_id`),
  CONSTRAINT `fk_description_project` FOREIGN KEY (`project_id`) REFERENCES `tblbidding_project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbidding_project_description`
--

LOCK TABLES `tblbidding_project_description` WRITE;
/*!40000 ALTER TABLE `tblbidding_project_description` DISABLE KEYS */;
INSERT INTO `tblbidding_project_description` VALUES (2,2,'sjbdcjd','2026-08-27',''),(5,5,' dss','2026-08-21','/uploads/doc_1787585505_3c532bbc.pdf'),(6,6,'nsd cd','2026-07-31',''),(7,6,'djncd','2026-08-07','/uploads/doc_1787586453_be719540.pdf'),(10,9,'1','2026-08-12','/uploads/doc_1787616606_824d52a8.pdf'),(11,10,'22','2026-08-04','/uploads/doc_1787616673_ccb9b3bd.pdf'),(12,10,'21','2026-08-08','/uploads/doc_1787616709_cc362e0c.pdf'),(13,11,'212','2026-08-12','/uploads/doc_1787618729_dcc23c90.pdf'),(14,11,'321','2026-08-25','/uploads/doc_1787618743_1fe144e6.pdf'),(15,12,'Test description','2026-08-25',''),(18,14,'hello','2026-08-19',''),(19,14,'hi','2026-08-11',''),(20,15,'new','2026-08-20','/uploads/doc_1787624577_1286d6ac.pdf'),(21,16,'2','2026-08-20','/uploads/doc_1787713022_aa1164a2.pdf'),(26,19,'try description','2026-09-02','/uploads/doc_1788225164_8b743272.pdf'),(27,19,'try description 2','2026-09-23','/uploads/doc_1788225167_6fafdd35.pdf');
/*!40000 ALTER TABLE `tblbidding_project_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbluser`
--

DROP TABLE IF EXISTS `tbluser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbluser` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_initial` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbluser`
--

LOCK TABLES `tbluser` WRITE;
/*!40000 ALTER TABLE `tbluser` DISABLE KEYS */;
INSERT INTO `tbluser` VALUES (1,'','havs','','ascds@gmail.com','$2y$12$epQGmbVct4DWGpeQxutKLOqTKuXomPZHzyt/WnU0Zpw1fW4d1LNjm','Editor','ACTIVE'),(3,'','try','','try@gmail.com','$2y$12$epQGmbVct4DWGpeQxutKLOqTKuXomPZHzyt/WnU0Zpw1fW4d1LNjm','Editor','ACTIVE'),(4,'','venice','','venice@1234','$2y$12$epQGmbVct4DWGpeQxutKLOqTKuXomPZHzyt/WnU0Zpw1fW4d1LNjm','Editor','ACTIVE');
/*!40000 ALTER TABLE `tbluser` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-01 10:18:04
