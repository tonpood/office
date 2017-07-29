# ระบบบัญชีลูกหนี้
ระบบบัญชีลูกหนี้สร้างจากคชสารเว็บเฟรมเวิร์คโดยใช้โปรเจ็ค Personnel มาพัฒนาต่อ
เป็นระบบบันทึกข้อมูลลูกหนี้ (มีการใช้งานจริงอยู่) มีความสามารถในการพิมพ์เอกสารสัญญา คำนวณดอกเบี้ย บันทึกประวัติการชำระเงิน
มีการใช้งาน autocomplete ในการค้นหารายชื่อลูกค้ามาใช้งานในฟอร์ม

## การติดตั้ง
### 1. สร้างฐานข้อมูล ```office``` และ ตารางตามโค้ดด้านล่าง

```
CREATE TABLE IF NOT EXISTS `office_ar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `id_card` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `expire_date` date NOT NULL,
  `address` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `provinceID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `interest` double NOT NULL,
  `period` smallint(6) NOT NULL,
  `period_type` tinyint(1) NOT NULL,
  `aggregate` double NOT NULL,
  `include_interest` tinyint(1) NOT NULL,
  `latigude` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `lantigude` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `map` text COLLATE utf8_unicode_ci NOT NULL,
  `zoom` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `office_ar` (`id`, `name`, `sex`, `id_card`, `expire_date`, `address`, `phone`, `provinceID`, `zipcode`, `detail`, `comment`, `interest`, `period`, `period_type`, `aggregate`, `include_interest`, `latigude`, `lantigude`, `map`, `zoom`) VALUES
(1, 'นายทดสอบ ลูกหนี้', 'm', '010101010101', '2017-01-01', '111 หมู่ 1 ต.ลาดหญ้า อ.เมือง', '01010101', '103', '71000', 'ที่ดินพร้อมสิ่งปลูกสร้าง ตามใบ ภ.บ.ท.5 เลขที่สำรวจ 11/111 หมู่ที่ 11 ตำบล วังด้ง อำเภอ เมือง จังหวัด กาญจนบุรี เนื้อที่ 11 ไร่ 11 งาน 11 วา', '', 20, 1, 12, 34000, 1, '', '', '', 0);

CREATE TABLE IF NOT EXISTS `office_ar_details` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `office_id` int(11) unsigned NOT NULL COMMENT 'id ของ office',
  `member_id` int(11) unsigned NOT NULL COMMENT 'id สมาชิก',
  `type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` int(11) NOT NULL,
  `amount` double NOT NULL,
  `percent` int(11) NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `office_ar_details` (`id`, `office_id`, `member_id`, `type`, `create_date`, `amount`, `percent`, `detail`) VALUES
(1, 1, 1, 'out', 1498582800, 10000, 20, ''),
(2, 1, 1, 'in', 1498582800, 2000, 0, '');

CREATE TABLE IF NOT EXISTS `office_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `permission` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_card` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expire_date` date NOT NULL,
  `address` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provinceID` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visited` int(11) NOT NULL,
  `lastvisited` int(11) NOT NULL,
  `session_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `office_user` (`id`, `username`, `password`, `status`, `permission`, `name`, `sex`, `id_card`, `expire_date`, `address`, `phone`, `provinceID`, `zipcode`, `visited`, `lastvisited`, `session_id`, `ip`, `create_date`) VALUES
(1, 'admin@localhost', 'b620e8b83d7fcf7278148d21b088511917762014', 1, 'can_config,can_login,loan_payable,accountant', 'นาย เจ้าหนี้ สมมุติ', 'm', '', '1899-11-30', '1 หมู่ 1 ตำบล ลาดหญ้า อำเภอ เมือง', '0123456789', '102', '10000', 14, 1498715871, 'viea2oe1aupgrqcebrlucgtok7', '119.76.143.252', '0000-00-00 00:00:00'),
(104, '', '', 0, '', 'นายทดสอบ ลูกหนี้', 'm', '010101010101', '2017-01-01', '111 หมู่ 1 ต.ลาดหญ้า อ.เมือง', '01010101', '103', '71000', 0, 0, NULL, NULL, '2017-06-28 23:16:20');
```

### 2. แก้ไขค่าติดตั้งของฐานข้อมูลให้ถูกต้อง ไฟล์ settings/database.php

```

<?php
/* settings/database.php */
return array(
  'mysql' => array(
    'dbdriver' => 'mysql',
    'username' => 'root',
    'password' => '',
    'dbname' => 'office',
    'prefix' => 'office',
  ),
  'tables' => array(
    'user' => 'user',
    'ar' => 'ar',
    'ar_details' => 'ar_details',
  )
);
```

### 3. สร้างไดเร็คทอรี่ ```datas/``` ถ้ายังไม่มีและปรับ chmod ให้สามารถเขียนได้หรือปรับ chmod ให้เป็น 777

## การใช้งาน
เข้าระบบโดย User : ```admin@localhost``` และ Password : ```admin```