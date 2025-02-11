/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.1.38-MariaDB : Database - hris
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`hris` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hris`;

/*Table structure for table `payroll_details` */

DROP TABLE IF EXISTS `payroll_details`;

CREATE TABLE `payroll_details` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `payrollperiod` int(45) DEFAULT NULL,
  `reghours` double DEFAULT NULL,
  `reghoursot` double DEFAULT NULL,
  `reghoursotamount` double DEFAULT NULL,
  `regholidayhrsnotwork` double DEFAULT NULL,
  `regholidayamountnotwork` double DEFAULT NULL,
  `regholidayhrswork1` double DEFAULT NULL,
  `regholidayamountwork1` double DEFAULT NULL,
  `regholidayhrswork2` double DEFAULT NULL,
  `regholidayamountwork2` double DEFAULT NULL,
  `regholidayothrs` double DEFAULT NULL,
  `regholidayotamount` double DEFAULT NULL,
  `spholidayhrs1` double DEFAULT NULL,
  `spholidayamount1` double DEFAULT NULL,
  `spholidayhrs2` double DEFAULT NULL,
  `spholidayamount2` double DEFAULT NULL,
  `spholidayothrs` double DEFAULT NULL,
  `spholidayotamount` double DEFAULT NULL,
  `ndhrs` double DEFAULT NULL,
  `ndamount` double DEFAULT NULL,
  `paidslhrs` double DEFAULT NULL,
  `paidslamount` double DEFAULT NULL,
  `paidvlhrs` double DEFAULT NULL,
  `paidvlamount` double DEFAULT NULL,
  `paidblhrs` double DEFAULT NULL,
  `paidblamount` double DEFAULT NULL,
  `bdayleavehrs` double DEFAULT NULL,
  `bdayleaveamount` double DEFAULT NULL,
  `totalpay` double DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL,
  `status` varchar(100) DEFAULT 'pending',
  `dateposted` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
