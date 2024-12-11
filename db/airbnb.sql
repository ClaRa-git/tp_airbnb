/*M!999999\- enable the sandbox mode */
-- MariaDB dump 10.19-11.6.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: airbnb
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB-ubu2404
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */
;
/*!40103 SET TIME_ZONE='+00:00' */
;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */
;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `addresses` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `city` varchar(50) NOT NULL,
    `country` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */
;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `equipments`
--

DROP TABLE IF EXISTS `equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `equipments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `labelEquipment` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `equipments`
--

LOCK TABLES `equipments` WRITE;
/*!40000 ALTER TABLE `equipments` DISABLE KEYS */
;
/*!40000 ALTER TABLE `equipments` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `rentals`
--

DROP TABLE IF EXISTS `rentals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `rentals` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(50) NOT NULL,
    `price` decimal(6, 2) NOT NULL,
    `surface` int(11) NOT NULL,
    `description` text NOT NULL,
    `beddings` int(11) NOT NULL,
    `typeLogement_id` int(11) DEFAULT NULL,
    `address_id` int(11) DEFAULT NULL,
    `owner_id` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_rentals_types` (`typeLogement_id`),
    KEY `fk_rentals_addresses` (`address_id`),
    KEY `fk_rentals_users` (`owner_id`),
    CONSTRAINT `fk_rentals_addresses` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
    CONSTRAINT `fk_rentals_types` FOREIGN KEY (`typeLogement_id`) REFERENCES `typesLogement` (`id`),
    CONSTRAINT `fk_rentals_users` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `rentals`
--

LOCK TABLES `rentals` WRITE;
/*!40000 ALTER TABLE `rentals` DISABLE KEYS */
;
/*!40000 ALTER TABLE `rentals` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `rentals_equipments`
--

DROP TABLE IF EXISTS `rentals_equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `rentals_equipments` (
    `rental_id` int(11) NOT NULL,
    `equipment_id` int(11) NOT NULL,
    PRIMARY KEY (`rental_id`, `equipment_id`),
    KEY `fk_rentals_equipments_equipments` (`equipment_id`),
    CONSTRAINT `fk_rentals_equipments_equipments` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`),
    CONSTRAINT `fk_rentals_equipments_rentals` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `rentals_equipments`
--

LOCK TABLES `rentals_equipments` WRITE;
/*!40000 ALTER TABLE `rentals_equipments` DISABLE KEYS */
;
/*!40000 ALTER TABLE `rentals_equipments` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `reservations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `beginDate` datetime NOT NULL,
    `endDate` datetime NOT NULL,
    `user_id` int(11) NOT NULL,
    `rental_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_reservations_users` (`user_id`),
    KEY `fk_reservations_rentals` (`rental_id`),
    CONSTRAINT `fk_reservations_rentals` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`),
    CONSTRAINT `fk_reservations_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */
;
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `typesLogement`
--

DROP TABLE IF EXISTS `typesLogement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `typesLogement` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `labelTypeLogement` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `typesLogement`
--

LOCK TABLES `typesLogement` WRITE;
/*!40000 ALTER TABLE `typesLogement` DISABLE KEYS */
;
/*!40000 ALTER TABLE `typesLogement` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `firstName` varchar(50) NOT NULL,
    `lastName` varchar(50) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `typeAccount` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */
;
/*!40000 ALTER TABLE `users` ENABLE KEYS */
;

UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */
;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */
;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */
;

-- Dump completed on 2024-12-06 14:16:09