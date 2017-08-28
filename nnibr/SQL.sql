/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.1.41-community : Database - tmuseum
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tmuseum` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `da_admin` */

CREATE TABLE `da_admin` (
  `idx` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '관리자테이블',
  `adm_id` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '아이디',
  `adm_nm` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '이름',
  `adm_enm` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '영문명',
  `adm_pw` varchar(20) CHARACTER SET utf8 NOT NULL,
  `adm_level` tinyint(3) unsigned DEFAULT NULL COMMENT '직급',
  `adm_part` char(1) CHARACTER SET utf8 DEFAULT NULL COMMENT '부서',
  `adm_position` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '직책',
  `adm_mno` text CHARACTER SET utf8,
  `adm_hp` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '핸드폰',
  `adm_email` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '이메일',
  `enter_date` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '입사일',
  `leave_date` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '퇴사일',
  `latest_login` datetime DEFAULT NULL COMMENT '최근로그인',
  `idDel` char(1) CHARACTER SET utf8 DEFAULT '0' COMMENT '삭제여부',
  `w_date` datetime DEFAULT NULL,
  `u_date` datetime DEFAULT NULL,
  `w_id` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `u_id` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `da_admin` */

insert  into `da_admin`(`idx`,`adm_id`,`adm_nm`,`adm_enm`,`adm_pw`,`adm_level`,`adm_part`,`adm_position`,`adm_mno`,`adm_hp`,`adm_email`,`enter_date`,`leave_date`,`latest_login`,`idDel`,`w_date`,`u_date`,`w_id`,`u_id`) values (1,'admin','관리자2','','1234',0,'','','관리자 메모3','','','','','2014-12-31 10:26:12','0',NULL,NULL,NULL,NULL);

/*Table structure for table `da_admin_auth` */

CREATE TABLE `da_admin_auth` (
  `m_id` varchar(20) CHARACTER SET utf8 NOT NULL,
  `mnu_pid` varchar(10) CHARACTER SET utf8 NOT NULL,
  `mnu_id` varchar(10) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`m_id`,`mnu_pid`,`mnu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `da_admin_auth` */

/*Table structure for table `da_data` */

