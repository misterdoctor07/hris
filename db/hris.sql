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

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `attendance` */

DROP TABLE IF EXISTS `attendance`;

CREATE TABLE `attendance` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `loginam` time DEFAULT '00:00:00',
  `logoutam` time DEFAULT '00:00:00',
  `loginpm` time DEFAULT '00:00:00',
  `logoutpm` time DEFAULT '00:00:00',
  `logindate` date DEFAULT NULL,
  `status` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `department` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Table structure for table `employee_benefits` */

DROP TABLE IF EXISTS `employee_benefits`;

CREATE TABLE `employee_benefits` (
  `idno` varchar(100) DEFAULT NULL,
  `insurance` date DEFAULT NULL,
  `hmo` date DEFAULT NULL,
  `sss` varchar(100) DEFAULT NULL,
  `tin` varchar(100) DEFAULT NULL,
  `phic` varchar(100) DEFAULT NULL,
  `hdmf` varchar(100) DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `employee_checklist` */

DROP TABLE IF EXISTS `employee_checklist`;

CREATE TABLE `employee_checklist` (
  `idno` varchar(100) DEFAULT NULL,
  `dateoriented` date DEFAULT NULL,
  `releasedtempid` varchar(100) DEFAULT NULL,
  `releasedpermanentid` varchar(100) DEFAULT NULL,
  `clearance` varchar(100) DEFAULT NULL,
  `healthcard` varchar(100) DEFAULT NULL,
  `birthcertificate` varchar(100) DEFAULT NULL,
  `idpicture1` varchar(100) DEFAULT NULL,
  `idpicture2` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `employee_contract` */

DROP TABLE IF EXISTS `employee_contract`;

CREATE TABLE `employee_contract` (
  `idno` varchar(100) DEFAULT NULL,
  `probationary` varchar(100) DEFAULT NULL,
  `probationarydate` date DEFAULT NULL,
  `regular` varchar(100) DEFAULT NULL,
  `regulardate` date DEFAULT NULL,
  `fulltime` varchar(100) DEFAULT NULL,
  `fulltimedate` date DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `employee_details` */

DROP TABLE IF EXISTS `employee_details`;

CREATE TABLE `employee_details` (
  `idno` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `dateofhired` date DEFAULT NULL,
  `dateofregular` date DEFAULT NULL,
  `dateoffulltime` date DEFAULT NULL,
  `startshift` time DEFAULT NULL,
  `endshift` time DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `employee_payroll` */

DROP TABLE IF EXISTS `employee_payroll`;

CREATE TABLE `employee_payroll` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `sss` double DEFAULT NULL,
  `phic` double DEFAULT NULL,
  `hdmf` double DEFAULT NULL,
  `salary` double DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `employee_profile` */

DROP TABLE IF EXISTS `employee_profile`;

CREATE TABLE `employee_profile` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `civilstatus` varchar(100) DEFAULT NULL,
  `sex` varchar(100) DEFAULT NULL,
  `eligibility` varchar(100) DEFAULT NULL,
  `address` text,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `employee_referral` */

DROP TABLE IF EXISTS `employee_referral`;

CREATE TABLE `employee_referral` (
  `idno` varchar(100) DEFAULT NULL,
  `referredby` varchar(100) DEFAULT NULL,
  `effectivity` date DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `infraction` */

DROP TABLE IF EXISTS `infraction`;

CREATE TABLE `infraction` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `dateissued` date DEFAULT NULL,
  `dateserved` date DEFAULT NULL,
  `typeofmemo` varchar(100) DEFAULT NULL,
  `typeofoffense` varchar(100) DEFAULT NULL,
  `points` double DEFAULT NULL,
  `memonumber` varchar(100) DEFAULT NULL,
  `dateofsuspension` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `jobtitle` */

DROP TABLE IF EXISTS `jobtitle`;

CREATE TABLE `jobtitle` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `jobtitle` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Table structure for table `leave_application` */

DROP TABLE IF EXISTS `leave_application`;

CREATE TABLE `leave_application` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `leavetype` varchar(100) DEFAULT NULL,
  `numberofdays` int(45) DEFAULT NULL,
  `inclusivedates` text,
  `reason` text,
  `datearray` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `leave_credits` */

DROP TABLE IF EXISTS `leave_credits`;

CREATE TABLE `leave_credits` (
  `idno` varchar(100) DEFAULT NULL,
  `vacationleave` double DEFAULT NULL,
  `vlused` double DEFAULT NULL,
  `sickleave` double DEFAULT NULL,
  `slused` double DEFAULT NULL,
  `pto` double DEFAULT NULL,
  `ptoused` double DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `memo` */

DROP TABLE IF EXISTS `memo`;

CREATE TABLE `memo` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `movement_tracker` */

DROP TABLE IF EXISTS `movement_tracker`;

CREATE TABLE `movement_tracker` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `companyfrom` varchar(100) DEFAULT NULL,
  `companyto` varchar(100) DEFAULT NULL,
  `departmentfrom` varchar(100) DEFAULT NULL,
  `departmentto` varchar(100) DEFAULT NULL,
  `jobfrom` varchar(100) DEFAULT NULL,
  `jobto` varchar(100) DEFAULT NULL,
  `shiftfrom` varchar(100) DEFAULT NULL,
  `shiftto` varchar(100) DEFAULT NULL,
  `effectivitydate` date DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `offense` */

DROP TABLE IF EXISTS `offense`;

CREATE TABLE `offense` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `payroll` */

DROP TABLE IF EXISTS `payroll`;

CREATE TABLE `payroll` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `periodfrom` date DEFAULT NULL,
  `periodto` date DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `payroll_deductions` */

DROP TABLE IF EXISTS `payroll_deductions`;

CREATE TABLE `payroll_deductions` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `payrollperiod` int(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `payroll_details` */

DROP TABLE IF EXISTS `payroll_details`;

CREATE TABLE `payroll_details` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `payrollperiod` int(45) DEFAULT NULL,
  `reghours` double DEFAULT NULL,
  `baserate` double DEFAULT NULL,
  `hourswork` double DEFAULT NULL,
  `otbefore` double DEFAULT NULL,
  `ndhours` double DEFAULT NULL,
  `regdaysot` double DEFAULT NULL,
  `ndrate` double DEFAULT NULL,
  `specialholiday` double DEFAULT NULL,
  `otafter` double DEFAULT NULL,
  `otregular` double DEFAULT NULL,
  `regholiday` double DEFAULT NULL,
  `totalpay` double DEFAULT NULL,
  `addedby` varchar(100) DEFAULT NULL,
  `addeddatetime` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL,
  `updateddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `resignation` */

DROP TABLE IF EXISTS `resignation`;

CREATE TABLE `resignation` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `idno` varchar(100) DEFAULT NULL,
  `dateresigned` date DEFAULT NULL,
  `reason` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `companycode` varchar(100) DEFAULT NULL,
  `companyname` varchar(200) DEFAULT NULL,
  `companyaddress` text,
  `companylogo` varchar(100) DEFAULT NULL,
  `companyceo` varchar(100) DEFAULT NULL,
  `companyemail` varchar(100) DEFAULT NULL,
  `companyphone` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `idno` varchar(100) DEFAULT NULL,
  `access` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
