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

/*Table structure for table `leave_protocols` */

DROP TABLE IF EXISTS `leave_protocols`;

CREATE TABLE `leave_protocols` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `approvingofficer` varchar(100) DEFAULT NULL,
  `requestingoffice` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=507 DEFAULT CHARSET=utf8mb4;

/*Data for the table `leave_protocols` */

insert  into `leave_protocols`(`id`,`approvingofficer`,`requestingoffice`) values 
(297,'35','2'),
(298,'35','3'),
(299,'35','79'),
(300,'35','5'),
(301,'35','77'),
(302,'35','6'),
(303,'35','7'),
(304,'35','8'),
(305,'35','10'),
(306,'35','11'),
(307,'35','12'),
(308,'35','14'),
(309,'35','16'),
(310,'35','17'),
(311,'35','18'),
(312,'35','19'),
(313,'35','75'),
(314,'35','20'),
(315,'35','22'),
(316,'35','23'),
(317,'35','26'),
(318,'35','78'),
(319,'35','27'),
(320,'35','74'),
(321,'35','28'),
(322,'35','29'),
(323,'35','30'),
(324,'35','31'),
(325,'35','33'),
(326,'35','34'),
(327,'35','35'),
(328,'35','36'),
(329,'35','37'),
(330,'35','38'),
(331,'35','46'),
(332,'35','49'),
(333,'35','50'),
(334,'35','51'),
(335,'35','52'),
(336,'35','54'),
(337,'35','1'),
(338,'35','56'),
(339,'35','57'),
(340,'35','73'),
(341,'35','76'),
(342,'35','59'),
(343,'35','65'),
(344,'35','67'),
(345,'35','68'),
(346,'35','69'),
(347,'35','70'),
(348,'35','71'),
(349,'35','72'),
(350,'59','8'),
(351,'59','10'),
(352,'59','11'),
(353,'59','12'),
(354,'59','14'),
(355,'59','16'),
(356,'59','19'),
(357,'59','75'),
(358,'59','20'),
(359,'59','22'),
(360,'59','23'),
(361,'59','26'),
(362,'59','74'),
(363,'59','28'),
(364,'59','29'),
(365,'59','30'),
(366,'59','36'),
(367,'59','46'),
(368,'59','49'),
(369,'59','51'),
(370,'59','52'),
(371,'59','54'),
(372,'59','1'),
(373,'59','56'),
(374,'59','57'),
(375,'59','76'),
(376,'59','67'),
(377,'59','69'),
(378,'59','70'),
(379,'59','72'),
(380,'65','8'),
(381,'65','10'),
(382,'65','11'),
(383,'65','12'),
(384,'65','14'),
(385,'65','16'),
(386,'65','19'),
(387,'65','75'),
(388,'65','20'),
(389,'65','22'),
(390,'65','23'),
(391,'65','26'),
(392,'65','74'),
(393,'65','28'),
(394,'65','29'),
(395,'65','30'),
(396,'65','36'),
(397,'65','46'),
(398,'65','49'),
(399,'65','51'),
(400,'65','52'),
(401,'65','54'),
(402,'65','1'),
(403,'65','56'),
(404,'65','57'),
(405,'65','76'),
(406,'65','59'),
(407,'65','67'),
(408,'65','69'),
(409,'65','70'),
(410,'65','72'),
(411,'50','8'),
(412,'50','10'),
(413,'50','11'),
(414,'50','12'),
(415,'50','14'),
(416,'50','16'),
(417,'50','19'),
(418,'50','75'),
(419,'50','20'),
(420,'50','22'),
(421,'50','23'),
(422,'50','26'),
(423,'50','74'),
(424,'50','28'),
(425,'50','29'),
(426,'50','30'),
(427,'50','36'),
(428,'50','46'),
(429,'50','49'),
(430,'50','51'),
(431,'50','52'),
(432,'50','54'),
(433,'50','1'),
(434,'50','56'),
(435,'50','57'),
(436,'50','76'),
(437,'50','59'),
(438,'50','65'),
(439,'50','67'),
(440,'50','69'),
(441,'50','70'),
(442,'50','72'),
(443,'17','2'),
(444,'17','3'),
(445,'17','79'),
(446,'17','5'),
(447,'17','77'),
(448,'17','6'),
(449,'17','7'),
(450,'17','8'),
(451,'17','10'),
(452,'17','11'),
(453,'17','12'),
(454,'17','14'),
(455,'17','16'),
(456,'17','17'),
(457,'17','18'),
(458,'17','19'),
(459,'17','75'),
(460,'17','20'),
(461,'17','22'),
(462,'17','23'),
(463,'17','26'),
(464,'17','78'),
(465,'17','27'),
(466,'17','74'),
(467,'17','28'),
(468,'17','29'),
(469,'17','30'),
(470,'17','31'),
(471,'17','33'),
(472,'17','34'),
(473,'17','35'),
(474,'17','36'),
(475,'17','37'),
(476,'17','38'),
(477,'17','46'),
(478,'17','49'),
(479,'17','50'),
(480,'17','51'),
(481,'17','52'),
(482,'17','54'),
(483,'17','1'),
(484,'17','56'),
(485,'17','57'),
(486,'17','73'),
(487,'17','76'),
(488,'17','59'),
(489,'17','65'),
(490,'17','67'),
(491,'17','68'),
(492,'17','69'),
(493,'17','70'),
(494,'17','71'),
(495,'17','72'),
(496,'6','79'),
(497,'6','5'),
(498,'6','77'),
(499,'6','7'),
(500,'6','27'),
(501,'6','37'),
(502,'6','38'),
(503,'6','68'),
(504,'6','71'),
(505,'37','38'),
(506,'37','68');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
