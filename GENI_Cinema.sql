-- MySQL dump 10.13  Distrib 5.6.22, for Linux (i686)
--
-- Host: localhost    Database: GENI_Cinema_New
-- ------------------------------------------------------
-- Server version	5.6.22

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
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(50) NOT NULL,
  `view_pwd` varchar(16) DEFAULT NULL,
  `admin_pwd` varchar(16) DEFAULT NULL,
  `school` varchar(50) NOT NULL,
  `dept` varchar(50) NOT NULL,
  `course_descp` varchar(1024) DEFAULT NULL,
  `prof_name` varchar(50) NOT NULL,
  `course_status` varchar(20) NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (19,'Test Course 2','','','Clemson','CpSc','Test Courese','KC','CLOSE','2015-11-26','2015-11-30',7),(20,'Test Course 1','','','Clemson','ECE','Test Course','Ryan','CLOSE','2015-11-26','2015-12-15',9),(21,'Test Course 3','','','Clemson','ECE','Test Course','QQ','CLOSE','2015-11-26','2015-12-05',9);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `upload_channel_process`
--

DROP TABLE IF EXISTS `upload_channel_process`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `upload_channel_process` (
  `process_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `channel_descp` varchar(1024) DEFAULT NULL,
  `ingress_gw_ip` varchar(50) DEFAULT NULL,
  `ingress_gw_port` int(11) DEFAULT NULL,
  `start_time` date DEFAULT NULL,
  `end_time` date DEFAULT NULL,
  `video_channel_id` int(11) DEFAULT NULL,
  `channel_demand` int(11) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`process_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upload_channel_process`
--

LOCK TABLES `upload_channel_process` WRITE;
/*!40000 ALTER TABLE `upload_channel_process` DISABLE KEYS */;
/*!40000 ALTER TABLE `upload_channel_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(16) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `email` varchar(1024) NOT NULL,
  `signup_time` date NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `type` varchar(1024) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (5,'qqqq','$2y$10$dK0QK/Ir7k6nUazIjWGdXON0aY7aaz5WI0YNAtiOgVLpD2p7pY/8K','qw@g.clemson.edu','2015-11-24','Qing','Wang','student'),(6,'ggg','$2a$10$1f3e1cb66707f479d058fuxyQl7cg4AUK2XzDkHzFISJo31yovzzu','qw@g.clemson.edu','2015-11-24','Qing','Wang','student'),(7,'qingwang','$2a$10$39ee52672ef77381a456defwfwHpUILjh3jj.RHGmcylGrk9TQFB.','qw@g.clemson.edu','2015-11-26','Qing','Wang','professor'),(8,'Student','$2a$10$d1934fc05e06b8acf65e7u2GxwDEM.tnPmw17sDlbQGq0OfQGbez6','qw@g.clemson.edu','2015-11-26','Qing','Wang','student'),(9,'Ryan','$2a$10$19168036229432b5ca82dO/iQsy.CS.ctnKB3odXRkQpBsbnAQm/G','Ryan@g.clemson.edu','2015-11-26','Ryan','Izard','professor');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `video_channel_id` int(11) NOT NULL,
  `video_status` varchar(10) NOT NULL,
  `upload_process_id` int(11) NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-23 15:51:09
