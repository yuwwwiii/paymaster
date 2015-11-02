-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: orangehrm_db
-- ------------------------------------------------------
-- Server version	5.5.16

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
-- Table structure for table `hs_hr_config`
--

DROP TABLE IF EXISTS `hs_hr_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_config` (
  `key` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_config`
--

LOCK TABLES `hs_hr_config` WRITE;
/*!40000 ALTER TABLE `hs_hr_config` DISABLE KEYS */;
INSERT INTO `hs_hr_config` VALUES ('admin.localization.default_date_format','Y-m-d'),('admin.localization.default_language','en'),('admin.localization.use_browser_language','No'),('attendanceEmpEditSubmitted','No'),('attendanceSupEditSubmitted','No'),('authorize_user_role_manager_class','BasicUserRoleManager'),('hsp_accrued_last_updated','0000-00-00'),('hsp_current_plan','0'),('hsp_used_last_updated','0000-00-00'),('ldap_domain_name',''),('ldap_port',''),('ldap_server',''),('ldap_status',''),('leave.country_based','off'),('leave.isLeavePeriodStartOnFeb29th','No'),('leave.leavePeriodStartDate','1-1'),('leave.nonLeapYearLeavePeriodStartDate',''),('leave_period_defined','Yes'),('pim_show_deprecated_fields','0'),('pim_show_sin','1'),('pim_show_ssn','1'),('pim_show_tax_exemptions','0'),('showSIN','0'),('showSSN','0'),('showTaxExemptions','0'),('timesheet_period_and_start_date','<TimesheetPeriod><PeriodType>Weekly</PeriodType><ClassName>WeeklyTimesheetPeriod</ClassName><StartDate>1</StartDate><Heading>Week</Heading></TimesheetPeriod>'),('timesheet_period_set','Yes'),('timesheet_time_format','1');
/*!40000 ALTER TABLE `hs_hr_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_country`
--

DROP TABLE IF EXISTS `hs_hr_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_country` (
  `cou_code` char(2) NOT NULL DEFAULT '',
  `name` varchar(80) NOT NULL DEFAULT '',
  `cou_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`cou_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_country`
--

LOCK TABLES `hs_hr_country` WRITE;
/*!40000 ALTER TABLE `hs_hr_country` DISABLE KEYS */;
INSERT INTO `hs_hr_country` VALUES ('AD','ANDORRA','Andorra','AND',20),('AE','UNITED ARAB EMIRATES','United Arab Emirates','ARE',784),('AF','AFGHANISTAN','Afghanistan','AFG',4),('AG','ANTIGUA AND BARBUDA','Antigua and Barbuda','ATG',28),('AI','ANGUILLA','Anguilla','AIA',660),('AL','ALBANIA','Albania','ALB',8),('AM','ARMENIA','Armenia','ARM',51),('AN','NETHERLANDS ANTILLES','Netherlands Antilles','ANT',530),('AO','ANGOLA','Angola','AGO',24),('AQ','ANTARCTICA','Antarctica',NULL,NULL),('AR','ARGENTINA','Argentina','ARG',32),('AS','AMERICAN SAMOA','American Samoa','ASM',16),('AT','AUSTRIA','Austria','AUT',40),('AU','AUSTRALIA','Australia','AUS',36),('AW','ARUBA','Aruba','ABW',533),('AZ','AZERBAIJAN','Azerbaijan','AZE',31),('BA','BOSNIA AND HERZEGOVINA','Bosnia and Herzegovina','BIH',70),('BB','BARBADOS','Barbados','BRB',52),('BD','BANGLADESH','Bangladesh','BGD',50),('BE','BELGIUM','Belgium','BEL',56),('BF','BURKINA FASO','Burkina Faso','BFA',854),('BG','BULGARIA','Bulgaria','BGR',100),('BH','BAHRAIN','Bahrain','BHR',48),('BI','BURUNDI','Burundi','BDI',108),('BJ','BENIN','Benin','BEN',204),('BM','BERMUDA','Bermuda','BMU',60),('BN','BRUNEI DARUSSALAM','Brunei Darussalam','BRN',96),('BO','BOLIVIA','Bolivia','BOL',68),('BR','BRAZIL','Brazil','BRA',76),('BS','BAHAMAS','Bahamas','BHS',44),('BT','BHUTAN','Bhutan','BTN',64),('BV','BOUVET ISLAND','Bouvet Island',NULL,NULL),('BW','BOTSWANA','Botswana','BWA',72),('BY','BELARUS','Belarus','BLR',112),('BZ','BELIZE','Belize','BLZ',84),('CA','CANADA','Canada','CAN',124),('CC','COCOS (KEELING) ISLANDS','Cocos (Keeling) Islands',NULL,NULL),('CD','CONGO, THE DEMOCRATIC REPUBLIC OF THE','Congo, the Democratic Republic of the','COD',180),('CF','CENTRAL AFRICAN REPUBLIC','Central African Republic','CAF',140),('CG','CONGO','Congo','COG',178),('CH','SWITZERLAND','Switzerland','CHE',756),('CI','COTE D\'IVOIRE','Cote D\'Ivoire','CIV',384),('CK','COOK ISLANDS','Cook Islands','COK',184),('CL','CHILE','Chile','CHL',152),('CM','CAMEROON','Cameroon','CMR',120),('CN','CHINA','China','CHN',156),('CO','COLOMBIA','Colombia','COL',170),('CR','COSTA RICA','Costa Rica','CRI',188),('CS','SERBIA AND MONTENEGRO','Serbia and Montenegro',NULL,NULL),('CU','CUBA','Cuba','CUB',192),('CV','CAPE VERDE','Cape Verde','CPV',132),('CX','CHRISTMAS ISLAND','Christmas Island',NULL,NULL),('CY','CYPRUS','Cyprus','CYP',196),('CZ','CZECH REPUBLIC','Czech Republic','CZE',203),('DE','GERMANY','Germany','DEU',276),('DJ','DJIBOUTI','Djibouti','DJI',262),('DK','DENMARK','Denmark','DNK',208),('DM','DOMINICA','Dominica','DMA',212),('DO','DOMINICAN REPUBLIC','Dominican Republic','DOM',214),('DZ','ALGERIA','Algeria','DZA',12),('EC','ECUADOR','Ecuador','ECU',218),('EE','ESTONIA','Estonia','EST',233),('EG','EGYPT','Egypt','EGY',818),('EH','WESTERN SAHARA','Western Sahara','ESH',732),('ER','ERITREA','Eritrea','ERI',232),('ES','SPAIN','Spain','ESP',724),('ET','ETHIOPIA','Ethiopia','ETH',231),('FI','FINLAND','Finland','FIN',246),('FJ','FIJI','Fiji','FJI',242),('FK','FALKLAND ISLANDS (MALVINAS)','Falkland Islands (Malvinas)','FLK',238),('FM','MICRONESIA, FEDERATED STATES OF','Micronesia, Federated States of','FSM',583),('FO','FAROE ISLANDS','Faroe Islands','FRO',234),('FR','FRANCE','France','FRA',250),('GA','GABON','Gabon','GAB',266),('GB','UNITED KINGDOM','United Kingdom','GBR',826),('GD','GRENADA','Grenada','GRD',308),('GE','GEORGIA','Georgia','GEO',268),('GF','FRENCH GUIANA','French Guiana','GUF',254),('GH','GHANA','Ghana','GHA',288),('GI','GIBRALTAR','Gibraltar','GIB',292),('GL','GREENLAND','Greenland','GRL',304),('GM','GAMBIA','Gambia','GMB',270),('GN','GUINEA','Guinea','GIN',324),('GP','GUADELOUPE','Guadeloupe','GLP',312),('GQ','EQUATORIAL GUINEA','Equatorial Guinea','GNQ',226),('GR','GREECE','Greece','GRC',300),('GS','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','South Georgia and the South Sandwich Islands',NULL,NULL),('GT','GUATEMALA','Guatemala','GTM',320),('GU','GUAM','Guam','GUM',316),('GW','GUINEA-BISSAU','Guinea-Bissau','GNB',624),('GY','GUYANA','Guyana','GUY',328),('HK','HONG KONG','Hong Kong','HKG',344),('HM','HEARD ISLAND AND MCDONALD ISLANDS','Heard Island and Mcdonald Islands',NULL,NULL),('HN','HONDURAS','Honduras','HND',340),('HR','CROATIA','Croatia','HRV',191),('HT','HAITI','Haiti','HTI',332),('HU','HUNGARY','Hungary','HUN',348),('ID','INDONESIA','Indonesia','IDN',360),('IE','IRELAND','Ireland','IRL',372),('IL','ISRAEL','Israel','ISR',376),('IN','INDIA','India','IND',356),('IO','BRITISH INDIAN OCEAN TERRITORY','British Indian Ocean Territory',NULL,NULL),('IQ','IRAQ','Iraq','IRQ',368),('IR','IRAN, ISLAMIC REPUBLIC OF','Iran, Islamic Republic of','IRN',364),('IS','ICELAND','Iceland','ISL',352),('IT','ITALY','Italy','ITA',380),('JM','JAMAICA','Jamaica','JAM',388),('JO','JORDAN','Jordan','JOR',400),('JP','JAPAN','Japan','JPN',392),('KE','KENYA','Kenya','KEN',404),('KG','KYRGYZSTAN','Kyrgyzstan','KGZ',417),('KH','CAMBODIA','Cambodia','KHM',116),('KI','KIRIBATI','Kiribati','KIR',296),('KM','COMOROS','Comoros','COM',174),('KN','SAINT KITTS AND NEVIS','Saint Kitts and Nevis','KNA',659),('KP','KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','Korea, Democratic People\'s Republic of','PRK',408),('KR','KOREA, REPUBLIC OF','Korea, Republic of','KOR',410),('KW','KUWAIT','Kuwait','KWT',414),('KY','CAYMAN ISLANDS','Cayman Islands','CYM',136),('KZ','KAZAKHSTAN','Kazakhstan','KAZ',398),('LA','LAO PEOPLE\'S DEMOCRATIC REPUBLIC','Lao People\'s Democratic Republic','LAO',418),('LB','LEBANON','Lebanon','LBN',422),('LC','SAINT LUCIA','Saint Lucia','LCA',662),('LI','LIECHTENSTEIN','Liechtenstein','LIE',438),('LK','SRI LANKA','Sri Lanka','LKA',144),('LR','LIBERIA','Liberia','LBR',430),('LS','LESOTHO','Lesotho','LSO',426),('LT','LITHUANIA','Lithuania','LTU',440),('LU','LUXEMBOURG','Luxembourg','LUX',442),('LV','LATVIA','Latvia','LVA',428),('LY','LIBYAN ARAB JAMAHIRIYA','Libyan Arab Jamahiriya','LBY',434),('MA','MOROCCO','Morocco','MAR',504),('MC','MONACO','Monaco','MCO',492),('MD','MOLDOVA, REPUBLIC OF','Moldova, Republic of','MDA',498),('MG','MADAGASCAR','Madagascar','MDG',450),('MH','MARSHALL ISLANDS','Marshall Islands','MHL',584),('MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','Macedonia, the Former Yugoslav Republic of','MKD',807),('ML','MALI','Mali','MLI',466),('MM','MYANMAR','Myanmar','MMR',104),('MN','MONGOLIA','Mongolia','MNG',496),('MO','MACAO','Macao','MAC',446),('MP','NORTHERN MARIANA ISLANDS','Northern Mariana Islands','MNP',580),('MQ','MARTINIQUE','Martinique','MTQ',474),('MR','MAURITANIA','Mauritania','MRT',478),('MS','MONTSERRAT','Montserrat','MSR',500),('MT','MALTA','Malta','MLT',470),('MU','MAURITIUS','Mauritius','MUS',480),('MV','MALDIVES','Maldives','MDV',462),('MW','MALAWI','Malawi','MWI',454),('MX','MEXICO','Mexico','MEX',484),('MY','MALAYSIA','Malaysia','MYS',458),('MZ','MOZAMBIQUE','Mozambique','MOZ',508),('NA','NAMIBIA','Namibia','NAM',516),('NC','NEW CALEDONIA','New Caledonia','NCL',540),('NE','NIGER','Niger','NER',562),('NF','NORFOLK ISLAND','Norfolk Island','NFK',574),('NG','NIGERIA','Nigeria','NGA',566),('NI','NICARAGUA','Nicaragua','NIC',558),('NL','NETHERLANDS','Netherlands','NLD',528),('NO','NORWAY','Norway','NOR',578),('NP','NEPAL','Nepal','NPL',524),('NR','NAURU','Nauru','NRU',520),('NU','NIUE','Niue','NIU',570),('NZ','NEW ZEALAND','New Zealand','NZL',554),('OM','OMAN','Oman','OMN',512),('PA','PANAMA','Panama','PAN',591),('PE','PERU','Peru','PER',604),('PF','FRENCH POLYNESIA','French Polynesia','PYF',258),('PG','PAPUA NEW GUINEA','Papua New Guinea','PNG',598),('PH','PHILIPPINES','Philippines','PHL',608),('PK','PAKISTAN','Pakistan','PAK',586),('PL','POLAND','Poland','POL',616),('PM','SAINT PIERRE AND MIQUELON','Saint Pierre and Miquelon','SPM',666),('PN','PITCAIRN','Pitcairn','PCN',612),('PR','PUERTO RICO','Puerto Rico','PRI',630),('PS','PALESTINIAN TERRITORY, OCCUPIED','Palestinian Territory, Occupied',NULL,NULL),('PT','PORTUGAL','Portugal','PRT',620),('PW','PALAU','Palau','PLW',585),('PY','PARAGUAY','Paraguay','PRY',600),('QA','QATAR','Qatar','QAT',634),('RE','REUNION','Reunion','REU',638),('RO','ROMANIA','Romania','ROM',642),('RU','RUSSIAN FEDERATION','Russian Federation','RUS',643),('RW','RWANDA','Rwanda','RWA',646),('SA','SAUDI ARABIA','Saudi Arabia','SAU',682),('SB','SOLOMON ISLANDS','Solomon Islands','SLB',90),('SC','SEYCHELLES','Seychelles','SYC',690),('SD','SUDAN','Sudan','SDN',736),('SE','SWEDEN','Sweden','SWE',752),('SG','SINGAPORE','Singapore','SGP',702),('SH','SAINT HELENA','Saint Helena','SHN',654),('SI','SLOVENIA','Slovenia','SVN',705),('SJ','SVALBARD AND JAN MAYEN','Svalbard and Jan Mayen','SJM',744),('SK','SLOVAKIA','Slovakia','SVK',703),('SL','SIERRA LEONE','Sierra Leone','SLE',694),('SM','SAN MARINO','San Marino','SMR',674),('SN','SENEGAL','Senegal','SEN',686),('SO','SOMALIA','Somalia','SOM',706),('SR','SURINAME','Suriname','SUR',740),('ST','SAO TOME AND PRINCIPE','Sao Tome and Principe','STP',678),('SV','EL SALVADOR','El Salvador','SLV',222),('SY','SYRIAN ARAB REPUBLIC','Syrian Arab Republic','SYR',760),('SZ','SWAZILAND','Swaziland','SWZ',748),('TC','TURKS AND CAICOS ISLANDS','Turks and Caicos Islands','TCA',796),('TD','CHAD','Chad','TCD',148),('TF','FRENCH SOUTHERN TERRITORIES','French Southern Territories',NULL,NULL),('TG','TOGO','Togo','TGO',768),('TH','THAILAND','Thailand','THA',764),('TJ','TAJIKISTAN','Tajikistan','TJK',762),('TK','TOKELAU','Tokelau','TKL',772),('TL','TIMOR-LESTE','Timor-Leste',NULL,NULL),('TM','TURKMENISTAN','Turkmenistan','TKM',795),('TN','TUNISIA','Tunisia','TUN',788),('TO','TONGA','Tonga','TON',776),('TR','TURKEY','Turkey','TUR',792),('TT','TRINIDAD AND TOBAGO','Trinidad and Tobago','TTO',780),('TV','TUVALU','Tuvalu','TUV',798),('TW','TAIWAN, PROVINCE OF CHINA','Taiwan','TWN',158),('TZ','TANZANIA, UNITED REPUBLIC OF','Tanzania, United Republic of','TZA',834),('UA','UKRAINE','Ukraine','UKR',804),('UG','UGANDA','Uganda','UGA',800),('UM','UNITED STATES MINOR OUTLYING ISLANDS','United States Minor Outlying Islands',NULL,NULL),('US','UNITED STATES','United States','USA',840),('UY','URUGUAY','Uruguay','URY',858),('UZ','UZBEKISTAN','Uzbekistan','UZB',860),('VA','HOLY SEE (VATICAN CITY STATE)','Holy See (Vatican City State)','VAT',336),('VC','SAINT VINCENT AND THE GRENADINES','Saint Vincent and the Grenadines','VCT',670),('VE','VENEZUELA','Venezuela','VEN',862),('VG','VIRGIN ISLANDS, BRITISH','Virgin Islands, British','VGB',92),('VI','VIRGIN ISLANDS, U.S.','Virgin Islands, U.s.','VIR',850),('VN','VIET NAM','Viet Nam','VNM',704),('VU','VANUATU','Vanuatu','VUT',548),('WF','WALLIS AND FUTUNA','Wallis and Futuna','WLF',876),('WS','SAMOA','Samoa','WSM',882),('YE','YEMEN','Yemen','YEM',887),('YT','MAYOTTE','Mayotte',NULL,NULL),('ZA','SOUTH AFRICA','South Africa','ZAF',710),('ZM','ZAMBIA','Zambia','ZMB',894),('ZW','ZIMBABWE','Zimbabwe','ZWE',716);
/*!40000 ALTER TABLE `hs_hr_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_currency_type`
--

DROP TABLE IF EXISTS `hs_hr_currency_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_currency_type` (
  `code` int(11) NOT NULL DEFAULT '0',
  `currency_id` char(3) NOT NULL DEFAULT '',
  `currency_name` varchar(70) NOT NULL DEFAULT '',
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_currency_type`
--

LOCK TABLES `hs_hr_currency_type` WRITE;
/*!40000 ALTER TABLE `hs_hr_currency_type` DISABLE KEYS */;
INSERT INTO `hs_hr_currency_type` VALUES (3,'AED','Utd. Arab Emir. Dirham'),(4,'AFN','Afghanistan Afghani'),(5,'ALL','Albanian Lek'),(6,'ANG','NL Antillian Guilder'),(7,'AOR','Angolan New Kwanza'),(177,'ARP','Argentina Pesos'),(8,'ARS','Argentine Peso'),(10,'AUD','Australian Dollar'),(11,'AWG','Aruban Florin'),(12,'BBD','Barbados Dollar'),(13,'BDT','Bangladeshi Taka'),(15,'BGL','Bulgarian Lev'),(16,'BHD','Bahraini Dinar'),(17,'BIF','Burundi Franc'),(18,'BMD','Bermudian Dollar'),(19,'BND','Brunei Dollar'),(20,'BOB','Bolivian Boliviano'),(21,'BRL','Brazilian Real'),(22,'BSD','Bahamian Dollar'),(23,'BTN','Bhutan Ngultrum'),(24,'BWP','Botswana Pula'),(25,'BZD','Belize Dollar'),(26,'CAD','Canadian Dollar'),(27,'CHF','Swiss Franc'),(28,'CLP','Chilean Peso'),(29,'CNY','Chinese Yuan Renminbi'),(30,'COP','Colombian Peso'),(31,'CRC','Costa Rican Colon'),(32,'CUP','Cuban Peso'),(33,'CVE','Cape Verde Escudo'),(34,'CYP','Cyprus Pound'),(171,'CZK','Czech Koruna'),(37,'DJF','Djibouti Franc'),(38,'DKK','Danish Krona'),(39,'DOP','Dominican Peso'),(40,'DZD','Algerian Dinar'),(41,'ECS','Ecuador Sucre'),(43,'EEK','Estonian Krona'),(44,'EGP','Egyptian Pound'),(46,'ETB','Ethiopian Birr'),(42,'EUR','Euro'),(48,'FJD','Fiji Dollar'),(49,'FKP','Falkland Islands Pound'),(51,'GBP','Pound Sterling'),(52,'GHC','Ghanaian Cedi'),(53,'GIP','Gibraltar Pound'),(54,'GMD','Gambian Dalasi'),(55,'GNF','Guinea Franc'),(57,'GTQ','Guatemalan Quetzal'),(58,'GYD','Guyanan Dollar'),(59,'HKD','Hong Kong Dollar'),(60,'HNL','Honduran Lempira'),(61,'HRK','Croatian Kuna'),(62,'HTG','Haitian Gourde'),(63,'HUF','Hungarian Forint'),(64,'IDR','Indonesian Rupiah'),(66,'ILS','Israeli New Shekel'),(67,'INR','Indian Rupee'),(68,'IQD','Iraqi Dinar'),(69,'IRR','Iranian Rial'),(70,'ISK','Iceland Krona'),(72,'JMD','Jamaican Dollar'),(73,'JOD','Jordanian Dinar'),(74,'JPY','Japanese Yen'),(75,'KES','Kenyan Shilling'),(76,'KHR','Kampuchean Riel'),(77,'KMF','Comoros Franc'),(78,'KPW','North Korean Won'),(79,'KRW','Korean Won'),(80,'KWD','Kuwaiti Dinar'),(81,'KYD','Cayman Islands Dollar'),(82,'KZT','Kazakhstan Tenge'),(83,'LAK','Lao Kip'),(84,'LBP','Lebanese Pound'),(85,'LKR','Sri Lanka Rupee'),(86,'LRD','Liberian Dollar'),(87,'LSL','Lesotho Loti'),(88,'LTL','Lithuanian Litas'),(90,'LVL','Latvian Lats'),(91,'LYD','Libyan Dinar'),(92,'MAD','Moroccan Dirham'),(93,'MGF','Malagasy Franc'),(94,'MMK','Myanmar Kyat'),(95,'MNT','Mongolian Tugrik'),(96,'MOP','Macau Pataca'),(97,'MRO','Mauritanian Ouguiya'),(98,'MTL','Maltese Lira'),(99,'MUR','Mauritius Rupee'),(100,'MVR','Maldive Rufiyaa'),(101,'MWK','Malawi Kwacha'),(102,'MXN','Mexican New Peso'),(172,'MXP','Mexican Peso'),(103,'MYR','Malaysian Ringgit'),(104,'MZM','Mozambique Metical'),(105,'NAD','Namibia Dollar'),(106,'NGN','Nigerian Naira'),(107,'NIO','Nicaraguan Cordoba Oro'),(109,'NOK','Norwegian Krona'),(110,'NPR','Nepalese Rupee'),(111,'NZD','New Zealand Dollar'),(112,'OMR','Omani Rial'),(113,'PAB','Panamanian Balboa'),(114,'PEN','Peruvian Nuevo Sol'),(115,'PGK','Papua New Guinea Kina'),(116,'PHP','Philippine Peso'),(117,'PKR','Pakistan Rupee'),(118,'PLN','Polish Zloty'),(120,'PYG','Paraguay Guarani'),(121,'QAR','Qatari Rial'),(122,'ROL','Romanian Leu'),(123,'RUB','Russian Rouble'),(180,'RUR','Russia Rubles'),(173,'SAR','Saudi Arabia Riyal'),(125,'SBD','Solomon Islands Dollar'),(126,'SCR','Seychelles Rupee'),(127,'SDD','Sudanese Dinar'),(128,'SDP','Sudanese Pound'),(129,'SEK','Swedish Krona'),(131,'SGD','Singapore Dollar'),(132,'SHP','St. Helena Pound'),(130,'SKK','Slovak Koruna'),(135,'SLL','Sierra Leone Leone'),(136,'SOS','Somali Shilling'),(137,'SRD','Surinamese Dollar'),(138,'STD','Sao Tome/Principe Dobra'),(139,'SVC','El Salvador Colon'),(140,'SYP','Syrian Pound'),(141,'SZL','Swaziland Lilangeni'),(142,'THB','Thai Baht'),(143,'TND','Tunisian Dinar'),(144,'TOP','Tongan Pa\'anga'),(145,'TRL','Turkish Lira'),(146,'TTD','Trinidad/Tobago Dollar'),(147,'TWD','Taiwan Dollar'),(148,'TZS','Tanzanian Shilling'),(149,'UAH','Ukraine Hryvnia'),(150,'UGX','Uganda Shilling'),(151,'USD','United States Dollar'),(152,'UYP','Uruguayan Peso'),(153,'VEB','Venezuelan Bolivar'),(154,'VND','Vietnamese Dong'),(155,'VUV','Vanuatu Vatu'),(156,'WST','Samoan Tala'),(158,'XAF','CFA Franc BEAC'),(159,'XAG','Silver (oz.)'),(160,'XAU','Gold (oz.)'),(161,'XCD','Eastern Caribbean Dollars'),(179,'XDR','IMF Special Drawing Right'),(162,'XOF','CFA Franc BCEAO'),(163,'XPD','Palladium (oz.)'),(164,'XPF','CFP Franc'),(165,'XPT','Platinum (oz.)'),(166,'YER','Yemeni Riyal'),(167,'YUM','Yugoslavian Dinar'),(175,'YUN','Yugoslav Dinar'),(168,'ZAR','South African Rand'),(176,'ZMK','Zambian Kwacha'),(169,'ZRN','New Zaire'),(170,'ZWD','Zimbabwe Dollar');
/*!40000 ALTER TABLE `hs_hr_currency_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_custom_export`
--

DROP TABLE IF EXISTS `hs_hr_custom_export`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_custom_export` (
  `export_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `fields` text,
  `headings` text,
  PRIMARY KEY (`export_id`),
  KEY `emp_number` (`export_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_custom_export`
--

LOCK TABLES `hs_hr_custom_export` WRITE;
/*!40000 ALTER TABLE `hs_hr_custom_export` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_custom_export` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_custom_fields`
--

DROP TABLE IF EXISTS `hs_hr_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_custom_fields` (
  `field_num` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` int(11) NOT NULL,
  `screen` varchar(100) DEFAULT NULL,
  `extra_data` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`field_num`),
  KEY `emp_number` (`field_num`),
  KEY `screen` (`screen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_custom_fields`
--

LOCK TABLES `hs_hr_custom_fields` WRITE;
/*!40000 ALTER TABLE `hs_hr_custom_fields` DISABLE KEYS */;
INSERT INTO `hs_hr_custom_fields` VALUES (1,'Type',0,'job',''),(2,'Religion',0,'personal','');
/*!40000 ALTER TABLE `hs_hr_custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_custom_import`
--

DROP TABLE IF EXISTS `hs_hr_custom_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_custom_import` (
  `import_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `fields` text,
  `has_heading` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`import_id`),
  KEY `emp_number` (`import_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_custom_import`
--

LOCK TABLES `hs_hr_custom_import` WRITE;
/*!40000 ALTER TABLE `hs_hr_custom_import` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_custom_import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_district`
--

DROP TABLE IF EXISTS `hs_hr_district`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_district` (
  `district_code` varchar(13) NOT NULL DEFAULT '',
  `district_name` varchar(50) DEFAULT NULL,
  `province_code` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`district_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_district`
--

LOCK TABLES `hs_hr_district` WRITE;
/*!40000 ALTER TABLE `hs_hr_district` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_district` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_attachment`
--

DROP TABLE IF EXISTS `hs_hr_emp_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_attachment` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `eattach_id` int(11) NOT NULL DEFAULT '0',
  `eattach_desc` varchar(200) DEFAULT NULL,
  `eattach_filename` varchar(100) DEFAULT NULL,
  `eattach_size` int(11) DEFAULT '0',
  `eattach_attachment` mediumblob,
  `eattach_type` varchar(200) DEFAULT NULL,
  `screen` varchar(100) DEFAULT '',
  `attached_by` int(11) DEFAULT NULL,
  `attached_by_name` varchar(200) DEFAULT NULL,
  `attached_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`emp_number`,`eattach_id`),
  KEY `screen` (`screen`),
  CONSTRAINT `hs_hr_emp_attachment_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_attachment`
--

LOCK TABLES `hs_hr_emp_attachment` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_basicsalary`
--

DROP TABLE IF EXISTS `hs_hr_emp_basicsalary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_basicsalary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `sal_grd_code` int(11) DEFAULT NULL,
  `currency_id` varchar(6) NOT NULL DEFAULT '',
  `ebsal_basic_salary` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `payperiod_code` varchar(13) DEFAULT NULL,
  `salary_component` varchar(100) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT '1',
  `salary_rate` tinyint(3) unsigned DEFAULT NULL,
  `effective_date` date NOT NULL,
  `cola` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sal_grd_code` (`sal_grd_code`),
  KEY `currency_id` (`currency_id`),
  KEY `emp_number` (`emp_number`),
  KEY `payperiod_code` (`payperiod_code`),
  CONSTRAINT `hs_hr_emp_basicsalary_ibfk_1` FOREIGN KEY (`sal_grd_code`) REFERENCES `ohrm_pay_grade` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_basicsalary_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `hs_hr_currency_type` (`currency_id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_basicsalary_ibfk_3` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_basicsalary_ibfk_4` FOREIGN KEY (`payperiod_code`) REFERENCES `hs_hr_payperiod` (`payperiod_code`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_basicsalary`
--

LOCK TABLES `hs_hr_emp_basicsalary` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_basicsalary` DISABLE KEYS */;
INSERT INTO `hs_hr_emp_basicsalary` VALUES (1,1,NULL,'PHP','25000','3','Basic Pay','',1,5,'2012-05-21',0.00),(2,4,NULL,'PHP','0','3','0','',1,5,'2010-07-12',0.00),(3,6,NULL,'PHP','0','3','0','',1,5,'1991-09-09',0.00),(4,7,NULL,'PHP','0','3','0','',1,5,'2010-04-01',0.00),(5,9,NULL,'PHP','0','3','0','',1,5,'2006-11-06',0.00),(6,10,NULL,'PHP','0','3','0','',1,5,'2010-12-21',0.00),(7,12,NULL,'PHP','0','1','0','',1,5,'1991-04-01',0.00),(8,11,NULL,'PHP','0','3','0','',1,5,'2012-04-01',0.00),(9,13,NULL,'PHP','0','3','0','',1,5,'2006-11-06',0.00),(10,14,NULL,'PHP','0','3','0','',1,5,'1989-09-09',0.00),(11,15,NULL,'PHP','0','3','0','',1,5,'2010-10-01',0.00),(12,16,NULL,'PHP','0','3','0','',1,5,'2010-10-01',0.00),(13,17,NULL,'PHP','0','3','0','',1,5,'1992-01-06',0.00),(14,18,NULL,'PHP','0','3','0','',1,5,'2008-02-11',0.00),(15,20,NULL,'PHP','0','3','0','',1,5,'2010-08-24',0.00),(16,21,NULL,'PHP','0','3','0','',1,5,'2000-01-04',0.00),(17,22,NULL,'PHP','0','3','0','',1,5,'2010-10-01',0.00),(18,23,NULL,'PHP','0','3','0','',1,5,'2008-01-16',0.00),(19,24,NULL,'PHP','0','3','0','',1,5,'2007-04-01',0.00),(20,25,NULL,'PHP','0','3','0','',1,5,'2007-09-04',0.00),(21,26,NULL,'PHP','0','3','0','',1,5,'2006-11-06',0.00),(22,27,NULL,'PHP','0','3','0','',1,5,'2007-09-24',0.00),(23,30,NULL,'PHP','0','3','0','',1,5,'2010-02-02',0.00),(24,31,NULL,'PHP','0','3','0','',1,5,'2000-04-01',0.00),(25,32,NULL,'PHP','0','3','0','',1,5,'2010-10-01',0.00),(26,33,NULL,'PHP','0','3','0','',1,5,'2010-04-01',0.00),(27,34,NULL,'PHP','0','3','0','',1,5,'2008-01-16',0.00),(28,35,NULL,'PHP','0','3','0','',1,5,'2000-06-01',0.00),(29,36,NULL,'PHP','0','3','0','',1,5,'2011-08-11',0.00),(30,37,NULL,'PHP','0','3','0','',1,5,'2010-10-01',0.00),(31,38,NULL,'PHP','0','3','0','',1,5,'2011-10-18',0.00),(32,39,NULL,'PHP','0','3','0','',1,5,'2012-01-16',0.00),(33,40,NULL,'PHP','0','3','0','',1,5,'2012-05-22',0.00),(34,41,NULL,'PHP','0','3','0','',1,5,'2011-06-01',0.00),(35,42,NULL,'PHP','0','3','0','',1,5,'2009-04-01',0.00),(36,44,NULL,'PHP','0','3','0','',1,5,'2002-03-07',0.00),(37,45,NULL,'PHP','0','3','0','',1,5,'2010-07-12',0.00),(38,46,NULL,'PHP','0','3','0','',1,5,'2010-07-14',0.00),(39,47,NULL,'PHP','0','3','0','',1,5,'2012-04-01',0.00),(40,48,NULL,'PHP','0','3','0','',1,5,'2011-10-11',0.00),(41,49,NULL,'PHP','0','3','0','',1,5,'2008-06-23',0.00),(42,50,NULL,'PHP','0','3','0','',1,5,'2012-01-01',0.00),(43,51,NULL,'PHP','0','3','0','',1,5,'2010-10-01',0.00),(44,52,NULL,'PHP','0','3','0','',1,5,'1991-09-03',0.00),(45,53,NULL,'PHP','0','3','0','',1,5,'2012-06-01',0.00),(46,54,NULL,'PHP','0','3','0','',1,5,'2004-05-10',0.00),(47,55,NULL,'PHP','0','3','0','',1,5,'2010-10-01',0.00),(48,56,NULL,'PHP','0','3','0','',1,5,'2009-11-03',0.00),(49,57,NULL,'PHP','0','3','0','',1,5,'2007-09-04',0.00);
/*!40000 ALTER TABLE `hs_hr_emp_basicsalary` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_insert_hs_hr_emp_basicsalary` AFTER INSERT ON `hs_hr_emp_basicsalary`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = 1;
  CALL audit_insert_hs_hr_emp_basicsalary(@orangehrm_user, @orangehrm_action_name, new_version, NEW.emp_number, NEW.sal_grd_code, NEW.salary_component, NEW.status, NEW.salary_rate, NEW.payperiod_code, NEW.currency_id, NEW.ebsal_basic_salary, NEW.effective_date, NEW.cola, NEW.comments);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_update_hs_hr_emp_basicsalary` AFTER UPDATE ON `hs_hr_emp_basicsalary`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_salary_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_update_hs_hr_emp_basicsalary(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number,  NEW.sal_grd_code, OLD.sal_grd_code, NEW.salary_component, OLD.salary_component, NEW.status, OLD.status, NEW.salary_rate, OLD.salary_rate, NEW.payperiod_code, OLD.payperiod_code, NEW.currency_id, OLD.currency_id, NEW.ebsal_basic_salary, OLD.ebsal_basic_salary, NEW.effective_date, OLD.effective_date, NEW.cola, OLD.cola, NEW.comments, OLD.comments);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_delete_hs_hr_emp_basicsalary` AFTER DELETE ON `hs_hr_emp_basicsalary`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_salary_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_delete_hs_hr_emp_basicsalary(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number, OLD.sal_grd_code, OLD.salary_component, OLD.status, OLD.salary_rate, OLD.payperiod_code, OLD.currency_id, OLD.ebsal_basic_salary, OLD.effective_date, OLD.cola, OLD.comments);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `hs_hr_emp_children`
--

DROP TABLE IF EXISTS `hs_hr_emp_children`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_children` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `ec_seqno` decimal(2,0) NOT NULL DEFAULT '0',
  `ec_name` varchar(100) DEFAULT '',
  `ec_date_of_birth` date DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`ec_seqno`),
  CONSTRAINT `hs_hr_emp_children_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_children`
--

LOCK TABLES `hs_hr_emp_children` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_children` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_children` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_contract_extend`
--

DROP TABLE IF EXISTS `hs_hr_emp_contract_extend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_contract_extend` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `econ_extend_id` decimal(10,0) NOT NULL DEFAULT '0',
  `econ_extend_start_date` datetime DEFAULT NULL,
  `econ_extend_end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`econ_extend_id`),
  CONSTRAINT `hs_hr_emp_contract_extend_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_contract_extend`
--

LOCK TABLES `hs_hr_emp_contract_extend` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_contract_extend` DISABLE KEYS */;
INSERT INTO `hs_hr_emp_contract_extend` VALUES (1,1,NULL,NULL),(4,1,NULL,NULL),(5,1,NULL,NULL),(6,1,NULL,NULL),(7,1,NULL,NULL),(8,1,NULL,NULL),(9,1,NULL,NULL),(10,1,NULL,NULL),(11,1,NULL,NULL),(12,1,NULL,NULL),(13,1,NULL,NULL),(14,1,NULL,NULL),(15,1,NULL,NULL),(16,1,NULL,NULL),(17,1,NULL,NULL),(18,1,NULL,NULL),(19,1,NULL,NULL),(20,1,NULL,NULL),(21,1,NULL,NULL),(22,1,NULL,NULL),(23,1,NULL,NULL),(24,1,NULL,NULL),(25,1,NULL,NULL),(26,1,NULL,NULL),(27,1,NULL,NULL),(28,1,NULL,NULL),(29,1,NULL,NULL),(30,1,NULL,NULL),(31,1,NULL,NULL),(32,1,NULL,NULL),(33,1,NULL,NULL),(34,1,NULL,NULL),(35,1,NULL,NULL),(36,1,NULL,NULL),(37,1,NULL,NULL),(38,1,NULL,NULL),(39,1,NULL,NULL),(40,1,NULL,NULL),(41,1,NULL,NULL),(42,1,NULL,NULL),(43,1,NULL,NULL),(44,1,NULL,NULL),(45,1,NULL,NULL),(46,1,NULL,NULL),(47,1,NULL,NULL),(48,1,NULL,NULL),(49,1,NULL,NULL),(50,1,NULL,NULL),(51,1,NULL,NULL),(52,1,NULL,NULL),(53,1,NULL,NULL),(54,1,NULL,NULL),(55,1,NULL,NULL),(56,1,NULL,NULL),(57,1,NULL,NULL);
/*!40000 ALTER TABLE `hs_hr_emp_contract_extend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_dependents`
--

DROP TABLE IF EXISTS `hs_hr_emp_dependents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_dependents` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `ed_seqno` decimal(2,0) NOT NULL DEFAULT '0',
  `ed_name` varchar(100) DEFAULT '',
  `ed_relationship_type` enum('child','other') DEFAULT NULL,
  `ed_relationship` varchar(100) DEFAULT '',
  `ed_date_of_birth` date DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`ed_seqno`),
  CONSTRAINT `hs_hr_emp_dependents_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_dependents`
--

LOCK TABLES `hs_hr_emp_dependents` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_dependents` DISABLE KEYS */;
INSERT INTO `hs_hr_emp_dependents` VALUES (4,1,'RIENZIE A. LOPEZ','child','','1995-12-06'),(6,1,'JOHN MATTHEW APALISOK','child','','2001-05-22'),(6,2,'SHARIA CLAIRE APALISOK','child','','1999-02-12'),(7,1,'DIONNE JUSTIN OCOMA','child','','2009-11-12'),(9,1,'PATRICIA NICOLE J. AZORES','child','','2011-12-11'),(13,1,'AILI NOELLE B. SEBASTIAN','child','','2006-08-29'),(14,1,'SHARLEENE CHUA','child','','2007-10-29'),(14,2,'SANDRA CHUA','child','','2000-09-11'),(18,1,'ELEYNAH CUEVO','child','','2002-12-21'),(24,1,'BRYLLE DEMIAN PABALAN','child','','2004-03-02'),(24,2,'BRENT ANSLEIGH PABALAN','child','','2009-04-29'),(25,1,'DIVINE ANGEL LACTAO','child','','2008-10-31'),(25,2,'DENNISE SHYANNE L. ORNUM','child','','2008-12-16'),(29,1,'RAFUNZEL CASSANDRA DE LEON','child','','2007-12-21'),(37,1,'Espino, Alejandro Gabriel','child','','2003-05-19'),(41,1,'SAMANTHA GAYLE HONRADO','child','','2009-07-19'),(44,1,'PATRICIA JERSEY LOGINA','child','','2001-05-23'),(47,1,'MARY ANNE ELLYZZ MAGLASANG','child','','2012-04-02'),(47,2,'ELARA SHIRON HERRA MAGLASANG','child','','2012-04-01'),(47,3,'EMMANUEL MAGLASANG','child','','2012-04-01'),(47,4,'EZEKIEL MAGLASANG','child','','2012-04-01'),(51,1,'Rivera, Alexander Jerome','child','','2011-04-08'),(51,2,'Rivera, Andreus Jophiel','child','','1999-09-10'),(52,1,'ELIZABETH NICOLE SABAY','child','','1996-11-11'),(54,1,'KRISHEN HEIDA SAN MIGUEL','child','','2010-11-28'),(55,1,'Santos, Teresa Alegria S','child','','2010-02-18');
/*!40000 ALTER TABLE `hs_hr_emp_dependents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_directdebit`
--

DROP TABLE IF EXISTS `hs_hr_emp_directdebit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_directdebit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_id` int(11) NOT NULL,
  `dd_routing_num` int(9) NOT NULL,
  `dd_account` varchar(100) NOT NULL DEFAULT '',
  `dd_amount` decimal(11,2) NOT NULL,
  `dd_account_type` varchar(20) NOT NULL DEFAULT '' COMMENT 'CHECKING, SAVINGS',
  `dd_transaction_type` varchar(20) NOT NULL DEFAULT '' COMMENT 'BLANK, PERC, FLAT, FLATMINUS',
  PRIMARY KEY (`id`),
  KEY `salary_id` (`salary_id`),
  CONSTRAINT `hs_hr_emp_directdebit_ibfk_1` FOREIGN KEY (`salary_id`) REFERENCES `hs_hr_emp_basicsalary` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_directdebit`
--

LOCK TABLES `hs_hr_emp_directdebit` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_directdebit` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_directdebit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_emergency_contacts`
--

DROP TABLE IF EXISTS `hs_hr_emp_emergency_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_emergency_contacts` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `eec_seqno` decimal(2,0) NOT NULL DEFAULT '0',
  `eec_name` varchar(100) DEFAULT '',
  `eec_relationship` varchar(100) DEFAULT '',
  `eec_home_no` varchar(100) DEFAULT '',
  `eec_mobile_no` varchar(100) DEFAULT '',
  `eec_office_no` varchar(100) DEFAULT '',
  PRIMARY KEY (`emp_number`,`eec_seqno`),
  CONSTRAINT `hs_hr_emp_emergency_contacts_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_emergency_contacts`
--

LOCK TABLES `hs_hr_emp_emergency_contacts` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_emergency_contacts` DISABLE KEYS */;
INSERT INTO `hs_hr_emp_emergency_contacts` VALUES (6,1,'BENITO H. APALISOK, JR.','Spouse','1','',''),(8,1,'NERISSA ASUNCION','Spouse','1','',''),(12,1,'CARLOS CELMAR','Spouse','1','',''),(12,2,'MARY GRACE CELMAR','Daughter','1','',''),(15,1,'KIMBERLY CO','Spouse','1','',''),(17,1,'OLIVIA ZAMORA','Spouse','1','',''),(18,1,'JOSELITO CUEVO','Spouse','1','',''),(22,1,'De Guzman, Maria Leah S.','Spouse','1','',''),(23,1,'ROMAR S. PEREZ','Spouse','1','',''),(24,1,'ONOFRE PABALAN, JR.','Spouse','1','',''),(29,1,'RICHARD DE LEON','Spouse','1','',''),(34,1,'GEM KNOWELL B. SEBELDIA',' Spouse','1','',''),(37,1,'Espino, Giselle','Spouse','1','',''),(44,1,'EDWIN LITO LOGINA','Spouse','1','',''),(47,1,'FERNANDO MAGLASANG','Spouse','1','',''),(49,1,'CHRISTIAN ALAN G. RAMOS','Spouse','1','',''),(51,1,'Rivera, Jasmin','Spouse','1','',''),(52,1,'RAYMUND SABAY','Spouse','1','',''),(54,1,'HENRY SAN MIGUEL','Spouse','1','',''),(55,1,'Santos, Josephine Grace','Spouse','1','',''),(56,1,'SENEN B. SARMIENTO','Spouse','1','','');
/*!40000 ALTER TABLE `hs_hr_emp_emergency_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_history_of_ealier_pos`
--

DROP TABLE IF EXISTS `hs_hr_emp_history_of_ealier_pos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_history_of_ealier_pos` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `emp_seqno` decimal(2,0) NOT NULL DEFAULT '0',
  `ehoep_job_title` varchar(100) DEFAULT '',
  `ehoep_years` varchar(100) DEFAULT '',
  PRIMARY KEY (`emp_number`,`emp_seqno`),
  CONSTRAINT `hs_hr_emp_history_of_ealier_pos_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_history_of_ealier_pos`
--

LOCK TABLES `hs_hr_emp_history_of_ealier_pos` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_history_of_ealier_pos` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_history_of_ealier_pos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_language`
--

DROP TABLE IF EXISTS `hs_hr_emp_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_language` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `lang_id` int(11) NOT NULL,
  `fluency` smallint(6) NOT NULL DEFAULT '0',
  `competency` smallint(6) DEFAULT '0',
  `comments` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`lang_id`,`fluency`),
  KEY `lang_id` (`lang_id`),
  CONSTRAINT `hs_hr_emp_language_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_language_ibfk_2` FOREIGN KEY (`lang_id`) REFERENCES `ohrm_language` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_language`
--

LOCK TABLES `hs_hr_emp_language` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_leave_accrual`
--

DROP TABLE IF EXISTS `hs_hr_emp_leave_accrual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_leave_accrual` (
  `acc_id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type_id` varchar(13) NOT NULL,
  `leave_period_id` int(7) NOT NULL,
  `employee_id` int(7) NOT NULL,
  `acc_time` date NOT NULL,
  `acc_days` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`acc_id`),
  KEY `leave_period_id` (`leave_period_id`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_type_id` (`leave_type_id`),
  CONSTRAINT `hs_hr_emp_leave_accrual_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_leave_accrual_ibfk_2` FOREIGN KEY (`leave_period_id`) REFERENCES `hs_hr_leave_period` (`leave_period_id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_leave_accrual_ibfk_3` FOREIGN KEY (`leave_type_id`) REFERENCES `hs_hr_leavetype` (`leave_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_leave_accrual`
--

LOCK TABLES `hs_hr_emp_leave_accrual` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_leave_accrual` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_leave_accrual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_locations`
--

DROP TABLE IF EXISTS `hs_hr_emp_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_locations` (
  `emp_number` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`emp_number`,`location_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `hs_hr_emp_locations_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `ohrm_location` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_locations_ibfk_2` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_locations`
--

LOCK TABLES `hs_hr_emp_locations` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_member_detail`
--

DROP TABLE IF EXISTS `hs_hr_emp_member_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_member_detail` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `membship_code` int(6) NOT NULL DEFAULT '0',
  `ememb_subscript_ownership` varchar(20) DEFAULT NULL,
  `ememb_subscript_amount` decimal(15,2) DEFAULT NULL,
  `ememb_subs_currency` varchar(20) DEFAULT NULL,
  `ememb_commence_date` date DEFAULT NULL,
  `ememb_renewal_date` date DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`membship_code`),
  KEY `membship_code` (`membship_code`),
  CONSTRAINT `hs_hr_emp_member_detail_ibfk_1` FOREIGN KEY (`membship_code`) REFERENCES `ohrm_membership` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_member_detail_ibfk_2` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_member_detail`
--

LOCK TABLES `hs_hr_emp_member_detail` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_member_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_member_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_passport`
--

DROP TABLE IF EXISTS `hs_hr_emp_passport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_passport` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `ep_seqno` decimal(2,0) NOT NULL DEFAULT '0',
  `ep_passport_num` varchar(100) NOT NULL DEFAULT '',
  `ep_passportissueddate` datetime DEFAULT NULL,
  `ep_passportexpiredate` datetime DEFAULT NULL,
  `ep_comments` varchar(255) DEFAULT NULL,
  `ep_passport_type_flg` smallint(6) DEFAULT NULL,
  `ep_i9_status` varchar(100) DEFAULT '',
  `ep_i9_review_date` date DEFAULT NULL,
  `cou_code` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`ep_seqno`),
  CONSTRAINT `hs_hr_emp_passport_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_passport`
--

LOCK TABLES `hs_hr_emp_passport` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_passport` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_passport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_picture`
--

DROP TABLE IF EXISTS `hs_hr_emp_picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_picture` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `epic_picture` mediumblob,
  `epic_filename` varchar(100) DEFAULT NULL,
  `epic_type` varchar(50) DEFAULT NULL,
  `epic_file_size` varchar(20) DEFAULT NULL,
  `epic_file_width` varchar(20) DEFAULT NULL,
  `epic_file_height` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`emp_number`),
  CONSTRAINT `hs_hr_emp_picture_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_picture`
--

LOCK TABLES `hs_hr_emp_picture` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_picture` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_picture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_reportto`
--

DROP TABLE IF EXISTS `hs_hr_emp_reportto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_reportto` (
  `erep_sup_emp_number` int(7) NOT NULL DEFAULT '0',
  `erep_sub_emp_number` int(7) NOT NULL DEFAULT '0',
  `erep_reporting_mode` int(7) NOT NULL DEFAULT '0',
  PRIMARY KEY (`erep_sup_emp_number`,`erep_sub_emp_number`,`erep_reporting_mode`),
  KEY `erep_sub_emp_number` (`erep_sub_emp_number`),
  KEY `erep_reporting_mode` (`erep_reporting_mode`),
  CONSTRAINT `hs_hr_emp_reportto_ibfk_1` FOREIGN KEY (`erep_sup_emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_reportto_ibfk_2` FOREIGN KEY (`erep_sub_emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_reportto_ibfk_3` FOREIGN KEY (`erep_reporting_mode`) REFERENCES `ohrm_emp_reporting_method` (`reporting_method_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_reportto`
--

LOCK TABLES `hs_hr_emp_reportto` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_reportto` DISABLE KEYS */;
INSERT INTO `hs_hr_emp_reportto` VALUES (12,23,1);
/*!40000 ALTER TABLE `hs_hr_emp_reportto` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_insert_hs_hr_emp_reportto` AFTER INSERT ON `hs_hr_emp_reportto`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = 1;
  CALL audit_insert_hs_hr_emp_reportto(@orangehrm_user, @orangehrm_action_name, new_version, NEW.erep_sub_emp_number, NEW.erep_sup_emp_number, NEW.erep_reporting_mode);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_update_hs_hr_emp_reportto` AFTER UPDATE ON `hs_hr_emp_reportto`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_reportto_trail` WHERE `affected_entity_id` = OLD.erep_sub_emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_update_hs_hr_emp_reportto(@orangehrm_user, @orangehrm_action_name, new_version, OLD.erep_sub_emp_number, NEW.erep_sup_emp_number, OLD.erep_sup_emp_number, NEW.erep_reporting_mode, OLD.erep_reporting_mode);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_delete_hs_hr_emp_reportto` AFTER DELETE ON `hs_hr_emp_reportto`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_reportto_trail` WHERE `affected_entity_id` = OLD.erep_sub_emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_delete_hs_hr_emp_reportto(@orangehrm_user, @orangehrm_action_name, new_version, OLD.erep_sub_emp_number, OLD.erep_sup_emp_number, OLD.erep_reporting_mode);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `hs_hr_emp_skill`
--

DROP TABLE IF EXISTS `hs_hr_emp_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_skill` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `skill_id` int(11) NOT NULL,
  `proficiency_id` int(10) unsigned DEFAULT NULL,
  `years_of_exp` decimal(2,0) NOT NULL DEFAULT '0',
  `comments` varchar(100) NOT NULL DEFAULT '',
  KEY `emp_number` (`emp_number`),
  KEY `skill_id` (`skill_id`),
  CONSTRAINT `hs_hr_emp_skill_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emp_skill_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `ohrm_skill` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_skill`
--

LOCK TABLES `hs_hr_emp_skill` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_skill` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_us_tax`
--

DROP TABLE IF EXISTS `hs_hr_emp_us_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_us_tax` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `tax_federal_status` varchar(13) DEFAULT NULL,
  `tax_federal_exceptions` int(2) DEFAULT '0',
  `tax_state` varchar(13) DEFAULT NULL,
  `tax_state_status` varchar(13) DEFAULT NULL,
  `tax_state_exceptions` int(2) DEFAULT '0',
  `tax_unemp_state` varchar(13) DEFAULT NULL,
  `tax_work_state` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`emp_number`),
  CONSTRAINT `hs_hr_emp_us_tax_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_us_tax`
--

LOCK TABLES `hs_hr_emp_us_tax` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_us_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_us_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emp_work_experience`
--

DROP TABLE IF EXISTS `hs_hr_emp_work_experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emp_work_experience` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `eexp_seqno` decimal(10,0) NOT NULL DEFAULT '0',
  `eexp_employer` varchar(100) DEFAULT NULL,
  `eexp_jobtit` varchar(120) DEFAULT NULL,
  `eexp_from_date` datetime DEFAULT NULL,
  `eexp_to_date` datetime DEFAULT NULL,
  `eexp_comments` varchar(200) DEFAULT NULL,
  `eexp_internal` int(1) DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`eexp_seqno`),
  CONSTRAINT `hs_hr_emp_work_experience_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emp_work_experience`
--

LOCK TABLES `hs_hr_emp_work_experience` WRITE;
/*!40000 ALTER TABLE `hs_hr_emp_work_experience` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emp_work_experience` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_insert_hs_hr_emp_work_experience` AFTER INSERT ON `hs_hr_emp_work_experience`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = 1;
  CALL audit_insert_hs_hr_emp_work_experience(@orangehrm_user, @orangehrm_action_name, new_version, NEW.emp_number,  NEW.eexp_employer, NEW.eexp_jobtit, NEW.eexp_from_date, NEW.eexp_to_date);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_update_hs_hr_emp_work_experience` AFTER UPDATE ON `hs_hr_emp_work_experience`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_work_experience_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_update_hs_hr_emp_work_experience(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number, NEW.eexp_employer, OLD.eexp_employer, NEW.eexp_jobtit, OLD.eexp_jobtit, CONVERT(NEW.eexp_from_date, DATE), CONVERT(OLD.eexp_from_date, DATE), CONVERT(NEW.eexp_to_date, DATE), CONVERT(OLD.eexp_to_date, DATE), NEW.eexp_comments, OLD.eexp_comments);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_delete_hs_hr_emp_work_experience` AFTER DELETE ON `hs_hr_emp_work_experience`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_work_experience_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_delete_hs_hr_emp_work_experience(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number, OLD.eexp_employer, OLD.eexp_jobtit);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `hs_hr_employee`
--

DROP TABLE IF EXISTS `hs_hr_employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_employee` (
  `emp_number` int(7) NOT NULL DEFAULT '0',
  `employee_id` varchar(50) DEFAULT NULL,
  `emp_lastname` varchar(100) NOT NULL DEFAULT '',
  `emp_firstname` varchar(100) NOT NULL DEFAULT '',
  `emp_middle_name` varchar(100) NOT NULL DEFAULT '',
  `emp_nick_name` varchar(100) DEFAULT '',
  `emp_smoker` smallint(6) DEFAULT '0',
  `ethnic_race_code` varchar(13) DEFAULT NULL,
  `emp_birthday` date DEFAULT NULL,
  `nation_code` int(4) DEFAULT NULL,
  `emp_gender` smallint(6) DEFAULT NULL,
  `emp_marital_status` varchar(20) DEFAULT NULL,
  `emp_ssn_num` varchar(100) CHARACTER SET latin1 DEFAULT '',
  `emp_sin_num` varchar(100) DEFAULT '',
  `emp_other_id` varchar(100) DEFAULT '',
  `emp_dri_lice_num` varchar(100) DEFAULT '',
  `emp_dri_lice_exp_date` date DEFAULT NULL,
  `emp_military_service` varchar(100) DEFAULT '',
  `emp_status` int(13) DEFAULT NULL,
  `job_title_code` int(7) DEFAULT NULL,
  `eeo_cat_code` int(11) DEFAULT NULL,
  `work_station` int(6) DEFAULT NULL,
  `emp_street1` varchar(150) DEFAULT '',
  `emp_street2` varchar(150) DEFAULT '',
  `city_code` varchar(150) DEFAULT '',
  `coun_code` varchar(100) DEFAULT '',
  `provin_code` varchar(150) DEFAULT '',
  `emp_zipcode` varchar(150) DEFAULT NULL,
  `emp_hm_telephone` varchar(150) DEFAULT NULL,
  `emp_mobile` varchar(150) DEFAULT NULL,
  `emp_work_telephone` varchar(150) DEFAULT NULL,
  `emp_work_email` varchar(150) DEFAULT NULL,
  `sal_grd_code` varchar(13) DEFAULT NULL,
  `joined_date` date DEFAULT NULL,
  `emp_oth_email` varchar(150) DEFAULT NULL,
  `termination_id` int(4) DEFAULT NULL,
  `custom1` varchar(250) DEFAULT NULL,
  `custom2` varchar(250) DEFAULT NULL,
  `custom3` varchar(250) DEFAULT NULL,
  `custom4` varchar(250) DEFAULT NULL,
  `custom5` varchar(250) DEFAULT NULL,
  `custom6` varchar(250) DEFAULT NULL,
  `custom7` varchar(250) DEFAULT NULL,
  `custom8` varchar(250) DEFAULT NULL,
  `custom9` varchar(250) DEFAULT NULL,
  `custom10` varchar(250) DEFAULT NULL,
  `tax_exepmtion_id` int(10) unsigned DEFAULT NULL,
  `tin` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`emp_number`),
  KEY `work_station` (`work_station`),
  KEY `nation_code` (`nation_code`),
  KEY `job_title_code` (`job_title_code`),
  KEY `emp_status` (`emp_status`),
  KEY `eeo_cat_code` (`eeo_cat_code`),
  KEY `termination_id` (`termination_id`),
  CONSTRAINT `hs_hr_employee_ibfk_1` FOREIGN KEY (`work_station`) REFERENCES `ohrm_subunit` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hs_hr_employee_ibfk_2` FOREIGN KEY (`nation_code`) REFERENCES `ohrm_nationality` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hs_hr_employee_ibfk_3` FOREIGN KEY (`job_title_code`) REFERENCES `ohrm_job_title` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hs_hr_employee_ibfk_4` FOREIGN KEY (`emp_status`) REFERENCES `ohrm_employment_status` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hs_hr_employee_ibfk_5` FOREIGN KEY (`eeo_cat_code`) REFERENCES `ohrm_job_category` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hs_hr_employee_ibfk_6` FOREIGN KEY (`termination_id`) REFERENCES `ohrm_emp_termination` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_employee`
--

LOCK TABLES `hs_hr_employee` WRITE;
/*!40000 ALTER TABLE `hs_hr_employee` DISABLE KEYS */;
INSERT INTO `hs_hr_employee` VALUES (1,'120521','ACOSTA','GRACE CIELO','IWARAT','',0,NULL,'1991-01-10',62,2,'Single','34-3221023-2','1001-1991-','10-513883892','',NULL,'',1,18,2,8,'G8G Macopa St., Fort Bonifacio,  Taguig Philippines','','Taguig City','PH','','','8571126','0917-5362859','','acosta-gc@itochu.com.ph',NULL,'2012-05-21','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'422-754-663'),(4,'100712A','AFUNGGOL','MARY ROSE','ESCASINAS','',0,NULL,'1972-09-21',62,2,'Other','33-1548011-8','0921-1972-','19-052670959-8','',NULL,'',1,24,2,9,'Phase 1 Blk 1 Lot 15  San Lorenzo South Sta. Rosa','','','PH',' Laguna','','8571162','0917-5236596','','afunggol-mr@itochu.com.ph',NULL,'2010-07-12','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,'422-754-663'),(5,'81115','AGUINALDO','SHIELLA','BROQUEZA','',0,NULL,'1986-04-26',62,2,'Single','34-0741046-5','2003-1473-4181','01-050618139-4','',NULL,'',1,17,3,6,'46 PAGASA ST., SIGNAL VILLAGE, Taguig Philippines','','Taguig','PH','','','8571174','0917-8007046','','aguinaldo-s@itochu.com.ph',NULL,'2008-11-15','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'260-911-630'),(6,'910909','APALISOK','SONIA','BLANCO','',0,NULL,'1968-10-29',62,2,'Married','03-9367482-6','1010-0009-4489','19-088759257-1','',NULL,'',1,19,1,2,'1126 CRISTOBAL ST. Manila Philippines','','','PH','','','8571104','0915-1274781','742-4667','apalisok-s@itochu.com.ph',NULL,'1991-09-09','',NULL,NULL,'Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'112-096-919'),(7,'100401A','ARQUINEZ','ANA','ABRAZALDO','',0,NULL,'1970-12-21',62,2,'Married','33-1820033-7','1221-70','10-500986080','',NULL,'',1,17,3,9,'37B MAKABAYAN ST., BRGY. OBRERO,  ','','Makati City','PH','','','','','','arquinez-a@itochu.com.ph',NULL,'2010-04-01','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,''),(8,'101001C','ASUNCION','ALEXANDER','MIRANDA','',0,NULL,'1977-07-20',62,1,'Single','33-6278611-1','2003-1495-4390','19-051774838-6','',NULL,'',1,3,1,13,'','','','0','','','','','','asuncion-a@itochu.com.ph',NULL,'2010-10-01','',NULL,NULL,'Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'203-545-785'),(9,'10705','AZORES','NERINE','JORNALES','',0,NULL,'1979-07-05',62,2,'Single','33-6786338-5','1010-0010-1903','01-050818682-2','',NULL,'',1,16,2,2,'29 IRID ST. Mandaluyong Philippines','','Mandaluyong','PH','','','8571107','0917-8019439','','azores-n@itochu.com.ph',NULL,'2001-07-05','',NULL,'Regular','Christian',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'300-050-296'),(10,'101221','CANLAS','ROWENA','ELLAB','',0,NULL,'1980-11-22',62,2,'Married','33-5637508-8','1122-1980-','30-501990600-','',NULL,'',7,14,6,12,'No. 17 Vibora St., 10th Avenue  ','','Makati City','PH','','','','','','',NULL,'2010-12-21','',NULL,'1O% Consultant',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,''),(11,'120401A','CANO','JANICE SYBEL','DIALOGO','',0,NULL,'1980-12-22',62,2,'Single','34-0860459-7','1222-1980-','02-050376123-9','',NULL,'',1,16,2,13,'#422 Solitaria St.  ','','Pasay City','PH','','','','0917-5133154','8571117','cano-js@itochu.com.ph',NULL,'2012-04-01','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'924-928-828'),(12,'910401','CELMAR','JOSEPHINE','DULAY','',0,NULL,'1960-10-22',62,2,'Married','03-6833820-7','1010-0009-7864','19-050609846-0','',NULL,'',NULL,6,1,5,'LOT 6 BLOCK 8 DIVIDEND HOMES SUBDIVISION, TAYTAY, Rizal Philippines','','','PH','','','8571103','0917-5236631','','dulay-j@itochu.com.ph',NULL,'1991-04-01','charity.adug@sigmasoft.com.ph',NULL,'Regular','Christian',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'108-786-706'),(13,'061106A','BALUYOT','SHEILA','AUGIA','',0,NULL,'1980-06-20',62,2,'Single','02-3148824-2','1010-0011-9065','19-089745700-1','',NULL,'',1,1,2,2,'2493 Belarmino St., Bangkal,  Makati City Philippines','','','0','','1223','','0917-8658143','8571118','baluyot-s@itochu.com.ph',NULL,'2006-11-06','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'219-512-223'),(14,'890907','CHUA','STUART','LI','',0,NULL,'1968-06-08',62,1,'Married','33-0563369-0','1010-0008-8894','19-088759265-2','',NULL,'',1,7,1,9,'197 ENGRACIO SANTOS ST. San Juan Philippines','','','0','','','0917-5253145','','8571160','chua-s@itochu.com.ph',NULL,'1989-09-09','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'112-096-976'),(15,'101001A','CO','ERWIN','ONG','',0,NULL,'1979-03-19',62,1,'Married','33-8023997-0','1010-0010-0750','06-050093197-6','',NULL,'',7,7,1,13,'','','','','',NULL,NULL,NULL,NULL,NULL,NULL,'2010-10-01',NULL,NULL,'Regular','RESIGNED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'219-209-278'),(16,'120603','COLANDOG','JOSAN GRACE','BARNACHEA','',0,NULL,'1988-07-19',62,2,'Single','34-1513987-7','7191-988-','10-510845353-','',NULL,'',1,14,6,12,'1047 J. VERGARA ST., Makati City Philippines','','','0','','','','','','colando-jg@itochu.com.ph',NULL,'2011-06-03','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'285-989-489'),(17,'920106','ZAMORA','ROBERTO','LIM','',0,NULL,'1957-08-12',62,1,'Married','06-0563308-9','1010-0008-8915','19-050406600-6','',NULL,'',1,19,1,13,'#8 ROTTERHAM ST., HILLSBOROUGH ALABANG VILLAGE, ','',' Muntinlupa City','PH','','1770','','0917-8110337','8571150','zamora-r@itochu.com.ph',NULL,'1992-01-06','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'136-996-502'),(18,'80211','CUEVO','EVANGELYN','ROBLES','',0,NULL,'1972-11-16',62,2,'Married','33-1495177-4','1010-0013-5430','04-050008441-5','',NULL,'',1,2,1,6,'2306D MARCONI ST.,','',' Makati City','PH','','','','0917-8530916','8571172','cuevo-e@itochu.com.ph',NULL,'2008-02-11','',NULL,NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'164-383-736'),(19,'101004','YONG LOOK','JAMES','WONG','',0,NULL,'1957-08-12',62,1,'Single','91-8866129-9','7021-973-','19-050445909-1','',NULL,'',7,19,1,16,'','','','','',NULL,NULL,NULL,NULL,NULL,NULL,'2010-10-04',NULL,NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,''),(20,'100824','VILLESENDA','ROCHA MAE','MIRADOR','',0,NULL,'1985-08-15',62,2,'Single','72-2673663-3','0815-1985-','08-050767966-1','',NULL,'',1,14,6,12,'088 SUNRISE COMPOUND, BRGY. 193 ZONE 20, PILDERA 2','','Pasay City','PH','','','','0927-7958731','8571188','villesenda-rm@itochu.com.ph',NULL,'2010-08-24','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,'261-185-144'),(21,'104','TRINIDAD','LAILANI','CARPENA','',0,NULL,'1964-05-04',62,2,'Single','03-9020159-3','1010-0009-5899','19-052181651-5','',NULL,'',1,10,5,5,'12 MARIPOSA ST. GULOD, NOVALICHES,','',' Quezon City','PH','','','0917-8539476','','8571101','trinidad-l@itochu.com.ph',NULL,'2000-01-04','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'147-297-958'),(22,'101001F','DE GUZMAN','MARK ANTHONY','BUMALAY','',0,NULL,'1978-06-18',62,1,'Married','33-5426932-9','1010-0010-7520','06-050093196-8','',NULL,'',1,11,2,17,'Block 7, Lot 24 Marigman St.,  ','',' Antipolo City','PH','Rizal','','','0917-5853960','8571143','deguzman-ma@itochu.com.ph',NULL,'2010-10-01','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'911-843-615'),(23,'080116A','PEREZ','JOYCE','ANTONIO','',0,NULL,'1978-12-12',62,2,'Married','33-6885092-8','1010-0013-5441','19-089226753-0','',NULL,'',1,13,2,5,'UNIT T-05 NEW MANILA CONDOMINIUM, ','21 N. DOMINGO ST., BRGY. VALENCIA  ','Quezon City','PH','Luzon','1112','','0917-5236634','8571105','perez-j@itochu.com.ph',NULL,'2008-01-16','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'214-415-982'),(24,'70401','PABALAN','GIRLIE','TONGKO','',0,NULL,'1980-09-18',62,2,'Married','33-7713708-5','1010-0012-5595','19-025290298-1','',NULL,'',1,17,3,13,'AD BLOCK 3, LOT 1 MONTEMAR ST., ','HOLIDAY HOMES, PH-1 SAN ANTONIO, ','SAN PEDRO ','PH','Laguna ','','','0926-6490991','8571152','pabalan-g@itochu.com.ph',NULL,'2007-04-01','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'224-012-438'),(25,'070904B','ORNUM','DONNA DIVINE','LACTAO','',0,NULL,'1980-01-03',62,2,'Married','33-5932246-5','1010-0013-0492','19-090048790-1','',NULL,'',1,21,5,NULL,'2459 PARK AVENUE ','','Pasay City','PH','','','','0921-3537261','8571181','lactao-d@itochu.com.ph',NULL,'2007-09-04','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'919-130-924'),(26,'061106B','ONG','JOHN LAWRENCE','BACAR','',0,NULL,'1984-06-02',62,1,'Single','34-0301403-0','1010-0011-9076','06-050093199-2','',NULL,'',1,2,1,8,'3611 V MAPA  EXTENSION Manila','','','PH','','','','0917-8811171','8571121','ong-jl@itochu.com.ph',NULL,'2006-11-06','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'216-425-829'),(27,'70924','MONTANO','JENNIFER','DANO','',0,NULL,'1984-12-04',62,2,'Single','07-2262350-3','1010-0013-2844','01-050588428-6','',NULL,'',1,23,5,12,'088 SUNRISE COMPOUND, ','BRGY. 193 ZONE 20, PILDERA 2 ','Pasay City ','PH','','','','0928-3772747','8571182','montano-j@itochu.com.ph',NULL,'2007-09-24','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'253-506-561'),(28,'101001G','MENDOZA','ROMMEL','CADIAS','',0,NULL,'1967-07-14',62,1,'Married','10-8274974-4','1010-0011-0220','10-8274974-','',NULL,'',7,11,2,17,' Makati City Philippines','','','0','','','','','','',NULL,'2010-10-01','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,''),(29,'120621','DE LEON','CRISTINA','MIRANDA','',0,NULL,'1985-12-05',62,2,'Married','34-0758138-1','1040-0234-2399','01-050550915-9','',NULL,'',1,14,6,12,'','','','','',NULL,NULL,NULL,NULL,NULL,NULL,'2012-06-21',NULL,NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'251-462-552'),(30,'100201','HISATOMI','KENICHI','','',0,NULL,'1964-07-21',91,1,'Married','34-1954465-9','3419-5446-59','','',NULL,'',1,5,1,13,'Unit 37-D The Residences At Greenbelt, Legaspi Village,','',' Makati City','PH','','','','0917-5940396','8571100','hisatomi-k@itochu.com.ph',NULL,'2010-02-02','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'284-075-375'),(31,'401','TOLENTINO','ARISTEO','SANCHEZ','',0,NULL,'1970-12-13',NULL,1,'Married','33-3445503-0','1010-0009-6319','06-050093201-8','',NULL,'',1,22,4,5,'15 SAN ANDRES, ALAMINOS, ','','','PH','Laguna Philippines','','','0917-8925712','8571108','tolentino-a@itochu.com.ph',NULL,'2000-04-01','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'152-649-230'),(32,'101001H','SOLIMEN','NOEL','MADARANG','',0,NULL,'1981-01-06',62,1,'Single','11-6484251-1','1010-0011-7134','11-6484251-','',NULL,'',7,11,2,17,' Makati City Philippines','','','0','','','','','','',NULL,'2010-10-01','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,''),(33,'100401B','DOLOR','JEANETH','BALMES','',0,NULL,'1985-08-27',62,2,'Single','07-2328257-4','1040-0153-2398','01-050656924-4','',NULL,'',1,14,6,12,'Phase 1, Block 4, Paso Cocohills, Bagumbayan,  ','','Taguig Philippines','0','','','','0920-3462976','8571189','dolor-j@itochu.com.ph',NULL,'2010-04-01','',NULL,'Regular','Christian',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'256-024-598'),(34,'080116B','SEBELDIA','DONNA MARIE','TUMANENG','',0,NULL,'1981-06-15',62,2,'Married','33-8566083-2','1010-0013-5452','19-089951758-3','',NULL,'',1,12,2,5,'Blk. 18 Lot 83 KC34 St.,',' Karangalan Village,  Manggahan,  ',' Pasig City Philippines','PH','','','','0917-8666455','8571106','sebeldia-dm@itochu.com.ph',NULL,'2008-01-16','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'225-067-915'),(35,'601','DOMALANTA','MARIA GRACIA','CABUG','',0,NULL,'1973-04-01',62,2,'Single','33-4256061-6','1010-0009-7641','19-051575034-0','',NULL,'',1,17,2,14,'1829 DART ST. Manila Philippines','','','PH','','','','0917-5230063','8571130','domalanta-g@itochu.com.ph',NULL,'2000-06-01','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'902-469-429'),(36,'110811','DUARTE','SYDNEY','CERVANTES','',0,NULL,'1985-05-05',62,2,'Single','33-9032884-2','0032-6003-6311','03-050106815-9','',NULL,'',1,14,6,12,'515 PALTOC ST., STA. MESA,  ','Manila Philippines','','PH','','','','0921-9939678','8571191','duarte-s@itochu.com.ph',NULL,'2011-08-11','',NULL,'Regular','Christian',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'239-608-532'),(37,'101001B','ESPINO','ALLAN FERNANDO REI','CABRAL','',0,NULL,'1973-04-18',62,1,'Married','33-1695205-6','1010-0011-1028','33-1695205-6','',NULL,'',1,19,1,8,'206 Aries St., Dona Soledad Townhomes, ',' Hawaii Circle, Annex 45, Betterliving Subdivision,   ','Paranaque City Philippines','PH','','','','0917-5236627','8571120','espino-a@itochu.com.ph',NULL,'2010-10-01','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'169-853-784'),(38,'111018','GARROVILLO','ELIZA','CALAYCAY','',0,NULL,'1985-11-05',62,2,'Single','34-0043961-8','1105-1985-','01-050347596-6','',NULL,'',1,14,6,12,'197 Tiaga St., Wildcat Village,  ','Brgy. Ususan Annex,  ','Taguig Philippines','PH','','','','0917-5399054','8571187','garrovillo-e@itochu.com.ph',NULL,'2011-10-18','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'247-117-087'),(39,'120119','GO','WINSTON','SY','',0,NULL,'1969-08-10',40,1,'Married','33-3040855-7','1080-0000-4039','09-050501779-3','',NULL,'',1,19,1,16,'810 B. MAYOR ST., MALIBAY, ','','Pasay City Philippines','0','','','','0917-8392646','8571171','go-w@itochu.com.ph',NULL,'2012-01-19','',NULL,'Regular','Christian',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'165-190-164'),(40,'120522','GO','FRANCIS','GUEVARRA','',0,NULL,'1970-06-23',62,1,'Married','33-1831221-2','1080-0000-4039','09-050501779-3','',NULL,'',1,24,2,8,'56 A. Rita St.,  San Juan Philippines','','','0','','','','0917-5362861','8571127','go-fran@itochu.com.ph',NULL,'2012-05-22','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'165-190-164'),(41,'110601','HONRADO','NERIELYN','BALLON','',0,NULL,'1984-12-05',62,2,'Married','33-9675524-4','1205-1984-','02-050177642-5','',NULL,'',1,14,6,12,'B-32 L-1 BRGY. SAN ISIDRO I, DASMARI','','','PH','','','','0915-7286081','8571190','honrado-n@itochu.com.ph',NULL,'2011-06-01','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'301-022-950'),(42,'90401','HOSHIAI','DAI','','',0,NULL,'1969-07-07',62,1,'Married','34-1598990-4','0729-69-','','',NULL,'',1,8,1,7,'Unit 1001 Four Seasons, Toledo St. corner Tordesillas St., Salcedo Village,','',' Makati City ','PH','','','','0917-8132422','8571170','hoshiai-d@itochu.com.ph',NULL,'2009-04-01','',NULL,'Contractual',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'272-213-118'),(43,'120217','HULDONG','KARLO ANDREI','MILLARES','',0,NULL,'1974-10-07',62,1,'Single','34-1513987-7','1113-1991-','10-513402819-','',NULL,'',7,15,3,8,'','','','','',NULL,NULL,NULL,NULL,NULL,NULL,'2012-02-17',NULL,NULL,'1O% Consultant','Christian',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,''),(44,'20703','LOGINA','JENNIFER','CAYABYAB','',0,NULL,NULL,NULL,NULL,NULL,'','','','',NULL,'',1,18,NULL,16,'587 M. DELA FUENTE ST. Manila Philippines','','','0','','','','0917-8544912','8571173','logina-j@itochu.com.ph',NULL,'2002-03-07','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,'100712C','LOMUGDANG','JOHN RAY','SEVILLA','',0,NULL,NULL,NULL,NULL,NULL,'','','','',NULL,'',1,3,2,13,'#1 Blk 2 Lot 4 St. Francis Homes 2 San Pedro, ','','','PH',' Laguna Philippines','','','0917-8132421','8571155','lomugdang-jr@itochu.com.ph',NULL,'2010-07-12','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,'100714','LOPEZ','ANNE MARIEL','SAPDIN','',0,NULL,'1990-02-05',62,2,'Single','34-2186677-8','0205-1990-','01-050925799-5','',NULL,'',1,18,2,8,'6741 Taylo St., ','',' Makati City ','PH','','','','0917-8045020','8571124','lopez-am@itochu.com.ph',NULL,'2010-07-14','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'295-913-583'),(47,'120401B','MAGLASANG','MAMERTA','BERDAN','',0,NULL,'1971-03-24',62,2,'Married','33-3202279-5','0324-1971-','19-050491417-1','',NULL,'',1,17,3,9,'Block 22 Lot 29 Diamond Crest Village, San Jose Del Monte,  ','','','PH','Bulacan Philippines','3023','','0917-5890275','8571132','maglasang-m@itochu.com.ph',NULL,'2012-04-01','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,'123-456-789'),(48,'101011','PINGOY','MARIE BERCHEL','MACAYAN','',0,NULL,'1982-12-04',62,2,'Single','33-8667509-1','3134-1422-10','80-504265068-','',NULL,'',7,9,6,12,'','','','','',NULL,NULL,NULL,NULL,NULL,NULL,'2010-10-11',NULL,NULL,'1O% Consultant','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,''),(49,'80623','RAMOS','JUVI','MAGPANTAY','',0,NULL,'1977-09-19',62,2,'Married','04-3302183-4','0907-1986-','19-090404733-7','',NULL,'',1,14,NULL,12,'PHASE 2, SUMATRA ST., SPRINGHOMES SUBD., ','','Makati City','PH','','','','','','ramos-j@itochu.com.ph',NULL,'2008-06-23','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'207-345-431'),(50,'120101','RAMOS','ROLDIE VIC','CORSINO','',0,NULL,'1986-09-07',62,1,'Single','11-9127612-2','0907-1986-','19-090404733-7','',NULL,'',1,11,2,17,'LAS-UD, CABA,','','','PH',' La Union Philippines','','','0917-5281328','8571142','ramos-r@itochu.com.ph',NULL,'2012-01-01','',NULL,'1O% Consultant',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'207-345-431'),(51,'101001E','RIVERA','ALLAN','SANTIAGO','',0,NULL,'2010-10-01',62,1,'Married','33-3432105-4','1010-0010-6199','08-050355467-8','',NULL,'',1,11,2,17,'NO. 98 SIXTO AVENUE, MAYBUNGA  ','','Makati City','PH','','','','0917-8842231','8571142','rivera-a@itochu.com.ph',NULL,'2010-10-01','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'170-898-371'),(52,'910903','SABAY','AMELIA','LESCANO','',0,NULL,'1970-02-08',62,2,'Married','33-1077026-3','1010-0013-2855','19-050406611-1','',NULL,'',1,20,6,8,'L2, B2, P6, OMAN ST., SOUTHVILLE, BI','','','0','','','(049) 241-3380','0917-7938799','8571122','sabay-a@itochu.com.ph',NULL,'1991-09-03','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'112-097-127'),(53,'120601','SAITO','SHINYA','','',0,NULL,'1983-06-29',91,1,'Single','34-3198063-1','0629-1983-','','',NULL,'',1,15,1,7,'20C The Biltmore Condominium','','','PH','','','','0917-8666454','8571176','saito-shin@itochu.com.ph',NULL,'2012-06-01','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'421-478-906'),(54,'40510','SAN MIGUEL','KRISTIN MAE','MERCADO','',0,NULL,'1982-05-17',62,NULL,'Married','04-1257820-1','1010-0010-4656','19-090548772-1','',NULL,'',1,18,2,17,'#35 NEPTUNE ST., ','GOLDEN COUNTRY HOMES SUBD., ','','PH','Batangas ','','','0917-5248006','8571141','mercado-km@itochu.com.ph',NULL,'2004-05-10','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'230-540-299'),(55,'101001D','SANTOS','RENATO','DACUMOS','',0,NULL,'1970-04-18',62,1,'Married','33-3026300-6','1010-0010-2514','19-089632328-1','',NULL,'',1,19,1,17,'530 J. LUNA ST., ','',' Pasay City Philippines','PH','','','','0917-5262293 ','8571140','santos-r@itochu.com.ph',NULL,'2010-10-01','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'901-238-707'),(56,'091103B','SARMIENTO','RICHEL','SUCATRE','',0,NULL,'1976-11-20',62,2,'Married','33-2785499-4','2003-1473-4200','01-050207768-1','',NULL,'',1,14,6,12,'B12, L17 P-3 UPPER BICUTAN, A. BONIFACIO AVE., ','','Taguig Philippines','PH','','','','0921-6731531','8571185','sucatre@itochu.com.ph',NULL,'2009-11-03','',NULL,'Regular','Catholic',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'914-480-043'),(57,'070904A','SANTOS','MARIA THERESA','BUTUAN','',0,NULL,'1975-01-26',62,2,'Single','33-4510169-0','1010-0010-2514','19-089632328-1','',NULL,'',1,2,1,12,'BLK2 LT 14 CASIMIRO TOWNHOMES,','HABAY 1 BACOOR,','','PH',' Cavite Philippines','','','0917-5511468','8571180','santos-mt@itochu.com.ph',NULL,'2007-09-04','',NULL,'Regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,'901-238-707'),(58,'0058','Uy','Rosel','Roa','',0,NULL,NULL,NULL,NULL,NULL,'','','','',NULL,'',NULL,NULL,NULL,NULL,'','','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(59,'','Orange','Orange','','',0,NULL,NULL,NULL,NULL,NULL,'','','','',NULL,'',NULL,23,NULL,NULL,'','','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'orange@yahoo.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `hs_hr_employee` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_insert_hs_hr_employee_personal_details` AFTER INSERT ON `hs_hr_employee`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = 1;
  CALL audit_insert_hs_hr_employee_personal_details(@orangehrm_user, @orangehrm_action_name, new_version, NEW.emp_number, NEW.emp_firstname, NEW.emp_lastname);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_update_hs_hr_employee` AFTER UPDATE ON `hs_hr_employee`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_personal_details_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_update_hs_hr_employee_personal_details(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number, NEW.emp_firstname, OLD.emp_firstname, NEW.emp_lastname, OLD.emp_lastname, NEW.emp_middle_name, OLD.emp_middle_name);

  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_contact_info_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_update_hs_hr_employee_contact_information(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number, NEW.emp_street1, OLD.emp_street1, NEW.emp_street2, OLD.emp_street2, NEW.city_code, OLD.city_code, NEW.coun_code, OLD.coun_code, NEW.provin_code, OLD.provin_code, NEW.emp_zipcode, OLD.emp_zipcode, NEW.emp_hm_telephone, OLD.emp_hm_telephone, NEW.emp_mobile, OLD.emp_mobile, NEW.emp_work_telephone, OLD.emp_work_telephone, NEW.emp_work_email, OLD.emp_work_email, NEW.emp_oth_email, OLD.emp_oth_email);

  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_job_title_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_update_hs_hr_employee_job_title(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number, NEW.job_title_code, OLD.job_title_code);

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_delete_hs_hr_employee_personal_details` AFTER DELETE ON `hs_hr_employee`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_pim_personal_details_trail` WHERE `affected_entity_id` = OLD.emp_number) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_delete_hs_hr_employee_personal_details(@orangehrm_user, @orangehrm_action_name, new_version, OLD.emp_number);
  CALL archive_reference('hs_hr_employee', OLD.emp_number, CONCAT(OLD.emp_firstname, ' ', OLD.emp_lastname));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `hs_hr_employee_leave_quota`
--

DROP TABLE IF EXISTS `hs_hr_employee_leave_quota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_employee_leave_quota` (
  `leave_type_id` varchar(13) NOT NULL,
  `leave_period_id` int(7) NOT NULL,
  `employee_id` int(7) NOT NULL,
  `no_of_days_allotted` decimal(6,2) DEFAULT NULL,
  `leave_taken` decimal(6,2) DEFAULT '0.00',
  `leave_brought_forward` decimal(6,2) DEFAULT '0.00',
  `leave_carried_forward` decimal(6,2) DEFAULT '0.00',
  PRIMARY KEY (`leave_type_id`,`employee_id`,`leave_period_id`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_period_id` (`leave_period_id`),
  CONSTRAINT `hs_hr_employee_leave_quota_ibfk_1` FOREIGN KEY (`leave_type_id`) REFERENCES `hs_hr_leavetype` (`leave_type_id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_employee_leave_quota_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_employee_leave_quota_ibfk_3` FOREIGN KEY (`leave_period_id`) REFERENCES `hs_hr_leave_period` (`leave_period_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_employee_leave_quota`
--

LOCK TABLES `hs_hr_employee_leave_quota` WRITE;
/*!40000 ALTER TABLE `hs_hr_employee_leave_quota` DISABLE KEYS */;
INSERT INTO `hs_hr_employee_leave_quota` VALUES ('LTY001',1,23,0.00,0.00,0.00,0.00),('LTY002',1,23,0.00,0.00,0.00,0.00),('LTY003',1,23,0.00,0.00,0.00,0.00),('LTY004',1,23,0.00,0.00,0.00,0.00),('LTY005',1,23,17.00,0.00,0.00,0.00),('LTY006',1,23,0.00,0.00,0.00,0.00),('LTY007',1,23,0.00,0.00,0.00,0.00),('LTY008',1,23,0.00,0.00,0.00,0.00),('LTY009',1,23,0.00,0.00,0.00,0.00),('LTY010',1,23,0.00,0.00,0.00,0.00),('LTY011',1,23,0.00,0.00,0.00,0.00),('LTY012',1,23,0.00,0.00,0.00,0.00),('LTY013',1,23,0.00,0.00,0.00,0.00),('LTY014',1,23,0.00,0.00,0.00,0.00),('LTY015',1,23,0.00,0.00,0.00,0.00),('LTY016',1,23,0.00,0.00,0.00,0.00),('LTY017',1,23,0.00,0.00,0.00,0.00),('LTY018',1,23,0.00,0.00,0.00,0.00),('LTY019',1,23,90.00,0.00,0.00,0.00),('LTY020',1,23,0.00,0.00,0.00,0.00),('LTY021',1,23,0.00,0.00,0.00,0.00),('LTY022',1,23,0.00,0.00,0.00,0.00),('LTY023',1,23,0.00,0.00,0.00,0.00),('LTY024',1,23,7.00,-143.00,0.00,0.00),('LTY025',1,23,0.00,0.00,0.00,0.00);
/*!40000 ALTER TABLE `hs_hr_employee_leave_quota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_emprep_usergroup`
--

DROP TABLE IF EXISTS `hs_hr_emprep_usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_emprep_usergroup` (
  `userg_id` varchar(13) NOT NULL DEFAULT '',
  `rep_code` varchar(13) NOT NULL DEFAULT '',
  PRIMARY KEY (`userg_id`,`rep_code`),
  KEY `rep_code` (`rep_code`),
  CONSTRAINT `hs_hr_emprep_usergroup_ibfk_1` FOREIGN KEY (`userg_id`) REFERENCES `hs_hr_user_group` (`userg_id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_emprep_usergroup_ibfk_2` FOREIGN KEY (`rep_code`) REFERENCES `hs_hr_empreport` (`rep_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_emprep_usergroup`
--

LOCK TABLES `hs_hr_emprep_usergroup` WRITE;
/*!40000 ALTER TABLE `hs_hr_emprep_usergroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_emprep_usergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_empreport`
--

DROP TABLE IF EXISTS `hs_hr_empreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_empreport` (
  `rep_code` varchar(13) NOT NULL DEFAULT '',
  `rep_name` varchar(60) DEFAULT NULL,
  `rep_cridef_str` varchar(200) DEFAULT NULL,
  `rep_flddef_str` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`rep_code`),
  UNIQUE KEY `rep_name` (`rep_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_empreport`
--

LOCK TABLES `hs_hr_empreport` WRITE;
/*!40000 ALTER TABLE `hs_hr_empreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_empreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_hsp`
--

DROP TABLE IF EXISTS `hs_hr_hsp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_hsp` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `benefit_year` date DEFAULT NULL,
  `hsp_value` decimal(10,2) NOT NULL,
  `total_acrued` decimal(10,2) NOT NULL,
  `accrued_last_updated` date DEFAULT NULL,
  `amount_per_day` decimal(10,2) NOT NULL,
  `edited_status` tinyint(4) DEFAULT '0',
  `termination_date` date DEFAULT NULL,
  `halted` tinyint(4) DEFAULT '0',
  `halted_date` date DEFAULT NULL,
  `terminated` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `hs_hr_hsp_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_hsp`
--

LOCK TABLES `hs_hr_hsp` WRITE;
/*!40000 ALTER TABLE `hs_hr_hsp` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_hsp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_hsp_payment_request`
--

DROP TABLE IF EXISTS `hs_hr_hsp_payment_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_hsp_payment_request` (
  `id` int(11) NOT NULL,
  `hsp_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_incurred` date NOT NULL,
  `provider_name` varchar(100) DEFAULT NULL,
  `person_incurring_expense` varchar(100) DEFAULT NULL,
  `expense_description` varchar(250) DEFAULT NULL,
  `expense_amount` decimal(10,2) NOT NULL,
  `payment_made_to` varchar(100) DEFAULT NULL,
  `third_party_account_number` varchar(50) DEFAULT NULL,
  `mail_address` varchar(250) DEFAULT NULL,
  `comments` varchar(250) DEFAULT NULL,
  `date_paid` date DEFAULT NULL,
  `check_number` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `hr_notes` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `hsp_id` (`hsp_id`),
  CONSTRAINT `hs_hr_hsp_payment_request_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_hsp_payment_request`
--

LOCK TABLES `hs_hr_hsp_payment_request` WRITE;
/*!40000 ALTER TABLE `hs_hr_hsp_payment_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_hsp_payment_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_hsp_summary`
--

DROP TABLE IF EXISTS `hs_hr_hsp_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_hsp_summary` (
  `summary_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `hsp_plan_id` tinyint(2) NOT NULL,
  `hsp_plan_year` int(6) NOT NULL,
  `hsp_plan_status` tinyint(2) NOT NULL DEFAULT '0',
  `annual_limit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `employer_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `employee_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_accrued` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_used` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`summary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_hsp_summary`
--

LOCK TABLES `hs_hr_hsp_summary` WRITE;
/*!40000 ALTER TABLE `hs_hr_hsp_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_hsp_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_jobtit_empstat`
--

DROP TABLE IF EXISTS `hs_hr_jobtit_empstat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_jobtit_empstat` (
  `jobtit_code` int(7) NOT NULL,
  `estat_code` int(13) NOT NULL,
  PRIMARY KEY (`jobtit_code`,`estat_code`),
  KEY `estat_code` (`estat_code`),
  CONSTRAINT `hs_hr_jobtit_empstat_ibfk_1` FOREIGN KEY (`jobtit_code`) REFERENCES `ohrm_job_title` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_jobtit_empstat_ibfk_2` FOREIGN KEY (`estat_code`) REFERENCES `ohrm_employment_status` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_jobtit_empstat`
--

LOCK TABLES `hs_hr_jobtit_empstat` WRITE;
/*!40000 ALTER TABLE `hs_hr_jobtit_empstat` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_jobtit_empstat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_kpi`
--

DROP TABLE IF EXISTS `hs_hr_kpi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_kpi` (
  `id` int(13) NOT NULL,
  `job_title_code` varchar(13) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `rate_min` double DEFAULT NULL,
  `rate_max` double DEFAULT NULL,
  `rate_default` tinyint(4) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_kpi`
--

LOCK TABLES `hs_hr_kpi` WRITE;
/*!40000 ALTER TABLE `hs_hr_kpi` DISABLE KEYS */;
INSERT INTO `hs_hr_kpi` VALUES (3,'13','Work Efficiency: Meet Annual Project Amount (APA)',15,150,1,1),(4,'13','Work Efficiency: Finishes new projects on time',15,150,0,1),(5,'13','Customer Loyalty: Customer retention',15,150,0,1);
/*!40000 ALTER TABLE `hs_hr_kpi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_leave`
--

DROP TABLE IF EXISTS `hs_hr_leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_leave` (
  `leave_id` int(11) NOT NULL,
  `leave_date` date DEFAULT NULL,
  `leave_length_hours` decimal(6,2) unsigned DEFAULT NULL,
  `leave_length_days` decimal(4,2) unsigned DEFAULT NULL,
  `leave_status` smallint(6) DEFAULT NULL,
  `leave_comments` varchar(256) DEFAULT NULL,
  `leave_request_id` int(11) NOT NULL,
  `leave_type_id` varchar(13) NOT NULL,
  `employee_id` int(7) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  PRIMARY KEY (`leave_id`,`leave_request_id`,`leave_type_id`,`employee_id`),
  KEY `leave_request_id` (`leave_request_id`,`leave_type_id`,`employee_id`),
  KEY `leave_type_id` (`leave_type_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `hs_hr_leave_ibfk_1` FOREIGN KEY (`leave_request_id`, `leave_type_id`, `employee_id`) REFERENCES `hs_hr_leave_requests` (`leave_request_id`, `leave_type_id`, `employee_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_leave`
--

LOCK TABLES `hs_hr_leave` WRITE;
/*!40000 ALTER TABLE `hs_hr_leave` DISABLE KEYS */;
INSERT INTO `hs_hr_leave` VALUES (1,'2012-01-01',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(2,'2012-01-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(3,'2012-01-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(4,'2012-01-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(5,'2012-01-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(6,'2012-01-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(7,'2012-01-07',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(8,'2012-01-08',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(9,'2012-01-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(10,'2012-01-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(11,'2012-01-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(12,'2012-01-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(13,'2012-01-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(14,'2012-01-14',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(15,'2012-01-15',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(16,'2012-01-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(17,'2012-01-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(18,'2012-01-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(19,'2012-01-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(20,'2012-01-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(21,'2012-01-21',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(22,'2012-01-22',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(23,'2012-01-23',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(24,'2012-01-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(25,'2012-01-25',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(26,'2012-01-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(27,'2012-01-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(28,'2012-01-28',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(29,'2012-01-29',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(30,'2012-01-30',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(31,'2012-01-31',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(32,'2012-02-01',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(33,'2012-02-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(34,'2012-02-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(35,'2012-02-04',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(36,'2012-02-05',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(37,'2012-02-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(38,'2012-02-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(39,'2012-02-08',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(40,'2012-02-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(41,'2012-02-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(42,'2012-02-11',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(43,'2012-02-12',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(44,'2012-02-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(45,'2012-02-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(46,'2012-02-15',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(47,'2012-02-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(48,'2012-02-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(49,'2012-02-18',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(50,'2012-02-19',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(51,'2012-02-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(52,'2012-02-21',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(53,'2012-02-22',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(54,'2012-02-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(55,'2012-02-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(56,'2012-02-25',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(57,'2012-02-26',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(58,'2012-02-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(59,'2012-02-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(60,'2012-02-29',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(61,'2012-03-01',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(62,'2012-03-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(63,'2012-03-03',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(64,'2012-03-04',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(65,'2012-03-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(66,'2012-03-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(67,'2012-03-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(68,'2012-03-08',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(69,'2012-03-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(70,'2012-03-10',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(71,'2012-03-11',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(72,'2012-03-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(73,'2012-03-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(74,'2012-03-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(75,'2012-03-15',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(76,'2012-03-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(77,'2012-03-17',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(78,'2012-03-18',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(79,'2012-03-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(80,'2012-03-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(81,'2012-03-21',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(82,'2012-03-22',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(83,'2012-03-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(84,'2012-03-24',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(85,'2012-03-25',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(86,'2012-03-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(87,'2012-03-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(88,'2012-03-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(89,'2012-03-29',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(90,'2012-03-30',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(91,'2012-03-31',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(92,'2012-04-01',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(93,'2012-04-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(94,'2012-04-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(95,'2012-04-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(96,'2012-04-05',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(97,'2012-04-06',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(98,'2012-04-07',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(99,'2012-04-08',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(100,'2012-04-09',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(101,'2012-04-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(102,'2012-04-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(103,'2012-04-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(104,'2012-04-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(105,'2012-04-14',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(106,'2012-04-15',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(107,'2012-04-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(108,'2012-04-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(109,'2012-04-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(110,'2012-04-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(111,'2012-04-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(112,'2012-04-21',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(113,'2012-04-22',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(114,'2012-04-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(115,'2012-04-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(116,'2012-04-25',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(117,'2012-04-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(118,'2012-04-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(119,'2012-04-28',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(120,'2012-04-29',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(121,'2012-04-30',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(122,'2012-05-01',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(123,'2012-05-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(124,'2012-05-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(125,'2012-05-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(126,'2012-05-05',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(127,'2012-05-06',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(128,'2012-05-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(129,'2012-05-08',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(130,'2012-05-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(131,'2012-05-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(132,'2012-05-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(133,'2012-05-12',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(134,'2012-05-13',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(135,'2012-05-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(136,'2012-05-15',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(137,'2012-05-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(138,'2012-05-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(139,'2012-05-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(140,'2012-05-19',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(141,'2012-05-20',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(142,'2012-05-21',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(143,'2012-05-22',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(144,'2012-05-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(145,'2012-05-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(146,'2012-05-25',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(147,'2012-05-26',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(148,'2012-05-27',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(149,'2012-05-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(150,'2012-05-29',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(151,'2012-05-30',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(152,'2012-05-31',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(153,'2012-06-01',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(154,'2012-06-02',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(155,'2012-06-03',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(156,'2012-06-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(157,'2012-06-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(158,'2012-06-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(159,'2012-06-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(160,'2012-06-08',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(161,'2012-06-09',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(162,'2012-06-10',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(163,'2012-06-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(164,'2012-06-12',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(165,'2012-06-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(166,'2012-06-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(167,'2012-06-15',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(168,'2012-06-16',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(169,'2012-06-17',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(170,'2012-06-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(171,'2012-06-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(172,'2012-06-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(173,'2012-06-21',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(174,'2012-06-22',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(175,'2012-06-23',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(176,'2012-06-24',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(177,'2012-06-25',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(178,'2012-06-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(179,'2012-06-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(180,'2012-06-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(181,'2012-06-29',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(182,'2012-06-30',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(183,'2012-07-01',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(184,'2012-07-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(185,'2012-07-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(186,'2012-07-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(187,'2012-07-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(188,'2012-07-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(189,'2012-07-07',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(190,'2012-07-08',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(191,'2012-07-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(192,'2012-07-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(193,'2012-07-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(194,'2012-07-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(195,'2012-07-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(196,'2012-07-14',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(197,'2012-07-15',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(198,'2012-07-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(199,'2012-07-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(200,'2012-07-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(201,'2012-07-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(202,'2012-07-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(203,'2012-07-21',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(204,'2012-07-22',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(205,'2012-07-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(206,'2012-07-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(207,'2012-07-25',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(208,'2012-07-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(209,'2012-07-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(210,'2012-07-28',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(211,'2012-07-29',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(212,'2012-07-30',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(213,'2012-07-31',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(214,'2012-08-01',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(215,'2012-08-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(216,'2012-08-03',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(217,'2012-08-04',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(218,'2012-08-05',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(219,'2012-08-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(220,'2012-08-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(221,'2012-08-08',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(222,'2012-08-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(223,'2012-08-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(224,'2012-08-11',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(225,'2012-08-12',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(226,'2012-08-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(227,'2012-08-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(228,'2012-08-15',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(229,'2012-08-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(230,'2012-08-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(231,'2012-08-18',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(232,'2012-08-19',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(233,'2012-08-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(234,'2012-08-21',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(235,'2012-08-22',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(236,'2012-08-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(237,'2012-08-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(238,'2012-08-25',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(239,'2012-08-26',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(240,'2012-08-27',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(241,'2012-08-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(242,'2012-08-29',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(243,'2012-08-30',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(244,'2012-08-31',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(245,'2012-09-01',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(246,'2012-09-02',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(247,'2012-09-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(248,'2012-09-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(249,'2012-09-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(250,'2012-09-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(251,'2012-09-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(252,'2012-09-08',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(253,'2012-09-09',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(254,'2012-09-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(255,'2012-09-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(256,'2012-09-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(257,'2012-09-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(258,'2012-09-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(259,'2012-09-15',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(260,'2012-09-16',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(261,'2012-09-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(262,'2012-09-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(263,'2012-09-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(264,'2012-09-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(265,'2012-09-21',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(266,'2012-09-22',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(267,'2012-09-23',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(268,'2012-09-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(269,'2012-09-25',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(270,'2012-09-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(271,'2012-09-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(272,'2012-09-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(273,'2012-09-29',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(274,'2012-09-30',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(275,'2012-10-01',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(276,'2012-10-02',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(277,'2012-10-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(278,'2012-10-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(279,'2012-10-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(280,'2012-10-06',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(281,'2012-10-07',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(282,'2012-10-08',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(283,'2012-10-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(284,'2012-10-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(285,'2012-10-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(286,'2012-10-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(287,'2012-10-13',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(288,'2012-10-14',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(289,'2012-10-15',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(290,'2012-10-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(291,'2012-10-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(292,'2012-10-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(293,'2012-10-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(294,'2012-10-20',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(295,'2012-10-21',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(296,'2012-10-22',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(297,'2012-10-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(298,'2012-10-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(299,'2012-10-25',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(300,'2012-10-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(301,'2012-10-27',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(302,'2012-10-28',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(303,'2012-10-29',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(304,'2012-10-30',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(305,'2012-10-31',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(306,'2012-11-01',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(307,'2012-11-02',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(308,'2012-11-03',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(309,'2012-11-04',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(310,'2012-11-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(311,'2012-11-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(312,'2012-11-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(313,'2012-11-08',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(314,'2012-11-09',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(315,'2012-11-10',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(316,'2012-11-11',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(317,'2012-11-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(318,'2012-11-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(319,'2012-11-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(320,'2012-11-15',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(321,'2012-11-16',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(322,'2012-11-17',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(323,'2012-11-18',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(324,'2012-11-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(325,'2012-11-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(326,'2012-11-21',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(327,'2012-11-22',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(328,'2012-11-23',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(329,'2012-11-24',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(330,'2012-11-25',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(331,'2012-11-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(332,'2012-11-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(333,'2012-11-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(334,'2012-11-29',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(335,'2012-11-30',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(336,'2012-12-01',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(337,'2012-12-02',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(338,'2012-12-03',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(339,'2012-12-04',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(340,'2012-12-05',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(341,'2012-12-06',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(342,'2012-12-07',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(343,'2012-12-08',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(344,'2012-12-09',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(345,'2012-12-10',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(346,'2012-12-11',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(347,'2012-12-12',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(348,'2012-12-13',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(349,'2012-12-14',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(350,'2012-12-15',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(351,'2012-12-16',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(352,'2012-12-17',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(353,'2012-12-18',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(354,'2012-12-19',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(355,'2012-12-20',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(356,'2012-12-21',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(357,'2012-12-22',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(358,'2012-12-23',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(359,'2012-12-24',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(360,'2012-12-25',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(361,'2012-12-26',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(362,'2012-12-27',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(363,'2012-12-28',8.00,1.00,0,'',1,'LTY024',23,'00:00:00','00:00:00'),(364,'2012-12-29',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(365,'2012-12-30',0.00,0.00,4,'',1,'LTY024',23,'00:00:00','00:00:00'),(366,'2012-12-31',0.00,0.00,5,'',1,'LTY024',23,'00:00:00','00:00:00'),(367,'2012-07-31',8.00,1.00,3,'',2,'LTY024',23,'07:00:00','15:00:00'),(368,'2012-08-06',8.50,1.00,1,'',3,'LTY024',23,'08:00:00','16:30:00');
/*!40000 ALTER TABLE `hs_hr_leave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_leave_period`
--

DROP TABLE IF EXISTS `hs_hr_leave_period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_leave_period` (
  `leave_period_id` int(11) NOT NULL,
  `leave_period_start_date` date NOT NULL,
  `leave_period_end_date` date NOT NULL,
  PRIMARY KEY (`leave_period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_leave_period`
--

LOCK TABLES `hs_hr_leave_period` WRITE;
/*!40000 ALTER TABLE `hs_hr_leave_period` DISABLE KEYS */;
INSERT INTO `hs_hr_leave_period` VALUES (1,'2012-01-01','2012-12-31');
/*!40000 ALTER TABLE `hs_hr_leave_period` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_leave_requests`
--

DROP TABLE IF EXISTS `hs_hr_leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_leave_requests` (
  `leave_request_id` int(11) NOT NULL,
  `leave_type_id` varchar(13) NOT NULL,
  `leave_period_id` int(7) NOT NULL,
  `leave_type_name` char(50) DEFAULT NULL,
  `date_applied` date NOT NULL,
  `employee_id` int(7) NOT NULL,
  `leave_comments` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`leave_request_id`,`leave_type_id`,`employee_id`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_type_id` (`leave_type_id`),
  KEY `leave_period_id` (`leave_period_id`),
  CONSTRAINT `hs_hr_leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_leave_requests_ibfk_2` FOREIGN KEY (`leave_period_id`) REFERENCES `hs_hr_leave_period` (`leave_period_id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_leave_requests_ibfk_3` FOREIGN KEY (`leave_type_id`) REFERENCES `hs_hr_leavetype` (`leave_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_leave_requests`
--

LOCK TABLES `hs_hr_leave_requests` WRITE;
/*!40000 ALTER TABLE `hs_hr_leave_requests` DISABLE KEYS */;
INSERT INTO `hs_hr_leave_requests` VALUES (1,'LTY024',1,'EL','2012-01-01',23,''),(2,'LTY024',1,'EL','2012-07-31',23,''),(3,'LTY024',1,'EL','2012-08-06',23,'');
/*!40000 ALTER TABLE `hs_hr_leave_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_leavetype`
--

DROP TABLE IF EXISTS `hs_hr_leavetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_leavetype` (
  `leave_type_id` varchar(13) NOT NULL,
  `leave_type_name` varchar(50) DEFAULT NULL,
  `available_flag` smallint(6) DEFAULT NULL,
  `operational_country_id` int(10) unsigned DEFAULT NULL,
  `leave_rules` text,
  PRIMARY KEY (`leave_type_id`),
  KEY `operational_country_id` (`operational_country_id`),
  CONSTRAINT `hs_hr_leavetype_ibfk_1` FOREIGN KEY (`operational_country_id`) REFERENCES `ohrm_operational_country` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_leavetype`
--

LOCK TABLES `hs_hr_leavetype` WRITE;
/*!40000 ALTER TABLE `hs_hr_leavetype` DISABLE KEYS */;
INSERT INTO `hs_hr_leavetype` VALUES ('LTY001','Paternity',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY002','Maternity_Norm',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY003','Maternity_CS',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY004','SSS_SL',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY005','VL1',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY006','VL2',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY007','VL3',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY008','SL1',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY009','SL2',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY010','SL3',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY011','SL4',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY012','SL5',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY013','SL6',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY014','SL-HP 1',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY015','SL-HP 2',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY016','SL-HP 3',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY017','SL-HP 4',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY018','SL-HP 5',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY019','EX-SL1',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY020','EX-SL2',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY021','EX-SL3',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY022','EX-SL4',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY023','UL',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY024','EL',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n'),('LTY025','MRL',1,NULL,'<?xml version=\"1.0\"?>\n<xml>\n			<flow><empapply>1</empapply><adminassign>1</adminassign></flow><elgibility><empstatus><include><status>1</status></include><exclude><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>2</status></exclude></empstatus><empjobtitles><include><status>1</status><status>2</status><status>3</status><status>4</status><status>5</status><status>6</status><status>7</status><status>8</status><status>9</status><status>10</status><status>11</status><status>12</status><status>13</status><status>14</status><status>15</status><status>16</status><status>17</status><status>18</status><status>19</status><status>20</status><status>21</status><status>22</status><status>23</status><status>24</status></include><exclude/></empjobtitles><yearofservice><from>1</from><to>100</to></yearofservice><restriction><moreThanCurrent>0</moreThanCurrent></restriction></elgibility><entitlement><adminAdjust>1</adminAdjust><accure>0</accure><accrualRules><frequency><type/><dayOfCredit/></frequency><accrualGroups/></accrualRules></entitlement><rollover><allow>0</allow><date>12/31</date><expDate></expDate><maxDays></maxDays><amount>1</amount></rollover></xml>\n');
/*!40000 ALTER TABLE `hs_hr_leavetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_mailnotifications`
--

DROP TABLE IF EXISTS `hs_hr_mailnotifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_mailnotifications` (
  `user_id` int(20) NOT NULL,
  `notification_type_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  KEY `user_id` (`user_id`),
  KEY `notification_type_id` (`notification_type_id`),
  CONSTRAINT `hs_hr_mailnotifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `ohrm_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_mailnotifications`
--

LOCK TABLES `hs_hr_mailnotifications` WRITE;
/*!40000 ALTER TABLE `hs_hr_mailnotifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_mailnotifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_module`
--

DROP TABLE IF EXISTS `hs_hr_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_module` (
  `mod_id` varchar(36) NOT NULL DEFAULT '',
  `name` varchar(45) DEFAULT NULL,
  `owner` varchar(45) DEFAULT NULL,
  `owner_email` varchar(100) DEFAULT NULL,
  `version` varchar(36) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_module`
--

LOCK TABLES `hs_hr_module` WRITE;
/*!40000 ALTER TABLE `hs_hr_module` DISABLE KEYS */;
INSERT INTO `hs_hr_module` VALUES ('MOD001','Admin','OrangeHRM','info@orangehrm.com','VER001','HR Admin'),('MOD002','PIM','OrangeHRM','info@orangehrm.com','VER001','HR Functions'),('MOD004','Report','OrangeHRM','info@orangehrm.com','VER001','Reporting'),('MOD005','Leave','OrangeHRM','info@orangehrm.com','VER001','Leave Tracking'),('MOD006','Time','OrangeHRM','info@orangehrm.com','VER001','Time Tracking'),('MOD007','Benefits','OrangeHRM','info@orangehrm.com','VER001','Benefits Tracking'),('MOD008','Recruitment','OrangeHRM','info@orangehrm.com','VER001','Recruitment'),('MOD009','Performance','OrangeHRM','info@orangehrm.com','VER001','Performance');
/*!40000 ALTER TABLE `hs_hr_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_pay_period`
--

DROP TABLE IF EXISTS `hs_hr_pay_period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_pay_period` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `close_date` date NOT NULL,
  `check_date` date NOT NULL,
  `timesheet_aproval_due_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_pay_period`
--

LOCK TABLES `hs_hr_pay_period` WRITE;
/*!40000 ALTER TABLE `hs_hr_pay_period` DISABLE KEYS */;
/*!40000 ALTER TABLE `hs_hr_pay_period` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_payperiod`
--

DROP TABLE IF EXISTS `hs_hr_payperiod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_payperiod` (
  `payperiod_code` varchar(13) NOT NULL DEFAULT '',
  `payperiod_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`payperiod_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_payperiod`
--

LOCK TABLES `hs_hr_payperiod` WRITE;
/*!40000 ALTER TABLE `hs_hr_payperiod` DISABLE KEYS */;
INSERT INTO `hs_hr_payperiod` VALUES ('1','Weekly'),('2','Bi Weekly'),('3','Semi Monthly'),('4','Monthly'),('5','Monthly on first pay of month.'),('6','Hourly');
/*!40000 ALTER TABLE `hs_hr_payperiod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_performance_review`
--

DROP TABLE IF EXISTS `hs_hr_performance_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_performance_review` (
  `id` int(13) NOT NULL,
  `employee_id` int(13) NOT NULL,
  `reviewer_id` int(13) NOT NULL,
  `creator_id` varchar(36) DEFAULT NULL,
  `job_title_code` varchar(10) NOT NULL,
  `sub_division_id` int(13) DEFAULT NULL,
  `creation_date` date NOT NULL,
  `period_from` date NOT NULL,
  `period_to` date NOT NULL,
  `due_date` date NOT NULL,
  `state` tinyint(2) DEFAULT NULL,
  `kpis` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_performance_review`
--

LOCK TABLES `hs_hr_performance_review` WRITE;
/*!40000 ALTER TABLE `hs_hr_performance_review` DISABLE KEYS */;
INSERT INTO `hs_hr_performance_review` VALUES (1,23,12,'1','13',5,'2012-07-31','2012-01-01','2012-06-01','2012-07-31',3,'<?xml version=\"1.0\"?>\n<xml>\n			<kpis><kpi><id>1</id><desc>aaa</desc><min>50</min><max>100</max><rate>100</rate><comment>keep up the good job!</comment></kpi></kpis></xml>\n'),(3,23,12,'1','13',5,'2012-08-02','2011-04-01','2012-04-30','2012-08-02',1,'<?xml version=\"1.0\"?>\n<xml>\n			<kpis><kpi><id>3</id><desc>Work Efficiency: Meet Annual Project Amount (APA)</desc><min>15</min><max>150</max><rate> </rate><comment> </comment></kpi><kpi><id>4</id><desc>Work Efficiency: Finishes new projects on time</desc><min>15</min><max>150</max><rate> </rate><comment> </comment></kpi><kpi><id>5</id><desc>Customer Loyalty: Customer retention</desc><min>15</min><max>150</max><rate> </rate><comment> </comment></kpi></kpis></xml>\n');
/*!40000 ALTER TABLE `hs_hr_performance_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_performance_review_comments`
--

DROP TABLE IF EXISTS `hs_hr_performance_review_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_performance_review_comments` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `pr_id` int(13) NOT NULL,
  `employee_id` int(13) DEFAULT NULL,
  `comment` text,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_performance_review_comments`
--

LOCK TABLES `hs_hr_performance_review_comments` WRITE;
/*!40000 ALTER TABLE `hs_hr_performance_review_comments` DISABLE KEYS */;
INSERT INTO `hs_hr_performance_review_comments` VALUES (1,1,NULL,'good luck always!','2012-07-31');
/*!40000 ALTER TABLE `hs_hr_performance_review_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_province`
--

DROP TABLE IF EXISTS `hs_hr_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_name` varchar(40) NOT NULL DEFAULT '',
  `province_code` char(2) NOT NULL DEFAULT '',
  `cou_code` char(2) NOT NULL DEFAULT 'us',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_province`
--

LOCK TABLES `hs_hr_province` WRITE;
/*!40000 ALTER TABLE `hs_hr_province` DISABLE KEYS */;
INSERT INTO `hs_hr_province` VALUES (1,'Alaska','AK','US'),(2,'Alabama','AL','US'),(3,'American Samoa','AS','US'),(4,'Arizona','AZ','US'),(5,'Arkansas','AR','US'),(6,'California','CA','US'),(7,'Colorado','CO','US'),(8,'Connecticut','CT','US'),(9,'Delaware','DE','US'),(10,'District of Columbia','DC','US'),(11,'Federated States of Micronesia','FM','US'),(12,'Florida','FL','US'),(13,'Georgia','GA','US'),(14,'Guam','GU','US'),(15,'Hawaii','HI','US'),(16,'Idaho','ID','US'),(17,'Illinois','IL','US'),(18,'Indiana','IN','US'),(19,'Iowa','IA','US'),(20,'Kansas','KS','US'),(21,'Kentucky','KY','US'),(22,'Louisiana','LA','US'),(23,'Maine','ME','US'),(24,'Marshall Islands','MH','US'),(25,'Maryland','MD','US'),(26,'Massachusetts','MA','US'),(27,'Michigan','MI','US'),(28,'Minnesota','MN','US'),(29,'Mississippi','MS','US'),(30,'Missouri','MO','US'),(31,'Montana','MT','US'),(32,'Nebraska','NE','US'),(33,'Nevada','NV','US'),(34,'New Hampshire','NH','US'),(35,'New Jersey','NJ','US'),(36,'New Mexico','NM','US'),(37,'New York','NY','US'),(38,'North Carolina','NC','US'),(39,'North Dakota','ND','US'),(40,'Northern Mariana Islands','MP','US'),(41,'Ohio','OH','US'),(42,'Oklahoma','OK','US'),(43,'Oregon','OR','US'),(44,'Palau','PW','US'),(45,'Pennsylvania','PA','US'),(46,'Puerto Rico','PR','US'),(47,'Rhode Island','RI','US'),(48,'South Carolina','SC','US'),(49,'South Dakota','SD','US'),(50,'Tennessee','TN','US'),(51,'Texas','TX','US'),(52,'Utah','UT','US'),(53,'Vermont','VT','US'),(54,'Virgin Islands','VI','US'),(55,'Virginia','VA','US'),(56,'Washington','WA','US'),(57,'West Virginia','WV','US'),(58,'Wisconsin','WI','US'),(59,'Wyoming','WY','US'),(60,'Armed Forces Africa','AE','US'),(61,'Armed Forces Americas (except Canada)','AA','US'),(62,'Armed Forces Canada','AE','US'),(63,'Armed Forces Europe','AE','US'),(64,'Armed Forces Middle East','AE','US'),(65,'Armed Forces Pacific','AP','US');
/*!40000 ALTER TABLE `hs_hr_province` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_rights`
--

DROP TABLE IF EXISTS `hs_hr_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_rights` (
  `userg_id` varchar(36) NOT NULL DEFAULT '',
  `mod_id` varchar(36) NOT NULL DEFAULT '',
  `addition` smallint(5) unsigned DEFAULT '0',
  `editing` smallint(5) unsigned DEFAULT '0',
  `deletion` smallint(5) unsigned DEFAULT '0',
  `viewing` smallint(5) unsigned DEFAULT '0',
  PRIMARY KEY (`mod_id`,`userg_id`),
  KEY `userg_id` (`userg_id`),
  CONSTRAINT `hs_hr_rights_ibfk_1` FOREIGN KEY (`mod_id`) REFERENCES `hs_hr_module` (`mod_id`) ON DELETE CASCADE,
  CONSTRAINT `hs_hr_rights_ibfk_2` FOREIGN KEY (`userg_id`) REFERENCES `hs_hr_user_group` (`userg_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_rights`
--

LOCK TABLES `hs_hr_rights` WRITE;
/*!40000 ALTER TABLE `hs_hr_rights` DISABLE KEYS */;
INSERT INTO `hs_hr_rights` VALUES ('USG001','MOD001',1,1,1,1),('USG001','MOD002',1,1,1,1),('USG001','MOD004',1,1,1,1),('USG001','MOD005',1,1,1,1),('USG001','MOD006',1,1,1,1),('USG001','MOD007',1,1,1,1),('USG001','MOD008',1,1,1,1),('USG001','MOD009',1,1,1,1);
/*!40000 ALTER TABLE `hs_hr_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_unique_id`
--

DROP TABLE IF EXISTS `hs_hr_unique_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_unique_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_id` int(10) unsigned NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_field` (`table_name`,`field_name`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_unique_id`
--

LOCK TABLES `hs_hr_unique_id` WRITE;
/*!40000 ALTER TABLE `hs_hr_unique_id` DISABLE KEYS */;
INSERT INTO `hs_hr_unique_id` VALUES (1,59,'hs_hr_employee','emp_number'),(2,9,'hs_hr_module','mod_id'),(3,1,'hs_hr_user_group','userg_id'),(4,0,'hs_hr_empreport','rep_code'),(5,368,'hs_hr_leave','leave_id'),(6,25,'hs_hr_leavetype','leave_type_id'),(7,3,'hs_hr_leave_requests','leave_request_id'),(8,0,'hs_hr_custom_export','export_id'),(9,0,'hs_hr_custom_import','import_id'),(10,0,'hs_hr_pay_period','id'),(11,0,'hs_hr_hsp_summary','summary_id'),(12,0,'hs_hr_hsp_payment_request','id'),(13,5,'hs_hr_kpi','id'),(14,3,'hs_hr_performance_review','id'),(15,1,'hs_hr_leave_period','leave_period_id'),(16,2,'ohrm_emp_reporting_method','reporting_method_id'),(17,2,'ohrm_timesheet','timesheet_id'),(18,0,'ohrm_timesheet_action_log','timesheet_action_log_id'),(19,0,'ohrm_timesheet_item','timesheet_item_id'),(20,0,'ohrm_attendance_record','id'),(21,1,'ohrm_job_vacancy','id'),(22,1,'ohrm_job_candidate','id'),(23,82,'ohrm_workflow_state_machine','id'),(24,0,'ohrm_job_candidate_attachment','id'),(25,0,'ohrm_job_vacancy_attachment','id'),(26,1,'ohrm_job_candidate_vacancy','id'),(27,3,'ohrm_job_candidate_history','id'),(28,0,'ohrm_job_interview','id');
/*!40000 ALTER TABLE `hs_hr_unique_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hs_hr_user_group`
--

DROP TABLE IF EXISTS `hs_hr_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hs_hr_user_group` (
  `userg_id` varchar(36) NOT NULL DEFAULT '',
  `userg_name` varchar(45) DEFAULT NULL,
  `userg_repdef` smallint(5) unsigned DEFAULT '0',
  PRIMARY KEY (`userg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hs_hr_user_group`
--

LOCK TABLES `hs_hr_user_group` WRITE;
/*!40000 ALTER TABLE `hs_hr_user_group` DISABLE KEYS */;
INSERT INTO `hs_hr_user_group` VALUES ('USG001','Admin',1);
/*!40000 ALTER TABLE `hs_hr_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `ohrm_adv_leave_country_based_employee_leave_types`
--

DROP TABLE IF EXISTS `ohrm_adv_leave_country_based_employee_leave_types`;
/*!50001 DROP VIEW IF EXISTS `ohrm_adv_leave_country_based_employee_leave_types`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `ohrm_adv_leave_country_based_employee_leave_types` (
  `emp_number` int(11),
  `leave_type_id` varchar(13),
  `leave_period_id` int(11),
  `editable` bigint(20)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ohrm_attendance_record`
--

DROP TABLE IF EXISTS `ohrm_attendance_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_attendance_record` (
  `id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `punch_in_utc_time` datetime DEFAULT NULL,
  `punch_in_note` varchar(255) DEFAULT NULL,
  `punch_in_time_offset` varchar(255) DEFAULT NULL,
  `punch_in_user_time` datetime DEFAULT NULL,
  `punch_out_utc_time` datetime DEFAULT NULL,
  `punch_out_note` varchar(255) DEFAULT NULL,
  `punch_out_time_offset` varchar(255) DEFAULT NULL,
  `punch_out_user_time` datetime DEFAULT NULL,
  `state` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_attendance_record`
--

LOCK TABLES `ohrm_attendance_record` WRITE;
/*!40000 ALTER TABLE `ohrm_attendance_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_attendance_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_module`
--

DROP TABLE IF EXISTS `ohrm_audittrail_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_module` (
  `id` smallint(6) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_module`
--

LOCK TABLES `ohrm_audittrail_module` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_module` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_module` VALUES (1,'PIM'),(2,'Recruitment');
/*!40000 ALTER TABLE `ohrm_audittrail_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_pim_contact_info_trail`
--

DROP TABLE IF EXISTS `ohrm_audittrail_pim_contact_info_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_pim_contact_info_trail` (
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_owner_id` varchar(6) NOT NULL,
  `version_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL,
  `affected_entity_id` varchar(20) NOT NULL DEFAULT '',
  `action_description` text,
  PRIMARY KEY (`action_time`,`action_owner_id`,`version_id`,`affected_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_pim_contact_info_trail`
--

LOCK TABLES `ohrm_audittrail_pim_contact_info_trail` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_pim_contact_info_trail` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_pim_contact_info_trail` VALUES ('2012-07-26 10:15:03','1',1,'UPDATE CONTACT INFORMATION','1',' Address Street 1 was changed from \"  \" to G8G Macopa St., Fort Bonifacio,  Taguig Philippines.\n City was changed from \"  \" to Taguig City.\n Country was changed from NULL to Philippines.\n Home Telephone was changed from \"  \" to 8571126.\n Mobile was changed from \"  \" to 0917-5362859.\n Work Email was changed from \"  \" to acosta-gc@itochu.com.ph.\n'),('2012-07-26 14:05:06','1',1,'UPDATE CONTACT INFORMATION','4',' Address Street 1 was changed from \"  \" to Phase 1 Blk 1 Lot 15  San Lorenzo South Sta. Rosa.\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to  Laguna.\n Home Telephone was changed from \"  \" to 8571162.\n Mobile was changed from \"  \" to 0917-5236596.\n Work Email was changed from \"  \" to afunggol-mr@itochu.com.ph.\n'),('2012-07-26 14:25:21','1',1,'UPDATE CONTACT INFORMATION','5',' Address Street 1 was changed from \"  \" to 46 PAGASA ST., SIGNAL VILLAGE, Taguig Philippines.\n City was changed from \"  \" to Taguig.\n Country was changed from NULL to Philippines.\n Home Telephone was changed from \"  \" to 8571174.\n Mobile was changed from \"  \" to 0917-8007046.\n Work Email was changed from \"  \" to aguinaldo-s@itochu.com.ph.\n'),('2012-07-27 09:55:29','1',1,'UPDATE CONTACT INFORMATION','6',' Address Street 1 was changed from \"  \" to 1126 CRISTOBAL ST. Manila Philippines.\n Country was changed from NULL to Philippines.\n Home Telephone was changed from \"  \" to 8571104.\n Mobile was changed from \"  \" to 0915-1274781.\n Work Telephone was changed from \"  \" to 742-4667.\n Work Email was changed from \"  \" to apalisok-s@itochu.com.ph.\n'),('2012-07-27 10:16:42','1',1,'UPDATE CONTACT INFORMATION','7',' Address Street 1 was changed from \"  \" to 37B MAKABAYAN ST., BRGY. OBRERO,  .\n City was changed from \"  \" to Makati City.\n Country was changed from NULL to Philippines.\n Work Email was changed from \"  \" to arquinez-a@itochu.com.ph.\n'),('2012-07-27 10:41:37','1',1,'UPDATE CONTACT INFORMATION','8',' Work Email was changed from \"  \" to asuncion-a@itochu.com.ph.\n'),('2012-07-27 14:41:56','1',1,'UPDATE CONTACT INFORMATION','9',' Address Street 1 was changed from \"  \" to 29 IRID ST. Mandaluyong Philippines.\n Country was changed from NULL to Philippines.\n Home Telephone was changed from \"  \" to 8571107.\n Mobile was changed from \"  \" to 0917-8019439.\n Work Email was changed from \"  \" to azores-n@itochu.com.ph.\n'),('2012-07-27 14:42:12','1',2,'UPDATE CONTACT INFORMATION','9',' City was changed from \"  \" to Mandaluyong.\n'),('2012-07-27 14:52:05','1',1,'UPDATE CONTACT INFORMATION','10',' Address Street 1 was changed from \"  \" to No. 17 Vibora St., 10th Avenue  .\n City was changed from \"  \" to Makati City.\n Country was changed from NULL to Philippines.\n'),('2012-07-28 09:28:54','1',1,'UPDATE CONTACT INFORMATION','12',' Address Street 1 was changed from \"  \" to LOT 6 BLOCK 8 DIVIDEND HOMES SUBDIVISION, TAYTAY, Rizal Philippines.\n Country was changed from NULL to Philippines.\n Home Telephone was changed from \"  \" to 8571103.\n Mobile was changed from \"  \" to 0917-5236631.\n Work Email was changed from \"  \" to dulay-j@itochu.com.ph.\n'),('2012-07-28 09:35:55','1',1,'UPDATE CONTACT INFORMATION','11',' Address Street 1 was changed from \"  \" to #422 Solitaria St.  .\n City was changed from \"  \" to Pasay City.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-5133154.\n Work Telephone was changed from \"  \" to 8571117.\n Work Email was changed from \"  \" to cano-js@itochu.com.ph.\n'),('2012-07-28 10:04:20','1',1,'UPDATE CONTACT INFORMATION','13',' Address Street 1 was changed from \"  \" to 2493 Belarmino St., Bangkal,  Makati City Philippines.\n Zip/Postal Code was changed from \"  \" to 1223.\n Mobile was changed from \"  \" to 0917-8658143.\n Work Telephone was changed from \"  \" to 8571118.\n Work Email was changed from \"  \" to baluyot-s@itochu.com.ph.\n'),('2012-07-28 10:17:24','1',1,'UPDATE CONTACT INFORMATION','14',' Address Street 1 was changed from \"  \" to 197 ENGRACIO SANTOS ST. San Juan Philippines.\n Home Telephone was changed from \"  \" to 0917-5253145.\n Work Telephone was changed from \"  \" to 8571160.\n Work Email was changed from \"  \" to chua-s@itochu.com.ph.\n'),('2012-07-28 10:39:05','1',1,'UPDATE CONTACT INFORMATION','16',' Address Street 1 was changed from \"  \" to 1047 J. VERGARA ST., Makati City Philippines.\n Work Email was changed from \"  \" to colando-jg@itochu.com.ph.\n'),('2012-07-28 10:52:36','1',1,'UPDATE CONTACT INFORMATION','17',' Address Street 1 was changed from \"  \" to #8 ROTTERHAM ST., HILLSBOROUGH ALABANG VILLAGE, .\n City was changed from \"  \" to  Muntinlupa City.\n Country was changed from NULL to Philippines.\n Zip/Postal Code was changed from \"  \" to 1770.\n Mobile was changed from \"  \" to 0917-8110337.\n Work Telephone was changed from \"  \" to 8571150.\n Work Email was changed from \"  \" to zamora-r@itochu.com.ph.\n'),('2012-07-28 11:17:38','1',1,'UPDATE CONTACT INFORMATION','18',' Address Street 1 was changed from \"  \" to 2306D MARCONI ST.,.\n City was changed from \"  \" to  Makati City.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-8530916.\n Work Telephone was changed from \"  \" to 8571172.\n Work Email was changed from \"  \" to cuevo-e@itochu.com.ph.\n'),('2012-07-28 11:30:01','1',1,'UPDATE CONTACT INFORMATION','20',' Address Street 1 was changed from \"  \" to 088 SUNRISE COMPOUND, BRGY. 193 ZONE 20, PILDERA 2.\n City was changed from \"  \" to Pasay City.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0927-7958731.\n Work Telephone was changed from \"  \" to 8571188.\n Work Email was changed from \"  \" to villesenda-rm@itochu.com.ph.\n'),('2012-07-28 11:40:09','1',1,'UPDATE CONTACT INFORMATION','21',' Address Street 1 was changed from \"  \" to 12 MARIPOSA ST. GULOD, NOVALICHES,.\n City was changed from \"  \" to  Quezon City.\n Country was changed from NULL to Philippines.\n Home Telephone was changed from \"  \" to 0917-8539476.\n Work Telephone was changed from \"  \" to 8571101.\n Work Email was changed from \"  \" to trinidad-l@itochu.com.ph.\n'),('2012-07-28 11:47:31','1',1,'UPDATE CONTACT INFORMATION','22',' Address Street 1 was changed from \"  \" to Block 7, Lot 24 Marigman St.,  .\n City was changed from \"  \" to  Antipolo City.\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to Rizal.\n Mobile was changed from \"  \" to 0917-5853960.\n Work Telephone was changed from \"  \" to 8571143.\n Work Email was changed from \"  \" to deguzman-ma@itochu.com.ph.\n'),('2012-07-28 12:03:42','1',1,'UPDATE CONTACT INFORMATION','23',' Address Street 1 was changed from \"  \" to UNIT T-05 NEW MANILA CONDOMINIUM, .\n Address Street 2 was changed from \"  \" to 21 N. DOMINGO ST., BRGY. VALENCIA  .\n City was changed from \"  \" to Quezon City.\n Country was changed from NULL to Philippines.\n Zip/Postal Code was changed from \"  \" to 1112.\n Mobile was changed from \"  \" to 0917-5236634.\n Work Telephone was changed from \"  \" to 8571105.\n Work Email was changed from \"  \" to perez-j@itochu.com.ph.\n'),('2012-07-28 12:24:56','1',1,'UPDATE CONTACT INFORMATION','24',' Address Street 1 was changed from \"  \" to AD BLOCK 3, LOT 1 MONTEMAR ST., HOLIDAY HOMES, PH-1 SAN ANTONIO, SAN PEDRO .\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to Laguna .\n Mobile was changed from \"  \" to 0926-6490991.\n Work Telephone was changed from \"  \" to 8571152.\n Work Email was changed from \"  \" to pabalan-g@itochu.com.ph.\n'),('2012-07-28 12:25:25','1',2,'UPDATE CONTACT INFORMATION','24',' Address Street 1 was changed from AD BLOCK 3, LOT 1 MONTEMAR ST., HOLIDAY HOMES, PH-1 SAN ANTONIO, SAN PEDRO  to AD BLOCK 3, LOT 1 MONTEMAR ST., .\n Address Street 2 was changed from \"  \" to HOLIDAY HOMES, PH-1 SAN ANTONIO, .\n City was changed from \"  \" to SAN PEDRO .\n'),('2012-07-28 12:48:00','1',1,'UPDATE CONTACT INFORMATION','25',' Address Street 1 was changed from \"  \" to 2459 PARK AVENUE .\n City was changed from \"  \" to Pasay City.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0921-3537261.\n Work Telephone was changed from \"  \" to 8571181.\n Work Email was changed from \"  \" to lactao-d@itochu.com.ph.\n'),('2012-07-28 12:55:03','1',1,'UPDATE CONTACT INFORMATION','26',' Address Street 1 was changed from \"  \" to 3611 V MAPA  EXTENSION Manila.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-8811171.\n Work Telephone was changed from \"  \" to 8571121.\n Work Email was changed from \"  \" to ong-jl@itochu.com.ph.\n'),('2012-07-28 13:03:12','1',1,'UPDATE CONTACT INFORMATION','27',' Address Street 1 was changed from \"  \" to 088 SUNRISE COMPOUND, .\n Address Street 2 was changed from \"  \" to BRGY. 193 ZONE 20, PILDERA 2 .\n City was changed from \"  \" to Pasay City .\n Country was changed from NULL to Philippines.\n'),('2012-07-28 13:03:32','1',2,'UPDATE CONTACT INFORMATION','27',' Mobile was changed from \"  \" to 0928-3772747.\n Work Telephone was changed from \"  \" to 8571182.\n Work Email was changed from \"  \" to montano-j@itochu.com.ph.\n'),('2012-07-28 13:12:45','1',1,'UPDATE CONTACT INFORMATION','28',' Address Street 1 was changed from \"  \" to  Makati City Philippines.\n'),('2012-07-28 13:30:58','1',1,'UPDATE CONTACT INFORMATION','30',' Address Street 1 was changed from \"  \" to Unit 37-D The Residences At Greenbelt, Legaspi Village,.\n City was changed from \"  \" to  Makati City.\n Country was changed from NULL to Philippines.\n'),('2012-07-28 13:32:30','1',2,'UPDATE CONTACT INFORMATION','30',' Mobile was changed from \"  \" to 0917-5940396.\n Work Telephone was changed from \"  \" to 8571100.\n Work Email was changed from \"  \" to hisatomi-k@itochu.com.ph.\n'),('2012-07-28 13:38:02','1',1,'UPDATE CONTACT INFORMATION','31',' Address Street 1 was changed from \"  \" to 15 SAN ANDRES, ALAMINOS, .\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to Laguna Philippines.\n Mobile was changed from \"  \" to 0917-8925712.\n Work Telephone was changed from \"  \" to 8571108.\n Work Email was changed from \"  \" to tolentino-a@itochu.com.ph.\n'),('2012-07-28 14:00:06','1',1,'UPDATE CONTACT INFORMATION','32',' Address Street 1 was changed from \"  \" to  Makati City Philippines.\n'),('2012-07-28 14:12:32','1',1,'UPDATE CONTACT INFORMATION','33',' Address Street 1 was changed from \"  \" to Phase 1, Block 4, Paso Cocohills, Bagumbayan,  .\n City was changed from \"  \" to Taguig Philippines.\n Mobile was changed from \"  \" to 0920-3462976.\n Work Telephone was changed from \"  \" to 8571189.\n Work Email was changed from \"  \" to dolor-j@itochu.com.ph.\n'),('2012-07-28 14:27:34','1',1,'UPDATE CONTACT INFORMATION','34',' Address Street 1 was changed from \"  \" to Blk. 18 Lot 83 KC34 St.,.\n Address Street 2 was changed from \"  \" to  Karangalan Village,  Manggahan,  .\n City was changed from \"  \" to  Pasig City Philippines.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-8666455.\n Work Telephone was changed from \"  \" to 8571106.\n Work Email was changed from \"  \" to sebeldia-dm@itochu.com.ph.\n'),('2012-07-28 14:47:52','1',1,'UPDATE CONTACT INFORMATION','35',' Address Street 1 was changed from \"  \" to 1829 DART ST. Manila Philippines.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-5230063.\n Work Telephone was changed from \"  \" to 8571130.\n Work Email was changed from \"  \" to domalanta-g@itochu.com.ph.\n'),('2012-07-28 15:06:05','1',1,'UPDATE CONTACT INFORMATION','36',' Address Street 1 was changed from \"  \" to 515 PALTOC ST., STA. MESA,  .\n Address Street 2 was changed from \"  \" to Manila Philippines.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0921-9939678.\n Work Telephone was changed from \"  \" to 8571191.\n Work Email was changed from \"  \" to duarte-s@itochu.com.ph.\n'),('2012-07-28 15:34:16','1',1,'UPDATE CONTACT INFORMATION','37',' Address Street 1 was changed from \"  \" to 206 Aries St., Dona Soledad Townhomes, .\n Address Street 2 was changed from \"  \" to  Hawaii Circle, Annex 45, Betterliving Subdivision,   .\n City was changed from \"  \" to Paranaque City Philippines.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-5236627.\n Work Telephone was changed from \"  \" to 8571120.\n Work Email was changed from \"  \" to espino-a@itochu.com.ph.\n'),('2012-07-28 16:15:26','1',1,'UPDATE CONTACT INFORMATION','38',' Address Street 1 was changed from \"  \" to 197 Tiaga St., Wildcat Village,  .\n Address Street 2 was changed from \"  \" to Brgy. Ususan Annex,  .\n City was changed from \"  \" to Taguig Philippines.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-5399054.\n Work Telephone was changed from \"  \" to 8571187.\n Work Email was changed from \"  \" to garrovillo-e@itochu.com.ph.\n'),('2012-07-28 16:47:05','1',1,'UPDATE CONTACT INFORMATION','39',' Address Street 1 was changed from \"  \" to 810 B. MAYOR ST., MALIBAY, .\n City was changed from \"  \" to Pasay City Philippines.\n Mobile was changed from \"  \" to 0917-8392646.\n Work Telephone was changed from \"  \" to 8571171.\n Work Email was changed from \"  \" to go-w@itochu.com.ph.\n'),('2012-07-28 17:43:34','1',1,'UPDATE CONTACT INFORMATION','40',' Address Street 1 was changed from \"  \" to 56 A. Rita St.,  San Juan Philippines.\n Mobile was changed from \"  \" to 0917-5362861.\n Work Telephone was changed from \"  \" to 8571127.\n Work Email was changed from \"  \" to go-fran@itochu.com.ph.\n'),('2012-07-30 01:42:51','1',1,'UPDATE CONTACT INFORMATION','41',' Address Street 1 was changed from \"  \" to B-32 L-1 BRGY. SAN ISIDRO I, DASMARI.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0915-7286081.\n Work Telephone was changed from \"  \" to 8571190.\n Work Email was changed from \"  \" to honrado-n@itochu.com.ph.\n'),('2012-07-30 01:59:22','1',1,'UPDATE CONTACT INFORMATION','42',' Address Street 1 was changed from \"  \" to Unit 1001 Four Seasons, Toledo St. corner Tordesillas St., Salcedo Village,.\n City was changed from \"  \" to  Makati City .\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-8132422.\n Work Telephone was changed from \"  \" to 8571170.\n Work Email was changed from \"  \" to hoshiai-d@itochu.com.ph.\n'),('2012-07-30 02:14:10','1',1,'UPDATE CONTACT INFORMATION','44',' Address Street 1 was changed from \"  \" to 587 M. DELA FUENTE ST. Manila Philippines.\n Mobile was changed from \"  \" to 0917-8544912.\n Work Telephone was changed from \"  \" to 8571173.\n Work Email was changed from \"  \" to logina-j@itochu.com.ph.\n'),('2012-07-30 02:19:10','1',1,'UPDATE CONTACT INFORMATION','45',' Address Street 1 was changed from \"  \" to #1 Blk 2 Lot 4 St. Francis Homes 2 San Pedro, .\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to  Laguna Philippines.\n Mobile was changed from \"  \" to 0917-8132421.\n Work Telephone was changed from \"  \" to 8571155.\n Work Email was changed from \"  \" to lomugdang-jr@itochu.com.ph.\n'),('2012-07-30 02:26:35','1',1,'UPDATE CONTACT INFORMATION','46',' Address Street 1 was changed from \"  \" to 6741 Taylo St., .\n City was changed from \"  \" to  Makati City .\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-8045020.\n Work Telephone was changed from \"  \" to 8571124.\n Work Email was changed from \"  \" to lopez-am@itochu.com.ph.\n'),('2012-07-30 02:34:57','1',1,'UPDATE CONTACT INFORMATION','47',' Address Street 1 was changed from \"  \" to Block 22 Lot 29 Diamond Crest Village, San Jose Del Monte,  .\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to Bulacan Philippines.\n Zip/Postal Code was changed from \"  \" to 3023.\n Mobile was changed from \"  \" to 0917-5890275.\n Work Telephone was changed from \"  \" to 8571132.\n Work Email was changed from \"  \" to maglasang-m@itochu.com.ph.\n'),('2012-07-30 02:50:34','1',1,'UPDATE CONTACT INFORMATION','49',' Address Street 1 was changed from \"  \" to PHASE 2, SUMATRA ST., SPRINGHOMES SUBD., .\n City was changed from \"  \" to Makati City.\n Country was changed from NULL to Philippines.\n Work Email was changed from \"  \" to ramos-j@itochu.com.ph.\n'),('2012-07-30 02:58:39','1',1,'UPDATE CONTACT INFORMATION','50',' Address Street 1 was changed from \"  \" to LAS-UD, CABA,.\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to  La Union Philippines.\n Mobile was changed from \"  \" to 0917-5281328.\n Work Telephone was changed from \"  \" to 8571142.\n Work Email was changed from \"  \" to ramos-r@itochu.com.ph.\n'),('2012-07-30 03:04:54','1',1,'UPDATE CONTACT INFORMATION','51',' Address Street 1 was changed from \"  \" to NO. 98 SIXTO AVENUE, MAYBUNGA  .\n City was changed from \"  \" to Makati City.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-8842231.\n Work Telephone was changed from \"  \" to 8571142.\n Work Email was changed from \"  \" to rivera-a@itochu.com.ph.\n'),('2012-07-30 03:11:43','1',1,'UPDATE CONTACT INFORMATION','52',' Address Street 1 was changed from \"  \" to L2, B2, P6, OMAN ST., SOUTHVILLE, BI.\n Home Telephone was changed from \"  \" to (049) 241-3380.\n Mobile was changed from \"  \" to 0917-7938799.\n Work Telephone was changed from \"  \" to 8571122.\n Work Email was changed from \"  \" to sabay-a@itochu.com.ph.\n'),('2012-07-30 03:19:17','1',1,'UPDATE CONTACT INFORMATION','53',' Address Street 1 was changed from \"  \" to 20C The Biltmore Condominium.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-8666454.\n Work Telephone was changed from \"  \" to 8571176.\n Work Email was changed from \"  \" to saito-shin@itochu.com.ph.\n'),('2012-07-30 03:24:32','1',1,'UPDATE CONTACT INFORMATION','54',' Address Street 1 was changed from \"  \" to #35 NEPTUNE ST., .\n Address Street 2 was changed from \"  \" to GOLDEN COUNTRY HOMES SUBD., .\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to Batangas .\n Mobile was changed from \"  \" to 0917-5248006.\n Work Telephone was changed from \"  \" to 8571141.\n Work Email was changed from \"  \" to mercado-km@itochu.com.ph.\n'),('2012-07-30 03:30:07','1',1,'UPDATE CONTACT INFORMATION','55',' Address Street 1 was changed from \"  \" to 530 J. LUNA ST., .\n City was changed from \"  \" to  Pasay City Philippines.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0917-5262293 .\n Work Telephone was changed from \"  \" to 8571140.\n Work Email was changed from \"  \" to santos-r@itochu.com.ph.\n'),('2012-07-30 03:38:00','1',1,'UPDATE CONTACT INFORMATION','56',' Address Street 1 was changed from \"  \" to B12, L17 P-3 UPPER BICUTAN, A. BONIFACIO AVE., .\n City was changed from \"  \" to Taguig Philippines.\n Country was changed from NULL to Philippines.\n Mobile was changed from \"  \" to 0921-6731531.\n Work Telephone was changed from \"  \" to 8571185.\n Work Email was changed from \"  \" to sucatre@itochu.com.ph.\n'),('2012-07-30 03:48:54','1',1,'UPDATE CONTACT INFORMATION','57',' Address Street 1 was changed from \"  \" to BLK2 LT 14 CASIMIRO TOWNHOMES,.\n Address Street 2 was changed from \"  \" to HABAY 1 BACOOR,.\n Country was changed from NULL to Philippines.\n State/Province was changed from \"  \" to  Cavite Philippines.\n Mobile was changed from \"  \" to 0917-5511468.\n Work Telephone was changed from \"  \" to 8571180.\n Work Email was changed from \"  \" to santos-mt@itochu.com.ph.\n'),('2012-07-31 01:35:47','1',2,'UPDATE CONTACT INFORMATION','12',' Other Email was changed from \"  \" to charity.adug@yahoo.com.\n'),('2012-07-31 01:36:03','1',3,'UPDATE CONTACT INFORMATION','12',' Other Email was changed from charity.adug@yahoo.com to charity.adug@sigmasoft.com.ph.\n'),('2012-08-02 06:33:07','3',2,'UPDATE CONTACT INFORMATION','23',' State/Province was changed from \"  \" to Luzon.\n');
/*!40000 ALTER TABLE `ohrm_audittrail_pim_contact_info_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_pim_job_title_trail`
--

DROP TABLE IF EXISTS `ohrm_audittrail_pim_job_title_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_pim_job_title_trail` (
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_owner_id` varchar(6) NOT NULL,
  `version_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL,
  `affected_entity_id` varchar(20) NOT NULL DEFAULT '',
  `action_description` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`action_time`,`action_owner_id`,`version_id`,`affected_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_pim_job_title_trail`
--

LOCK TABLES `ohrm_audittrail_pim_job_title_trail` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_pim_job_title_trail` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_pim_job_title_trail` VALUES ('2012-07-26 10:35:11','1',1,'CHANGE JOB DETAILS','1',' Job title was changed from NULL to Sales Officer.'),('2012-07-26 14:00:49','1',1,'CHANGE JOB DETAILS','4',' Job title was changed from NULL to TRADE SPECIALIST.'),('2012-07-26 14:21:03','1',1,'CHANGE JOB DETAILS','5',' Job title was changed from NULL to Sales Assistant.'),('2012-07-27 10:07:24','1',1,'CHANGE JOB DETAILS','6',' Job title was changed from NULL to Section Manager.'),('2012-07-27 10:20:39','1',1,'CHANGE JOB DETAILS','7',' Job title was changed from NULL to Sales Assistant.'),('2012-07-27 10:43:48','1',1,'CHANGE JOB DETAILS','8',' Job title was changed from NULL to Business Development Officer.'),('2012-07-27 14:45:01','1',1,'CHANGE JOB DETAILS','9',' Job title was changed from NULL to OFFICER.'),('2012-07-27 14:57:09','1',1,'CHANGE JOB DETAILS','10',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-28 09:32:26','1',1,'CHANGE JOB DETAILS','12',' Job title was changed from NULL to Deputy Admin Manager.'),('2012-07-28 09:38:33','1',1,'CHANGE JOB DETAILS','11',' Job title was changed from NULL to OFFICER.'),('2012-07-28 10:11:41','1',1,'CHANGE JOB DETAILS','13',' Job title was changed from NULL to Accounting Assistant.'),('2012-07-28 10:21:16','1',1,'CHANGE JOB DETAILS','14',' Job title was changed from NULL to DEPUTY MANAGER.'),('2012-07-28 10:30:40','1',1,'CHANGE JOB DETAILS','15',' Job title was changed from NULL to DEPUTY MANAGER.'),('2012-07-28 10:43:55','1',1,'CHANGE JOB DETAILS','16',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-28 10:54:29','1',1,'CHANGE JOB DETAILS','17',' Job title was changed from NULL to Section Manager.'),('2012-07-28 11:19:54','1',1,'CHANGE JOB DETAILS','18',' Job title was changed from NULL to Assistant Manager.'),('2012-07-28 11:26:18','1',1,'CHANGE JOB DETAILS','19',' Job title was changed from NULL to Section Manager.'),('2012-07-28 11:34:39','1',1,'CHANGE JOB DETAILS','20',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-28 11:41:34','1',1,'CHANGE JOB DETAILS','21',' Job title was changed from NULL to Executive Secretary.'),('2012-07-28 11:51:15','1',1,'CHANGE JOB DETAILS','22',' Job title was changed from NULL to Field Service Engineer.'),('2012-07-28 12:12:58','1',1,'CHANGE JOB DETAILS','23',' Job title was changed from NULL to HR Officer.'),('2012-07-28 12:31:23','1',1,'CHANGE JOB DETAILS','24',' Job title was changed from NULL to Sales Assistant.'),('2012-07-28 12:50:36','1',1,'CHANGE JOB DETAILS','25',' Job title was changed from NULL to Supervisor.'),('2012-07-28 12:56:19','1',1,'CHANGE JOB DETAILS','26',' Job title was changed from NULL to Assistant Manager.'),('2012-07-28 13:04:53','1',1,'CHANGE JOB DETAILS','27',' Job title was changed from NULL to Team Leader.'),('2012-07-28 13:13:42','1',1,'CHANGE JOB DETAILS','28',' Job title was changed from NULL to Field Service Engineer.'),('2012-07-28 13:23:53','1',1,'CHANGE JOB DETAILS','29',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-28 13:27:51','1',1,'CHANGE JOB DETAILS','30',' Job title was changed from NULL to Department Manager.'),('2012-07-28 13:35:14','1',1,'CHANGE JOB DETAILS','31',' Job title was changed from NULL to Systems Administrator.'),('2012-07-28 13:56:38','1',1,'CHANGE JOB DETAILS','32',' Job title was changed from NULL to Field Service Engineer.'),('2012-07-28 14:05:46','1',1,'CHANGE JOB DETAILS','33',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-28 14:25:47','1',1,'CHANGE JOB DETAILS','34',' Job title was changed from NULL to Financial Analyst.'),('2012-07-28 14:43:33','1',1,'CHANGE JOB DETAILS','35',' Job title was changed from NULL to Sales Assistant.'),('2012-07-28 15:04:28','1',1,'CHANGE JOB DETAILS','36',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-28 15:24:23','1',1,'CHANGE JOB DETAILS','37',' Job title was changed from NULL to Section Manager.'),('2012-07-28 16:13:09','1',1,'CHANGE JOB DETAILS','38',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-28 16:46:07','1',1,'CHANGE JOB DETAILS','39',' Job title was changed from NULL to Section Manager.'),('2012-07-28 17:42:19','1',1,'CHANGE JOB DETAILS','40',' Job title was changed from NULL to TRADE SPECIALIST.'),('2012-07-30 01:41:25','1',1,'CHANGE JOB DETAILS','41',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-30 01:57:21','1',1,'CHANGE JOB DETAILS','42',' Job title was changed from NULL to Division Manager.'),('2012-07-30 02:03:43','1',1,'CHANGE JOB DETAILS','43',' Job title was changed from NULL to Office Trainee.'),('2012-07-30 02:07:41','1',1,'CHANGE JOB DETAILS','44',' Job title was changed from NULL to Sales Officer.'),('2012-07-30 02:16:48','1',1,'CHANGE JOB DETAILS','45',' Job title was changed from NULL to Business Development Officer.'),('2012-07-30 02:22:29','1',1,'CHANGE JOB DETAILS','46',' Job title was changed from NULL to Sales Officer.'),('2012-07-30 02:31:14','1',1,'CHANGE JOB DETAILS','47',' Job title was changed from NULL to Sales Assistant.'),('2012-07-30 02:39:49','1',1,'CHANGE JOB DETAILS','48',' Job title was changed from NULL to Documentation Assistant.'),('2012-07-30 02:46:18','1',1,'CHANGE JOB DETAILS','49',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-30 02:57:15','1',1,'CHANGE JOB DETAILS','50',' Job title was changed from NULL to Field Service Engineer.'),('2012-07-30 03:03:57','1',1,'CHANGE JOB DETAILS','51',' Job title was changed from NULL to Field Service Engineer.'),('2012-07-30 03:10:45','1',1,'CHANGE JOB DETAILS','52',' Job title was changed from NULL to Senior Assistant.'),('2012-07-30 03:18:16','1',1,'CHANGE JOB DETAILS','53',' Job title was changed from NULL to Office Trainee.'),('2012-07-30 03:23:18','1',1,'CHANGE JOB DETAILS','54',' Job title was changed from NULL to Sales Officer.'),('2012-07-30 03:29:12','1',1,'CHANGE JOB DETAILS','55',' Job title was changed from NULL to Section Manager.'),('2012-07-30 03:36:34','1',1,'CHANGE JOB DETAILS','56',' Job title was changed from NULL to Logistics Assistant.'),('2012-07-30 03:47:21','1',1,'CHANGE JOB DETAILS','57',' Job title was changed from NULL to Assistant Manager.');
/*!40000 ALTER TABLE `ohrm_audittrail_pim_job_title_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_pim_personal_details_trail`
--

DROP TABLE IF EXISTS `ohrm_audittrail_pim_personal_details_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_pim_personal_details_trail` (
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_owner_id` varchar(6) NOT NULL,
  `version_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL,
  `affected_entity_id` varchar(20) NOT NULL DEFAULT '',
  `action_description` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`action_time`,`action_owner_id`,`version_id`,`affected_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_pim_personal_details_trail`
--

LOCK TABLES `ohrm_audittrail_pim_personal_details_trail` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_pim_personal_details_trail` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_pim_personal_details_trail` VALUES ('2012-07-26 09:53:35','1',1,'ADD EMPLOYEE','1',' New employee record was added to the system. (Name: GRACE CIELO ACOSTA)'),('2012-07-26 13:55:25','1',1,'ADD EMPLOYEE','4',' New employee record was added to the system. (Name: MARY ROSE AFUNGGOL)'),('2012-07-26 14:19:32','1',1,'ADD EMPLOYEE','5',' New employee record was added to the system. (Name: SHIELLA AGUINALDO)'),('2012-07-27 09:51:03','1',1,'ADD EMPLOYEE','6',' New employee record was added to the system. (Name: SONIA APALISOK)'),('2012-07-27 10:11:45','1',1,'ADD EMPLOYEE','7',' New employee record was added to the system. (Name: ANA ARQUINEZ)'),('2012-07-27 10:23:07','1',1,'ADD EMPLOYEE','8',' New employee record was added to the system. (Name: ALEXANDER ASUNCION)'),('2012-07-27 10:45:02','1',1,'ADD EMPLOYEE','9',' New employee record was added to the system. (Name: NERINE AZORES)'),('2012-07-27 14:49:05','1',1,'ADD EMPLOYEE','10',' New employee record was added to the system. (Name: ROWENA CANLAS)'),('2012-07-27 15:01:17','1',1,'ADD EMPLOYEE','11',' New employee record was added to the system. (Name: JANICE SYBEL CANO)'),('2012-07-28 09:24:48','1',1,'ADD EMPLOYEE','12',' New employee record was added to the system. (Name: JOSEPHINE CANO)'),('2012-07-28 09:57:17','1',1,'ADD EMPLOYEE','13',' New employee record was added to the system. (Name: SHEILA BALUYOT)'),('2012-07-28 10:14:13','1',1,'ADD EMPLOYEE','14',' New employee record was added to the system. (Name: STUART CHUA)'),('2012-07-28 10:27:06','1',1,'ADD EMPLOYEE','15',' New employee record was added to the system. (Name: ERWIN CO)'),('2012-07-28 10:33:01','1',1,'ADD EMPLOYEE','16',' New employee record was added to the system. (Name: JOSAN GRACE COLANDOG)'),('2012-07-28 10:47:57','1',1,'ADD EMPLOYEE','17',' New employee record was added to the system. (Name: ROBERTO ZAMORA)'),('2012-07-28 10:56:27','1',1,'ADD EMPLOYEE','18',' New employee record was added to the system. (Name: EVANGELYN CUEVO)'),('2012-07-28 11:22:47','1',1,'ADD EMPLOYEE','19',' New employee record was added to the system. (Name: JAMES YONG LOOK)'),('2012-07-28 11:27:15','1',1,'ADD EMPLOYEE','20',' New employee record was added to the system. (Name: ROCHA MAE VILLESENDA)'),('2012-07-28 11:37:05','1',1,'ADD EMPLOYEE','21',' New employee record was added to the system. (Name: LAILANI TRINIDAD)'),('2012-07-28 11:43:31','1',1,'ADD EMPLOYEE','22',' New employee record was added to the system. (Name: MARK ANTHONY DE GUZMAN)'),('2012-07-28 12:00:05','1',1,'ADD EMPLOYEE','23',' New employee record was added to the system. (Name: JOYCE PEREZ)'),('2012-07-28 12:15:19','1',1,'ADD EMPLOYEE','24',' New employee record was added to the system. (Name: GIRLIE PABALAN)'),('2012-07-28 12:36:56','1',1,'ADD EMPLOYEE','25',' New employee record was added to the system. (Name: DONNA DIVINE ORNUM)'),('2012-07-28 12:52:54','1',1,'ADD EMPLOYEE','26',' New employee record was added to the system. (Name: JOHN LAWRENCE ONG)'),('2012-07-28 12:59:10','1',1,'ADD EMPLOYEE','27',' New employee record was added to the system. (Name: JENNIFER MONTANO)'),('2012-07-28 13:10:02','1',1,'ADD EMPLOYEE','28',' New employee record was added to the system. (Name: ROMMEL MENDOZA)'),('2012-07-28 13:14:58','1',1,'ADD EMPLOYEE','29',' New employee record was added to the system. (Name: CRISTINA DE LEON)'),('2012-07-28 13:26:41','1',1,'ADD EMPLOYEE','30',' New employee record was added to the system. (Name: KENICHI HISATOMI)'),('2012-07-28 13:33:59','1',1,'ADD EMPLOYEE','31',' New employee record was added to the system. (Name: ARISTEO TOLENTINO)'),('2012-07-28 13:47:46','1',1,'ADD EMPLOYEE','32',' New employee record was added to the system. (Name: NOEL SOLIMEN)'),('2012-07-28 14:04:47','1',1,'ADD EMPLOYEE','33',' New employee record was added to the system. (Name: JEANETH DOLOR)'),('2012-07-28 14:24:34','1',1,'ADD EMPLOYEE','34',' New employee record was added to the system. (Name: DONNA MARIE SEBELDIA)'),('2012-07-28 14:38:19','1',1,'ADD EMPLOYEE','35',' New employee record was added to the system. (Name: MARIA GRACIA DOMALANTA)'),('2012-07-28 15:00:28','1',1,'ADD EMPLOYEE','36',' New employee record was added to the system. (Name: SYDNEY DUARTE)'),('2012-07-28 15:13:43','1',1,'ADD EMPLOYEE','37',' New employee record was added to the system. (Name: ALLAN FERNANDO REI ESPINO)'),('2012-07-28 16:04:33','1',1,'ADD EMPLOYEE','38',' New employee record was added to the system. (Name: ELIZA GARROVILLO)'),('2012-07-28 16:39:18','1',1,'ADD EMPLOYEE','39',' New employee record was added to the system. (Name: WINSTON GO)'),('2012-07-28 17:41:15','1',1,'ADD EMPLOYEE','40',' New employee record was added to the system. (Name: FRANCIS GO)'),('2012-07-30 01:38:47','1',1,'ADD EMPLOYEE','41',' New employee record was added to the system. (Name: NERIELYN HONRADO)'),('2012-07-30 01:56:24','1',1,'ADD EMPLOYEE','42',' New employee record was added to the system. (Name: DAI HOSHIAI)'),('2012-07-30 02:02:39','1',1,'ADD EMPLOYEE','43',' New employee record was added to the system. (Name: KARLO ANDREI HULDONG)'),('2012-07-30 02:06:09','1',1,'ADD EMPLOYEE','44',' New employee record was added to the system. (Name: JENNIFER LOGINA)'),('2012-07-30 02:15:46','1',1,'ADD EMPLOYEE','45',' New employee record was added to the system. (Name: JOHN RAY LOMUGDANG)'),('2012-07-30 02:21:18','1',1,'ADD EMPLOYEE','46',' New employee record was added to the system. (Name: ANNE MARIEL LOPEZ)'),('2012-07-30 02:30:13','1',1,'ADD EMPLOYEE','47',' New employee record was added to the system. (Name: MAMERTA MAGLASANG)'),('2012-07-30 02:38:59','1',1,'ADD EMPLOYEE','48',' New employee record was added to the system. (Name: MARIE BERCHEL PINGOY)'),('2012-07-30 02:45:04','1',1,'ADD EMPLOYEE','49',' New employee record was added to the system. (Name: JUVI RAMOS)'),('2012-07-30 02:56:27','1',1,'ADD EMPLOYEE','50',' New employee record was added to the system. (Name: ROLDIE VIC RAMOS)'),('2012-07-30 03:03:01','1',1,'ADD EMPLOYEE','51',' New employee record was added to the system. (Name: ALLAN RIVERA)'),('2012-07-30 03:09:50','1',1,'ADD EMPLOYEE','52',' New employee record was added to the system. (Name: AMELIA SABAY)'),('2012-07-30 03:17:17','1',1,'ADD EMPLOYEE','53',' New employee record was added to the system. (Name: SHINYA SAITO)'),('2012-07-30 03:22:30','1',1,'ADD EMPLOYEE','54',' New employee record was added to the system. (Name: KRISTIN MAE SAN MIGUEL)'),('2012-07-30 03:28:27','1',1,'ADD EMPLOYEE','55',' New employee record was added to the system. (Name: RENATO SANTOS)'),('2012-07-30 03:34:49','1',1,'ADD EMPLOYEE','56',' New employee record was added to the system. (Name: RICHEL SARMIENTO)'),('2012-07-30 03:43:49','1',2,'CHANGE PERSONAL DETAILS','12',' Last name was changed from CANO to CELMAR.\n'),('2012-07-30 03:46:35','1',1,'ADD EMPLOYEE','57',' New employee record was added to the system. (Name: MARIA THERESA SANTOS)'),('2012-08-06 01:29:00','1',1,'ADD EMPLOYEE','58',' New employee record was added to the system. (Name: Rosel Uy)'),('2012-08-06 01:40:37','1',1,'CHANGE CANDIDATE VACANCY STATUS','59',' New employee record was added to the system. (Name: Orange Orange)'),('2012-08-06 11:35:47','1',2,'CHANGE PERSONAL DETAILS','1',' First name was changed from GRACE CIELO to GRACE CIELO555.\n'),('2012-08-07 04:17:10','1',3,'CHANGE PERSONAL DETAILS','1',' First name was changed from GRACE CIELO555 to GRACE CIELO.\n');
/*!40000 ALTER TABLE `ohrm_audittrail_pim_personal_details_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_pim_reportto_trail`
--

DROP TABLE IF EXISTS `ohrm_audittrail_pim_reportto_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_pim_reportto_trail` (
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_owner_id` varchar(6) NOT NULL,
  `version_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL,
  `affected_entity_id` varchar(20) NOT NULL DEFAULT '',
  `action_description` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`action_time`,`action_owner_id`,`version_id`,`affected_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_pim_reportto_trail`
--

LOCK TABLES `ohrm_audittrail_pim_reportto_trail` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_pim_reportto_trail` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_pim_reportto_trail` VALUES ('2012-07-30 09:49:52','1',1,'UPDATE REPORT TO DETAILS','23',' New supervisor was added. (JOSEPHINE CELMAR - Reporting Method: Direct)');
/*!40000 ALTER TABLE `ohrm_audittrail_pim_reportto_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_pim_salary_trail`
--

DROP TABLE IF EXISTS `ohrm_audittrail_pim_salary_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_pim_salary_trail` (
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_owner_id` varchar(6) NOT NULL,
  `version_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL,
  `affected_entity_id` varchar(20) NOT NULL DEFAULT '',
  `action_description` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`action_time`,`action_owner_id`,`version_id`,`affected_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_pim_salary_trail`
--

LOCK TABLES `ohrm_audittrail_pim_salary_trail` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_pim_salary_trail` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_pim_salary_trail` VALUES ('2012-07-26 10:33:24','1',1,'UPDATE SALARY','1','New salary record was added. (Salary Component: 12,400.00, Basic Pay: {encrypted:12400} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2012-05-21, Comments: )'),('2012-07-26 13:51:17','1',2,'UPDATE SALARY','1',' Salary component was changed from 12,400.00 to 0.\n Basic pay was changed from {encrypted:12400} to {encrypted:0}.\n'),('2012-07-26 14:08:34','1',1,'UPDATE SALARY','4','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-07-12, Comments: )'),('2012-07-27 10:08:26','1',1,'UPDATE SALARY','6','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 1991-09-09, Comments: )'),('2012-07-27 10:21:38','1',1,'UPDATE SALARY','7','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-04-01, Comments: )'),('2012-07-27 14:46:43','1',1,'UPDATE SALARY','9','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2006-11-06, Comments: )'),('2012-07-27 14:59:14','1',1,'UPDATE SALARY','10','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-12-21, Comments: )'),('2012-07-28 09:33:19','1',1,'UPDATE SALARY','12','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Weekly, COLA: 0.00, Status: Active, Effective Date: 1991-04-01, Comments: )'),('2012-07-28 09:39:32','1',1,'UPDATE SALARY','11','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2012-04-01, Comments: )'),('2012-07-28 10:12:43','1',1,'UPDATE SALARY','13','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2006-11-06, Comments: )'),('2012-07-28 10:22:17','1',1,'UPDATE SALARY','14','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 1989-09-09, Comments: )'),('2012-07-28 10:31:35','1',1,'UPDATE SALARY','15','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-10-01, Comments: )'),('2012-07-28 10:46:35','1',1,'UPDATE SALARY','16','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-10-01, Comments: )'),('2012-07-28 10:55:45','1',1,'UPDATE SALARY','17','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 1992-01-06, Comments: )'),('2012-07-28 11:20:41','1',1,'UPDATE SALARY','18','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2008-02-11, Comments: )'),('2012-07-28 11:35:41','1',1,'UPDATE SALARY','20','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-08-24, Comments: )'),('2012-07-28 11:43:04','1',1,'UPDATE SALARY','21','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2000-01-04, Comments: )'),('2012-07-28 11:52:29','1',1,'UPDATE SALARY','22','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-10-01, Comments: )'),('2012-07-28 12:13:41','1',1,'UPDATE SALARY','23','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2008-01-16, Comments: )'),('2012-07-28 12:32:15','1',1,'UPDATE SALARY','24','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2007-04-01, Comments: )'),('2012-07-28 12:51:29','1',1,'UPDATE SALARY','25','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2007-09-04, Comments: )'),('2012-07-28 12:57:23','1',1,'UPDATE SALARY','26','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2006-11-06, Comments: )'),('2012-07-28 13:05:36','1',1,'UPDATE SALARY','27','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2007-09-24, Comments: )'),('2012-07-28 13:33:14','1',1,'UPDATE SALARY','30','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-02-02, Comments: )'),('2012-07-28 13:39:16','1',1,'UPDATE SALARY','31','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2000-04-01, Comments: )'),('2012-07-28 14:00:48','1',1,'UPDATE SALARY','32','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-10-01, Comments: )'),('2012-07-28 14:17:11','1',1,'UPDATE SALARY','33','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-04-01, Comments: )'),('2012-07-28 14:33:56','1',1,'UPDATE SALARY','34','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2008-01-06, Comments: )'),('2012-07-28 14:35:22','1',2,'UPDATE SALARY','34',' Effective date was changed from 2008-01-06 to 2008-01-16.\n'),('2012-07-28 14:51:05','1',1,'UPDATE SALARY','35','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2000-06-01, Comments: )'),('2012-07-28 15:12:14','1',1,'UPDATE SALARY','36','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2011-08-11, Comments: )'),('2012-07-28 15:46:06','1',1,'UPDATE SALARY','37','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-10-01, Comments: )'),('2012-07-28 16:26:08','1',1,'UPDATE SALARY','38','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2011-10-18, Comments: )'),('2012-07-28 17:39:47','1',1,'UPDATE SALARY','39','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2012-01-16, Comments: )'),('2012-07-28 17:47:14','1',1,'UPDATE SALARY','40','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2012-05-22, Comments: )'),('2012-07-30 01:54:19','1',1,'UPDATE SALARY','41','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2011-06-01, Comments: )'),('2012-07-30 02:01:46','1',1,'UPDATE SALARY','42','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2009-04-01, Comments: )'),('2012-07-30 02:12:47','1',1,'UPDATE SALARY','44','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2002-03-07, Comments: )'),('2012-07-30 02:20:10','1',1,'UPDATE SALARY','45','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-07-12, Comments: )'),('2012-07-30 02:27:29','1',1,'UPDATE SALARY','46','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-07-14, Comments: )'),('2012-07-30 02:31:50','1',1,'UPDATE SALARY','47','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2012-04-01, Comments: )'),('2012-07-30 02:42:34','1',1,'UPDATE SALARY','48','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2011-10-11, Comments: )'),('2012-07-30 02:53:57','1',1,'UPDATE SALARY','49','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2008-06-23, Comments: )'),('2012-07-30 03:02:04','1',1,'UPDATE SALARY','50','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2012-01-01, Comments: )'),('2012-07-30 03:08:47','1',1,'UPDATE SALARY','51','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-10-01, Comments: )'),('2012-07-30 03:16:26','1',1,'UPDATE SALARY','52','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 1991-09-03, Comments: )'),('2012-07-30 03:21:36','1',1,'UPDATE SALARY','53','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2012-06-01, Comments: )'),('2012-07-30 03:27:29','1',1,'UPDATE SALARY','54','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2004-05-10, Comments: )'),('2012-07-30 03:33:19','1',1,'UPDATE SALARY','55','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2010-10-01, Comments: )'),('2012-07-30 03:40:54','1',1,'UPDATE SALARY','56','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2009-11-03, Comments: )'),('2012-07-30 04:16:35','1',1,'UPDATE SALARY','57','New salary record was added. (Salary Component: 0, Basic Pay: {encrypted:0} Philippine Peso, Pay Grade: NULL, Salary Rate: Monthly, Pay Period: Semi Monthly, COLA: 0.00, Status: Active, Effective Date: 2007-09-04, Comments: )'),('2012-08-06 07:48:45','1',3,'UPDATE SALARY','1',' Salary component was changed from 0 to Basic Pay.\n Basic pay was changed from {encrypted:0} to {encrypted:25000}.\n');
/*!40000 ALTER TABLE `ohrm_audittrail_pim_salary_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_pim_work_experience_trail`
--

DROP TABLE IF EXISTS `ohrm_audittrail_pim_work_experience_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_pim_work_experience_trail` (
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_owner_id` varchar(6) NOT NULL,
  `version_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL,
  `affected_entity_id` varchar(20) NOT NULL DEFAULT '',
  `action_description` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`action_time`,`action_owner_id`,`version_id`,`affected_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_pim_work_experience_trail`
--

LOCK TABLES `ohrm_audittrail_pim_work_experience_trail` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_pim_work_experience_trail` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_audittrail_pim_work_experience_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_recruitment_job_vacancy_trail`
--

DROP TABLE IF EXISTS `ohrm_audittrail_recruitment_job_vacancy_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_recruitment_job_vacancy_trail` (
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_owner_id` varchar(6) NOT NULL,
  `version_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(40) NOT NULL,
  `affected_entity_id` varchar(20) NOT NULL DEFAULT '',
  `action_description` text,
  PRIMARY KEY (`action_time`,`action_owner_id`,`version_id`,`affected_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_recruitment_job_vacancy_trail`
--

LOCK TABLES `ohrm_audittrail_recruitment_job_vacancy_trail` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_recruitment_job_vacancy_trail` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_recruitment_job_vacancy_trail` VALUES ('2012-07-31 05:37:13','1',1,'ADD JOB VACANCY','1',' New job vacancy was added. (Hiring Manager: JOYCE PEREZ, Vacancy Name: Team Leader, Status: Active, No. of Positions: 1, Publish in RSS feed: Active, Description: \"  \")');
/*!40000 ALTER TABLE `ohrm_audittrail_recruitment_job_vacancy_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_reference_archive`
--

DROP TABLE IF EXISTS `ohrm_audittrail_reference_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_reference_archive` (
  `table` varchar(50) NOT NULL DEFAULT '',
  `reference_key` varchar(10) NOT NULL DEFAULT '',
  `record_descriptor` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`table`,`reference_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_reference_archive`
--

LOCK TABLES `ohrm_audittrail_reference_archive` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_reference_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_audittrail_reference_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_section`
--

DROP TABLE IF EXISTS `ohrm_audittrail_section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_section` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `description` varchar(50) NOT NULL,
  `affected_entity` varchar(25) NOT NULL,
  `module_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_section`
--

LOCK TABLES `ohrm_audittrail_section` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_section` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_section` VALUES (1,'PersonalDetails','Personal Details','Employee',1),(2,'JobTitle','Job Title','Employee',1),(3,'Salary','Salary','Employee',1),(4,'ContactInformation','Contact Information','Employee',1),(5,'ReportTo','Supervisor/Subordinate','Subordinate',1),(6,'WorkExperience','Work Experience','Employee',1),(7,'JobVacancy','Job Vacancy','Job Vacancy',2);
/*!40000 ALTER TABLE `ohrm_audittrail_section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_section_actions`
--

DROP TABLE IF EXISTS `ohrm_audittrail_section_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_section_actions` (
  `section_id` smallint(6) NOT NULL DEFAULT '0',
  `action` varchar(30) NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`section_id`,`action`),
  UNIQUE KEY `action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_section_actions`
--

LOCK TABLES `ohrm_audittrail_section_actions` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_section_actions` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_section_actions` VALUES (1,'ADD EMPLOYEE','Add Employee'),(1,'CHANGE PERSONAL DETAILS','Change Personal Details'),(1,'DELETE EMPLOYEE','Delete Employee'),(2,'CHANGE JOB DETAILS','Change Job Details'),(3,'DELETE SALARY','Delete Salary'),(3,'UPDATE SALARY','Update Salary'),(4,'UPDATE CONTACT INFORMATION','Update Contact Information'),(5,'DELETE SUPERVISOR','Delete Supervisor'),(5,'UPDATE REPORT TO DETAILS','Update Report to Details'),(6,'ADD WORK EXPERIENCE','Add Work Experience'),(6,'CHANGE WORK EXPERIENCE','Change Work Experience'),(6,'DELETE WORK EXPERIENCE','Delete Work Experience'),(7,'ADD JOB VACANCY','Add Job Vacancy'),(7,'CHANGE JOB VACANCY','Change Job Vacancy'),(7,'DELETE JOB VACANCY','Delete Job Vacancy');
/*!40000 ALTER TABLE `ohrm_audittrail_section_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_audittrail_static_reference`
--

DROP TABLE IF EXISTS `ohrm_audittrail_static_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_audittrail_static_reference` (
  `table` varchar(50) NOT NULL DEFAULT '',
  `field` varchar(50) NOT NULL DEFAULT '',
  `key_value` varchar(10) NOT NULL DEFAULT '',
  `reference_value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`table`,`field`,`key_value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_audittrail_static_reference`
--

LOCK TABLES `ohrm_audittrail_static_reference` WRITE;
/*!40000 ALTER TABLE `ohrm_audittrail_static_reference` DISABLE KEYS */;
INSERT INTO `ohrm_audittrail_static_reference` VALUES ('hs_hr_emp_basicsalary','payperiod_code','1','Weekly'),('hs_hr_emp_basicsalary','payperiod_code','2','Bi Weekly'),('hs_hr_emp_basicsalary','payperiod_code','3','Semi Monthly'),('hs_hr_emp_basicsalary','payperiod_code','4','Monthly'),('hs_hr_emp_basicsalary','payperiod_code','5','Monthly on first pay of month'),('hs_hr_emp_basicsalary','payperiod_code','6','Hourly'),('hs_hr_emp_basicsalary','salary_rate','1','Hourly'),('hs_hr_emp_basicsalary','salary_rate','2','Daily'),('hs_hr_emp_basicsalary','salary_rate','3','Weekly'),('hs_hr_emp_basicsalary','salary_rate','4','Bi-Weekly'),('hs_hr_emp_basicsalary','salary_rate','5','Monthly'),('hs_hr_emp_basicsalary','salary_rate','6','Annual'),('hs_hr_emp_basicsalary','status','0','Inactive'),('hs_hr_emp_basicsalary','status','1','Active'),('ohrm_job_vacancy','published_in_feed','0','Inactive'),('ohrm_job_vacancy','published_in_feed','1','Active'),('ohrm_job_vacancy','status','1','Active'),('ohrm_job_vacancy','status','2','Inactive');
/*!40000 ALTER TABLE `ohrm_audittrail_static_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_available_group_field`
--

DROP TABLE IF EXISTS `ohrm_available_group_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_available_group_field` (
  `report_group_id` bigint(20) NOT NULL,
  `group_field_id` bigint(20) NOT NULL,
  PRIMARY KEY (`report_group_id`,`group_field_id`),
  KEY `report_group_id` (`report_group_id`),
  KEY `group_field_id` (`group_field_id`),
  CONSTRAINT `ohrm_available_group_field_ibfk_1` FOREIGN KEY (`group_field_id`) REFERENCES `ohrm_group_field` (`group_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_available_group_field`
--

LOCK TABLES `ohrm_available_group_field` WRITE;
/*!40000 ALTER TABLE `ohrm_available_group_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_available_group_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_composite_display_field`
--

DROP TABLE IF EXISTS `ohrm_composite_display_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_composite_display_field` (
  `composite_display_field_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_group_id` bigint(20) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_alias` varchar(255) DEFAULT NULL,
  `is_sortable` varchar(10) NOT NULL,
  `sort_order` varchar(255) DEFAULT NULL,
  `sort_field` varchar(255) DEFAULT NULL,
  `element_type` varchar(255) NOT NULL,
  `element_property` varchar(1000) NOT NULL,
  `width` varchar(255) NOT NULL,
  `is_exportable` varchar(10) DEFAULT NULL,
  `text_alignment_style` varchar(20) DEFAULT NULL,
  `is_value_list` tinyint(1) NOT NULL DEFAULT '0',
  `display_field_group_id` int(10) unsigned DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `is_meta` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`composite_display_field_id`),
  KEY `report_group_id` (`report_group_id`),
  KEY `display_field_group_id` (`display_field_group_id`),
  CONSTRAINT `ohrm_composite_display_field_ibfk_1` FOREIGN KEY (`report_group_id`) REFERENCES `ohrm_report_group` (`report_group_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_composite_display_field_ibfk_2` FOREIGN KEY (`display_field_group_id`) REFERENCES `ohrm_display_field_group` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_composite_display_field`
--

LOCK TABLES `ohrm_composite_display_field` WRITE;
/*!40000 ALTER TABLE `ohrm_composite_display_field` DISABLE KEYS */;
INSERT INTO `ohrm_composite_display_field` VALUES (1,1,'CONCAT(hs_hr_employee.emp_firstname, \" \" ,hs_hr_employee.emp_lastname)','Employee Name','employeeName','false',NULL,NULL,'label','<xml><getter>employeeName</getter></xml>','300','0',NULL,0,NULL,'Deleted Employee',0,0),(2,1,'CONCAT(ohrm_customer.name, \" - \" ,ohrm_project.name)','Project Name','projectname','false',NULL,NULL,'label','<xml><getter>projectname</getter></xml>','300','0',NULL,0,NULL,NULL,0,0);
/*!40000 ALTER TABLE `ohrm_composite_display_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_customer`
--

DROP TABLE IF EXISTS `ohrm_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_customer`
--

LOCK TABLES `ohrm_customer` WRITE;
/*!40000 ALTER TABLE `ohrm_customer` DISABLE KEYS */;
INSERT INTO `ohrm_customer` VALUES (1,'Betson','',0);
/*!40000 ALTER TABLE `ohrm_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_display_field`
--

DROP TABLE IF EXISTS `ohrm_display_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_display_field` (
  `display_field_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_group_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_alias` varchar(255) DEFAULT NULL,
  `is_sortable` varchar(10) NOT NULL,
  `sort_order` varchar(255) DEFAULT NULL,
  `sort_field` varchar(255) DEFAULT NULL,
  `element_type` varchar(255) NOT NULL,
  `element_property` varchar(1000) NOT NULL,
  `width` varchar(255) NOT NULL,
  `is_exportable` varchar(10) DEFAULT NULL,
  `text_alignment_style` varchar(20) DEFAULT NULL,
  `is_value_list` tinyint(1) NOT NULL DEFAULT '0',
  `display_field_group_id` int(10) unsigned DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `is_meta` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`display_field_id`),
  KEY `report_group_id` (`report_group_id`),
  KEY `display_field_group_id` (`display_field_group_id`),
  CONSTRAINT `ohrm_display_field_ibfk_1` FOREIGN KEY (`report_group_id`) REFERENCES `ohrm_report_group` (`report_group_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_display_field_ibfk_2` FOREIGN KEY (`display_field_group_id`) REFERENCES `ohrm_display_field_group` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_display_field`
--

LOCK TABLES `ohrm_display_field` WRITE;
/*!40000 ALTER TABLE `ohrm_display_field` DISABLE KEYS */;
INSERT INTO `ohrm_display_field` VALUES (1,1,'ohrm_project.name','Project Name','projectname','false',NULL,NULL,'label','<xml><getter>projectname</getter></xml>','200','0',NULL,0,NULL,NULL,0,0),(2,1,'ohrm_project_activity.name','Activity Name','activityname','false',NULL,NULL,'link','<xml><labelGetter>activityname</labelGetter><placeholderGetters><id>activity_id</id><total>totalduration</total><projectId>projectId</projectId><from>fromDate</from><to>toDate</to><approved>onlyIncludeApprovedTimesheets</approved></placeholderGetters><urlPattern>../../displayProjectActivityDetailsReport?reportId=3#activityId={id}#total={total}#from={from}#to={to}#projectId={projectId}#onlyIncludeApprovedTimesheets={approved}</urlPattern></xml>','200','0',NULL,0,NULL,NULL,0,0),(3,1,'ohrm_project_activity.project_id','Project Id',NULL,'false',NULL,NULL,'label','<xml><getter>project_id</getter></xml>','75','0','right',0,NULL,NULL,0,1),(4,1,'ohrm_project_activity.activity_id','Activity Id',NULL,'false',NULL,NULL,'label','<xml><getter>activity_id</getter></xml>','75','0','right',0,NULL,NULL,0,1),(5,1,'ohrm_timesheet_item.duration','Time (hours)',NULL,'false',NULL,NULL,'label','<xml><getter>duration</getter></xml>','75','0','right',0,NULL,NULL,0,0),(6,1,'hs_hr_employee.emp_firstname','Employee First Name',NULL,'false',NULL,NULL,'label','<xml><getter>emp_firstname</getter></xml>','200','0',NULL,0,NULL,NULL,0,0),(7,1,'hs_hr_employee.emp_lastname','Employee Last Name',NULL,'false',NULL,NULL,'label','<xml><getter>emp_lastname</getter></xml>','200','0',NULL,0,NULL,NULL,0,0),(8,1,'ohrm_project_activity.name','Activity Name','activityname','false',NULL,NULL,'label','<xml><getter>activityname</getter></xml>','200','0',NULL,0,NULL,NULL,0,0),(9,3,'hs_hr_employee.employee_id','Employee Id','employeeId','false',NULL,NULL,'label','<xml><getter>employeeId</getter></xml>','100','0',NULL,0,1,'---',0,0),(10,3,'hs_hr_employee.emp_lastname','Employee Last Name','employeeLastname','false',NULL,NULL,'label','<xml><getter>employeeLastname</getter></xml>','200','0',NULL,0,1,'---',0,0),(11,3,'hs_hr_employee.emp_firstname','Employee First Name','employeeFirstname','false',NULL,NULL,'label','<xml><getter>employeeFirstname</getter></xml>','200','0',NULL,0,1,'---',0,0),(12,3,'hs_hr_employee.emp_middle_name','Employee Middle Name','employeeMiddlename','false',NULL,NULL,'label','<xml><getter>employeeMiddlename</getter></xml>','200','0',NULL,0,1,'---',0,0),(13,3,'hs_hr_employee.emp_birthday','Date of Birth','empBirthday','false',NULL,NULL,'label','<xml><getter>empBirthday</getter></xml>','100','0',NULL,0,1,'---',0,0),(14,3,'ohrm_nationality.name','Nationality','nationality','false',NULL,NULL,'label','<xml><getter>nationality</getter></xml>','200','0',NULL,0,1,'---',0,0),(15,3,'CASE hs_hr_employee.emp_gender WHEN 1 THEN \"Male\" WHEN 2 THEN \"Female\" WHEN 3 THEN \"Other\" END','Gender','empGender','false',NULL,NULL,'label','<xml><getter>empGender</getter></xml>','80','0',NULL,0,1,'---',0,0),(17,3,'hs_hr_employee.emp_marital_status','Marital Status','maritalStatus','false',NULL,NULL,'label','<xml><getter>maritalStatus</getter></xml>','100','0',NULL,0,1,'---',0,0),(18,3,'hs_hr_employee.emp_dri_lice_num','Driver License Number','driversLicenseNumber','false',NULL,NULL,'label','<xml><getter>driversLicenseNumber</getter></xml>','240','0',NULL,0,1,'---',0,0),(19,3,'hs_hr_employee.emp_dri_lice_exp_date','License Expiry Date','licenseExpiryDate','false',NULL,NULL,'label','<xml><getter>licenseExpiryDate</getter></xml>','135','0',NULL,0,1,'---',0,0),(20,3,'CONCAT_WS(\", \", NULLIF(hs_hr_employee.emp_street1, \"\"), NULLIF(hs_hr_employee.emp_street2, \"\"), NULLIF(hs_hr_employee.city_code, \"\"), NULLIF(hs_hr_employee.provin_code,\"\"), NULLIF(hs_hr_employee.emp_zipcode,\"\"), NULLIF(hs_hr_country.cou_name,\"\"))','Address','address','false',NULL,NULL,'label','<xml><getter>address</getter></xml>','200','0',NULL,0,2,'---',0,0),(21,3,'hs_hr_employee.emp_hm_telephone','Home Telephone','homeTelephone','false',NULL,NULL,'label','<xml><getter>homeTelephone</getter></xml>','130','0',NULL,0,2,'---',0,0),(22,3,'hs_hr_employee.emp_mobile','Mobile','mobile','false',NULL,NULL,'label','<xml><getter>mobile</getter></xml>','100','0',NULL,0,2,'---',0,0),(23,3,'hs_hr_employee.emp_work_telephone','Work Telephone','workTelephone','false',NULL,NULL,'label','<xml><getter>workTelephone</getter></xml>','100','0',NULL,0,2,'---',0,0),(24,3,'hs_hr_employee.emp_work_email','Work Email','workEmail','false',NULL,NULL,'label','<xml><getter>workEmail</getter></xml>','200','0',NULL,0,2,'---',0,0),(25,3,'hs_hr_employee.emp_oth_email','Other Email','otherEmail','false',NULL,NULL,'label','<xml><getter>otherEmail</getter></xml>','200','0',NULL,0,2,'---',0,0),(26,3,'hs_hr_emp_emergency_contacts.eec_name','Name','ecname','false',NULL,NULL,'label','<xml><getter>ecname</getter></xml>','200','0',NULL,1,3,'---',0,0),(27,3,'hs_hr_emp_emergency_contacts.eec_home_no','Home Telephone','ecHomeTelephone','false',NULL,NULL,'label','<xml><getter>ecHomeTelephone</getter></xml>','130','0',NULL,1,3,'---',0,0),(28,3,'hs_hr_emp_emergency_contacts.eec_office_no','Work Telephone','ecWorkTelephone','false',NULL,NULL,'label','<xml><getter>ecWorkTelephone</getter></xml>','100','0',NULL,1,3,'---',0,0),(29,3,'hs_hr_emp_emergency_contacts.eec_relationship','Relationship','ecRelationship','false',NULL,NULL,'label','<xml><getter>ecRelationship</getter></xml>','200','0',NULL,1,3,'---',0,0),(30,3,'hs_hr_emp_emergency_contacts.eec_mobile_no','Mobile','ecMobile','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,3,'---',0,0),(31,3,'hs_hr_emp_dependents.ed_name','Name','dependentName','false',NULL,NULL,'label','<xml><getter>dependentName</getter></xml>','200','0',NULL,1,4,'---',0,0),(32,3,'IF (hs_hr_emp_dependents.ed_relationship_type = \'other\', hs_hr_emp_dependents.ed_relationship, hs_hr_emp_dependents.ed_relationship_type)','Relationship','dependentRelationship','false',NULL,NULL,'label','<xml><getter>dependentRelationship</getter></xml>','200','0',NULL,1,4,'---',0,0),(33,3,'hs_hr_emp_dependents.ed_date_of_birth','Date of Birth','dependentDateofBirth','false',NULL,NULL,'label','<xml><getter>dependentDateofBirth</getter></xml>','100','0',NULL,1,4,'---',0,0),(35,3,'ohrm_membership.name','Membership','name','false',NULL,NULL,'label','<xml><getter>name</getter></xml>','200','0',NULL,1,15,'---',0,0),(36,3,'hs_hr_emp_member_detail.ememb_subscript_ownership','Subscription Paid By','subscriptionPaidBy','false',NULL,NULL,'label','<xml><getter>subscriptionPaidBy</getter></xml>','200','0',NULL,1,15,'---',0,0),(37,3,'hs_hr_emp_member_detail.ememb_subscript_amount','Subscription Amount','subscriptionAmount','false',NULL,NULL,'label','<xml><getter>subscriptionAmount</getter></xml>','200','0',NULL,1,15,'---',0,0),(38,3,'hs_hr_emp_member_detail.ememb_subs_currency','Currency','membershipCurrency','false',NULL,NULL,'label','<xml><getter>membershipCurrency</getter></xml>','200','0',NULL,1,15,'---',0,0),(39,3,'hs_hr_emp_member_detail.ememb_commence_date','Subscription Commence Date','subscriptionCommenceDate','false',NULL,NULL,'label','<xml><getter>subscriptionCommenceDate</getter></xml>','200','0',NULL,1,15,'---',0,0),(40,3,'hs_hr_emp_member_detail.ememb_renewal_date','Subscription Renewal Date','subscriptionRenewalDate','false',NULL,NULL,'label','<xml><getter>subscriptionRenewalDate</getter></xml>','200','0',NULL,1,15,'---',0,0),(41,3,'hs_hr_emp_work_experience.eexp_employer','Company','expCompany','false',NULL,NULL,'label','<xml><getter>expCompany</getter></xml>','200','0',NULL,1,10,'---',0,0),(42,3,'hs_hr_emp_work_experience.eexp_jobtit','Job Title','expJobTitle','false',NULL,NULL,'label','<xml><getter>expJobTitle</getter></xml>','200','0',NULL,1,10,'---',0,0),(43,3,'DATE(hs_hr_emp_work_experience.eexp_from_date)','From','expFrom','false',NULL,NULL,'label','<xml><getter>expFrom</getter></xml>','100','0',NULL,1,10,'---',0,0),(44,3,'DATE(hs_hr_emp_work_experience.eexp_to_date)','To','expTo','false',NULL,NULL,'label','<xml><getter>expTo</getter></xml>','100','0',NULL,1,10,'---',0,0),(45,3,'hs_hr_emp_work_experience.eexp_comments','Comment','expComment','false',NULL,NULL,'label','<xml><getter>expComment</getter></xml>','200','0',NULL,1,10,'---',0,0),(47,3,'ohrm_education.name','Level','eduProgram','false',NULL,NULL,'label','<xml><getter>eduProgram</getter></xml>','200','0',NULL,1,11,'---',0,0),(48,3,'ohrm_emp_education.year','Year','eduYear','false',NULL,NULL,'label','<xml><getter>eduYear</getter></xml>','100','0',NULL,1,11,'---',0,0),(49,3,'ohrm_emp_education.score','Score','eduGPAOrScore','false',NULL,NULL,'label','<xml><getter>eduGPAOrScore</getter></xml>','80','0',NULL,1,11,'---',0,0),(52,3,'ohrm_skill.name','Skill','skill','false',NULL,NULL,'label','<xml><getter>skill</getter></xml>','200','0',NULL,1,12,'---',0,0),(53,3,'hs_hr_emp_skill.years_of_exp','Years of Experience','skillYearsOfExperience','false',NULL,NULL,'label','<xml><getter>skillYearsOfExperience</getter></xml>','135','0',NULL,1,12,'---',0,0),(54,3,'hs_hr_emp_skill.comments','Comments','skillComments','false',NULL,NULL,'label','<xml><getter>skillComments</getter></xml>','200','0',NULL,1,12,'---',0,0),(55,3,'ohrm_language.name','Language','langName','false',NULL,NULL,'label','<xml><getter>langName</getter></xml>','200','0',NULL,1,13,'---',0,0),(57,3,'CASE hs_hr_emp_language.competency WHEN 1 THEN \"Poor\" WHEN 2 THEN \"Basic\" WHEN 3 THEN \"Good\" WHEN 4 THEN \"Mother Tongue\" END','Competency','langCompetency','false',NULL,NULL,'label','<xml><getter>langCompetency</getter></xml>','130','0',NULL,1,13,'---',0,0),(58,3,'hs_hr_emp_language.comments','Comments','langComments','false',NULL,NULL,'label','<xml><getter>langComments</getter></xml>','200','0',NULL,1,13,'---',0,0),(59,3,'ohrm_license.name','License Type','empLicenseType','false',NULL,NULL,'label','<xml><getter>empLicenseType</getter></xml>','200','0',NULL,1,14,'---',0,0),(60,3,'ohrm_emp_license.license_issued_date','Issued Date','empLicenseIssuedDate','false',NULL,NULL,'label','<xml><getter>empLicenseIssuedDate</getter></xml>','100','0',NULL,1,14,'---',0,0),(61,3,'ohrm_emp_license.license_expiry_date','Expiry Date','empLicenseExpiryDate','false',NULL,NULL,'label','<xml><getter>empLicenseExpiryDate</getter></xml>','100','0',NULL,1,14,'---',0,0),(62,3,'supervisor.emp_firstname','First Name','supervisorFirstName','false',NULL,NULL,'label','<xml><getter>supervisorFirstName</getter></xml>','200','0',NULL,1,9,'---',0,0),(63,3,'subordinate.emp_firstname','First Name','subordinateFirstName','false',NULL,NULL,'label','<xml><getter>subordinateFirstName</getter></xml>','200','0',NULL,1,8,'---',0,0),(64,3,'supervisor.emp_lastname','Last Name','supervisorLastName','false',NULL,NULL,'label','<xml><getter>supervisorLastName</getter></xml>','200','0',NULL,1,9,'---',0,0),(65,3,'ohrm_pay_grade.name','Pay Grade','salPayGrade','false',NULL,NULL,'label','<xml><getter>salPayGrade</getter></xml>','200','0',NULL,1,7,'---',0,0),(66,3,'hs_hr_emp_basicsalary.salary_component','Salary Component','salSalaryComponent','false',NULL,NULL,'label','<xml><getter>salSalaryComponent</getter></xml>','200','0',NULL,1,7,'---',0,0),(67,3,'hs_hr_emp_basicsalary.ebsal_basic_salary','Amount','salAmount','false',NULL,NULL,'label','<xml><getter>salAmount</getter></xml>','200','0',NULL,1,7,'---',1,0),(68,3,'hs_hr_emp_basicsalary.comments','Comments','salComments','false',NULL,NULL,'label','<xml><getter>salComments</getter></xml>','200','0',NULL,1,7,'---',0,0),(69,3,'hs_hr_payperiod.payperiod_name','Pay Frequency','salPayFrequency','false',NULL,NULL,'label','<xml><getter>salPayFrequency</getter></xml>','200','0',NULL,1,7,'---',0,0),(70,3,'hs_hr_currency_type.currency_name','Currency','salCurrency','false',NULL,NULL,'label','<xml><getter>salCurrency</getter></xml>','200','0',NULL,1,7,'---',0,0),(71,3,'hs_hr_emp_directdebit.dd_account','Direct Deposit Account Number','ddAccountNumber','false',NULL,NULL,'label','<xml><getter>ddAccountNumber</getter></xml>','200','0',NULL,1,7,'---',0,0),(72,3,'hs_hr_emp_directdebit.dd_account_type','Direct Deposit Account Type','ddAccountType','false',NULL,NULL,'label','<xml><getter>ddAccountType</getter></xml>','200','0',NULL,1,7,'---',0,0),(73,3,'hs_hr_emp_directdebit.dd_routing_num','Direct Deposit Routing Number','ddRoutingNumber','false',NULL,NULL,'label','<xml><getter>ddRoutingNumber</getter></xml>','200','0',NULL,1,7,'---',0,0),(74,3,'hs_hr_emp_directdebit.dd_amount','Direct Deposit Amount','ddAmount','false',NULL,NULL,'label','<xml><getter>ddAmount</getter></xml>','200','0',NULL,1,7,'---',0,0),(75,3,'DATE(hs_hr_emp_contract_extend.econ_extend_start_date)','Contract Start Date','empContStartDate','false',NULL,NULL,'label','<xml><getter>empContStartDate</getter></xml>','200','0',NULL,1,6,'---',0,0),(76,3,'DATE(hs_hr_emp_contract_extend.econ_extend_end_date)','Contract End Date','empContEndDate','false',NULL,NULL,'label','<xml><getter>empContEndDate</getter></xml>','200','0',NULL,1,6,'---',0,0),(77,3,'ohrm_job_title.job_title','Job Title','empJobTitle','false',NULL,NULL,'label','<xml><getter>empJobTitle</getter></xml>','200','0',NULL,1,6,'---',0,0),(78,3,'ohrm_employment_status.name','Employment Status','empEmploymentStatus','false',NULL,NULL,'label','<xml><getter>empEmploymentStatus</getter></xml>','200','0',NULL,1,6,'---',0,0),(80,3,'ohrm_job_category.name','Job Category','empJobCategory','false',NULL,NULL,'label','<xml><getter>empJobCategory</getter></xml>','200','0',NULL,1,6,'---',0,0),(81,3,'hs_hr_employee.joined_date','Joined Date','empJoinedDate','false',NULL,NULL,'label','<xml><getter>empJoinedDate</getter></xml>','100','0',NULL,1,6,'---',0,0),(82,3,'ohrm_subunit.name','Sub Unit','empSubUnit','false',NULL,NULL,'label','<xml><getter>empSubUnit</getter></xml>','200','0',NULL,1,6,'---',0,0),(83,3,'ohrm_location.name','Location','empLocation','false',NULL,NULL,'label','<xml><getter>empLocation</getter></xml>','200','0',NULL,1,6,'---',0,0),(84,3,'hs_hr_emp_passport.ep_passport_num','Number','empPassportNo','false',NULL,NULL,'label','<xml><getter>empPassportNo</getter></xml>','200','0',NULL,1,5,'---',0,0),(85,3,'DATE(hs_hr_emp_passport.ep_passportissueddate)','Issued Date','empPassportIssuedDate','false',NULL,NULL,'label','<xml><getter>empPassportIssuedDate</getter></xml>','100','0',NULL,1,5,'---',0,0),(86,3,'DATE(hs_hr_emp_passport.ep_passportexpiredate)','Expiry Date','empPassportExpiryDate','false',NULL,NULL,'label','<xml><getter>empPassportExpiryDate</getter></xml>','100','0',NULL,1,5,'---',0,0),(87,3,'hs_hr_emp_passport.ep_i9_status','Eligible Status','empPassportEligibleStatus','false',NULL,NULL,'label','<xml><getter>empPassportEligibleStatus</getter></xml>','200','0',NULL,1,5,'---',0,0),(88,3,'hs_hr_emp_passport.cou_code','Issued By','empPassportIssuedBy','false',NULL,NULL,'label','<xml><getter>empPassportIssuedBy</getter></xml>','200','0',NULL,1,5,'---',0,0),(89,3,'hs_hr_emp_passport.ep_i9_review_date','Eligible Review Date','empPassportEligibleReviewDate','false',NULL,NULL,'label','<xml><getter>empPassportEligibleReviewDate</getter></xml>','200','0',NULL,1,5,'---',0,0),(90,3,'hs_hr_emp_passport.ep_comments','Comments','empPassportComments','false',NULL,NULL,'label','<xml><getter>empPassportComments</getter></xml>','200','0',NULL,1,5,'---',0,0),(91,3,'subordinate.emp_lastname','Last Name','subordinateLastName','false',NULL,NULL,'label','<xml><getter>subordinateLastName</getter></xml>','200','0',NULL,1,8,'---',0,0),(92,3,'CASE hs_hr_emp_language.fluency WHEN 1 THEN \"Writing\" WHEN 2 THEN \"Speaking\" WHEN 3 THEN \"Reading\" END','Fluency','langFluency','false',NULL,NULL,'label','<xml><getter>langFluency</getter></xml>','200','0',NULL,1,13,'---',0,0),(93,3,'supervisor_reporting_method.reporting_method_name','Reporting Method','supReportingMethod','false',NULL,NULL,'label','<xml><getter>supReportingMethod</getter></xml>','200','0',NULL,1,9,'---',0,0),(94,3,'subordinate_reporting_method.reporting_method_name','Reporting Method','subReportingMethod','false',NULL,NULL,'label','<xml><getter>subReportingMethod</getter></xml>','200','0',NULL,1,8,'---',0,0),(95,3,'CASE hs_hr_emp_passport.ep_passport_type_flg WHEN 1 THEN \"Passport\" WHEN 2 THEN \"Visa\" END','Document Type','documentType','false',NULL,NULL,'label','<xml><getter>documentType</getter></xml>','200','0',NULL,1,5,'---',0,0),(97,3,'hs_hr_employee.emp_other_id','Other Id','otherId','false',NULL,NULL,'label','<xml><getter>otherId</getter></xml>','100','0',NULL,0,1,'---',0,0),(98,3,'hs_hr_emp_emergency_contacts.eec_seqno','ecSeqNo','ecSeqNo','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,3,'---',0,1),(99,3,'hs_hr_emp_dependents.ed_seqno','SeqNo','edSeqNo','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,4,'---',0,1),(100,3,'hs_hr_emp_passport.ep_seqno','SeqNo','epSeqNo','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,5,'---',0,1),(101,3,'hs_hr_emp_basicsalary.id','salaryId','salaryId','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,7,'---',0,1),(102,3,'subordinate.emp_number','subordinateId','subordinateId','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,8,'---',0,1),(103,3,'supervisor.emp_number','supervisorId','supervisorId','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,9,'---',0,1),(104,3,'hs_hr_emp_work_experience.eexp_seqno','workExpSeqNo','workExpSeqNo','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,10,'---',0,1),(105,3,'ohrm_emp_education.education_id','empEduCode','empEduCode','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,11,'---',0,1),(106,3,'hs_hr_emp_skill.skill_id','empSkillCode','empSkillCode','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,12,'---',0,1),(107,3,'hs_hr_emp_language.lang_id','empLangCode','empLangCode','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,13,'---',0,1),(108,3,'hs_hr_emp_language.fluency','empLangType','empLangType','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,13,'---',0,1),(109,3,'ohrm_emp_license.license_id','empLicenseCode','empLicenseCode','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,14,'---',0,1),(110,3,'hs_hr_emp_member_detail.membship_code','membershipCode','membershipCode','false',NULL,NULL,'label','<xml><getter>ecMobile</getter></xml>','100','0',NULL,1,15,'---',0,1),(112,3,'ROUND(DATEDIFF(hs_hr_emp_work_experience.eexp_to_date, hs_hr_emp_work_experience.eexp_from_date)/365,1)','Duration','expDuration','false',NULL,NULL,'label','<xml><getter>expDuration</getter></xml>','100','0',NULL,1,10,'---',0,0),(113,3,'ohrm_emp_termination.termination_date','Termination Date','terminationDate','false',NULL,NULL,'label','<xml><getter>terminationDate</getter></xml>','100','0',NULL,1,6,'---',0,0),(114,3,'ohrm_emp_termination_reason.name','Termination Reason','terminationReason','false',NULL,NULL,'label','<xml><getter>terminationReason</getter></xml>','100','0',NULL,1,6,'---',0,0),(115,4,'leave_period','Leave Period',NULL,'false',NULL,NULL,'label','<xml><getter>leave_period</getter></xml>','200','0','left',0,NULL,'---',0,0),(116,4,'type','Type',NULL,'false',NULL,NULL,'label','<xml><getter>type</getter></xml>','165','0','left',0,NULL,'---',0,0),(117,4,'entitlement','Entitlement',NULL,'false',NULL,NULL,'label','<xml><getter>entitlement</getter></xml>','120','0','right',0,NULL,'---',0,0),(118,4,'taken_days','Taken',NULL,'false',NULL,NULL,'label','<xml><getter>taken_days</getter></xml>','120','0','right',0,NULL,'---',0,0),(119,4,'brought_forward','Brought Forward',NULL,'false',NULL,NULL,'label','<xml><getter>brought_forward</getter></xml>','120','0','right',0,NULL,'---',0,0),(120,4,'(total - taken_days - scheduled_days)','Remaining','remaining','false',NULL,NULL,'label','<xml><getter>remaining</getter></xml>','120','0','right',0,NULL,'---',0,0),(121,4,'total','Total',NULL,'false',NULL,NULL,'label','<xml><getter>total</getter></xml>','120','0','right',0,NULL,'---',0,0),(122,5,'CONCAT (e.emp_firstname, \' \', e.emp_lastname)','Employee Name','employee_name','false',NULL,NULL,'label','<xml><getter>employee_name</getter></xml>','200','0','left',0,NULL,'---',0,0),(123,5,'get_sub_unit_path(cs.id)','Sub Unit','empSubUnit','false',NULL,NULL,'label','<xml><getter>empSubUnit</getter></xml>','300','0','left',1,NULL,'---',0,0),(124,5,'e.joined_date','Hired Date','hired_date','false',NULL,NULL,'label','<xml><getter>hired_date</getter></xml>','100','0','left',0,NULL,'---',0,0),(125,5,'jt.job_title','Job Title','job_title','false',NULL,NULL,'label','<xml><getter>job_title</getter></xml>','300','0','left',0,NULL,'---',0,0),(126,6,'CONCAT (e.emp_firstname, \' \', e.emp_lastname)','Employee Name','employee_name','false',NULL,NULL,'label','<xml><getter>employee_name</getter></xml>','200','0','left',0,NULL,'---',0,0),(127,6,'get_sub_unit_path(cs.id)','Sub Unit','sub_unit','false',NULL,NULL,'label','<xml><getter>sub_unit</getter></xml>','300','0','left',0,NULL,'---',0,0),(128,6,'te.termination_date','Terminated Date','termination_date','false',NULL,NULL,'label','<xml><getter>termination_date</getter></xml>','100','0','left',0,NULL,'---',0,0),(129,6,'jt.job_title','Job Title','job_title','false',NULL,NULL,'label','<xml><getter>job_title</getter></xml>','200','0','left',0,NULL,'---',0,0),(130,6,'tr.name','Reason','name','false',NULL,NULL,'label','<xml><getter>name</getter></xml>','300','0','left',0,NULL,'---',0,0),(131,7,'CASE WHEN p.id IS NULL THEN get_sub_unit_title(s.id) ELSE p.name END','Sub Unit','sub_unit','false',NULL,NULL,'treeLink','<xml><labelGetter>sub_unit</labelGetter><nodeTypeGetter>NodeType</nodeTypeGetter><placeholderGetters><id>SubUnitId</id><empStatus>empStatus</empStatus><include>include</include></placeholderGetters><urlPattern>../../displayHeadCountReport/reportId/9?sub_unit={id}#employment_status={request:time.employment_status,0}#include={request:time.include,1}</urlPattern></xml>','200','0','left',0,NULL,'---',0,1),(132,7,'COUNT(emp_number)','Number of Employees','emp_count','false',NULL,NULL,'label','<xml><getter>emp_count</getter></xml>','150','0','right',0,NULL,'---',0,1),(133,7,'CASE WHEN p.id IS NULL THEN s.id ELSE p.id END','SubUnitId','SubUnitId','false',NULL,NULL,'label','<xml><getter>SubUnitId</getter></xml>','200','0','left',0,NULL,'---',0,1),(134,7,'IF(e.emp_status is NULL ,0,e.emp_status)','empStatus','empStatus','false',NULL,NULL,'label','<xml><getter>empStatus</getter></xml>','200','0','left',0,NULL,'---',0,1),(135,7,'IF(is_sub_unit_leaf_node(p.id), \'leaf-node\', \'inner-node\')','NodeType','NodeType','false',NULL,NULL,'label','<xml><getter>NodeType</getter></xml>','0','0','left',0,NULL,'---',0,1),(136,8,'r.vacancy_added_date','Vacancy Added Date',NULL,'false',NULL,NULL,'label','<xml><getter>vacancy_added_date</getter></xml>','120','0','left',0,NULL,'---',0,0),(137,8,'r.job_title','Job Title',NULL,'false',NULL,NULL,'label','<xml><getter>job_title</getter></xml>','200','0','left',0,NULL,'---',0,0),(138,8,'r.no_of_positions','Number of Positions',NULL,'false',NULL,NULL,'label','<xml><getter>no_of_positions</getter></xml>','80','0','right',0,NULL,'---',0,0),(139,8,'r.no_of_applicants','Number of Applicants',NULL,'false',NULL,NULL,'label','<xml><getter>no_of_applicants</getter></xml>','80','0','right',0,NULL,'---',0,0),(140,8,'SUM(IF(r.no_of_shortlisted = 1, 1, 0))','Number of Shortlisted','no_of_shortlisted','false',NULL,NULL,'label','<xml><getter>no_of_shortlisted</getter></xml>','80','0','right',0,NULL,'---',0,0),(141,8,'SUM(IF(r.no_of_scheduled >= 1 AND r.no_of_passed >= 1, 1, 0))','1st Interview Passed','first_interview_passed','false',NULL,NULL,'label','<xml><getter>first_interview_passed</getter></xml>','80','0','right',0,NULL,'---',0,0),(142,8,'SUM(IF(r.no_of_scheduled = 1 AND r.no_of_failed = 1, 1, 0))','1st Interview Failed','first_interview_failed','false',NULL,NULL,'label','<xml><getter>first_interview_failed</getter></xml>','80','0','right',0,NULL,'---',0,0),(143,8,'SUM(IF(r.no_of_scheduled >= 2 AND r.no_of_passed >= 2, 1, 0))','2nd Interview Passed','second_interview_passed','false',NULL,NULL,'label','<xml><getter>second_interview_passed</getter></xml>','80','0','right',0,NULL,'---',0,0),(144,8,'SUM(IF(r.no_of_scheduled >= 2 AND r.no_of_failed = 1, 1, 0))','2nd Interview Failed','second_interview_failed','false',NULL,NULL,'label','<xml><getter>second_interview_failed</getter></xml>','80','0','right',0,NULL,'---',0,0),(145,8,'SUM(IF(r.no_of_hired = 1, 1, 0))','Number of Hired Applicants','no_of_hired','false',NULL,NULL,'label','<xml><getter>no_of_hired</getter></xml>','100','0','right',0,NULL,'---',0,0),(146,8,'r.hiring_manager_id','Hiring Manager ID',NULL,'false',NULL,NULL,'label','<xml><getter>hiring_manager_id</getter></xml>','80','0','left',0,NULL,'---',0,1),(147,8,'r.job_title_code','Job Title Code',NULL,'false',NULL,NULL,'label','<xml><getter>job_title_code</getter></xml>','80','0','left',0,NULL,'---',0,1),(148,3,'hs_hr_employee.custom1','Type','customField1','false',NULL,NULL,'label','<xml><getter>customField1</getter></xml>','200','0',NULL,0,16,'---',0,0),(149,3,'hs_hr_employee.custom2','Religion','customField2','false',NULL,NULL,'label','<xml><getter>customField2</getter></xml>','200','0',NULL,0,16,'---',0,0);
/*!40000 ALTER TABLE `ohrm_display_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_display_field_group`
--

DROP TABLE IF EXISTS `ohrm_display_field_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_display_field_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_group_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_list` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `report_group_id` (`report_group_id`),
  CONSTRAINT `ohrm_display_field_group_ibfk_1` FOREIGN KEY (`report_group_id`) REFERENCES `ohrm_report_group` (`report_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_display_field_group`
--

LOCK TABLES `ohrm_display_field_group` WRITE;
/*!40000 ALTER TABLE `ohrm_display_field_group` DISABLE KEYS */;
INSERT INTO `ohrm_display_field_group` VALUES (1,3,'Personal',0),(2,3,'Contact Details',0),(3,3,'Emergency Contacts',1),(4,3,'Dependents',1),(5,3,'Immigration',1),(6,3,'Job',0),(7,3,'Salary',1),(8,3,'Subordinates',1),(9,3,'Supervisors',1),(10,3,'Work Experience',1),(11,3,'Education',1),(12,3,'Skills',1),(13,3,'Languages',1),(14,3,'License',1),(15,3,'Memberships',1),(16,3,'Custom Fields',0);
/*!40000 ALTER TABLE `ohrm_display_field_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_education`
--

DROP TABLE IF EXISTS `ohrm_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_education`
--

LOCK TABLES `ohrm_education` WRITE;
/*!40000 ALTER TABLE `ohrm_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_email_configuration`
--

DROP TABLE IF EXISTS `ohrm_email_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_email_configuration` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mail_type` varchar(50) DEFAULT NULL,
  `sent_as` varchar(250) NOT NULL,
  `sendmail_path` varchar(250) DEFAULT NULL,
  `smtp_host` varchar(250) DEFAULT NULL,
  `smtp_port` int(10) DEFAULT NULL,
  `smtp_username` varchar(250) DEFAULT NULL,
  `smtp_password` varchar(250) DEFAULT NULL,
  `smtp_auth_type` varchar(50) DEFAULT NULL,
  `smtp_security_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_email_configuration`
--

LOCK TABLES `ohrm_email_configuration` WRITE;
/*!40000 ALTER TABLE `ohrm_email_configuration` DISABLE KEYS */;
INSERT INTO `ohrm_email_configuration` VALUES (1,'sendmail','Leave Request','charity.adug@sigmasoft.com.ph','',NULL,'','','none',NULL);
/*!40000 ALTER TABLE `ohrm_email_configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_email_notification`
--

DROP TABLE IF EXISTS `ohrm_email_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_email_notification` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `is_enable` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_email_notification`
--

LOCK TABLES `ohrm_email_notification` WRITE;
/*!40000 ALTER TABLE `ohrm_email_notification` DISABLE KEYS */;
INSERT INTO `ohrm_email_notification` VALUES (1,'Leave Applications',1),(2,'Leave Assignments',1),(3,'Leave Approvals',1),(4,'Leave Cancellations',1),(5,'Leave Rejections',0),(7,'Performance Review Submissions',1);
/*!40000 ALTER TABLE `ohrm_email_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_email_queue`
--

DROP TABLE IF EXISTS `ohrm_email_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_email` varchar(60) NOT NULL,
  `from_email` varchar(60) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `type` varchar(60) NOT NULL,
  `content_type` enum('text/html','text/plain') DEFAULT NULL,
  `body` longtext NOT NULL,
  `status` enum('STARTED','SENT','PENDING') CHARACTER SET latin1 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_email_queue`
--

LOCK TABLES `ohrm_email_queue` WRITE;
/*!40000 ALTER TABLE `ohrm_email_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_email_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_email_subscriber`
--

DROP TABLE IF EXISTS `ohrm_email_subscriber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_email_subscriber` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `notification_id` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_id` (`notification_id`),
  CONSTRAINT `ohrm_email_subscriber_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `ohrm_email_notification` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_email_subscriber`
--

LOCK TABLES `ohrm_email_subscriber` WRITE;
/*!40000 ALTER TABLE `ohrm_email_subscriber` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_email_subscriber` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_emp_education`
--

DROP TABLE IF EXISTS `ohrm_emp_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_emp_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_number` int(11) NOT NULL,
  `education_id` int(11) NOT NULL,
  `institute` varchar(100) DEFAULT NULL,
  `major` varchar(100) DEFAULT NULL,
  `year` decimal(4,0) DEFAULT NULL,
  `score` varchar(25) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emp_number` (`emp_number`),
  KEY `education_id` (`education_id`),
  CONSTRAINT `ohrm_emp_education_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_emp_education_ibfk_2` FOREIGN KEY (`education_id`) REFERENCES `ohrm_education` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_emp_education`
--

LOCK TABLES `ohrm_emp_education` WRITE;
/*!40000 ALTER TABLE `ohrm_emp_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_emp_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_emp_license`
--

DROP TABLE IF EXISTS `ohrm_emp_license`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_emp_license` (
  `emp_number` int(11) NOT NULL,
  `license_id` int(11) NOT NULL,
  `license_no` varchar(50) DEFAULT NULL,
  `license_issued_date` date DEFAULT NULL,
  `license_expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`emp_number`,`license_id`),
  KEY `license_id` (`license_id`),
  CONSTRAINT `ohrm_emp_license_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_emp_license_ibfk_2` FOREIGN KEY (`license_id`) REFERENCES `ohrm_license` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_emp_license`
--

LOCK TABLES `ohrm_emp_license` WRITE;
/*!40000 ALTER TABLE `ohrm_emp_license` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_emp_license` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_emp_reporting_method`
--

DROP TABLE IF EXISTS `ohrm_emp_reporting_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_emp_reporting_method` (
  `reporting_method_id` int(7) NOT NULL AUTO_INCREMENT,
  `reporting_method_name` varchar(100) NOT NULL,
  PRIMARY KEY (`reporting_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_emp_reporting_method`
--

LOCK TABLES `ohrm_emp_reporting_method` WRITE;
/*!40000 ALTER TABLE `ohrm_emp_reporting_method` DISABLE KEYS */;
INSERT INTO `ohrm_emp_reporting_method` VALUES (1,'Direct'),(2,'Indirect');
/*!40000 ALTER TABLE `ohrm_emp_reporting_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_emp_termination`
--

DROP TABLE IF EXISTS `ohrm_emp_termination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_emp_termination` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `emp_number` int(4) DEFAULT NULL,
  `reason_id` int(4) DEFAULT NULL,
  `termination_date` date NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reason_id` (`reason_id`),
  KEY `emp_number` (`emp_number`),
  CONSTRAINT `ohrm_emp_termination_ibfk_1` FOREIGN KEY (`reason_id`) REFERENCES `ohrm_emp_termination_reason` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ohrm_emp_termination_ibfk_2` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_emp_termination`
--

LOCK TABLES `ohrm_emp_termination` WRITE;
/*!40000 ALTER TABLE `ohrm_emp_termination` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_emp_termination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_emp_termination_reason`
--

DROP TABLE IF EXISTS `ohrm_emp_termination_reason`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_emp_termination_reason` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_emp_termination_reason`
--

LOCK TABLES `ohrm_emp_termination_reason` WRITE;
/*!40000 ALTER TABLE `ohrm_emp_termination_reason` DISABLE KEYS */;
INSERT INTO `ohrm_emp_termination_reason` VALUES (1,'Other'),(2,'Retired'),(3,'Contract Not Renewed'),(4,'Resigned - Company Requested'),(5,'Resigned - Self Proposed'),(6,'Resigned'),(7,'Deceased'),(8,'Physically Disabled/Compensated'),(9,'Laid-off'),(10,'Dismissed');
/*!40000 ALTER TABLE `ohrm_emp_termination_reason` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_employee_work_shift`
--

DROP TABLE IF EXISTS `ohrm_employee_work_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_employee_work_shift` (
  `work_shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_number` int(11) NOT NULL,
  PRIMARY KEY (`work_shift_id`,`emp_number`),
  KEY `emp_number` (`emp_number`),
  CONSTRAINT `ohrm_employee_work_shift_ibfk_1` FOREIGN KEY (`work_shift_id`) REFERENCES `ohrm_work_shift` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_employee_work_shift_ibfk_2` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_employee_work_shift`
--

LOCK TABLES `ohrm_employee_work_shift` WRITE;
/*!40000 ALTER TABLE `ohrm_employee_work_shift` DISABLE KEYS */;
INSERT INTO `ohrm_employee_work_shift` VALUES (1,1),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,57);
/*!40000 ALTER TABLE `ohrm_employee_work_shift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_employment_status`
--

DROP TABLE IF EXISTS `ohrm_employment_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_employment_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_employment_status`
--

LOCK TABLES `ohrm_employment_status` WRITE;
/*!40000 ALTER TABLE `ohrm_employment_status` DISABLE KEYS */;
INSERT INTO `ohrm_employment_status` VALUES (1,'Active'),(2,'Terminated'),(3,'Deceased'),(4,'Dismissed'),(5,'Leave Country'),(6,'None'),(7,'Resigned'),(8,'Retired'),(9,'Retrenched');
/*!40000 ALTER TABLE `ohrm_employment_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_filter_field`
--

DROP TABLE IF EXISTS `ohrm_filter_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_filter_field` (
  `filter_field_id` bigint(20) NOT NULL,
  `report_group_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `where_clause_part` mediumtext NOT NULL,
  `filter_field_widget` varchar(255) DEFAULT NULL,
  `condition_no` int(20) NOT NULL,
  `required` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`filter_field_id`),
  KEY `report_group_id` (`report_group_id`),
  CONSTRAINT `ohrm_filter_field_ibfk_1` FOREIGN KEY (`report_group_id`) REFERENCES `ohrm_report_group` (`report_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_filter_field`
--

LOCK TABLES `ohrm_filter_field` WRITE;
/*!40000 ALTER TABLE `ohrm_filter_field` DISABLE KEYS */;
INSERT INTO `ohrm_filter_field` VALUES (1,1,'project_name','ohrm_project.project_id','ohrmWidgetProjectList',2,'true'),(2,1,'activity_show_deleted','ohrm_project_activity.is_deleted','ohrmWidgetInputCheckbox',2,'false'),(3,1,'project_date_range','date','ohrmWidgetDateRange',1,'false'),(4,1,'employee','hs_hr_employee.emp_number','ohrmReportWidgetEmployeeListAutoFill',2,'true'),(5,1,'activity_name','ohrm_project_activity.activity_id','ohrmWidgetProjectActivityList',2,'true'),(6,1,'project_name','ohrm_project.project_id','ohrmWidgetProjectListWithAllOption',2,'true'),(7,1,'only_include_approved_timesheets','ohrm_timesheet.state','ohrmWidgetApprovedTimesheetInputCheckBox',2,NULL),(8,3,'employee_name','hs_hr_employee.emp_number','ohrmReportWidgetEmployeeListAutoFill',1,NULL),(9,3,'pay_grade','hs_hr_emp_basicsalary.sal_grd_code','ohrmReportWidgetPayGradeDropDown',1,NULL),(10,3,'education','ohrm_emp_education.education_id','ohrmReportWidgetEducationtypeDropDown',1,NULL),(11,3,'employment_status','hs_hr_employee.emp_status','ohrmWidgetEmploymentStatusList',1,NULL),(12,3,'service_period','datediff(current_date(), hs_hr_employee.joined_date)/365','ohrmReportWidgetServicePeriod',1,NULL),(13,3,'joined_date','hs_hr_employee.joined_date','ohrmReportWidgetJoinedDate',1,NULL),(14,3,'job_title','hs_hr_employee.job_title_code','ohrmWidgetJobTitleList',1,NULL),(15,3,'language','hs_hr_emp_language.lang_id','ohrmReportWidgetLanguageDropDown',1,NULL),(16,3,'skill','hs_hr_emp_skill.skill_id','ohrmReportWidgetSkillDropDown',1,NULL),(17,3,'age_group','datediff(current_date(), hs_hr_employee.emp_birthday)/365','ohrmReportWidgetAgeGroup',1,NULL),(18,3,'sub_unit','hs_hr_employee.work_station','ohrmWidgetSubDivisionList',1,NULL),(19,3,'gender','hs_hr_employee.emp_gender','ohrmReportWidgetGenderDropDown',1,NULL),(20,3,'location','ohrm_location.id','ohrmReportWidgetOperationalCountryLocationDropDown',1,NULL),(21,1,'is_deleted','ohrm_project_activity.is_deleted','',2,NULL),(22,3,'include','hs_hr_employee.termination_id','ohrmReportWidgetIncludedEmployeesDropDown',1,'true'),(23,4,'employee','q.employee_id','ohrmReportWidgetEmployeeListAutoFill',1,'true'),(24,4,'leave_period','q.leave_period_id','ohrmWidgetLeavePeriodList',1,'false'),(25,4,'leave_type','q.leave_type_id','ohrmWidgetLeaveTypeCheckboxGroup',1,'true'),(26,5,'date_range','e.joined_date','ohrmWidgetDateRange',1,'false'),(27,5,'sub_unit','e.work_station','ohrmWidgetSubDivisionList',1,'false'),(28,6,'date_range','te.termination_date','ohrmWidgetDateRange',1,'false'),(29,6,'sub_unit','e.work_station','ohrmWidgetSubDivisionList',1,'false'),(30,7,'employment_status','e.emp_status','ohrmWidgetEmploymentStatusList',2,NULL),(31,7,'sub_unit','s.id','ohrmWidgetSubDivisionListWithParent',1,NULL),(32,7,'include','e.termination_id','ohrmWidgetTerminatedFilter',3,NULL),(33,8,'job_title','r.job_title_code','ohrmWidgetAllowedJobTitleList',1,'false'),(34,8,'date_range','r.vacancy_added_date','ohrmWidgetDateRange',1,'false'),(35,8,'hiring_manager_id','r.hiring_manager_id','ohrmWidgetHiringManagerHiddenField',1,'false');
/*!40000 ALTER TABLE `ohrm_filter_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_group_field`
--

DROP TABLE IF EXISTS `ohrm_group_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_group_field` (
  `group_field_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `group_by_clause` mediumtext NOT NULL,
  `group_field_widget` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_group_field`
--

LOCK TABLES `ohrm_group_field` WRITE;
/*!40000 ALTER TABLE `ohrm_group_field` DISABLE KEYS */;
INSERT INTO `ohrm_group_field` VALUES (1,'activity id','GROUP BY ohrm_project_activity.activity_id',NULL),(2,'employee number','GROUP BY hs_hr_employee.emp_number',NULL);
/*!40000 ALTER TABLE `ohrm_group_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_holiday`
--

DROP TABLE IF EXISTS `ohrm_holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_holiday` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text,
  `date` date DEFAULT NULL,
  `recurring` tinyint(3) unsigned DEFAULT '0',
  `length` int(10) unsigned DEFAULT NULL,
  `operational_country_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ohrm_holiday_ohrm_operational_country` (`operational_country_id`),
  CONSTRAINT `fk_ohrm_holiday_ohrm_operational_country` FOREIGN KEY (`operational_country_id`) REFERENCES `ohrm_operational_country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_holiday`
--

LOCK TABLES `ohrm_holiday` WRITE;
/*!40000 ALTER TABLE `ohrm_holiday` DISABLE KEYS */;
INSERT INTO `ohrm_holiday` VALUES (1,'New Year\'s Day','2012-01-01',1,0,NULL),(2,'Maundy Thursday','2012-04-05',1,0,NULL),(3,'Good Friday','2012-04-06',1,0,NULL),(4,'Araw ng Kagitingan','2012-04-09',1,0,NULL),(5,'Labor Day','2012-05-01',1,0,NULL),(6,'Independence Day','2012-06-12',1,0,NULL),(7,'National Heroes Day','2012-08-27',1,0,NULL),(8,'Eid\'l Fitr','2012-08-03',1,0,NULL),(9,'Eid\'l Adha','2012-08-03',0,0,NULL),(10,'Bonifacio Day','2012-11-30',1,0,NULL),(11,'Christmas Day','2012-12-25',1,0,NULL),(12,'Rizal Day','2012-07-30',1,0,NULL),(13,'Chinese New Year','2012-01-23',1,0,NULL),(14,'Ninoy Aquino Day','2012-08-21',1,0,NULL),(15,'All Saint\'s Day','2012-11-01',1,0,NULL),(16,'Additional Special Non-Working Day','2012-11-02',1,0,NULL),(17,'Last Day of the Year','2012-12-31',1,0,NULL);
/*!40000 ALTER TABLE `ohrm_holiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_holiday_location`
--

DROP TABLE IF EXISTS `ohrm_holiday_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_holiday_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `holiday_id` int(10) unsigned NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `holiday_id` (`holiday_id`),
  CONSTRAINT `ohrm_holiday_location_ibfk_1` FOREIGN KEY (`holiday_id`) REFERENCES `ohrm_holiday` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_holiday_location`
--

LOCK TABLES `ohrm_holiday_location` WRITE;
/*!40000 ALTER TABLE `ohrm_holiday_location` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_holiday_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_candidate`
--

DROP TABLE IF EXISTS `ohrm_job_candidate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_candidate` (
  `id` int(13) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contact_number` varchar(150) DEFAULT NULL,
  `status` int(4) NOT NULL,
  `comment` text,
  `mode_of_application` int(4) NOT NULL,
  `date_of_application` date NOT NULL,
  `cv_file_id` int(13) DEFAULT NULL,
  `cv_text_version` text,
  `keywords` varchar(255) DEFAULT NULL,
  `added_person` int(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `added_person` (`added_person`),
  CONSTRAINT `ohrm_job_candidate_ibfk_1` FOREIGN KEY (`added_person`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_candidate`
--

LOCK TABLES `ohrm_job_candidate` WRITE;
/*!40000 ALTER TABLE `ohrm_job_candidate` DISABLE KEYS */;
INSERT INTO `ohrm_job_candidate` VALUES (1,'Orange','','Orange','orange@yahoo.com','123 45 67',1,'',1,'2012-07-31',NULL,NULL,'',NULL);
/*!40000 ALTER TABLE `ohrm_job_candidate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_candidate_attachment`
--

DROP TABLE IF EXISTS `ohrm_job_candidate_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_candidate_attachment` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `candidate_id` int(13) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `file_content` mediumblob,
  `attachment_type` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `candidate_id` (`candidate_id`),
  CONSTRAINT `ohrm_job_candidate_attachment_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `ohrm_job_candidate` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_candidate_attachment`
--

LOCK TABLES `ohrm_job_candidate_attachment` WRITE;
/*!40000 ALTER TABLE `ohrm_job_candidate_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_job_candidate_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_candidate_history`
--

DROP TABLE IF EXISTS `ohrm_job_candidate_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_candidate_history` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `candidate_id` int(13) NOT NULL,
  `vacancy_id` int(13) DEFAULT NULL,
  `candidate_vacancy_name` varchar(255) DEFAULT NULL,
  `interview_id` int(13) DEFAULT NULL,
  `action` int(4) NOT NULL,
  `performed_by` int(13) DEFAULT NULL,
  `performed_date` datetime NOT NULL,
  `note` text,
  `interviewers` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `candidate_id` (`candidate_id`),
  KEY `vacancy_id` (`vacancy_id`),
  KEY `interview_id` (`interview_id`),
  KEY `performed_by` (`performed_by`),
  CONSTRAINT `ohrm_job_candidate_history_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `ohrm_job_candidate` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_job_candidate_history_ibfk_2` FOREIGN KEY (`vacancy_id`) REFERENCES `ohrm_job_vacancy` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ohrm_job_candidate_history_ibfk_3` FOREIGN KEY (`interview_id`) REFERENCES `ohrm_job_interview` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ohrm_job_candidate_history_ibfk_4` FOREIGN KEY (`performed_by`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_candidate_history`
--

LOCK TABLES `ohrm_job_candidate_history` WRITE;
/*!40000 ALTER TABLE `ohrm_job_candidate_history` DISABLE KEYS */;
INSERT INTO `ohrm_job_candidate_history` VALUES (1,1,1,'Team Leader',NULL,1,NULL,'2012-07-31 07:38:21',NULL,NULL),(2,1,NULL,NULL,NULL,16,NULL,'2012-07-31 07:38:21',NULL,NULL),(3,1,1,'Team Leader',NULL,9,NULL,'2012-08-06 03:40:37','',NULL);
/*!40000 ALTER TABLE `ohrm_job_candidate_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_candidate_vacancy`
--

DROP TABLE IF EXISTS `ohrm_job_candidate_vacancy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_candidate_vacancy` (
  `id` int(13) DEFAULT NULL,
  `candidate_id` int(13) NOT NULL,
  `vacancy_id` int(13) NOT NULL,
  `status` varchar(100) NOT NULL,
  `applied_date` date NOT NULL,
  PRIMARY KEY (`candidate_id`,`vacancy_id`),
  UNIQUE KEY `id` (`id`),
  KEY `vacancy_id` (`vacancy_id`),
  CONSTRAINT `ohrm_job_candidate_vacancy_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `ohrm_job_candidate` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_job_candidate_vacancy_ibfk_2` FOREIGN KEY (`vacancy_id`) REFERENCES `ohrm_job_vacancy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_candidate_vacancy`
--

LOCK TABLES `ohrm_job_candidate_vacancy` WRITE;
/*!40000 ALTER TABLE `ohrm_job_candidate_vacancy` DISABLE KEYS */;
INSERT INTO `ohrm_job_candidate_vacancy` VALUES (1,1,1,'HIRED','2012-07-31');
/*!40000 ALTER TABLE `ohrm_job_candidate_vacancy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_category`
--

DROP TABLE IF EXISTS `ohrm_job_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_category`
--

LOCK TABLES `ohrm_job_category` WRITE;
/*!40000 ALTER TABLE `ohrm_job_category` DISABLE KEYS */;
INSERT INTO `ohrm_job_category` VALUES (1,'Managerial'),(2,'Officer'),(3,'Secretarial'),(4,'Senior Officer'),(5,'SUPERVISOR'),(6,'Supplementary');
/*!40000 ALTER TABLE `ohrm_job_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_interview`
--

DROP TABLE IF EXISTS `ohrm_job_interview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_interview` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `candidate_vacancy_id` int(13) DEFAULT NULL,
  `candidate_id` int(13) DEFAULT NULL,
  `interview_name` varchar(100) NOT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_time` time DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`),
  KEY `candidate_vacancy_id` (`candidate_vacancy_id`),
  KEY `candidate_id` (`candidate_id`),
  CONSTRAINT `ohrm_job_interview_ibfk_1` FOREIGN KEY (`candidate_vacancy_id`) REFERENCES `ohrm_job_candidate_vacancy` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ohrm_job_interview_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `ohrm_job_candidate` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_interview`
--

LOCK TABLES `ohrm_job_interview` WRITE;
/*!40000 ALTER TABLE `ohrm_job_interview` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_job_interview` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_interview_attachment`
--

DROP TABLE IF EXISTS `ohrm_job_interview_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_interview_attachment` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `interview_id` int(13) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `file_content` mediumblob,
  `attachment_type` int(4) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interview_id` (`interview_id`),
  CONSTRAINT `ohrm_job_interview_attachment_ibfk_1` FOREIGN KEY (`interview_id`) REFERENCES `ohrm_job_interview` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_interview_attachment`
--

LOCK TABLES `ohrm_job_interview_attachment` WRITE;
/*!40000 ALTER TABLE `ohrm_job_interview_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_job_interview_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_interview_interviewer`
--

DROP TABLE IF EXISTS `ohrm_job_interview_interviewer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_interview_interviewer` (
  `interview_id` int(13) NOT NULL,
  `interviewer_id` int(13) NOT NULL,
  PRIMARY KEY (`interview_id`,`interviewer_id`),
  KEY `interviewer_id` (`interviewer_id`),
  CONSTRAINT `ohrm_job_interview_interviewer_ibfk_1` FOREIGN KEY (`interview_id`) REFERENCES `ohrm_job_interview` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_job_interview_interviewer_ibfk_2` FOREIGN KEY (`interviewer_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_interview_interviewer`
--

LOCK TABLES `ohrm_job_interview_interviewer` WRITE;
/*!40000 ALTER TABLE `ohrm_job_interview_interviewer` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_job_interview_interviewer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_specification_attachment`
--

DROP TABLE IF EXISTS `ohrm_job_specification_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_specification_attachment` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `job_title_id` int(13) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `file_content` mediumblob,
  PRIMARY KEY (`id`),
  KEY `job_title_id` (`job_title_id`),
  CONSTRAINT `ohrm_job_specification_attachment_ibfk_1` FOREIGN KEY (`job_title_id`) REFERENCES `ohrm_job_title` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_specification_attachment`
--

LOCK TABLES `ohrm_job_specification_attachment` WRITE;
/*!40000 ALTER TABLE `ohrm_job_specification_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_job_specification_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_title`
--

DROP TABLE IF EXISTS `ohrm_job_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_title` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `job_title` varchar(100) NOT NULL,
  `job_code` varchar(40) DEFAULT NULL,
  `job_description` varchar(400) DEFAULT NULL,
  `note` varchar(400) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_title`
--

LOCK TABLES `ohrm_job_title` WRITE;
/*!40000 ALTER TABLE `ohrm_job_title` DISABLE KEYS */;
INSERT INTO `ohrm_job_title` VALUES (1,'Accounting Assistant','Acctg  Asst','Accounting Assistant','',0),(2,'Assistant Manager','','Assistant Manager','',0),(3,'Business Development Officer','BDO','Business Development Officer','',0),(4,'Business Devt. Manager','BDM','Business Devt  Manager','',0),(5,'Department Manager','','Department Manager','',0),(6,'Deputy Admin Manager','DAM','Deputy Admin Manager','',0),(7,'DEPUTY MANAGER','','DEPUTY MANAGER','',0),(8,'Division Manager','','Division Manager','',0),(9,'Documentation Assistant','','Documentation Assistant','',0),(10,'Executive Secretary','ES','Executive Secretary','',0),(11,'Field Service Engineer','','Field Service Engineer','',0),(12,'Financial Analyst','FIN AN','Financial Analyst','',0),(13,'HR Officer','HR OFF','HR Officer','',0),(14,'Logistics Assistant','Log Asst','Logistics Assistant','',0),(15,'Office Trainee','','Office Trainee','',0),(16,'OFFICER','OFF','OFFICER','',0),(17,'Sales Assistant','','Sales Assistant','',0),(18,'Sales Officer','SO','Sales Officer','',0),(19,'Section Manager','SM','Section Manager','',0),(20,'Senior Assistant','Sr. Asst','Senior Assistant','',0),(21,'Supervisor','Sup','Supervisor','',0),(22,'Systems Administrator','SA','Systems Administrator','',0),(23,'Team Leader','','Team Leader','',0),(24,'TRADE SPECIALIST','Trd Spclt','TRADE SPECIALIST','',0);
/*!40000 ALTER TABLE `ohrm_job_title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_job_vacancy`
--

DROP TABLE IF EXISTS `ohrm_job_vacancy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_vacancy` (
  `id` int(13) NOT NULL,
  `job_title_code` int(4) NOT NULL,
  `hiring_manager_id` int(13) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `no_of_positions` int(13) DEFAULT NULL,
  `status` int(4) NOT NULL,
  `published_in_feed` tinyint(1) NOT NULL DEFAULT '0',
  `defined_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `job_title_code` (`job_title_code`),
  KEY `hiring_manager_id` (`hiring_manager_id`),
  CONSTRAINT `ohrm_job_vacancy_ibfk_1` FOREIGN KEY (`job_title_code`) REFERENCES `ohrm_job_title` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_job_vacancy_ibfk_2` FOREIGN KEY (`hiring_manager_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_vacancy`
--

LOCK TABLES `ohrm_job_vacancy` WRITE;
/*!40000 ALTER TABLE `ohrm_job_vacancy` DISABLE KEYS */;
INSERT INTO `ohrm_job_vacancy` VALUES (1,23,23,'Team Leader','',1,1,1,'2012-07-31 07:37:13','2012-07-31 07:37:13');
/*!40000 ALTER TABLE `ohrm_job_vacancy` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_insert_ohrm_job_vacancy` AFTER INSERT ON `ohrm_job_vacancy`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = 1;
  CALL audit_insert_ohrm_job_vacancy(@orangehrm_user, @orangehrm_action_name, new_version, NEW.id, NEW.job_title_code, NEW.hiring_manager_id, NEW.status, NEW.description, NEW.`name`, NEW.no_of_positions, NEW.published_in_feed);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_update_ohrm_job_vacancy` AFTER UPDATE ON `ohrm_job_vacancy`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_recruitment_job_vacancy_trail` WHERE `affected_entity_id` = OLD.id) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_update_ohrm_job_vacancy(@orangehrm_user, @orangehrm_action_name, new_version, OLD.id, NEW.job_title_code, OLD.job_title_code, NEW.hiring_manager_id, OLD.hiring_manager_id, NEW.status, OLD.status, NEW.description, OLD.description,NEW.name,OLD.name,NEW.no_of_positions,OLD.no_of_positions,NEW.published_in_feed,OLD.published_in_feed);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `watch_delete_ohrm_job_vacancy` AFTER DELETE ON `ohrm_job_vacancy`
 FOR EACH ROW BEGIN
  DECLARE new_version INT;
  SET new_version = (SELECT MAX(`version_id`) FROM `ohrm_audittrail_recruitment_job_vacancy_trail` WHERE `affected_entity_id` = OLD.id) + 1;
  SET new_version = IFNULL(new_version, 1);
  CALL audit_delete_ohrm_job_vacancy(@orangehrm_user, @orangehrm_action_name, new_version,  OLD.id, OLD.job_title_code, OLD.hiring_manager_id, OLD.status, OLD.description, OLD.`name`, OLD.no_of_positions, OLD.published_in_feed);
  CALL archive_reference('ohrm_job_vacancy', OLD.id, CONCAT(OLD.name, ' - ', (IFNULL((SELECT `job_title` FROM `ohrm_job_title` WHERE `id` = OLD.job_title_code), 'NULL'))));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `ohrm_job_vacancy_attachment`
--

DROP TABLE IF EXISTS `ohrm_job_vacancy_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_job_vacancy_attachment` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `vacancy_id` int(13) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `file_content` mediumblob,
  `attachment_type` int(4) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vacancy_id` (`vacancy_id`),
  CONSTRAINT `ohrm_job_vacancy_attachment_ibfk_1` FOREIGN KEY (`vacancy_id`) REFERENCES `ohrm_job_vacancy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_job_vacancy_attachment`
--

LOCK TABLES `ohrm_job_vacancy_attachment` WRITE;
/*!40000 ALTER TABLE `ohrm_job_vacancy_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_job_vacancy_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_language`
--

DROP TABLE IF EXISTS `ohrm_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_language`
--

LOCK TABLES `ohrm_language` WRITE;
/*!40000 ALTER TABLE `ohrm_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_leave_accrual_time_schedule`
--

DROP TABLE IF EXISTS `ohrm_leave_accrual_time_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_leave_accrual_time_schedule` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL,
  `location_code` int(11) DEFAULT NULL,
  `scheduled_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_leave_accrual_time_schedule`
--

LOCK TABLES `ohrm_leave_accrual_time_schedule` WRITE;
/*!40000 ALTER TABLE `ohrm_leave_accrual_time_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_leave_accrual_time_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_license`
--

DROP TABLE IF EXISTS `ohrm_license`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_license` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_license`
--

LOCK TABLES `ohrm_license` WRITE;
/*!40000 ALTER TABLE `ohrm_license` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_license` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_location`
--

DROP TABLE IF EXISTS `ohrm_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(110) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `province` varchar(60) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(35) DEFAULT NULL,
  `phone` varchar(35) DEFAULT NULL,
  `fax` varchar(35) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `tax_id` varchar(60) DEFAULT NULL,
  `industry` varchar(60) DEFAULT NULL,
  `ssn_no` varchar(60) DEFAULT NULL,
  `hdmf_no` varchar(60) DEFAULT NULL,
  `phc_no` varchar(60) DEFAULT NULL,
  `company_code` varchar(60) DEFAULT NULL,
  `company_type` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`),
  CONSTRAINT `ohrm_location_ibfk_1` FOREIGN KEY (`country_code`) REFERENCES `hs_hr_country` (`cou_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_location`
--

LOCK TABLES `ohrm_location` WRITE;
/*!40000 ALTER TABLE `ohrm_location` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_membership`
--

DROP TABLE IF EXISTS `ohrm_membership`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_membership` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_membership`
--

LOCK TABLES `ohrm_membership` WRITE;
/*!40000 ALTER TABLE `ohrm_membership` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_membership` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_module`
--

DROP TABLE IF EXISTS `ohrm_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_module`
--

LOCK TABLES `ohrm_module` WRITE;
/*!40000 ALTER TABLE `ohrm_module` DISABLE KEYS */;
INSERT INTO `ohrm_module` VALUES (1,'core',1),(2,'admin',1),(3,'pim',1),(4,'leave',1),(5,'time',1),(6,'attendance',1),(7,'recruitment',1),(8,'recruitmentApply',1),(9,'performance',1);
/*!40000 ALTER TABLE `ohrm_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_nationality`
--

DROP TABLE IF EXISTS `ohrm_nationality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_nationality` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_nationality`
--

LOCK TABLES `ohrm_nationality` WRITE;
/*!40000 ALTER TABLE `ohrm_nationality` DISABLE KEYS */;
INSERT INTO `ohrm_nationality` VALUES (1,'Afghan'),(2,'Albanian'),(3,'Algerian'),(4,'American'),(5,'Andorran'),(6,'Angolan'),(7,'Antiguans'),(8,'Argentinean'),(9,'Armenian'),(10,'Australian'),(11,'Austrian'),(12,'Azerbaijani'),(13,'Bahamian'),(14,'Bahraini'),(15,'Bangladeshi'),(16,'Barbadian'),(17,'Barbudans'),(18,'Batswana'),(19,'Belarusian'),(20,'Belgian'),(21,'Belizean'),(22,'Beninese'),(23,'Bhutanese'),(24,'Bolivian'),(25,'Bosnian'),(26,'Brazilian'),(27,'British'),(28,'Bruneian'),(29,'Bulgarian'),(30,'Burkinabe'),(31,'Burmese'),(32,'Burundian'),(33,'Cambodian'),(34,'Cameroonian'),(35,'Canadian'),(36,'Cape Verdean'),(37,'Central African'),(38,'Chadian'),(39,'Chilean'),(40,'Chinese'),(41,'Colombian'),(42,'Comoran'),(43,'Congolese'),(44,'Costa Rican'),(45,'Croatian'),(46,'Cuban'),(47,'Cypriot'),(48,'Czech'),(49,'Danish'),(50,'Djibouti'),(51,'Dominican'),(52,'Dutch'),(53,'East Timorese'),(54,'Ecuadorean'),(55,'Egyptian'),(56,'Emirian'),(57,'Equatorial Guinean'),(58,'Eritrean'),(59,'Estonian'),(60,'Ethiopian'),(61,'Fijian'),(62,'Filipino'),(63,'Finnish'),(64,'French'),(65,'Gabonese'),(66,'Gambian'),(67,'Georgian'),(68,'German'),(69,'Ghanaian'),(70,'Greek'),(71,'Grenadian'),(72,'Guatemalan'),(73,'Guinea-Bissauan'),(74,'Guinean'),(75,'Guyanese'),(76,'Haitian'),(77,'Herzegovinian'),(78,'Honduran'),(79,'Hungarian'),(80,'I-Kiribati'),(81,'Icelander'),(82,'Indian'),(83,'Indonesian'),(84,'Iranian'),(85,'Iraqi'),(86,'Irish'),(87,'Israeli'),(88,'Italian'),(89,'Ivorian'),(90,'Jamaican'),(91,'Japanese'),(92,'Jordanian'),(93,'Kazakhstani'),(94,'Kenyan'),(95,'Kittian and Nevisian'),(96,'Kuwaiti'),(97,'Kyrgyz'),(98,'Laotian'),(99,'Latvian'),(100,'Lebanese'),(101,'Liberian'),(102,'Libyan'),(103,'Liechtensteiner'),(104,'Lithuanian'),(105,'Luxembourger'),(106,'Macedonian'),(107,'Malagasy'),(108,'Malawian'),(109,'Malaysian'),(110,'Maldivan'),(111,'Malian'),(112,'Maltese'),(113,'Marshallese'),(114,'Mauritanian'),(115,'Mauritian'),(116,'Mexican'),(117,'Micronesian'),(118,'Moldovan'),(119,'Monacan'),(120,'Mongolian'),(121,'Moroccan'),(122,'Mosotho'),(123,'Motswana'),(124,'Mozambican'),(125,'Namibian'),(126,'Nauruan'),(127,'Nepalese'),(128,'New Zealander'),(129,'Nicaraguan'),(130,'Nigerian'),(131,'Nigerien'),(132,'North Korean'),(133,'Northern Irish'),(134,'Norwegian'),(135,'Omani'),(136,'Pakistani'),(137,'Palauan'),(138,'Panamanian'),(139,'Papua New Guinean'),(140,'Paraguayan'),(141,'Peruvian'),(142,'Polish'),(143,'Portuguese'),(144,'Qatari'),(145,'Romanian'),(146,'Russian'),(147,'Rwandan'),(148,'Saint Lucian'),(149,'Salvadoran'),(150,'Samoan'),(151,'San Marinese'),(152,'Sao Tomean'),(153,'Saudi'),(154,'Scottish'),(155,'Senegalese'),(156,'Serbian'),(157,'Seychellois'),(158,'Sierra Leonean'),(159,'Singaporean'),(160,'Slovakian'),(161,'Slovenian'),(162,'Solomon Islander'),(163,'Somali'),(164,'South African'),(165,'South Korean'),(166,'Spanish'),(167,'Sri Lankan'),(168,'Sudanese'),(169,'Surinamer'),(170,'Swazi'),(171,'Swedish'),(172,'Swiss'),(173,'Syrian'),(174,'Taiwanese'),(175,'Tajik'),(176,'Tanzanian'),(177,'Thai'),(178,'Togolese'),(179,'Tongan'),(180,'Trinidadian or Tobagonian'),(181,'Tunisian'),(182,'Turkish'),(183,'Tuvaluan'),(184,'Ugandan'),(185,'Ukrainian'),(186,'Uruguayan'),(187,'Uzbekistani'),(188,'Venezuelan'),(189,'Vietnamese'),(190,'Welsh'),(191,'Yemenite'),(192,'Zambian'),(193,'Zimbabwean');
/*!40000 ALTER TABLE `ohrm_nationality` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_notifications_definition`
--

DROP TABLE IF EXISTS `ohrm_notifications_definition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_notifications_definition` (
  `nd_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `nt_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `text` text,
  `subject` varchar(255) DEFAULT NULL,
  `recipients` varchar(255) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `param_values` varchar(255) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  PRIMARY KEY (`nd_id`),
  KEY `employee_id` (`employee_id`),
  KEY `nt_id` (`nt_id`),
  CONSTRAINT `ohrm_notifications_definition_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_notifications_definition_ibfk_2` FOREIGN KEY (`nt_id`) REFERENCES `ohrm_notifications_type` (`nt_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_notifications_definition`
--

LOCK TABLES `ohrm_notifications_definition` WRITE;
/*!40000 ALTER TABLE `ohrm_notifications_definition` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_notifications_definition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_notifications_type`
--

DROP TABLE IF EXISTS `ohrm_notifications_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_notifications_type` (
  `nt_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `text` text,
  `subject` varchar(255) DEFAULT NULL,
  `param` varchar(255) DEFAULT NULL,
  `notification_class` varchar(255) NOT NULL,
  `recipient_types` varchar(255) NOT NULL,
  PRIMARY KEY (`nt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_notifications_type`
--

LOCK TABLES `ohrm_notifications_type` WRITE;
/*!40000 ALTER TABLE `ohrm_notifications_type` DISABLE KEYS */;
INSERT INTO `ohrm_notifications_type` VALUES (1,'DOB Notification','PIM','Hi RECIPINTNAME ,\r\n\r\nThis is to remind that FIRSTNAME LASTNAME\'s Date of Birth is getting closer and only DAYSLEFT due for the date.\r\n\r\nRegards,\r\nHR Team','DOB Notification','','EmployeeEventNotification','staffOwningEvent,Admin'),(2,'Driving License Expiry Date','PIM','Hi RECIPINTNAME,\r\n\r\nThis is to remind that FIRSTNAME LASTNAME\'s Driving License Expiry Date is getting closer and only DAYSLEFT is due for the expiry date. Please take necessary steps needed to renew it.\r\n\r\nRegards,\r\nHR Team','Driving License Expiry Date','','EmployeeEventNotification','staffOwningEvent,Admin'),(3,'Immigration Expiry Notification','PIM','Hi RECIPINTNAME,\r\n\r\nThis is to remind that FIRSTNAME LASTNAME\'s Passport/Visa Expiry Date is getting closer and only DAYSLEFT is due for the expiry date.\r\nPlease take necessary steps needed to renew it.\r\nDetails follow.\r\n\r\nPassport/Visa No : NUMBER\r\nCountry : COUNTRY\r\nIssued Date : PASSPORT_ISSUE_DATE\r\nExpiry Date : PASSPORT_EXPIRE_DATE\r\n\r\nRegards,\r\nHR Team','Immigration Expiry Notification','','EmployeeEventNotification','staffOwningEvent,Admin'),(4,'Licenses Expiry Notification','PIM','Hi RECIPINTNAME,\r\n\r\nThis is to remind that FIRSTNAME LASTNAME\'\'s License Expiry Date is getting closer and only DAYSLEFT is due for the expiry date.\r\nPlease take necessary steps needed to renew it.\r\nDetails follow.\r\n\r\nLicense Type : NAME\r\nStart Date : LICENSEISSUEDDATE\r\nEnd Date : LICENSEEXPIRYDATE\r\n\r\nRegards,\r\nHR Team','Licenses Expiry Notification',NULL,'EmployeeEventNotification','staffOwningEvent,Admin'),(5,'Memberships Expiry Notification','PIM','Hi RECIPINTNAME,\r\n\r\nThis is to remind that FIRSTNAME LASTNAME\'\'s Membership Expiry Date is getting closer and only DAYSLEFT is due for the expiry date.\r\n\r\nPlease take necessary steps needed to renew it.\r\n\r\nDetails follow.\r\nMembership : NAME\r\nOwnership : SUBSCRIPTIONPAIDBY\r\nCommence Date : SUBSCRIPTIONCOMMENCEDATE\r\nRenewal Date : SUBSCRIPTIONRENEWALDATE\r\n\r\nRegards,\r\nHR Team','Memberships Expiry Notification',NULL,'EmployeeEventNotification','staffOwningEvent,Admin'),(6,'Employee Contracts Notification','PIM','Hi RECIPINTNAME,\r\n\r\nThis is to remind that FIRSTNAME LASTNAME\'s Contract Extension End Date is getting closer and only DAYSLEFT is due for the expiry date.\r\nPlease take necessary steps needed to renew it.\r\nDetails follow.\r\n\r\nContract id : CONTRACT_ID\r\nContract Extension Start Date : START_DATE\r\nContract Extension End Date : END_DATE\r\n\r\nRegards,\r\nHR Team','Employee Contracts Notification',NULL,'EmployeeEventNotification','staffOwningEvent,Admin'),(7,'Retirement Notification','PIM','Hi RECIPINTNAME,\r\n\r\nThis is to remind that FIRSTNAME LASTNAME\'s retirement date is getting closer and only DAYSLEFT are due for the retirement date. \r\nPlease provide HR with the following: \r\nbirth certificate, letters of employments from previous institutions for Pension purposes. \r\n\r\nPlease contact HR Office for assistance.\r\n\r\nRegards,\r\nHR Team ','Retirement Notification','{\"Year\": \"sfWidgetFormInputText\"}','RetirementEventNotification','staffOwningEvent,Admin'),(8,'Salary Increment Notification','PIM','Hi RECIPINTNAME,\r\n\r\nThis is to remind you that FIRSTNAME LASTNAME\'s salary increment is getting closer and only DAYSLEFT due for the date.\r\n\r\nRegards,\r\nHR Team ','Salary Increment','','EmployeeEventNotification','staffOwningEvent,Admin');
/*!40000 ALTER TABLE `ohrm_notifications_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_operational_country`
--

DROP TABLE IF EXISTS `ohrm_operational_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_operational_country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ohrm_operational_country_hs_hr_country` (`country_code`),
  CONSTRAINT `fk_ohrm_operational_country_hs_hr_country` FOREIGN KEY (`country_code`) REFERENCES `hs_hr_country` (`cou_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_operational_country`
--

LOCK TABLES `ohrm_operational_country` WRITE;
/*!40000 ALTER TABLE `ohrm_operational_country` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_operational_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_organization_gen_info`
--

DROP TABLE IF EXISTS `ohrm_organization_gen_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_organization_gen_info` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tax_id` varchar(30) DEFAULT NULL,
  `registration_number` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `province` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `zip_code` varchar(30) DEFAULT NULL,
  `street1` varchar(100) DEFAULT NULL,
  `street2` varchar(100) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `ssn_no` varchar(60) DEFAULT NULL,
  `hdmf_no` varchar(60) DEFAULT NULL,
  `phc_no` varchar(60) DEFAULT NULL,
  `company_code` varchar(60) DEFAULT NULL,
  `company_type` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_organization_gen_info`
--

LOCK TABLES `ohrm_organization_gen_info` WRITE;
/*!40000 ALTER TABLE `ohrm_organization_gen_info` DISABLE KEYS */;
INSERT INTO `ohrm_organization_gen_info` VALUES (1,'ITOCHU CORPORATION','000156785000','General Trading','8571111','8571112','perez-j@itochu.com.ph','PH','',' Makati City','1226','16/F 6788 Ayala Ave.,','Oledan Square,','','0303049001','0303049001','200276300578','',0);
/*!40000 ALTER TABLE `ohrm_organization_gen_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_pay_grade`
--

DROP TABLE IF EXISTS `ohrm_pay_grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_pay_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_pay_grade`
--

LOCK TABLES `ohrm_pay_grade` WRITE;
/*!40000 ALTER TABLE `ohrm_pay_grade` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_pay_grade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_pay_grade_currency`
--

DROP TABLE IF EXISTS `ohrm_pay_grade_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_pay_grade_currency` (
  `pay_grade_id` int(11) NOT NULL,
  `currency_id` varchar(6) NOT NULL DEFAULT '',
  `min_salary` double DEFAULT NULL,
  `max_salary` double DEFAULT NULL,
  PRIMARY KEY (`pay_grade_id`,`currency_id`),
  KEY `currency_id` (`currency_id`),
  CONSTRAINT `ohrm_pay_grade_currency_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `hs_hr_currency_type` (`currency_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_pay_grade_currency_ibfk_2` FOREIGN KEY (`pay_grade_id`) REFERENCES `ohrm_pay_grade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_pay_grade_currency`
--

LOCK TABLES `ohrm_pay_grade_currency` WRITE;
/*!40000 ALTER TABLE `ohrm_pay_grade_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_pay_grade_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_project`
--

DROP TABLE IF EXISTS `ohrm_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`project_id`,`customer_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_project`
--

LOCK TABLES `ohrm_project` WRITE;
/*!40000 ALTER TABLE `ohrm_project` DISABLE KEYS */;
INSERT INTO `ohrm_project` VALUES (1,1,'Betson Project','',0);
/*!40000 ALTER TABLE `ohrm_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_project_activity`
--

DROP TABLE IF EXISTS `ohrm_project_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_project_activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(110) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`activity_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `ohrm_project_activity_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `ohrm_project` (`project_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_project_activity`
--

LOCK TABLES `ohrm_project_activity` WRITE;
/*!40000 ALTER TABLE `ohrm_project_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_project_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_project_admin`
--

DROP TABLE IF EXISTS `ohrm_project_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_project_admin` (
  `project_id` int(11) NOT NULL,
  `emp_number` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`emp_number`),
  KEY `emp_number` (`emp_number`),
  CONSTRAINT `ohrm_project_admin_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `ohrm_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_project_admin_ibfk_2` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_project_admin`
--

LOCK TABLES `ohrm_project_admin` WRITE;
/*!40000 ALTER TABLE `ohrm_project_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_project_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_report`
--

DROP TABLE IF EXISTS `ohrm_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_report` (
  `report_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `report_group_id` bigint(20) NOT NULL,
  `use_filter_field` tinyint(1) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`report_id`),
  KEY `report_group_id` (`report_group_id`),
  CONSTRAINT `ohrm_report_ibfk_1` FOREIGN KEY (`report_group_id`) REFERENCES `ohrm_report_group` (`report_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_report`
--

LOCK TABLES `ohrm_report` WRITE;
/*!40000 ALTER TABLE `ohrm_report` DISABLE KEYS */;
INSERT INTO `ohrm_report` VALUES (1,'Project Report',1,1,NULL),(2,'Employee Report',1,1,NULL),(3,'Project Activity Details',1,1,NULL),(4,'Attendance Total Summary Report',2,0,NULL),(5,'PIM Sample Report',3,1,'PIM_DEFINED'),(6,'Employee Leave Report',4,1,'ADVANCED_REPORTS'),(7,'Employee Turnover Hiring Report',5,1,'ADVANCED_REPORTS'),(8,'Employee Turnover Termination Report',6,1,'ADVANCED_REPORTS'),(9,'Head Count Report',7,1,'ADVANCED_REPORTS'),(10,'Vacancy Succession Report',8,1,'ADVANCED_REPORTS');
/*!40000 ALTER TABLE `ohrm_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_report_group`
--

DROP TABLE IF EXISTS `ohrm_report_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_report_group` (
  `report_group_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `core_sql` mediumtext NOT NULL,
  PRIMARY KEY (`report_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_report_group`
--

LOCK TABLES `ohrm_report_group` WRITE;
/*!40000 ALTER TABLE `ohrm_report_group` DISABLE KEYS */;
INSERT INTO `ohrm_report_group` VALUES (1,'timesheet','SELECT selectCondition FROM ohrm_project_activity LEFT JOIN (SELECT * FROM ohrm_timesheet_item WHERE whereCondition1) AS ohrm_timesheet_item  ON (ohrm_timesheet_item.activity_id = ohrm_project_activity.activity_id) LEFT JOIN ohrm_project ON (ohrm_project.project_id = ohrm_project_activity.project_id) LEFT JOIN hs_hr_employee ON (hs_hr_employee.emp_number = ohrm_timesheet_item.employee_id) LEFT JOIN ohrm_timesheet ON (ohrm_timesheet.timesheet_id = ohrm_timesheet_item.timesheet_id) LEFT JOIN ohrm_customer ON (ohrm_customer.customer_id = ohrm_project.customer_id) WHERE whereCondition2 groupByClause ORDER BY ohrm_customer.name, ohrm_project.name, ohrm_project_activity.name, hs_hr_employee.emp_lastname, hs_hr_employee.emp_firstname'),(2,'attendance','SELECT selectCondition FROM hs_hr_employee LEFT JOIN (SELECT * FROM ohrm_attendance_record WHERE ( ( ohrm_attendance_record.punch_in_user_time BETWEEN \"#@fromDate@,@1970-01-01@#\" AND #@\"toDate\"@,@CURDATE()@# ) AND ( ohrm_attendance_record.punch_out_user_time BETWEEN \"#@fromDate@,@1970-01-01@#\" AND #@\"toDate\"@,@CURDATE()@# ) ) ) AS ohrm_attendance_record ON (hs_hr_employee.emp_number = ohrm_attendance_record.employee_id) WHERE hs_hr_employee.emp_number = #@employeeId@,@hs_hr_employee.emp_number AND (hs_hr_employee.termination_id is null) @# AND (hs_hr_employee.job_title_code = #@\"jobTitle\")@,@hs_hr_employee.job_title_code OR hs_hr_employee.job_title_code is null)@# AND (hs_hr_employee.work_station IN (#@subUnit)@,@SELECT id FROM ohrm_subunit) OR hs_hr_employee.work_station is null@#) AND (hs_hr_employee.emp_status = #@\"employeeStatus\")@,@hs_hr_employee.emp_status OR hs_hr_employee.emp_status is null)@# groupByClause ORDER BY hs_hr_employee.emp_lastname, hs_hr_employee.emp_firstname'),(3,'pim','SELECT selectCondition FROM hs_hr_employee \n                    LEFT JOIN hs_hr_emp_emergency_contacts ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_emergency_contacts.emp_number) \n                    LEFT JOIN ohrm_subunit ON \n                        (hs_hr_employee.work_station = ohrm_subunit.id) \n                    LEFT JOIN ohrm_employment_status ON \n                        (hs_hr_employee.emp_status = ohrm_employment_status.id) \n                    LEFT JOIN ohrm_job_title ON\n                        (hs_hr_employee.job_title_code = ohrm_job_title.id)\n                    LEFT JOIN ohrm_job_category ON \n                        (hs_hr_employee.eeo_cat_code = ohrm_job_category.id) \n                    LEFT JOIN ohrm_nationality ON\n                        (hs_hr_employee.nation_code = ohrm_nationality.id)\n                    LEFT JOIN hs_hr_emp_dependents ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_dependents.emp_number)\n                    LEFT JOIN hs_hr_emp_locations AS emp_location ON\n                        (hs_hr_employee.emp_number = emp_location.emp_number)\n                    LEFT JOIN ohrm_location ON\n                        (emp_location.location_id = ohrm_location.id)\n                    LEFT JOIN hs_hr_emp_contract_extend ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_contract_extend.emp_number) \n                    LEFT JOIN hs_hr_emp_basicsalary ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_basicsalary.emp_number) \n                    LEFT JOIN ohrm_pay_grade ON \n                        (hs_hr_emp_basicsalary.sal_grd_code = ohrm_pay_grade.id) \n                    LEFT JOIN hs_hr_currency_type ON \n                        (hs_hr_emp_basicsalary.currency_id = hs_hr_currency_type.currency_id) \n                    LEFT JOIN hs_hr_payperiod ON \n                        (hs_hr_emp_basicsalary.payperiod_code = hs_hr_payperiod.payperiod_code) \n                    LEFT JOIN hs_hr_emp_passport ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_passport.emp_number) \n                    LEFT JOIN hs_hr_emp_reportto AS subordinate_list ON \n                        (hs_hr_employee.emp_number = subordinate_list.erep_sup_emp_number) \n                    LEFT JOIN hs_hr_employee AS subordinate ON\n                        (subordinate.emp_number = subordinate_list.erep_sub_emp_number)\n                    LEFT JOIN ohrm_emp_reporting_method AS subordinate_reporting_method ON \n                        (subordinate_list.erep_reporting_mode = subordinate_reporting_method.reporting_method_id) \n                    LEFT JOIN hs_hr_emp_work_experience ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_work_experience.emp_number) \n                    LEFT JOIN ohrm_emp_education ON \n                        (hs_hr_employee.emp_number = ohrm_emp_education.emp_number) \n                    LEFT JOIN ohrm_education ON \n                        (ohrm_emp_education.education_id = ohrm_education.id) \n                    LEFT JOIN hs_hr_emp_skill ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_skill.emp_number) \n                    LEFT JOIN ohrm_skill ON \n                        (hs_hr_emp_skill.skill_id = ohrm_skill.id) \n                    LEFT JOIN hs_hr_emp_language ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_language.emp_number) \n                    LEFT JOIN ohrm_language ON \n                        (hs_hr_emp_language.lang_id = ohrm_language.id) \n                    LEFT JOIN ohrm_emp_license ON \n                        (hs_hr_employee.emp_number = ohrm_emp_license.emp_number) \n                    LEFT JOIN ohrm_license ON \n                        (ohrm_emp_license.license_id = ohrm_license.id) \n                    LEFT JOIN hs_hr_emp_member_detail ON \n                        (hs_hr_employee.emp_number = hs_hr_emp_member_detail.emp_number) \n                    LEFT JOIN ohrm_membership ON\n                        (hs_hr_emp_member_detail.membship_code = ohrm_membership.id)\n                    LEFT JOIN hs_hr_country ON \n                        (hs_hr_employee.coun_code = hs_hr_country.cou_code) \n                    LEFT JOIN hs_hr_emp_directdebit ON \n                        (hs_hr_emp_basicsalary.id = hs_hr_emp_directdebit.salary_id) \n                    LEFT JOIN hs_hr_emp_reportto AS supervisor_list ON \n                        (hs_hr_employee.emp_number = supervisor_list.erep_sub_emp_number) \n                    LEFT JOIN hs_hr_employee AS supervisor ON\n                        (supervisor.emp_number = supervisor_list.erep_sup_emp_number)\n                    LEFT JOIN ohrm_emp_reporting_method AS supervisor_reporting_method ON \n                        (supervisor_list.erep_reporting_mode = supervisor_reporting_method.reporting_method_id) \n                    LEFT JOIN ohrm_emp_termination ON\n                        (hs_hr_employee.termination_id = ohrm_emp_termination.id)\n                    LEFT JOIN ohrm_emp_termination_reason ON\n                        (ohrm_emp_termination.reason_id = ohrm_emp_termination_reason.id)\n                WHERE hs_hr_employee.emp_number in (\n                    SELECT hs_hr_employee.emp_number FROM hs_hr_employee\n                        LEFT JOIN hs_hr_emp_basicsalary ON \n                            (hs_hr_employee.emp_number = hs_hr_emp_basicsalary.emp_number) \n                        LEFT JOIN ohrm_emp_education ON \n                            (hs_hr_employee.emp_number = ohrm_emp_education.emp_number) \n                        LEFT JOIN hs_hr_emp_skill ON \n                            (hs_hr_employee.emp_number = hs_hr_emp_skill.emp_number) \n                        LEFT JOIN hs_hr_emp_language ON \n                            (hs_hr_employee.emp_number = hs_hr_emp_language.emp_number) \n                    WHERE whereCondition1\n                )\n                GROUP BY \n                     hs_hr_employee.emp_number,\n                     hs_hr_employee.emp_lastname,\n                     hs_hr_employee.emp_firstname,\n                     hs_hr_employee.emp_middle_name,\n                     hs_hr_employee.emp_birthday,\n                     ohrm_nationality.name,\n                     hs_hr_employee.emp_gender,\n                     hs_hr_employee.emp_marital_status,\n                     hs_hr_employee.emp_dri_lice_num,\n                     hs_hr_employee.emp_dri_lice_exp_date,\n                     hs_hr_employee.emp_street1,\n                     hs_hr_employee.emp_street2,\n                     hs_hr_employee.city_code,\n                     hs_hr_employee.provin_code,\n                     hs_hr_employee.emp_zipcode,\n                     hs_hr_country.cou_code,\n                     hs_hr_employee.emp_hm_telephone,\n                     hs_hr_employee.emp_mobile,\n                     hs_hr_employee.emp_work_telephone,\n                     hs_hr_employee.emp_work_email,\n                     hs_hr_employee.emp_oth_email\n\nORDER BY hs_hr_employee.emp_lastname\n'),(4,'employee_leave','SELECT  selectCondition\nFROM (\n  SELECT\n  CONVERT(CONCAT(lp.leave_period_start_date, \' to \', lp.leave_period_end_date) USING latin1) AS `leave_period`, \n  lt.leave_type_name AS `type`,\n  q.no_of_days_allotted AS `entitlement`,\n  SUM(IF(l.leave_status = 3, l.leave_length_days, 0)) AS `taken_days`, -- Taken status is 3\n  IF(YEAR(CURDATE()) = YEAR(lp.leave_period_start_date),0,SUM(IF(l.leave_status = 2, l.leave_length_days, 0))) AS `scheduled_days`,\n  q.leave_brought_forward AS `brought_forward`,\n  (q.leave_brought_forward + q.no_of_days_allotted) AS `total`\n  FROM hs_hr_employee_leave_quota AS q\n  LEFT JOIN hs_hr_leavetype lt ON q.leave_type_id = lt.leave_type_id\n  LEFT JOIN hs_hr_leave_period lp ON q.leave_period_id = lp.leave_period_id\n  LEFT JOIN hs_hr_leave_requests lr \n    ON q.leave_period_id = lr.leave_period_id AND q.leave_type_id = lr.leave_type_id AND q.employee_id = lr.employee_id\n  LEFT JOIN hs_hr_leave l ON lr.leave_request_id = l.leave_request_id\n  WHERE whereCondition1\n  GROUP BY q.leave_type_id, q.leave_period_id\n  ORDER BY `leave_period` DESC\n) AS `report_records`'),(5,'employee_turnover_hiring','SELECT selectCondition\nFROM `hs_hr_employee` AS e\nLEFT JOIN `ohrm_subunit` AS cs\n  ON e.work_station = cs.id\nLEFT JOIN `ohrm_job_title` AS jt\n  ON e.job_title_code = jt.id\nWHERE e.joined_date IS NOT NULL\n  AND whereCondition1\nGROUP BY e.emp_number\n;'),(6,'employee_turnover_termination','SELECT selectCondition\nFROM `hs_hr_employee` AS e\nLEFT JOIN `ohrm_subunit` AS cs\n  ON e.work_station = cs.id\nLEFT JOIN `ohrm_job_title` AS jt\n  ON e.job_title_code = jt.id\nLEFT JOIN `ohrm_emp_termination` AS te\n  ON e.termination_id = te.id\nLEFT JOIN `ohrm_emp_termination_reason` AS tr\n  ON te.reason_id = tr.id\nWHERE e.termination_id IS NOT NULL\n  AND whereCondition1\n;'),(7,'head_count','SELECT selectCondition\nFROM hs_hr_employee e\n\nLEFT JOIN ohrm_subunit s\nON whereCondition1 \n\nLEFT JOIN ohrm_subunit c\nON e.work_station = c.id\n\nLEFT JOIN ohrm_subunit p\nON get_parant_id(p.id) = s.id AND p.lft <= c.lft and p.rgt >= c.rgt\n\nWHERE \n\nwhereCondition2\n\nAND\n\nwhereCondition3\n\nAND \n\n((s.id = 1)\nOR\n(p.id is not null or s.id = c.id))\n\nGROUP BY sub_unit\n;'),(8,'vacancy_succession','SELECT selectCondition\nFROM\n(\n  SELECT\n  v.id,\n  v.hiring_manager_id,\n  v.job_title_code,\n  DATE(v.defined_time) AS `vacancy_added_date`,\n  jt.job_title AS `job_title`,\n  v.no_of_positions AS `no_of_positions`,\n  (\n    SELECT COUNT(*) FROM ohrm_job_candidate_vacancy AS jcv\n    WHERE jcv.vacancy_id = v.id\n  ) AS `no_of_applicants`,\n  SUM(IF(jch.action = 2, 1, 0)) AS `no_of_shortlisted`,\n  SUM(IF(action = 4, 1, 0)) AS `no_of_scheduled`,\n  SUM(IF(action = 5, 1, 0)) AS `no_of_passed`,\n  SUM(IF(action = 6, 1, 0)) AS `no_of_failed`,\n  SUM(IF(action = 9, 1, 0)) AS `no_of_hired`\n  FROM `ohrm_job_vacancy` v\n  INNER JOIN `ohrm_job_title` AS jt\n    ON v.job_title_code = jt.id\n  INNER JOIN `ohrm_job_candidate_history` AS jch\n    ON jch.vacancy_id = v.id\n  GROUP BY jch.candidate_id\n  ) AS r\nWHERE whereCondition1\nGROUP BY r.id\nORDER BY r.vacancy_added_date DESC\n;');
/*!40000 ALTER TABLE `ohrm_report_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_role_user_selection_rule`
--

DROP TABLE IF EXISTS `ohrm_role_user_selection_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_role_user_selection_rule` (
  `user_role_id` int(10) NOT NULL,
  `selection_rule_id` int(10) NOT NULL,
  `configurable_params` text,
  PRIMARY KEY (`user_role_id`,`selection_rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_role_user_selection_rule`
--

LOCK TABLES `ohrm_role_user_selection_rule` WRITE;
/*!40000 ALTER TABLE `ohrm_role_user_selection_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_role_user_selection_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_screen`
--

DROP TABLE IF EXISTS `ohrm_screen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_screen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `module_id` int(11) NOT NULL,
  `action_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `ohrm_screen_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `ohrm_module` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_screen`
--

LOCK TABLES `ohrm_screen` WRITE;
/*!40000 ALTER TABLE `ohrm_screen` DISABLE KEYS */;
INSERT INTO `ohrm_screen` VALUES (1,'User List',2,'viewSystemUsers'),(2,'Add/Edit System User',2,'saveSystemUser'),(3,'Delete System Users',2,'deleteSystemUsers'),(4,'Add Employee',3,'addEmployee'),(5,'View Employee List',3,'viewEmployeeList'),(6,'Delete Employees',3,'deleteEmployees'),(7,'Leave Type List',4,'leaveTypeList'),(8,'Define Leave Type',4,'defineLeaveType'),(9,'Undelete Leave Type',4,'undeleteLeaveType'),(10,'Delete Leave Type',4,'deleteLeaveType'),(11,'View Holiday List',4,'viewHolidayList'),(12,'Define Holiday',4,'defineHoliday'),(13,'Delete Holiday',4,'deleteHoliday'),(14,'Define WorkWeek',4,'defineWorkWeek'),(16,'Leave List',4,'viewLeaveList'),(17,'Assign Leave',4,'assignLeave'),(18,'View Leave Summary',4,'viewLeaveSummary'),(19,'Save Leave Entitlements',4,'saveLeaveEntitlements'),(20,'Leave Type List Advanced',4,'leaveTypeListAdvanced'),(21,'Define Leave Type Advanced',4,'defineLeaveTypeAdvanced'),(22,'Define Holiday Advanced',4,'defineHolidayAdvanced'),(23,'View Holiday List Advanced',4,'viewHolidayListAdvanced'),(24,'Define Work Week Advanced',4,'defineWorkWeekAdvanced'),(25,'Assign Leave Advanced',4,'assignLeaveAdvanced'),(26,'Leave List Advanced',4,'viewLeaveListAdvanced'),(27,'View Leave Summary Advanced',4,'viewLeaveSummaryAdvanced'),(28,'Save Leave Entitlements Advanced',4,'saveLeaveEntitlementsAdvanced');
/*!40000 ALTER TABLE `ohrm_screen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_selected_composite_display_field`
--

DROP TABLE IF EXISTS `ohrm_selected_composite_display_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_selected_composite_display_field` (
  `id` bigint(20) NOT NULL,
  `composite_display_field_id` bigint(20) NOT NULL,
  `report_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`,`composite_display_field_id`,`report_id`),
  KEY `composite_display_field_id` (`composite_display_field_id`),
  KEY `report_id` (`report_id`),
  CONSTRAINT `ohrm_selected_composite_display_field_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `ohrm_report` (`report_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_selected_composite_display_field_ibfk_2` FOREIGN KEY (`composite_display_field_id`) REFERENCES `ohrm_composite_display_field` (`composite_display_field_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_selected_composite_display_field`
--

LOCK TABLES `ohrm_selected_composite_display_field` WRITE;
/*!40000 ALTER TABLE `ohrm_selected_composite_display_field` DISABLE KEYS */;
INSERT INTO `ohrm_selected_composite_display_field` VALUES (1,1,3),(2,1,4),(3,2,2);
/*!40000 ALTER TABLE `ohrm_selected_composite_display_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_selected_display_field`
--

DROP TABLE IF EXISTS `ohrm_selected_display_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_selected_display_field` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `display_field_id` bigint(20) NOT NULL,
  `report_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`,`display_field_id`,`report_id`),
  KEY `display_field_id` (`display_field_id`),
  KEY `report_id` (`report_id`),
  CONSTRAINT `ohrm_selected_display_field_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `ohrm_report` (`report_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_selected_display_field_ibfk_2` FOREIGN KEY (`display_field_id`) REFERENCES `ohrm_display_field` (`display_field_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_selected_display_field`
--

LOCK TABLES `ohrm_selected_display_field` WRITE;
/*!40000 ALTER TABLE `ohrm_selected_display_field` DISABLE KEYS */;
INSERT INTO `ohrm_selected_display_field` VALUES (2,2,1),(4,8,2),(5,9,5),(6,10,5),(7,11,5),(8,12,5),(9,13,5),(10,14,5),(11,15,5),(13,17,5),(14,18,5),(15,19,5),(16,20,5),(17,21,5),(18,22,5),(19,23,5),(20,24,5),(21,25,5),(22,26,5),(23,27,5),(24,28,5),(25,29,5),(26,30,5),(27,31,5),(28,32,5),(29,33,5),(31,35,5),(32,36,5),(33,37,5),(34,38,5),(35,39,5),(36,40,5),(37,41,5),(38,42,5),(39,43,5),(40,44,5),(41,45,5),(43,47,5),(44,48,5),(45,49,5),(48,52,5),(49,53,5),(50,54,5),(51,55,5),(53,57,5),(54,58,5),(55,59,5),(56,60,5),(57,61,5),(58,62,5),(59,63,5),(60,64,5),(61,65,5),(62,66,5),(63,67,5),(64,68,5),(65,69,5),(66,70,5),(67,71,5),(68,72,5),(69,73,5),(70,74,5),(71,75,5),(72,76,5),(73,77,5),(74,78,5),(76,80,5),(77,81,5),(78,82,5),(79,83,5),(80,84,5),(81,85,5),(82,86,5),(83,87,5),(84,88,5),(85,89,5),(86,90,5),(87,91,5),(88,92,5),(89,93,5),(90,94,5),(91,95,5),(93,97,5),(94,115,6),(95,116,6),(96,117,6),(97,118,6),(98,119,6),(99,120,6),(100,121,6),(101,122,7),(102,123,7),(103,124,7),(104,125,7),(105,126,8),(106,127,8),(107,128,8),(108,129,8),(109,130,8),(110,131,9),(111,132,9),(112,136,10),(113,137,10),(114,138,10),(115,139,10),(116,140,10),(117,141,10),(118,142,10),(119,143,10),(120,144,10),(121,145,10);
/*!40000 ALTER TABLE `ohrm_selected_display_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_selected_display_field_group`
--

DROP TABLE IF EXISTS `ohrm_selected_display_field_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_selected_display_field_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` bigint(20) NOT NULL,
  `display_field_group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `report_id` (`report_id`),
  KEY `display_field_group_id` (`display_field_group_id`),
  CONSTRAINT `ohrm_selected_display_field_group_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `ohrm_report` (`report_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_selected_display_field_group_ibfk_2` FOREIGN KEY (`display_field_group_id`) REFERENCES `ohrm_display_field_group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_selected_display_field_group`
--

LOCK TABLES `ohrm_selected_display_field_group` WRITE;
/*!40000 ALTER TABLE `ohrm_selected_display_field_group` DISABLE KEYS */;
INSERT INTO `ohrm_selected_display_field_group` VALUES (1,5,1),(2,5,2),(3,5,3),(4,5,4),(5,5,5),(6,5,6),(7,5,7),(8,5,8),(9,5,9),(10,5,10),(11,5,11),(12,5,12),(13,5,13),(14,5,14),(15,5,15);
/*!40000 ALTER TABLE `ohrm_selected_display_field_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_selected_filter_field`
--

DROP TABLE IF EXISTS `ohrm_selected_filter_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_selected_filter_field` (
  `report_id` bigint(20) NOT NULL,
  `filter_field_id` bigint(20) NOT NULL,
  `filter_field_order` bigint(20) NOT NULL,
  `value1` varchar(255) DEFAULT NULL,
  `value2` varchar(255) DEFAULT NULL,
  `where_condition` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`report_id`,`filter_field_id`),
  KEY `report_id` (`report_id`),
  KEY `filter_field_id` (`filter_field_id`),
  CONSTRAINT `ohrm_selected_filter_field_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `ohrm_report` (`report_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_selected_filter_field_ibfk_2` FOREIGN KEY (`filter_field_id`) REFERENCES `ohrm_filter_field` (`filter_field_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_selected_filter_field`
--

LOCK TABLES `ohrm_selected_filter_field` WRITE;
/*!40000 ALTER TABLE `ohrm_selected_filter_field` DISABLE KEYS */;
INSERT INTO `ohrm_selected_filter_field` VALUES (1,1,1,NULL,NULL,NULL,'Runtime'),(1,3,2,NULL,NULL,NULL,'Runtime'),(1,7,3,NULL,NULL,NULL,'Runtime'),(1,21,4,'0',NULL,'=','Predefined'),(2,3,4,NULL,NULL,NULL,'Runtime'),(2,4,1,NULL,NULL,NULL,'Runtime'),(2,5,3,NULL,NULL,NULL,'Runtime'),(2,6,2,NULL,NULL,NULL,'Runtime'),(2,7,5,NULL,NULL,NULL,'Runtime'),(3,3,2,NULL,NULL,NULL,'Runtime'),(3,5,1,NULL,NULL,NULL,'Runtime'),(3,7,3,NULL,NULL,NULL,'Runtime'),(5,22,1,NULL,NULL,'IS NULL','Predefined'),(6,23,1,NULL,NULL,NULL,'Runtime'),(6,24,2,NULL,NULL,NULL,'Runtime'),(6,25,3,NULL,NULL,NULL,'Runtime'),(7,26,1,NULL,NULL,NULL,'Runtime'),(7,27,2,NULL,NULL,NULL,'Runtime'),(8,28,1,NULL,NULL,NULL,'Runtime'),(8,29,2,NULL,NULL,NULL,'Runtime'),(9,30,1,NULL,NULL,NULL,'Runtime'),(9,31,2,NULL,NULL,NULL,'Runtime'),(9,32,3,NULL,NULL,NULL,'Runtime'),(10,33,1,NULL,NULL,NULL,'Runtime'),(10,34,2,NULL,NULL,NULL,'Runtime'),(10,35,3,NULL,NULL,NULL,'Runtime');
/*!40000 ALTER TABLE `ohrm_selected_filter_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_selected_group_field`
--

DROP TABLE IF EXISTS `ohrm_selected_group_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_selected_group_field` (
  `group_field_id` bigint(20) NOT NULL,
  `summary_display_field_id` bigint(20) NOT NULL,
  `report_id` bigint(20) NOT NULL,
  PRIMARY KEY (`group_field_id`,`summary_display_field_id`,`report_id`),
  KEY `group_field_id` (`group_field_id`),
  KEY `summary_display_field_id` (`summary_display_field_id`),
  KEY `report_id` (`report_id`),
  CONSTRAINT `ohrm_selected_group_field_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `ohrm_report` (`report_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_selected_group_field_ibfk_2` FOREIGN KEY (`group_field_id`) REFERENCES `ohrm_group_field` (`group_field_id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_selected_group_field_ibfk_3` FOREIGN KEY (`summary_display_field_id`) REFERENCES `ohrm_summary_display_field` (`summary_display_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_selected_group_field`
--

LOCK TABLES `ohrm_selected_group_field` WRITE;
/*!40000 ALTER TABLE `ohrm_selected_group_field` DISABLE KEYS */;
INSERT INTO `ohrm_selected_group_field` VALUES (1,1,1),(1,1,2),(2,1,3),(2,2,4);
/*!40000 ALTER TABLE `ohrm_selected_group_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_bank`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_bank`
--

LOCK TABLES `ohrm_sigmasoft_bank` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_bank` DISABLE KEYS */;
INSERT INTO `ohrm_sigmasoft_bank` VALUES (1,'Standard Chartered');
/*!40000 ALTER TABLE `ohrm_sigmasoft_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_bank_account_type`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_bank_account_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_bank_account_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_bank_account_type`
--

LOCK TABLES `ohrm_sigmasoft_bank_account_type` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_bank_account_type` DISABLE KEYS */;
INSERT INTO `ohrm_sigmasoft_bank_account_type` VALUES (1,'Current'),(2,'Saving');
/*!40000 ALTER TABLE `ohrm_sigmasoft_bank_account_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_company_type`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_company_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_company_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_company_type`
--

LOCK TABLES `ohrm_sigmasoft_company_type` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_company_type` DISABLE KEYS */;
INSERT INTO `ohrm_sigmasoft_company_type` VALUES (1,'Corporation');
/*!40000 ALTER TABLE `ohrm_sigmasoft_company_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_emp_direct_deposit`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_emp_direct_deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_emp_direct_deposit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_number` int(11) NOT NULL,
  `component` varchar(100) DEFAULT NULL,
  `bank_id` int(10) unsigned NOT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `account_name` varchar(80) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_type_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_id` (`bank_id`),
  KEY `account_type_id` (`account_type_id`),
  CONSTRAINT `ohrm_sigmasoft_emp_direct_deposit_ibfk_1` FOREIGN KEY (`bank_id`) REFERENCES `ohrm_sigmasoft_bank` (`id`),
  CONSTRAINT `ohrm_sigmasoft_emp_direct_deposit_ibfk_2` FOREIGN KEY (`account_type_id`) REFERENCES `ohrm_sigmasoft_bank_account_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_emp_direct_deposit`
--

LOCK TABLES `ohrm_sigmasoft_emp_direct_deposit` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_emp_direct_deposit` DISABLE KEYS */;
INSERT INTO `ohrm_sigmasoft_emp_direct_deposit` VALUES (1,1,'',1,100.00,'GRACE CIELO ACOSTA','190521897486',2,1),(2,4,'',1,100.00,'MARY ROSE E. AFUNGGOL','164809312532',2,1),(3,5,'0',1,100.00,'SHIELLA B. AGUINALDO','128079088964',2,1),(4,7,'0',1,100.00,'ANA A. ARQUINEZ','128339573952',2,1),(5,8,'0',1,100.00,'ALEXANDER M ASUNCION','165525037252',2,1),(6,9,'0',1,100.00,'SHEILA A. BALUYOT','143300198232',2,1),(7,10,'0',1,100.00,'ROWENA E CANLAS','166315872779',2,1),(8,12,'0',1,100.00,'JOSEPHINE D CELMAR','131733312000',2,1),(9,13,'0',1,100.00,'SHEILA A. BALUYOT','143300198232',2,1),(10,14,'0',1,100.00,'STUART L. CHUA','131727292001',2,1),(11,15,'0',1,100.00,'ERWIN O. CO','131777749005',2,1),(12,18,'0',1,100.00,'EVANGELYN R. CUEVO','193811459762',2,1),(13,20,'0',1,100.00,'ROCHA MAE M VILLESENDA','150040958248',2,1),(14,22,'0',1,100.00,'MARK ANTHONY B DE GUZMAN','165521612483',2,1),(15,23,'0',1,100.00,'JOYCE A. PEREZ','193065597273',2,1),(16,24,'0',1,100.00,'GIRLIE T. PABALAN','143354289707',2,1),(17,25,'0',1,100.00,'DONNA DIVINE C. LACTAO','187658676569',2,1),(18,26,'0',1,100.00,'JOHN LAWRENCE B. ONG','143311263976',2,1),(19,27,'0',1,100.00,'JENNIFER D. MONTA','187851770266',2,1),(20,29,'-',1,100.00,'CRISTINA M DE LEON','180002867986',2,1),(21,30,'0',1,100.00,'KENICHI  HISATOMI','1134567491',2,1),(22,31,'0',1,100.00,'ARISTEO S. TOLENTINO','131727552003',2,1),(23,33,'0',1,100.00,'JEANETH B DOLOR','149279807606',2,1),(24,34,'0',1,100.00,'DONNA MARIE T. SEBELDIA','193066079014',2,1),(25,35,'0',1,100.00,'MARIA GRACIA C. DOMALANTA','127448396409',2,1),(26,36,'0',1,100.00,'SYDNEY C DUARTE','165069682885',2,1),(27,37,'0',1,100.00,'ALLAN FERNANDO REI C ESPINO','165499578856',2,1),(28,38,'0',1,100.00,'ELIZA C GARROVILLO','165773415507',2,1),(29,39,'0',1,100.00,'WINSTON S GO','184820026832',2,1),(30,41,'0',1,100.00,'NERIELYN B HONRADO','164500762304',1,1),(31,44,'0',1,100.00,'JENNIFER C. LOGINA','131734960006',1,1),(32,45,'0',1,100.00,'JOHN RAY S LOMUGDANG','164820937644',1,1),(33,46,'0',1,100.00,'ANNE MARIEL S LOPEZ','164820778566',1,1),(34,47,'0',1,100.00,'MAMERTA MAGLASANG','190251901632',1,1),(35,48,'0',1,100.00,'MARIE BERCHEL M PINGOY','165695254602',1,1),(36,49,'0',1,100.00,'JUVI M. RAMOS','126677617569',1,1),(37,50,'0',1,100.00,'ROLDIE VIC  C RAMOS','184707501238',1,1),(38,51,'0',1,100.00,'ALLAN RIVERA','165585007422',1,1),(39,52,'0',1,100.00,'AMELIA L. SABAY','131727321001',1,1),(40,53,'0',1,100.00,'SHINYA SAITO','170500995237',1,1),(41,54,'0',1,100.00,'KRISTIN MAE M. SAN MIGUEL','127448544769',1,1),(42,55,'0',1,100.00,'RENATO D SANTOS','165521393785',1,1),(43,56,'0',1,100.00,'RICHEL S SARMIENTO','128043837018',1,1),(44,57,'0',1,100.00,'MARIA THERESA B. SANTOS','187662555017',1,1);
/*!40000 ALTER TABLE `ohrm_sigmasoft_emp_direct_deposit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_location_bank_account`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_location_bank_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_location_bank_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `bank_id` int(10) unsigned NOT NULL,
  `account_name` varchar(80) DEFAULT NULL,
  `account_no` varchar(50) DEFAULT NULL,
  `branch_register` varchar(80) DEFAULT NULL,
  `contact_person` varchar(160) DEFAULT NULL,
  `account_type` smallint(5) unsigned DEFAULT NULL,
  `default_ceiling_amount` decimal(20,2) DEFAULT NULL,
  `swift_code` varchar(40) DEFAULT NULL,
  `status` smallint(5) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_location_bank_account`
--

LOCK TABLES `ohrm_sigmasoft_location_bank_account` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_location_bank_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_sigmasoft_location_bank_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_location_representative`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_location_representative`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_location_representative` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `employee_name` varchar(200) DEFAULT NULL,
  `job_position` varchar(100) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_location_representative`
--

LOCK TABLES `ohrm_sigmasoft_location_representative` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_location_representative` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_sigmasoft_location_representative` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_organization_bank_account`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_organization_bank_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_organization_bank_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bank_id` int(10) unsigned NOT NULL,
  `account_name` varchar(80) DEFAULT NULL,
  `account_no` varchar(50) DEFAULT NULL,
  `branch_register` varchar(80) DEFAULT NULL,
  `contact_person` varchar(160) DEFAULT NULL,
  `account_type` smallint(5) unsigned DEFAULT NULL,
  `default_ceiling_amount` decimal(20,2) DEFAULT NULL,
  `swift_code` varchar(40) DEFAULT NULL,
  `status` smallint(5) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_organization_bank_account`
--

LOCK TABLES `ohrm_sigmasoft_organization_bank_account` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_organization_bank_account` DISABLE KEYS */;
INSERT INTO `ohrm_sigmasoft_organization_bank_account` VALUES (1,1,'ITOCHU CORPORATION','0132548356006','AYALA BRANCH','',1,NULL,'',1,NULL);
/*!40000 ALTER TABLE `ohrm_sigmasoft_organization_bank_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_organization_representative`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_organization_representative`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_organization_representative` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_name` varchar(200) DEFAULT NULL,
  `job_position` varchar(100) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_organization_representative`
--

LOCK TABLES `ohrm_sigmasoft_organization_representative` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_organization_representative` DISABLE KEYS */;
INSERT INTO `ohrm_sigmasoft_organization_representative` VALUES (1,'Joyce Perez','HR Officer','8571111',NULL);
/*!40000 ALTER TABLE `ohrm_sigmasoft_organization_representative` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_proficiency`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_proficiency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_proficiency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_proficiency`
--

LOCK TABLES `ohrm_sigmasoft_proficiency` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_proficiency` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_sigmasoft_proficiency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sigmasoft_tax_exemption`
--

DROP TABLE IF EXISTS `ohrm_sigmasoft_tax_exemption`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sigmasoft_tax_exemption` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sigmasoft_tax_exemption`
--

LOCK TABLES `ohrm_sigmasoft_tax_exemption` WRITE;
/*!40000 ALTER TABLE `ohrm_sigmasoft_tax_exemption` DISABLE KEYS */;
INSERT INTO `ohrm_sigmasoft_tax_exemption` VALUES (1,'S','SINGLE'),(2,'M','MARRIED'),(3,'M1','MARRIED WITH 1 DEPENDENT'),(4,'M2','MARRIED WITH 2 DEPENDENT'),(5,'M3','MARRIED WITH 3 DEPENDENT'),(6,'M4','MARRIED WITH 4 DEPENDENT'),(7,'S1','SINGLE WITH 1 DEPENDENT'),(8,'S2','SINGLE WITH 2 DEPENDENT'),(9,'S3','SINGLE WITH 3 DEPENDENT'),(10,'S4','SINGLE WITH 4 DEPENDENT');
/*!40000 ALTER TABLE `ohrm_sigmasoft_tax_exemption` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_skill`
--

DROP TABLE IF EXISTS `ohrm_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_skill`
--

LOCK TABLES `ohrm_skill` WRITE;
/*!40000 ALTER TABLE `ohrm_skill` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_sso_token`
--

DROP TABLE IF EXISTS `ohrm_sso_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_sso_token` (
  `username` varchar(40) NOT NULL DEFAULT '',
  `token_value` varchar(200) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`username`,`token_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_sso_token`
--

LOCK TABLES `ohrm_sso_token` WRITE;
/*!40000 ALTER TABLE `ohrm_sso_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_sso_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_subunit`
--

DROP TABLE IF EXISTS `ohrm_subunit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_subunit` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `unit_id` varchar(100) DEFAULT NULL,
  `description` varchar(400) DEFAULT NULL,
  `lft` smallint(6) unsigned DEFAULT NULL,
  `rgt` smallint(6) unsigned DEFAULT NULL,
  `level` smallint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_subunit`
--

LOCK TABLES `ohrm_subunit` WRITE;
/*!40000 ALTER TABLE `ohrm_subunit` DISABLE KEYS */;
INSERT INTO `ohrm_subunit` VALUES (1,'ITOCHU CORPORATION','','',1,34,0),(2,'Accounting','Accounting','Accounting',2,7,1),(3,'Payroll','Payroll','Payroll',3,4,2),(4,'Gen Accounting','Gen Accounting','Gen Accounting',5,6,2),(5,'Administration','Administration','Administration',8,9,1),(6,'Chemicals','Chemicals','Chemicals',10,11,1),(7,'Chemicals and Energy','Chemicals and Energy','Chemicals and Energy',12,13,1),(8,'Food','Food','Food',14,15,1),(9,'General Merchandise','General Merchandise','General Merchandise',16,17,1),(10,'IDES','IDES','IDES',18,19,1),(11,'Legal','Legal','Legal',20,21,1),(12,'Logistics','Logistics','Logistics',22,23,1),(13,'Machinery','Machinery','Machinery',24,25,1),(14,'Metals and Minerals','Metals and Minerals','Metals and Minerals',26,27,1),(15,'None','None','None',28,29,1),(16,'Plastics','Plastics','Plastics',30,31,1),(17,'Semicon','Semicon','Semicon',32,33,1);
/*!40000 ALTER TABLE `ohrm_subunit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_summary_display_field`
--

DROP TABLE IF EXISTS `ohrm_summary_display_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_summary_display_field` (
  `summary_display_field_id` bigint(20) NOT NULL,
  `function` varchar(1000) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_alias` varchar(255) DEFAULT NULL,
  `is_sortable` varchar(10) NOT NULL,
  `sort_order` varchar(255) DEFAULT NULL,
  `sort_field` varchar(255) DEFAULT NULL,
  `element_type` varchar(255) NOT NULL,
  `element_property` varchar(1000) NOT NULL,
  `width` varchar(255) NOT NULL,
  `is_exportable` varchar(10) DEFAULT NULL,
  `text_alignment_style` varchar(20) DEFAULT NULL,
  `is_value_list` tinyint(1) NOT NULL DEFAULT '0',
  `display_field_group_id` int(10) unsigned DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`summary_display_field_id`),
  KEY `display_field_group_id` (`display_field_group_id`),
  CONSTRAINT `ohrm_summary_display_field_ibfk_1` FOREIGN KEY (`display_field_group_id`) REFERENCES `ohrm_display_field_group` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_summary_display_field`
--

LOCK TABLES `ohrm_summary_display_field` WRITE;
/*!40000 ALTER TABLE `ohrm_summary_display_field` DISABLE KEYS */;
INSERT INTO `ohrm_summary_display_field` VALUES (1,'ROUND(COALESCE(sum(duration)/3600, 0),2)','Time (Hours)','totalduration','false',NULL,NULL,'label','<xml><getter>totalduration</getter></xml>','100','false','right',0,NULL,NULL),(2,'ROUND(COALESCE(sum(TIMESTAMPDIFF(SECOND , ohrm_attendance_record.punch_in_utc_time , ohrm_attendance_record.punch_out_utc_time))/3600, 0),2)','Time (hours)','totalduration','false',NULL,NULL,'label','<xml><getter>totalduration</getter></xml>','100','false','right',0,NULL,NULL);
/*!40000 ALTER TABLE `ohrm_summary_display_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_timesheet`
--

DROP TABLE IF EXISTS `ohrm_timesheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_timesheet` (
  `timesheet_id` bigint(20) NOT NULL,
  `state` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  PRIMARY KEY (`timesheet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_timesheet`
--

LOCK TABLES `ohrm_timesheet` WRITE;
/*!40000 ALTER TABLE `ohrm_timesheet` DISABLE KEYS */;
INSERT INTO `ohrm_timesheet` VALUES (1,'NOT SUBMITTED','2012-07-30','2012-08-05',1),(2,'NOT SUBMITTED','2012-08-06','2012-08-12',24);
/*!40000 ALTER TABLE `ohrm_timesheet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_timesheet_action_log`
--

DROP TABLE IF EXISTS `ohrm_timesheet_action_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_timesheet_action_log` (
  `timesheet_action_log_id` bigint(20) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `date_time` date NOT NULL,
  `performed_by` int(20) NOT NULL,
  `timesheet_id` bigint(20) NOT NULL,
  PRIMARY KEY (`timesheet_action_log_id`),
  KEY `timesheet_id` (`timesheet_id`),
  KEY `performed_by` (`performed_by`),
  CONSTRAINT `ohrm_timesheet_action_log_ibfk_1` FOREIGN KEY (`performed_by`) REFERENCES `ohrm_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_timesheet_action_log`
--

LOCK TABLES `ohrm_timesheet_action_log` WRITE;
/*!40000 ALTER TABLE `ohrm_timesheet_action_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_timesheet_action_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_timesheet_item`
--

DROP TABLE IF EXISTS `ohrm_timesheet_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_timesheet_item` (
  `timesheet_item_id` bigint(20) NOT NULL,
  `timesheet_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `duration` bigint(20) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `project_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  PRIMARY KEY (`timesheet_item_id`),
  KEY `timesheet_id` (`timesheet_id`),
  KEY `activity_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_timesheet_item`
--

LOCK TABLES `ohrm_timesheet_item` WRITE;
/*!40000 ALTER TABLE `ohrm_timesheet_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_timesheet_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_upgrade_history`
--

DROP TABLE IF EXISTS `ohrm_upgrade_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_upgrade_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `start_version` varchar(30) DEFAULT NULL,
  `end_version` varchar(30) DEFAULT NULL,
  `start_increment` int(11) NOT NULL,
  `end_increment` int(11) NOT NULL,
  `upgraded_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_upgrade_history`
--

LOCK TABLES `ohrm_upgrade_history` WRITE;
/*!40000 ALTER TABLE `ohrm_upgrade_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_upgrade_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_user`
--

DROP TABLE IF EXISTS `ohrm_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_role_id` int(10) NOT NULL,
  `emp_number` int(13) DEFAULT NULL,
  `user_name` varchar(40) DEFAULT NULL,
  `user_password` varchar(40) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` int(10) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `user_role_id` (`user_role_id`),
  KEY `emp_number` (`emp_number`),
  KEY `modified_user_id` (`modified_user_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `ohrm_user_ibfk_1` FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_user_ibfk_2` FOREIGN KEY (`user_role_id`) REFERENCES `ohrm_user_role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_user`
--

LOCK TABLES `ohrm_user` WRITE;
/*!40000 ALTER TABLE `ohrm_user` DISABLE KEYS */;
INSERT INTO `ohrm_user` VALUES (1,1,NULL,'admin','21232f297a57a5a743894a0e4a801fc3',0,1,NULL,NULL,NULL,NULL),(2,2,1,'ACOSTA','e10adc3949ba59abbe56e057f20f883e',0,1,'2012-07-26 12:24:11','2012-07-26 15:57:02',1,1),(3,2,23,'joyce','19053d1f43416ad98dd9443425753488',0,1,'2012-07-30 07:07:13','2012-08-02 08:10:34',1,1),(4,2,12,'celmar','81dc9bdb52d04dc20036dbd8313ed055',0,1,'2012-07-30 08:12:03',NULL,NULL,1);
/*!40000 ALTER TABLE `ohrm_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_user_role`
--

DROP TABLE IF EXISTS `ohrm_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_user_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `is_assignable` tinyint(1) DEFAULT '0',
  `is_predefined` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_user_role`
--

LOCK TABLES `ohrm_user_role` WRITE;
/*!40000 ALTER TABLE `ohrm_user_role` DISABLE KEYS */;
INSERT INTO `ohrm_user_role` VALUES (1,'Admin','Admin',1,1),(2,'ESS','ESS',1,1),(3,'Supervisor','Supervisor',0,1),(4,'ProjectAdmin','ProjectAdmin',0,1),(5,'Interviewer','Interviewer',0,1),(6,'Offerer','Offerer',0,1),(7,'Interviewer','Interviewer',0,1),(8,'Offerer','Offerer',0,1);
/*!40000 ALTER TABLE `ohrm_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_user_role_screen`
--

DROP TABLE IF EXISTS `ohrm_user_role_screen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_user_role_screen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_role_id` int(11) NOT NULL,
  `screen_id` int(11) NOT NULL,
  `can_read` tinyint(1) NOT NULL DEFAULT '0',
  `can_create` tinyint(1) NOT NULL DEFAULT '0',
  `can_update` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_role_id` (`user_role_id`),
  KEY `screen_id` (`screen_id`),
  CONSTRAINT `ohrm_user_role_screen_ibfk_1` FOREIGN KEY (`user_role_id`) REFERENCES `ohrm_user_role` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ohrm_user_role_screen_ibfk_2` FOREIGN KEY (`screen_id`) REFERENCES `ohrm_screen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_user_role_screen`
--

LOCK TABLES `ohrm_user_role_screen` WRITE;
/*!40000 ALTER TABLE `ohrm_user_role_screen` DISABLE KEYS */;
INSERT INTO `ohrm_user_role_screen` VALUES (1,1,1,1,1,1,1),(2,2,1,0,0,0,0),(3,3,1,0,0,0,0),(4,1,2,1,1,1,1),(5,2,2,0,0,0,0),(6,3,2,0,0,0,0),(7,1,3,1,1,1,1),(8,2,3,0,0,0,0),(9,3,3,0,0,0,0),(10,1,4,1,1,1,1),(11,1,5,1,1,1,1),(12,3,5,1,0,0,0),(13,1,6,1,0,0,1),(14,1,7,1,1,1,1),(15,1,8,1,1,1,1),(16,1,9,1,1,1,1),(17,1,10,1,1,1,1),(18,1,11,1,1,1,1),(19,1,12,1,1,1,1),(20,1,13,1,1,1,1),(21,1,14,1,1,1,1),(22,1,16,1,1,1,0),(23,2,16,1,1,1,0),(24,1,17,1,1,1,0),(25,2,17,1,1,1,0),(26,1,18,1,1,1,0),(27,2,18,1,0,0,0),(28,3,18,1,0,0,0),(29,1,19,1,1,1,1),(30,1,20,1,1,1,1),(31,1,21,1,1,1,1),(32,1,22,1,1,1,1),(33,1,23,1,1,1,1),(34,1,24,1,1,1,1),(37,1,25,1,1,1,0),(38,3,25,1,1,1,0),(39,1,26,1,1,1,0),(40,3,26,1,1,1,0),(44,1,27,1,1,1,0),(45,2,27,1,0,0,0),(46,3,27,1,0,0,0),(48,1,28,1,1,1,1);
/*!40000 ALTER TABLE `ohrm_user_role_screen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_user_selection_rule`
--

DROP TABLE IF EXISTS `ohrm_user_selection_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_user_selection_rule` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `implementation_class` varchar(255) NOT NULL,
  `rule_xml_data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_user_selection_rule`
--

LOCK TABLES `ohrm_user_selection_rule` WRITE;
/*!40000 ALTER TABLE `ohrm_user_selection_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ohrm_user_selection_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_work_shift`
--

DROP TABLE IF EXISTS `ohrm_work_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_work_shift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `hours_per_day` decimal(4,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_work_shift`
--

LOCK TABLES `ohrm_work_shift` WRITE;
/*!40000 ALTER TABLE `ohrm_work_shift` DISABLE KEYS */;
INSERT INTO `ohrm_work_shift` VALUES (1,'Regular',8.00);
/*!40000 ALTER TABLE `ohrm_work_shift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_work_week`
--

DROP TABLE IF EXISTS `ohrm_work_week`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_work_week` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `operational_country_id` int(10) unsigned DEFAULT NULL,
  `mon` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tue` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `wed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `thu` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fri` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sat` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sun` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_ohrm_work_week_ohrm_operational_country` (`operational_country_id`),
  CONSTRAINT `fk_ohrm_work_week_ohrm_operational_country` FOREIGN KEY (`operational_country_id`) REFERENCES `ohrm_operational_country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_work_week`
--

LOCK TABLES `ohrm_work_week` WRITE;
/*!40000 ALTER TABLE `ohrm_work_week` DISABLE KEYS */;
INSERT INTO `ohrm_work_week` VALUES (1,NULL,0,0,0,0,0,8,8);
/*!40000 ALTER TABLE `ohrm_work_week` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ohrm_workflow_state_machine`
--

DROP TABLE IF EXISTS `ohrm_workflow_state_machine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ohrm_workflow_state_machine` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `workflow` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `resulting_state` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ohrm_workflow_state_machine`
--

LOCK TABLES `ohrm_workflow_state_machine` WRITE;
/*!40000 ALTER TABLE `ohrm_workflow_state_machine` DISABLE KEYS */;
INSERT INTO `ohrm_workflow_state_machine` VALUES (1,'0','INITIAL','SYSTEM','7','NOT SUBMITTED'),(2,'0','SUBMITTED','ADMIN','2','APPROVED'),(3,'0','SUBMITTED','ADMIN','3','REJECTED'),(4,'0','SUBMITTED','ADMIN','0','SUBMITTED'),(5,'0','SUBMITTED','ADMIN','5','SUBMITTED'),(6,'0','SUBMITTED','SUPERVISOR','2','APPROVED'),(7,'0','SUBMITTED','SUPERVISOR','3','REJECTED'),(8,'0','SUBMITTED','SUPERVISOR','5','SUBMITTED'),(9,'0','SUBMITTED','SUPERVISOR','0','SUBMITTED'),(10,'0','SUBMITTED','ESS USER','0','SUBMITTED'),(11,'0','SUBMITTED','ESS USER','5','SUBMITTED'),(12,'0','NOT SUBMITTED','ESS USER','1','SUBMITTED'),(13,'0','NOT SUBMITTED','ESS USER','5','NOT SUBMITTED'),(14,'0','NOT SUBMITTED','ESS USER','6','NOT SUBMITTED'),(15,'0','NOT SUBMITTED','ESS USER','0','NOT SUBMITTED'),(16,'0','NOT SUBMITTED','SUPERVISOR','0','NOT SUBMITTED'),(17,'0','NOT SUBMITTED','SUPERVISOR','5','NOT SUBMITTED'),(18,'0','NOT SUBMITTED','SUPERVISOR','1','SUBMITTED'),(19,'0','NOT SUBMITTED','ADMIN','0','NOT SUBMITTED'),(20,'0','NOT SUBMITTED','ADMIN','5','NOT SUBMITTED'),(21,'0','NOT SUBMITTED','ADMIN','1','SUBMITTED'),(22,'0','REJECTED','ESS USER','1','SUBMITTED'),(23,'0','REJECTED','ESS USER','0','REJECTED'),(24,'0','REJECTED','ESS USER','5','REJECTED'),(25,'0','REJECTED','SUPERVISOR','1','SUBMITTED'),(26,'0','REJECTED','SUPERVISOR','0','REJECTED'),(27,'0','REJECTED','SUPERVISOR','5','REJECTED'),(28,'0','REJECTED','ADMIN','0','REJECTED'),(29,'0','REJECTED','ADMIN','5','SUBMITTED'),(30,'0','REJECTED','ADMIN','1','SUBMITTED'),(31,'0','APPROVED','ESS USER','0','APPROVED'),(32,'0','APPROVED','SUPERVISOR','0','APPROVED'),(33,'0','APPROVED','ADMIN','0','APPROVED'),(34,'0','APPROVED','ADMIN','4','SUBMITTED'),(35,'1','PUNCHED IN','ESS USER','1','PUNCHED OUT'),(36,'1','INITIAL','ESS USER','0','PUNCHED IN'),(37,'2','INITIAL','ADMIN','1','APPLICATION INITIATED'),(38,'2','APPLICATION INITIATED','ADMIN','2','SHORTLISTED'),(39,'2','APPLICATION INITIATED','ADMIN','3','REJECTED'),(40,'2','SHORTLISTED','ADMIN','4','INTERVIEW SCHEDULED'),(41,'2','SHORTLISTED','ADMIN','3','REJECTED'),(42,'2','INTERVIEW SCHEDULED','ADMIN','3','REJECTED'),(43,'2','INTERVIEW SCHEDULED','ADMIN','5','INTERVIEW PASSED'),(44,'2','INTERVIEW SCHEDULED','ADMIN','6','INTERVIEW FAILED'),(45,'2','INTERVIEW PASSED','ADMIN','4','INTERVIEW SCHEDULED'),(46,'2','INTERVIEW PASSED','ADMIN','7','JOB OFFERED'),(47,'2','INTERVIEW PASSED','ADMIN','3','REJECTED'),(48,'2','INTERVIEW FAILED','ADMIN','3','REJECTED'),(49,'2','JOB OFFERED','ADMIN','8','OFFER DECLINED'),(50,'2','JOB OFFERED','ADMIN','3','REJECTED'),(51,'2','JOB OFFERED','ADMIN','9','HIRED'),(52,'2','OFFER DECLINED','ADMIN','3','REJECTED'),(53,'2','INITIAL','HIRING MANAGER','1','APPLICATION INITIATED'),(54,'2','APPLICATION INITIATED','HIRING MANAGER','2','SHORTLISTED'),(55,'2','APPLICATION INITIATED','HIRING MANAGER','3','REJECTED'),(56,'2','SHORTLISTED','HIRING MANAGER','4','INTERVIEW SCHEDULED'),(57,'2','SHORTLISTED','HIRING MANAGER','3','REJECTED'),(58,'2','INTERVIEW SCHEDULED','HIRING MANAGER','3','REJECTED'),(59,'2','INTERVIEW SCHEDULED','HIRING MANAGER','5','INTERVIEW PASSED'),(60,'2','INTERVIEW SCHEDULED','HIRING MANAGER','6','INTERVIEW FAILED'),(61,'2','INTERVIEW PASSED','HIRING MANAGER','4','INTERVIEW SCHEDULED'),(62,'2','INTERVIEW PASSED','HIRING MANAGER','7','JOB OFFERED'),(63,'2','INTERVIEW PASSED','HIRING MANAGER','3','REJECTED'),(64,'2','INTERVIEW FAILED','HIRING MANAGER','3','REJECTED'),(65,'2','JOB OFFERED','HIRING MANAGER','8','OFFER DECLINED'),(66,'2','JOB OFFERED','HIRING MANAGER','3','REJECTED'),(67,'2','JOB OFFERED','HIRING MANAGER','9','HIRED'),(68,'2','OFFER DECLINED','HIRING MANAGER','3','REJECTED'),(69,'2','INTERVIEW SCHEDULED','INTERVIEWER','5','INTERVIEW PASSED'),(70,'2','INTERVIEW SCHEDULED','INTERVIEWER','6','INTERVIEW FAILED'),(71,'1','INITIAL','ADMIN','5','PUNCHED IN'),(72,'1','PUNCHED IN','ADMIN','6','PUNCHED OUT'),(73,'1','PUNCHED IN','ADMIN','2','PUNCHED IN'),(74,'1','PUNCHED IN','ADMIN','7','N/A'),(75,'1','PUNCHED OUT','ADMIN','2','PUNCHED OUT'),(76,'1','PUNCHED OUT','ADMIN','3','PUNCHED OUT'),(77,'1','PUNCHED OUT','ADMIN','7','N/A'),(78,'0','INITIAL','ADMIN','7','NOT SUBMITTED'),(79,'0','INITIAL','ESS USER','7','NOT SUBMITTED'),(80,'0','INITIAL','SUPERVISOR','7','NOT SUBMITTED'),(81,'2','APPLICATION INITIATED','ADMIN','9','HIRED'),(82,'2','APPLICATION INITIATED','HIRING MANAGER','9','HIRED');
/*!40000 ALTER TABLE `ohrm_workflow_state_machine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'orangehrm_db'
--
/*!50003 DROP FUNCTION IF EXISTS `get_parant_id` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `get_parant_id`(
  id INT
) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
SELECT (SELECT t2.id 
               FROM ohrm_subunit t2 
               WHERE t2.lft < t1.lft AND t2.rgt > t1.rgt    
               ORDER BY t2.rgt-t1.rgt ASC LIMIT 1) INTO @parent
FROM ohrm_subunit t1 WHERE t1.id = id;

RETURN @parent;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `get_static_reference_value` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `get_static_reference_value`(
  value_table VARCHAR(40),
  value_field VARCHAR(40),
  reference_key_value VARCHAR(20)
) RETURNS varchar(100) CHARSET latin1
    READS SQL DATA
    DETERMINISTIC
BEGIN
  RETURN IFNULL((SELECT `reference_value` FROM `ohrm_audittrail_static_reference` WHERE `table` = value_table AND `field` = value_field AND `key_value` = reference_key_value), reference_key_value);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `get_sub_unit_path` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `get_sub_unit_path`(
  id INT
) RETURNS varchar(512) CHARSET utf8
    READS SQL DATA
    DETERMINISTIC
BEGIN

  IF id IS NULL
  THEN
    RETURN (SELECT `name` FROM `ohrm_subunit` WHERE `level` = 0);
  END IF;

    SELECT cst.lft, cst.rgt INTO @lft, @rgt
    FROM `ohrm_subunit` AS cst
    WHERE cst.id = id
    LIMIT 1;
  
  RETURN (
    SELECT GROUP_CONCAT(`cst`.`name` SEPARATOR ' / ') AS sub_unit_path
    FROM `ohrm_subunit` AS cst
    WHERE cst.lft <= @lft AND cst.rgt >= @rgt  
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `get_sub_unit_title` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `get_sub_unit_title`(
  id INT
) RETURNS varchar(255) CHARSET latin1
    READS SQL DATA
    DETERMINISTIC
BEGIN

  IF id IS NULL
    THEN
     RETURN '';
  END IF;

  SELECT `cst`.`name`, `cst`.`level` INTO @name, @level
     FROM `ohrm_subunit` AS cst
     WHERE `cst`.`id` = id
     LIMIT 1;

  IF @level = 0
   THEN
     RETURN 'Unassigned to Subunits';
   ELSE
     RETURN CONCAT(@name, ' (Current)');
  END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `is_sub_unit_leaf_node` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `is_sub_unit_leaf_node`(
  id INT
) RETURNS tinyint(1)
    READS SQL DATA
    DETERMINISTIC
BEGIN
  SELECT cst.lft, cst.rgt INTO @lft, @rgt
    FROM `ohrm_subunit` AS cst
    WHERE cst.id = id
    LIMIT 1;

  SELECT COUNT(*) INTO  @children_count
    FROM `ohrm_subunit` AS cst
    WHERE cst.lft > @lft AND cst.rgt < @rgt
    LIMIT 1;

  RETURN (@children_count = 0);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `resolve_empty` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `resolve_empty`(
  string VARCHAR(10240) CHARSET UTF8
) RETURNS varchar(10240) CHARSET utf8
    READS SQL DATA
    DETERMINISTIC
BEGIN
  RETURN IF(IFNULL(string, '') = '', '"  "', string);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `archive_reference` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `archive_reference`(
  IN subject_table VARCHAR(50),
  IN key_value VARCHAR(10),
  IN record_descriptor VARCHAR(200)
)
BEGIN
  INSERT INTO `ohrm_audittrail_reference_archive`  VALUES (subject_table, key_value, record_descriptor);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_delete_hs_hr_employee_personal_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_delete_hs_hr_employee_personal_details`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7)
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

   SET action_description = CONCAT(action_description, ' ', 'Employee record was deleted from the system.');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_personal_details_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_delete_hs_hr_emp_basicsalary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_delete_hs_hr_emp_basicsalary`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN old_sal_grd_code INT,
  IN old_salary_component VARCHAR(100) CHARSET UTF8,
  IN old_status TINYINT UNSIGNED,
  IN old_salary_rate TINYINT UNSIGNED,
  IN old_payperiod_code VARCHAR(13),
  IN old_currency_id VARCHAR(6),
  IN old_ebsal_basic_salary VARCHAR(100),
  IN old_effective_date DATE,
  IN old_cola DECIMAL(10, 2),
  IN old_comments VARCHAR(255) CHARSET UTF8
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;

  SET action_description = '';
  

  SET action_description = CONCAT(action_description, 'Salary record was deleted. (Salary Component: ', old_salary_component, ', Basic Pay: {encrypted:', old_ebsal_basic_salary , '} ', IFNULL((SELECT `currency_name` FROM `hs_hr_currency_type` WHERE `currency_id` = old_currency_id), 'NULL') ,', ');
  SET action_description = CONCAT(action_description, 'Pay Grade: ', IFNULL((SELECT `name` FROM `ohrm_pay_grade` WHERE `id` = old_sal_grd_code), 'NULL'),', ');
  SET action_description = CONCAT(action_description, 'Salary Rate: ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'salary_rate', old_salary_rate), 'NULL'), ', ');
  SET action_description = CONCAT(action_description, 'Pay Period: ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'payperiod_code', old_payperiod_code), 'NULL'), ', ');
  
  IF (old_cola IS NOT NULL) THEN
      SET action_description = CONCAT(action_description, 'COLA: ', IFNULL(old_cola, 'NULL'), ', ');
  END IF;
  
  SET action_description = CONCAT(action_description, 'Status: ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'status', old_status), 'NULL'), ', ');
  SET action_description = CONCAT(action_description, 'Effective Date: ', IFNULL(old_effective_date, 'NULL'), ', ');

  IF (old_comments IS NOT NULL) THEN
    SET action_description = CONCAT(action_description, 'Comments: ', IFNULL(old_comments, 'NULL'), ')');
  ELSE
    SET action_description = CONCAT(action_description, ')');
  END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_salary_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_delete_hs_hr_emp_reportto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_delete_hs_hr_emp_reportto`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN old_erep_sup_emp_number INT(7),
  IN old_erep_reporting_mode SMALLINT(6)
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

   SET action_description = CONCAT(action_description, ' ', 'Supervisor record was deleted. (', (SELECT CONCAT(`emp_firstname`, ' ', `emp_lastname`) FROM `hs_hr_employee` WHERE `emp_number` = old_erep_sup_emp_number) , ' - Reporting Method: ', IFNULL((SELECT `reporting_method_name` FROM ohrm_emp_reporting_method WHERE `reporting_method_id` = old_erep_reporting_mode), old_erep_reporting_mode) ,')');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_reportto_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_delete_hs_hr_emp_work_experience` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_delete_hs_hr_emp_work_experience`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN old_eexp_employer VARCHAR(100) CHARSET UTF8,
  IN old_eexp_jobtit VARCHAR(120) CHARSET UTF8
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;

  SET action_description = '';
  

   SET action_description = CONCAT(action_description, ' ', 'Work experience was deleted. (', old_eexp_jobtit, ' at ', old_eexp_employer, ')');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_work_experience_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_delete_ohrm_job_vacancy` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_delete_ohrm_job_vacancy`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(11),
  IN old_job_title_code VARCHAR(13),
  IN old_hiring_manager_id INT(7),
  IN old_status TINYINT(1),
  IN old_description TEXT CHARSET UTF8,
  IN old_name varchar(100),
  IN old_no_of_positions INT(13),
  IN old_published_in_feed TINYINT(1)  
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;

  SET action_description = '';
  

   SET action_description = CONCAT(action_description, ' ', 'Job vacancy was deleted. (Hiring Manager: ', IFNULL((SELECT CONCAT(`emp_firstname`, ' ', `emp_lastname`) FROM `hs_hr_employee` WHERE `emp_number` = old_hiring_manager_id), 'NULL'), ', ');
   SET action_description = CONCAT(action_description, ' ', 'Vacancy Name: ', resolve_empty(old_name), ',');
   SET action_description = CONCAT(action_description, ' ', 'Status: ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'status', old_status), 'NULL'), ',');
   SET action_description = CONCAT(action_description, ' ', 'No. of Positions: ', resolve_empty(old_no_of_positions), ',');
   SET action_description = CONCAT(action_description, ' ', 'Publish in RSS feed: ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'published_in_feed', old_published_in_feed), 'NULL'), ',');
   SET action_description = CONCAT(action_description, ' ', 'Description: ', resolve_empty(old_description), ')');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_recruitment_job_vacancy_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_insert_hs_hr_employee_personal_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_insert_hs_hr_employee_personal_details`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_emp_firstname VARCHAR(100) CHARSET UTF8,
  IN new_emp_lastname VARCHAR(100) CHARSET UTF8
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;

  SET action_description = '';
  

  SET action_description = CONCAT(action_description, ' ', 'New employee record was added to the system. (Name: ', new_emp_firstname, ' ', new_emp_lastname, ')');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_personal_details_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_insert_hs_hr_emp_basicsalary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_insert_hs_hr_emp_basicsalary`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_sal_grd_code INT,
  IN new_salary_component VARCHAR(100) CHARSET UTF8,
  IN new_status TINYINT UNSIGNED,
  IN new_salary_rate TINYINT UNSIGNED,
  IN new_payperiod_code VARCHAR(13),
  IN new_currency_id VARCHAR(6),
  IN new_ebsal_basic_salary VARCHAR(100),
  IN new_effective_date DATE,
  IN new_cola DECIMAL(10, 2),
  IN new_comments VARCHAR(255) CHARSET UTF8
)
BEGIN
  DECLARE action_description VARCHAR(1024) CHARSET UTF8;
 
  SET action_description = '';

  
  SET action_description = CONCAT(action_description, 'New salary record was added. (Salary Component: ', new_salary_component, ', Basic Pay: {encrypted:', new_ebsal_basic_salary , '} ', IFNULL((SELECT `currency_name` FROM `hs_hr_currency_type` WHERE `currency_id` = new_currency_id), 'NULL') ,', ');
  SET action_description = CONCAT(action_description, 'Pay Grade: ', IFNULL((SELECT `name` FROM `ohrm_pay_grade` WHERE `id` = new_sal_grd_code), 'NULL'),', ');
  SET action_description = CONCAT(action_description, 'Salary Rate: ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'salary_rate', new_salary_rate), 'NULL'), ', ');
  SET action_description = CONCAT(action_description, 'Pay Period: ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'payperiod_code', new_payperiod_code), 'NULL'), ', ');
  
  IF (new_cola IS NOT NULL) THEN
      SET action_description = CONCAT(action_description, 'COLA: ', IFNULL(new_cola, 'NULL'), ', ');
  END IF;
  
  SET action_description = CONCAT(action_description, 'Status: ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'status', new_status), 'NULL'), ', ');
  SET action_description = CONCAT(action_description, 'Effective Date: ', IFNULL(new_effective_date, 'NULL'), ', ');

  IF (new_comments IS NOT NULL) THEN
    SET action_description = CONCAT(action_description, 'Comments: ', IFNULL(new_comments, 'NULL'), ')');
  ELSE
    SET action_description = CONCAT(action_description, ')');
  END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_salary_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_insert_hs_hr_emp_reportto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_insert_hs_hr_emp_reportto`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_erep_sup_emp_number INT(7),
  IN new_erep_reporting_mode SMALLINT(6)
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

   SET action_description = CONCAT(action_description, ' ', 'New supervisor was added. (', (SELECT CONCAT(`emp_firstname`, ' ', `emp_lastname`) FROM `hs_hr_employee` WHERE `emp_number` = new_erep_sup_emp_number) , ' - Reporting Method: ', IFNULL((SELECT `reporting_method_name` FROM ohrm_emp_reporting_method WHERE `reporting_method_id` = new_erep_reporting_mode), new_erep_reporting_mode) , ')');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_reportto_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_insert_hs_hr_emp_work_experience` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_insert_hs_hr_emp_work_experience`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_eexp_employer VARCHAR(100) CHARSET UTF8,
  IN new_eexp_jobtit VARCHAR(120) CHARSET UTF8,
  IN new_eexp_from_date DATE,
  IN new_eexp_to_date DATE
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

  SET action_description = CONCAT(action_description, ' ', 'New work experience was added. (', new_eexp_jobtit, ' at ', new_eexp_employer);

  IF (new_eexp_from_date IS NOT NULL) THEN
    SET action_description = CONCAT(action_description, ' from ', new_eexp_from_date);
  END IF;

  IF (new_eexp_to_date IS NOT NULL) THEN
     SET action_description = CONCAT(action_description, ' to ', new_eexp_to_date);
  END IF;

  SET action_description = CONCAT(action_description, ')');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_work_experience_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_insert_ohrm_job_vacancy` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_insert_ohrm_job_vacancy`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(11),
  IN new_job_title_code VARCHAR(13),
  IN new_hiring_manager_id INT(7),
  IN new_status TINYINT(1),
  IN new_description TEXT CHARSET UTF8,
  IN new_name varchar(100),
  IN new_no_of_positions INT(13),
  IN new_published_in_feed TINYINT(1)  
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

   SET action_description = CONCAT(action_description, ' ', 'New job vacancy was added. (Hiring Manager: ', IFNULL((SELECT CONCAT(`emp_firstname`, ' ', `emp_lastname`) FROM `hs_hr_employee` WHERE `emp_number` = new_hiring_manager_id), 'NULL'), ',');
   SET action_description = CONCAT(action_description, ' ', 'Vacancy Name: ', resolve_empty(new_name), ',');
   SET action_description = CONCAT(action_description, ' ', 'Status: ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'status', new_status), 'NULL'), ',');
   SET action_description = CONCAT(action_description, ' ', 'No. of Positions: ', resolve_empty(new_no_of_positions), ',');
   SET action_description = CONCAT(action_description, ' ', 'Publish in RSS feed: ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'published_in_feed', new_published_in_feed), 'NULL'), ',');
   SET action_description = CONCAT(action_description, ' ', 'Description: ', resolve_empty(new_description), ')');

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_recruitment_job_vacancy_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_update_hs_hr_employee_contact_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_update_hs_hr_employee_contact_information`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_emp_street1 VARCHAR(100) CHARSET UTF8,
  IN old_emp_street1 VARCHAR(100) CHARSET UTF8,
  IN new_emp_street2 VARCHAR(100) CHARSET UTF8,
  IN old_emp_street2 VARCHAR(100) CHARSET UTF8,
  IN new_city_code VARCHAR(100),
  IN old_city_code VARCHAR(100),
  IN new_coun_code VARCHAR(100),
  IN old_coun_code VARCHAR(100),
  IN new_provin_code VARCHAR(100),
  IN old_provin_code VARCHAR(100),
  IN new_emp_zipcode VARCHAR(20),
  IN old_emp_zipcode VARCHAR(20),
  IN new_emp_hm_telephone VARCHAR(50),
  IN old_emp_hm_telephone VARCHAR(50),
  IN new_emp_mobile VARCHAR(50),
  IN old_emp_mobile VARCHAR(50),
  IN new_emp_work_telephone VARCHAR(50),
  IN old_emp_work_telephone VARCHAR(50),
  IN new_emp_work_email VARCHAR(50),
  IN old_emp_work_email VARCHAR(50),
  IN new_emp_oth_email VARCHAR(50),
  IN old_emp_oth_email VARCHAR(50)
)
BEGIN
  DECLARE action_description TEXT CHARSET UTF8;

  SET action_description = '';
  

  IF (old_emp_street1 != new_emp_street1) THEN SET action_description = CONCAT(action_description, ' ', 'Address Street 1 was changed from ', resolve_empty(old_emp_street1), ' to ', resolve_empty(new_emp_street1), '.\n');END IF;
  IF (old_emp_street2 != new_emp_street2) THEN SET action_description = CONCAT(action_description, ' ', 'Address Street 2 was changed from ', resolve_empty(old_emp_street2), ' to ', resolve_empty(new_emp_street2), '.\n');END IF;
  IF (old_city_code != new_city_code) THEN SET action_description = CONCAT(action_description, ' ', 'City was changed from ', resolve_empty(old_city_code), ' to ', resolve_empty(new_city_code), '.\n');END IF;
  IF (IF(old_coun_code = '', 0, old_coun_code) != IF(new_coun_code = '', 0, new_coun_code)) THEN SET action_description = CONCAT(action_description, ' ', 'Country was changed from ', IFNULL((SELECT `cou_name` FROM `hs_hr_country` WHERE `cou_code` = old_coun_code), 'NULL'), ' to ', IFNULL((SELECT `cou_name` FROM `hs_hr_country` WHERE `cou_code` = new_coun_code), 'NULL'), '.\n');END IF;
  IF (old_provin_code != new_provin_code) THEN SET action_description = CONCAT(action_description, ' ', 'State/Province was changed from ', resolve_empty(old_provin_code), ' to ', resolve_empty(new_provin_code), '.\n');END IF;
  IF (IFNULL(old_emp_zipcode, '') != IFNULL(new_emp_zipcode, '')) THEN SET action_description = CONCAT(action_description, ' ', 'Zip/Postal Code was changed from ', resolve_empty(old_emp_zipcode), ' to ', resolve_empty(new_emp_zipcode), '.\n');END IF;
  IF (IFNULL(old_emp_hm_telephone, '') != IFNULL(new_emp_hm_telephone, '')) THEN SET action_description = CONCAT(action_description, ' ', 'Home Telephone was changed from ', resolve_empty(old_emp_hm_telephone), ' to ', resolve_empty(new_emp_hm_telephone), '.\n');END IF;
  IF (IFNULL(old_emp_mobile, '') != IFNULL(new_emp_mobile, '')) THEN SET action_description = CONCAT(action_description, ' ', 'Mobile was changed from ', resolve_empty(old_emp_mobile), ' to ', resolve_empty(new_emp_mobile), '.\n');END IF;
  IF (IFNULL(old_emp_work_telephone, '') != IFNULL(new_emp_work_telephone, '')) THEN SET action_description = CONCAT(action_description, ' ', 'Work Telephone was changed from ', resolve_empty(old_emp_work_telephone), ' to ', resolve_empty(new_emp_work_telephone), '.\n');END IF;
  IF (IFNULL(old_emp_work_email, '') != IFNULL(new_emp_work_email, '')) THEN SET action_description = CONCAT(action_description, ' ', 'Work Email was changed from ', resolve_empty(old_emp_work_email), ' to ', resolve_empty(new_emp_work_email), '.\n');END IF;
  IF (IFNULL(old_emp_oth_email, '') != IFNULL(new_emp_oth_email, '')) THEN SET action_description = CONCAT(action_description, ' ', 'Other Email was changed from ', resolve_empty(old_emp_oth_email), ' to ', resolve_empty(new_emp_oth_email), '.\n');END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_contact_info_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_update_hs_hr_employee_job_title` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_update_hs_hr_employee_job_title`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_job_title_code VARCHAR(13),
  IN old_job_title_code VARCHAR(13)
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

  IF (IFNULL(old_job_title_code, '') != IFNULL(new_job_title_code, '')) THEN SET action_description = CONCAT(action_description, ' ', 'Job title was changed from ', IFNULL((SELECT `job_title` FROM `ohrm_job_title` WHERE `id` = old_job_title_code), 'NULL'), ' to ', IFNULL((SELECT `job_title` FROM `ohrm_job_title` WHERE `id` = new_job_title_code), 'NULL'), '.');END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_job_title_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_update_hs_hr_employee_personal_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_update_hs_hr_employee_personal_details`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_emp_firstname VARCHAR(100) CHARSET UTF8,
  IN old_emp_firstname VARCHAR(100) CHARSET UTF8,
  IN new_emp_lastname VARCHAR(100) CHARSET UTF8,
  IN old_emp_lastname VARCHAR(100) CHARSET UTF8,
  IN new_emp_middle_name VARCHAR(100) CHARSET UTF8,
  IN old_emp_middle_name VARCHAR(100) CHARSET UTF8
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

  IF (old_emp_firstname != new_emp_firstname) THEN SET action_description = CONCAT(action_description, ' ', 'First name was changed from ', IFNULL(old_emp_firstname, 'NULL'), ' to ', IFNULL(new_emp_firstname, 'NULL'), '.\n');END IF;
  IF (old_emp_lastname != new_emp_lastname) THEN SET action_description = CONCAT(action_description, ' ', 'Last name was changed from ', IFNULL(old_emp_lastname, 'NULL'), ' to ', IFNULL(new_emp_lastname, 'NULL'), '.\n');END IF;
  IF (old_emp_middle_name != new_emp_middle_name) THEN SET action_description = CONCAT(action_description, ' ', 'Middle name was changed from ', resolve_empty(old_emp_middle_name), ' to ', resolve_empty(new_emp_middle_name), '.\n');END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_personal_details_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_update_hs_hr_emp_basicsalary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_update_hs_hr_emp_basicsalary`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_sal_grd_code INT,
  IN old_sal_grd_code INT,
  IN new_salary_component VARCHAR(100) CHARSET UTF8,
  IN old_salary_component VARCHAR(100) CHARSET UTF8,
  IN new_status TINYINT UNSIGNED,
  IN old_status TINYINT UNSIGNED,
  IN new_salary_rate TINYINT UNSIGNED,
  IN old_salary_rate TINYINT UNSIGNED,
  IN new_payperiod_code VARCHAR(13),
  IN old_payperiod_code VARCHAR(13),
  IN new_currency_id VARCHAR(6),
  IN old_currency_id VARCHAR(6),
  IN new_ebsal_basic_salary VARCHAR(100),
  IN old_ebsal_basic_salary VARCHAR(100),
  IN new_effective_date DATE,
  IN old_effective_date DATE,
  IN new_cola DECIMAL(10, 2),
  IN old_cola DECIMAL(10, 2),
  IN new_comments VARCHAR(255) CHARSET UTF8,
  IN old_comments VARCHAR(255) CHARSET UTF8
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;

  SET action_description = '';
  
  

  IF (IFNULL(old_sal_grd_code, -1) != IFNULL(new_sal_grd_code, -1)) THEN SET action_description = CONCAT(action_description, ' ', 'Pay grade was changed from ', IFNULL((SELECT `name` FROM `ohrm_pay_grade` WHERE `id` = old_sal_grd_code), 'NULL'), ' to ', IFNULL((SELECT `name` FROM `ohrm_pay_grade` WHERE `id` = new_sal_grd_code), 'NULL'), '.\n');END IF;
  IF (old_currency_id != new_currency_id) THEN SET action_description = CONCAT(action_description, ' ', 'Currency was changed from ', IFNULL((SELECT `currency_name` FROM `hs_hr_currency_type` WHERE `currency_id` = old_currency_id), 'NULL'), ' to ', IFNULL((SELECT `currency_name` FROM `hs_hr_currency_type` WHERE `currency_id` = new_currency_id), 'NULL'), '.\n');END IF;
  IF (old_salary_component != new_salary_component) THEN SET action_description = CONCAT(action_description, ' ', 'Salary component was changed from ', IFNULL(old_salary_component, 'NULL'), ' to ', IFNULL(new_salary_component, 'NULL'), '.\n');END IF;
  IF (old_ebsal_basic_salary != new_ebsal_basic_salary) THEN SET action_description = CONCAT(action_description, ' ', 'Basic pay was changed from {encrypted:', IFNULL(old_ebsal_basic_salary, 'NULL'), '} to {encrypted:', IFNULL(new_ebsal_basic_salary, 'NULL'), '}.\n');END IF;
  IF (IFNULL(old_payperiod_code, -1) != IFNULL(new_payperiod_code, -1)) THEN SET action_description = CONCAT(action_description, ' ', 'Pay period was changed from ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'payperiod_code', old_payperiod_code), 'NULL'), ' to ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'payperiod_code', new_payperiod_code), 'NULL'), '.\n');END IF;
  IF (old_salary_rate != new_salary_rate) THEN SET action_description = CONCAT(action_description, ' ', 'Salary rate was changed from ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'salary_rate', old_salary_rate), 'NULL'), ' to ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'salary_rate', new_salary_rate), 'NULL'), '.\n');END IF;
  IF (old_status != new_status) THEN SET action_description = CONCAT(action_description, ' ', 'Status was changed from ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'status', old_status), 'NULL'), ' to ', IFNULL(get_static_reference_value('hs_hr_emp_basicsalary', 'status', new_status), 'NULL'), '.\n');END IF;
  IF (old_effective_date != new_effective_date) THEN SET action_description = CONCAT(action_description, ' ', 'Effective date was changed from ', IFNULL(old_effective_date, 'NULL'), ' to ', IFNULL(new_effective_date, 'NULL'), '.\n');END IF;
  IF (old_cola != new_cola) THEN SET action_description = CONCAT(action_description, ' ', 'COLA was changed from ', IFNULL(old_cola, 'NULL'), ' to ', IFNULL(new_cola, 'NULL'), '.\n');END IF;
  IF (old_comments != new_comments) THEN SET action_description = CONCAT(action_description, ' ', 'Comments were changed from ', IFNULL(old_comments, 'NULL'), ' to ', IFNULL(new_comments, 'NULL'), '.\n');END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_salary_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_update_hs_hr_emp_reportto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_update_hs_hr_emp_reportto`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_erep_sup_emp_number INT(7),
  IN old_erep_sup_emp_number INT(7),
  IN new_erep_reporting_mode SMALLINT(6),
  IN old_erep_reporting_mode SMALLINT(6)
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

  IF (old_erep_sup_emp_number != new_erep_sup_emp_number) THEN SET action_description = CONCAT(action_description, ' ', 'Supervisor was changed from ', IFNULL((SELECT `CONCAT(emp_firstname` FROM `hs_hr_employee` WHERE ` ` = old_erep_sup_emp_number), 'NULL'), ' to ', IFNULL((SELECT `CONCAT(emp_firstname` FROM `hs_hr_employee` WHERE ` ` = new_erep_sup_emp_number), 'NULL'), '');END IF;
  IF (old_erep_reporting_mode != new_erep_reporting_mode) THEN SET action_description = CONCAT(action_description, ' ', 'Reporting mode changed from ', IFNULL((SELECT `reporting_method_name` FROM ohrm_emp_reporting_method WHERE `reporting_method_id` = old_erep_reporting_mode), old_erep_reporting_mode), ' to ', IFNULL((SELECT `reporting_method_name` FROM ohrm_emp_reporting_method WHERE `reporting_method_id` = new_erep_reporting_mode), new_erep_reporting_mode), '');END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_reportto_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_update_hs_hr_emp_work_experience` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_update_hs_hr_emp_work_experience`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(7),
  IN new_eexp_employer VARCHAR(100) CHARSET UTF8,
  IN old_eexp_employer VARCHAR(100) CHARSET UTF8,
  IN new_eexp_jobtit VARCHAR(120) CHARSET UTF8,
  IN old_eexp_jobtit VARCHAR(120) CHARSET UTF8,
  IN new_eexp_from_date DATE,
  IN old_eexp_from_date DATE,
  IN new_eexp_to_date DATE,
  IN old_eexp_to_date DATE,
  IN new_eexp_comments VARCHAR(200) CHARSET UTF8,
  IN old_eexp_comments VARCHAR(200) CHARSET UTF8
)
BEGIN
  DECLARE action_description VARCHAR(600) CHARSET UTF8;


  SET action_description = '';
  

  IF (old_eexp_employer != new_eexp_employer) THEN SET action_description = CONCAT(action_description, ' ', 'Employer was changed from ', IFNULL(old_eexp_employer, 'NULL'), ' to ', IFNULL(new_eexp_employer, 'NULL'), '\n');END IF;
  IF (old_eexp_jobtit != new_eexp_jobtit) THEN SET action_description = CONCAT(action_description, ' ', 'Job Title was changed from ', IFNULL(old_eexp_jobtit, 'NULL'), ' to ', IFNULL(new_eexp_jobtit, 'NULL'), '\n');END IF;
  IF (old_eexp_from_date != new_eexp_from_date) THEN SET action_description = CONCAT(action_description, ' ', 'From Date was changed from ', IFNULL(old_eexp_from_date, 'NULL'), ' to ', IFNULL(new_eexp_from_date, 'NULL'), '\n');END IF;
  IF (old_eexp_to_date != new_eexp_to_date) THEN SET action_description = CONCAT(action_description, ' ', 'To Date was changed from ', IFNULL(old_eexp_to_date, 'NULL'), ' to ', IFNULL(new_eexp_to_date, 'NULL'), '\n');END IF;
  IF (old_eexp_comments != new_eexp_comments) THEN SET action_description = CONCAT(action_description, ' ', 'Comments were changed from ', resolve_empty(old_eexp_comments), ' to ', resolve_empty(new_eexp_comments), '');END IF;

  

  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_pim_work_experience_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      action_name,
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `audit_update_ohrm_job_vacancy` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `audit_update_ohrm_job_vacancy`(
  IN action_owner_id VARCHAR(6),
  IN action_name VARCHAR(40),
  IN new_version INT,
  IN affected_entity_id INT(11),
  IN new_job_title_code VARCHAR(13),
  IN old_job_title_code VARCHAR(13),
  IN new_hiring_manager_id INT(7),
  IN old_hiring_manager_id INT(7),
  IN new_status TINYINT(1),
  IN old_status TINYINT(1),
  IN new_description TEXT CHARSET UTF8,
  IN old_description TEXT CHARSET UTF8,
  IN new_name varchar(100),
  IN old_name varchar(100),
  IN new_no_of_positions INT(13),
  IN old_no_of_positions INT(13),
  IN new_published_in_feed TINYINT(1),
  IN old_published_in_feed TINYINT(1)  
)
BEGIN
  DECLARE action_description TEXT CHARSET UTF8;


  SET action_description = '';
  

  IF (old_job_title_code != new_job_title_code) THEN SET action_description = CONCAT(action_description, ' ', 'Job Title was changed from ', IFNULL((SELECT `job_title` FROM `ohrm_job_title` WHERE `id` = old_job_title_code), 'NULL'), ' to ', IFNULL((SELECT `job_title` FROM `ohrm_job_title` WHERE `id` = new_job_title_code), 'NULL'), '\n');END IF;
  IF (IFNULL(old_hiring_manager_id, -1) != IFNULL(new_hiring_manager_id, -1)) THEN SET action_description = CONCAT(action_description, ' ', 'Hiring manager was changed from ', IFNULL((SELECT CONCAT(`emp_firstname`, ' ', `emp_lastname`) FROM `hs_hr_employee` WHERE `emp_number` = old_hiring_manager_id), 'NULL'), ' to ', IFNULL((SELECT CONCAT(`emp_firstname`, ' ', `emp_lastname`) FROM `hs_hr_employee` WHERE `emp_number` = new_hiring_manager_id), 'NULL'), '\n');END IF;
  IF (old_status != new_status) THEN SET action_description = CONCAT(action_description, ' ', 'Status was changed from ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'status', old_status), 'NULL'), ' to ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'status', new_status), 'NULL'), '\n');END IF;
  IF (old_description != new_description) THEN SET action_description = CONCAT(action_description, ' ', 'Description was changed from ', resolve_empty(old_description), ' to ', resolve_empty(new_description), '\n');END IF;
  IF (old_name != new_name) THEN SET action_description = CONCAT(action_description, ' ', 'Vacancy Name was changed from ', resolve_empty(old_name), ' to ', resolve_empty(new_name), '\n');END IF;  
  IF (old_no_of_positions != new_no_of_positions) THEN SET action_description = CONCAT(action_description, ' ', 'No. of Positions was changed from ', resolve_empty(old_no_of_positions), ' to ', resolve_empty(new_no_of_positions), '\n');END IF;    
  IF (old_published_in_feed != new_published_in_feed) THEN SET action_description = CONCAT(action_description, ' ', 'Publish in RSS feed was changed from ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'published_in_feed', old_published_in_feed), 'NULL'), ' to ', IFNULL(get_static_reference_value('ohrm_job_vacancy', 'published_in_feed', new_published_in_feed), 'NULL'), '\n');END IF;


  IF (action_description != '') THEN
    INSERT INTO `ohrm_audittrail_recruitment_job_vacancy_trail`  VALUES (
      CURRENT_TIMESTAMP,
      action_owner_id,
      new_version,
      'CHANGE JOB VACANCY',
      affected_entity_id,
      action_description
    );
  END IF;
 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `ohrm_adv_leave_country_based_employee_leave_types`
--

/*!50001 DROP TABLE IF EXISTS `ohrm_adv_leave_country_based_employee_leave_types`*/;
/*!50001 DROP VIEW IF EXISTS `ohrm_adv_leave_country_based_employee_leave_types`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `ohrm_adv_leave_country_based_employee_leave_types` AS (select `e`.`emp_number` AS `emp_number`,`lt`.`leave_type_id` AS `leave_type_id`,NULL AS `leave_period_id`,1 AS `editable` from (((((`hs_hr_employee` `e` left join `hs_hr_emp_locations` `el` on((`e`.`emp_number` = `el`.`emp_number`))) left join `ohrm_location` `l` on((`el`.`location_id` = `l`.`id`))) left join `hs_hr_country` `c` on((`l`.`country_code` = `c`.`cou_code`))) left join `ohrm_operational_country` `oc` on((`c`.`cou_code` = `oc`.`country_code`))) join `hs_hr_leavetype` `lt` on((`oc`.`id` = `lt`.`operational_country_id`)))) union (select distinct `e`.`emp_number` AS `emp_number`,`lq`.`leave_type_id` AS `leave_type_id`,`lq`.`leave_period_id` AS `leave_period_id`,0 AS `editable` from (((`hs_hr_employee` `e` join `hs_hr_employee_leave_quota` `lq` on((`e`.`emp_number` = `lq`.`employee_id`))) left join `hs_hr_leave_requests` `lr` on(((`lr`.`leave_type_id` = `lq`.`leave_type_id`) and (`lr`.`employee_id` = `e`.`emp_number`)))) left join `hs_hr_leave` `l` on((`l`.`leave_request_id` = `lr`.`leave_request_id`))) where ((`lq`.`no_of_days_allotted` > 0) or (`l`.`leave_status` in (2,3)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-09 13:57:32
