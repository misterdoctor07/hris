/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.4.22-MariaDB : Database - hris
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`hris` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `hris`;

/*Table structure for table `payroll` */

DROP TABLE IF EXISTS `payroll`;

CREATE TABLE `payroll` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `periodfrom` date DEFAULT NULL,
  `periodto` date DEFAULT NULL,
  `period` varchar(100) DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `payroll` */

insert  into `payroll`(`id`,`periodfrom`,`periodto`,`period`,`addedby`,`addeddatetime`,`updatedby`,`updateddatetime`) values 
(2,'2021-12-28','2022-01-12','mid','Administrator','2022-01-25 00:37:45',NULL,NULL),
(3,'2022-01-13','2022-01-27','end','Cantomayor, Jayrald','2022-01-27 00:00:08',NULL,NULL),
(4,'2022-02-15','2022-02-26','end','Hassan, Lovely Jane','2022-02-16 00:05:03',NULL,NULL),
(5,'2022-02-15','2022-02-28','end','Salvador, Jacqueline','2022-02-16 00:30:03',NULL,NULL),
(6,'2022-02-17','2022-02-26','end','Hassan, Lovely Jane','2022-02-17 00:50:51',NULL,NULL),
(7,'2022-02-12','2022-02-28','end','Hassan, Lovely Jane','2022-02-18 02:54:35',NULL,NULL),
(8,'2022-02-13','2022-02-26','end','Hassan, Lovely Jane','2022-02-19 04:43:36',NULL,NULL),
(9,'2022-02-01','2022-03-12','mid','Administrator','2022-02-28 23:02:16',NULL,NULL),
(10,'2022-03-01','2022-03-12','mid','Administrator','2022-02-28 23:40:12',NULL,NULL),
(11,'2022-02-01','2022-02-12','mid','Administrator','2022-02-28 23:54:26',NULL,NULL),
(12,'2022-03-13','2022-03-27','end','Salvador, Jacqueline','2022-03-15 03:59:06',NULL,NULL),
(13,'2022-02-28','2022-03-12','mid','Hassan, Lovely Jane','2022-03-18 02:29:15',NULL,NULL),
(14,'2022-03-28','2022-04-12','mid','Hassan, Lovely Jane','2022-03-31 04:35:45',NULL,NULL),
(15,'2022-03-29','2022-04-02','mid','Hassan, Lovely Jane','2022-04-01 08:02:22',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