CREATE TABLE `da_data` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loc` tinyint(3) unsigned DEFAULT '1',
  `stat` char(1) DEFAULT 'Y',
  `tit_k` varchar(100) DEFAULT NULL,
  `tit_e` varchar(100) DEFAULT NULL,
  `tit_c` varchar(100) DEFAULT NULL,
  `tit_j` varchar(100) DEFAULT NULL,
  `nm` varchar(30) DEFAULT NULL,
  `nmtag` varchar(30) DEFAULT NULL,
  `img1` varchar(30) DEFAULT NULL,
  `img2` varchar(30) DEFAULT NULL,
  `img3` varchar(30) DEFAULT NULL,
  `img4` varchar(30) DEFAULT NULL,
  `img5` varchar(30) DEFAULT NULL,
  `img6` varchar(30) DEFAULT NULL,
  `img7` varchar(30) DEFAULT NULL,
  `img8` varchar(30) DEFAULT NULL,
  `img9` varchar(30) DEFAULT NULL,
  `mov1` varchar(30) DEFAULT NULL,
  `mov2` varchar(30) DEFAULT NULL,
  `mov3` varchar(30) DEFAULT NULL,
  `mov4` varchar(30) DEFAULT NULL,
  `exp_k` text,
  `exp_e` text,
  `exp_c` text,
  `exp_j` text,
  `mno` text,
  `in_date` datetime DEFAULT NULL,
  `up_date` datetime DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `da_data` */

insert  into `da_data`(`idx`,`loc`,`stat`,`tit_k`,`tit_e`,`tit_c`,`tit_j`,`nm`,`nmtag`,`img1`,`img2`,`img3`,`img4`,`img5`,`img6`,`img7`,`img8`,`img9`,`mov1`,`mov2`,`mov3`,`mov4`,`exp_k`,`exp_e`,`exp_c`,`exp_j`,`mno`,`in_date`,`up_date`) values (1,2,'1','첫번째 데이터',NULL,NULL,NULL,'김진영','010101','1419850602.jpg','','','1419850602_0.jpg','','',NULL,NULL,NULL,'','','','','ㄴㅁㅇㄻㄴㄹ111','ㅁㄴㄻㄴ222','ㄴㅁㄻㄴㅇㄹ333','ㅁㄴㅇㄻㄴㄹ\r\nㅁㄴㄹ\r\nㅁ4444','ㅁㄴㄻㄴㅇ\r\nㄻㅇ\r\nㄴㄻㄴㄹㄹㅇㄴㅁㅇㅎㅁㄴㅇㄻㄴㄻㄴㅇㄹ\r\nㅁㄴㄻㄴ','2014-12-29 19:25:58','2014-12-29 19:56:42');
insert  into `da_data`(`idx`,`loc`,`stat`,`tit_k`,`tit_e`,`tit_c`,`tit_j`,`nm`,`nmtag`,`img1`,`img2`,`img3`,`img4`,`img5`,`img6`,`img7`,`img8`,`img9`,`mov1`,`mov2`,`mov3`,`mov4`,`exp_k`,`exp_e`,`exp_c`,`exp_j`,`mno`,`in_date`,`up_date`) values (2,3,'1','테스트2',NULL,NULL,NULL,'홍길동','12342332','1419849115.jpg','1419849115_0.jpg','1419849379.jpg','1419849395.gif','1419849115_3.jpg','1419849379_0.jpg',NULL,NULL,NULL,'1419849115_4.jpg','1419849115_5.jpg','1419849115_6.jpg','1419849115_7.jpg','ㅁㄴㄻㄴㄹ11','1ㅁㄴㄹ22','ㅁㄴㅇㄻㄴㄹㅇ2ㄴㅇㄹㄴㄹㅇㄴ\r\nㅁㄴㄹ333','ㅁㄴㅇㄻㄴㅇㄹ\r\nㅁㄴㅇㄹ\r\n4444','4ㅁㄴㅇㄻㄴㅇㄻ\r\nㄴㅇㄻㄴ\r\nㅇㅁㄴㄹ\r\nㅁㄴㅇㄻㄴㅇㄻㄴㅇㅇㅁㄴ','2014-12-29 19:31:55','2014-12-29 19:36:35');

/*Table structure for table `da_menu` */

CREATE TABLE `da_menu` (
  `mnu_pid` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '1차메뉴아이디',
  `mnu_pnm` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '1차메뉴명',
  `mnu_id` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '2차메뉴아이디',
  `mnu_nm` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '2차메뉴명',
  `mnu_pseq` tinyint(3) unsigned DEFAULT NULL COMMENT '1차메뉴순서',
  `mnu_seq` smallint(5) unsigned DEFAULT NULL COMMENT '2차메뉴순서',
  `mnu_dir` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '폴더명',
  `mnu_file` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '파일명',
  `mnu_view` char(1) CHARACTER SET utf8 DEFAULT '0' COMMENT '화면에 메뉴표시여부',
  PRIMARY KEY (`mnu_pid`,`mnu_id`),
  UNIQUE KEY `mnu_id` (`mnu_id`),
  KEY `mnu_pid` (`mnu_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `da_menu` */

insert  into `da_menu`(`mnu_pid`,`mnu_pnm`,`mnu_id`,`mnu_nm`,`mnu_pseq`,`mnu_seq`,`mnu_dir`,`mnu_file`,`mnu_view`) values ('admin','관리자관리','adm01','관리자리스트',1,1,'admin','adm_list.html?','1');
insert  into `da_menu`(`mnu_pid`,`mnu_pnm`,`mnu_id`,`mnu_nm`,`mnu_pseq`,`mnu_seq`,`mnu_dir`,`mnu_file`,`mnu_view`) values ('board','유물관리','mus01','유물관리',1,1,'board','free_list.html?','1');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
