/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: airbnb
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES
(10,'MILLAS','FRANCE'),
(11,'LE SOLER','FRANCE'),
(12,'MILLAS','FRANCE'),
(13,'MILLAS','FRANCE'),
(14,'LES ANGLES','FRANCE'),
(15,'LES ANGLES','FRANCE'),
(16,'LES ANGLES','FRANCE'),
(17,'PAPEETE','FRANCE'),
(18,'PAPEETE','FRANCE'),
(19,'TAURINYA','FRANCE'),
(20,'TAURINYA','FRANCE'),
(21,'AZDDZ','AZDZDZA'),
(22,'CHATEAUROUX','FRANCE'),
(23,'AZDAZDAZ','DADAZDAZ'),
(24,'CHATEAUROUX','FRANCE');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipments`
--

DROP TABLE IF EXISTS `equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `labelEquipment` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipments`
--

LOCK TABLES `equipments` WRITE;
/*!40000 ALTER TABLE `equipments` DISABLE KEYS */;
INSERT INTO `equipments` VALUES
(1,'WIFI'),
(2,'MACHINE A LAVER'),
(3,'GRILLE PAIN'),
(4,'MICRO ONDE');
/*!40000 ALTER TABLE `equipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rentals`
--

DROP TABLE IF EXISTS `rentals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rentals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `surface` int(11) NOT NULL,
  `description` text NOT NULL,
  `beddings` int(11) NOT NULL,
  `typeLogement_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rentals_types` (`typeLogement_id`),
  KEY `fk_rentals_addresses` (`address_id`),
  KEY `fk_rentals_users` (`owner_id`),
  CONSTRAINT `fk_rentals_addresses` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
  CONSTRAINT `fk_rentals_types` FOREIGN KEY (`typeLogement_id`) REFERENCES `typesLogement` (`id`),
  CONSTRAINT `fk_rentals_users` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rentals`
--

LOCK TABLES `rentals` WRITE;
/*!40000 ALTER TABLE `rentals` DISABLE KEYS */;
INSERT INTO `rentals` VALUES
(8,'APPARTEMENT',50.00,25,'Petit appartement en centre ville',1,1,10,5,'assets/images/default.jpg'),
(9,'MAISON DE CAMPAGNE',150.00,125,'Maison en bordure de Perpignan',3,1,11,8,'assets/images/default.jpg'),
(10,'STUDIO',25.00,9,'Petit studio',1,1,12,5,'assets/images/default.jpg'),
(14,'CHALET',200.50,75,'Petit chalet à la montagne',2,1,16,5,'assets/images/6761d08e346f1chalet.jpg'),
(15,'BUNGALOW',300.00,100,'Bungalow sur pilotis',2,1,18,8,'assets/images/67628a8971131bungalow.jpg'),
(16,'BULLE',25.00,9,'Chambre en forme de bulle en campagne',2,2,20,8,'assets/images/default.jpg'),
(19,'AZDDAZDAZ',12.00,12,'fezzefzefezf',12,2,23,9,'assets/images/default.jpg'),
(20,'STUDIO DE FOU',200.00,50,'Logez dans un studio où vous aurez l&#039;occasion de rencontrer les plus grands chanteurs de cette génération comme Dali ou Luther.',2,3,24,10,'assets/images/default.jpg');
/*!40000 ALTER TABLE `rentals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rentals_equipments`
--

DROP TABLE IF EXISTS `rentals_equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rentals_equipments` (
  `rental_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  PRIMARY KEY (`rental_id`,`equipment_id`),
  KEY `fk_rentals_equipments_equipments` (`equipment_id`),
  CONSTRAINT `fk_rentals_equipments_equipments` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`),
  CONSTRAINT `fk_rentals_equipments_rentals` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rentals_equipments`
--

LOCK TABLES `rentals_equipments` WRITE;
/*!40000 ALTER TABLE `rentals_equipments` DISABLE KEYS */;
INSERT INTO `rentals_equipments` VALUES
(8,1),
(9,1),
(10,1),
(15,1),
(20,1),
(9,2),
(9,3),
(8,4),
(9,4),
(20,4);
/*!40000 ALTER TABLE `rentals_equipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateStart` datetime NOT NULL,
  `dateEnd` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `rental_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reservations_users` (`user_id`),
  KEY `fk_reservations_rentals` (`rental_id`),
  CONSTRAINT `fk_reservations_rentals` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`),
  CONSTRAINT `fk_reservations_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES
(8,'2024-12-15 17:28:00','2024-12-19 17:28:00',6,9),
(10,'2024-12-16 11:24:00','2024-12-18 11:24:00',6,10),
(16,'2024-12-20 10:12:00','2026-12-18 10:12:00',7,19);
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `typesLogement`
--

DROP TABLE IF EXISTS `typesLogement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `typesLogement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `labelTypeLogement` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `typesLogement`
--

LOCK TABLES `typesLogement` WRITE;
/*!40000 ALTER TABLE `typesLogement` DISABLE KEYS */;
INSERT INTO `typesLogement` VALUES
(1,'Logement entier'),
(2,'Chambre privée'),
(3,'Chambre partagée');
/*!40000 ALTER TABLE `typesLogement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `typeAccount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(5,'James','Doe','james@doe.com','c9a422e3c3864e346d795a0aeb316339a3f5d987d6de15b3756061685fc6001e223d8a90134cbd1b3a634126f03c783bb4ed421335b8d6cee8b0b821ace492b9',2),
(6,'Jane','Doe','jane@doe.com','678555d8e8bc48d10bc78b1a44e31a2935cce0e758221471afe59579fd5e66341f0edbdaec8181194624a0452e32908b285785ecf5b9d79c03ebbf6817ccba9a',1),
(7,'James','Doe','james@doe.com','c9a422e3c3864e346d795a0aeb316339a3f5d987d6de15b3756061685fc6001e223d8a90134cbd1b3a634126f03c783bb4ed421335b8d6cee8b0b821ace492b9',1),
(8,'Jake','Doe','jake@doe.com','d090a7b217f4bb8c7548768281ff321d4115c07b2eb565235923a94db8c56015e2dd88ea11b52c7365a00b6f20acd38b5a303c799764422edb163f41d6f1cffd',2),
(9,'azddazd','azddzadaz','azdazdaz@gmail.com','a59f67c82954e9b541b894f82fd69c00334963fe741f6a3634025ade4d95e8dce41d4935dc7d45d9e310139e09c8b8638701ffea039b5e627d09785e75c4ed52',2),
(10,'Julien','Dali','julien@dali.fr','3da21d6c421dc86e36c108fc26f752634e261194f008e1446ee41b992cb09523981e41a4ff5eb7c292385ec369e7d7e71fe3a9859344cdcc137bf9eba39bd40d',2);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2024-12-18 11:42:30
