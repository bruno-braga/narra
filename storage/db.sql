-- Table --
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(49) CHARACTER SET utf8 DEFAULT NULL,
  `iso_639-1` char(2) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=136 ;

-- Languages --
INSERT INTO `languages` VALUES(1, 'English', 'en');
INSERT INTO `languages` VALUES(2, 'Afar', 'aa');
INSERT INTO `languages` VALUES(3, 'Abkhazian', 'ab');
INSERT INTO `languages` VALUES(4, 'Afrikaans', 'af');
INSERT INTO `languages` VALUES(5, 'Amharic', 'am');
INSERT INTO `languages` VALUES(6, 'Arabic', 'ar');
INSERT INTO `languages` VALUES(7, 'Assamese', 'as');
INSERT INTO `languages` VALUES(8, 'Aymara', 'ay');
INSERT INTO `languages` VALUES(9, 'Azerbaijani', 'az');
INSERT INTO `languages` VALUES(10, 'Bashkir', 'ba');
INSERT INTO `languages` VALUES(11, 'Belarusian', 'be');
INSERT INTO `languages` VALUES(12, 'Bulgarian', 'bg');
INSERT INTO `languages` VALUES(13, 'Bihari', 'bh');
INSERT INTO `languages` VALUES(14, 'Bislama', 'bi');
INSERT INTO `languages` VALUES(15, 'Bengali/Bangla', 'bn');
INSERT INTO `languages` VALUES(16, 'Tibetan', 'bo');
INSERT INTO `languages` VALUES(17, 'Breton', 'br');
INSERT INTO `languages` VALUES(18, 'Catalan', 'ca');
INSERT INTO `languages` VALUES(19, 'Corsican', 'co');
INSERT INTO `languages` VALUES(20, 'Czech', 'cs');
INSERT INTO `languages` VALUES(21, 'Welsh', 'cy');
INSERT INTO `languages` VALUES(22, 'Danish', 'da');
INSERT INTO `languages` VALUES(23, 'German', 'de');
INSERT INTO `languages` VALUES(24, 'Bhutani', 'dz');
INSERT INTO `languages` VALUES(25, 'Greek', 'el');
INSERT INTO `languages` VALUES(26, 'Esperanto', 'eo');
INSERT INTO `languages` VALUES(27, 'Spanish', 'es');
INSERT INTO `languages` VALUES(28, 'Estonian', 'et');
INSERT INTO `languages` VALUES(29, 'Basque', 'eu');
INSERT INTO `languages` VALUES(30, 'Persian', 'fa');
INSERT INTO `languages` VALUES(31, 'Finnish', 'fi');
INSERT INTO `languages` VALUES(32, 'Fiji', 'fj');
INSERT INTO `languages` VALUES(33, 'Faeroese', 'fo');
INSERT INTO `languages` VALUES(34, 'French', 'fr');
INSERT INTO `languages` VALUES(35, 'Frisian', 'fy');
INSERT INTO `languages` VALUES(36, 'Irish', 'ga');
INSERT INTO `languages` VALUES(37, 'Scots/Gaelic', 'gd');
INSERT INTO `languages` VALUES(38, 'Galician', 'gl');
INSERT INTO `languages` VALUES(39, 'Guarani', 'gn');
INSERT INTO `languages` VALUES(40, 'Gujarati', 'gu');
INSERT INTO `languages` VALUES(41, 'Hausa', 'ha');
INSERT INTO `languages` VALUES(42, 'Hindi', 'hi');
INSERT INTO `languages` VALUES(43, 'Croatian', 'hr');
INSERT INTO `languages` VALUES(44, 'Hungarian', 'hu');
INSERT INTO `languages` VALUES(45, 'Armenian', 'hy');
INSERT INTO `languages` VALUES(46, 'Interlingua', 'ia');
INSERT INTO `languages` VALUES(47, 'Interlingue', 'ie');
INSERT INTO `languages` VALUES(48, 'Inupiak', 'ik');
INSERT INTO `languages` VALUES(49, 'Indonesian', 'in');
INSERT INTO `languages` VALUES(50, 'Icelandic', 'is');
INSERT INTO `languages` VALUES(51, 'Italian', 'it');
INSERT INTO `languages` VALUES(52, 'Hebrew', 'iw');
INSERT INTO `languages` VALUES(53, 'Japanese', 'ja');
INSERT INTO `languages` VALUES(54, 'Yiddish', 'ji');
INSERT INTO `languages` VALUES(55, 'Javanese', 'jw');
INSERT INTO `languages` VALUES(56, 'Georgian', 'ka');
INSERT INTO `languages` VALUES(57, 'Kazakh', 'kk');
INSERT INTO `languages` VALUES(58, 'Greenlandic', 'kl');
INSERT INTO `languages` VALUES(59, 'Cambodian', 'km');
INSERT INTO `languages` VALUES(60, 'Kannada', 'kn');
INSERT INTO `languages` VALUES(61, 'Korean', 'ko');
INSERT INTO `languages` VALUES(62, 'Kashmiri', 'ks');
INSERT INTO `languages` VALUES(63, 'Kurdish', 'ku');
INSERT INTO `languages` VALUES(64, 'Kirghiz', 'ky');
INSERT INTO `languages` VALUES(65, 'Latin', 'la');
INSERT INTO `languages` VALUES(66, 'Lingala', 'ln');
INSERT INTO `languages` VALUES(67, 'Laothian', 'lo');
INSERT INTO `languages` VALUES(68, 'Lithuanian', 'lt');
INSERT INTO `languages` VALUES(69, 'Latvian/Lettish', 'lv');
INSERT INTO `languages` VALUES(70, 'Malagasy', 'mg');
INSERT INTO `languages` VALUES(71, 'Maori', 'mi');
INSERT INTO `languages` VALUES(72, 'Macedonian', 'mk');
INSERT INTO `languages` VALUES(73, 'Malayalam', 'ml');
INSERT INTO `languages` VALUES(74, 'Mongolian', 'mn');
INSERT INTO `languages` VALUES(75, 'Moldavian', 'mo');
INSERT INTO `languages` VALUES(76, 'Marathi', 'mr');
INSERT INTO `languages` VALUES(77, 'Malay', 'ms');
INSERT INTO `languages` VALUES(78, 'Maltese', 'mt');
INSERT INTO `languages` VALUES(79, 'Burmese', 'my');
INSERT INTO `languages` VALUES(80, 'Nauru', 'na');
INSERT INTO `languages` VALUES(81, 'Nepali', 'ne');
INSERT INTO `languages` VALUES(82, 'Dutch', 'nl');
INSERT INTO `languages` VALUES(83, 'Norwegian', 'no');
INSERT INTO `languages` VALUES(84, 'Occitan', 'oc');
INSERT INTO `languages` VALUES(85, '(Afan)/Oromoor/Oriya', 'om');
INSERT INTO `languages` VALUES(86, 'Punjabi', 'pa');
INSERT INTO `languages` VALUES(87, 'Polish', 'pl');
INSERT INTO `languages` VALUES(88, 'Pashto/Pushto', 'ps');
INSERT INTO `languages` VALUES(89, 'Portuguese', 'pt');
INSERT INTO `languages` VALUES(90, 'Quechua', 'qu');
INSERT INTO `languages` VALUES(91, 'Rhaeto-Romance', 'rm');
INSERT INTO `languages` VALUES(92, 'Kirundi', 'rn');
INSERT INTO `languages` VALUES(93, 'Romanian', 'ro');
INSERT INTO `languages` VALUES(94, 'Russian', 'ru');
INSERT INTO `languages` VALUES(95, 'Kinyarwanda', 'rw');
INSERT INTO `languages` VALUES(96, 'Sanskrit', 'sa');
INSERT INTO `languages` VALUES(97, 'Sindhi', 'sd');
INSERT INTO `languages` VALUES(98, 'Sangro', 'sg');
INSERT INTO `languages` VALUES(99, 'Serbo-Croatian', 'sh');
INSERT INTO `languages` VALUES(100, 'Singhalese', 'si');
INSERT INTO `languages` VALUES(101, 'Slovak', 'sk');
INSERT INTO `languages` VALUES(102, 'Slovenian', 'sl');
INSERT INTO `languages` VALUES(103, 'Samoan', 'sm');
INSERT INTO `languages` VALUES(104, 'Shona', 'sn');
INSERT INTO `languages` VALUES(105, 'Somali', 'so');
INSERT INTO `languages` VALUES(106, 'Albanian', 'sq');
INSERT INTO `languages` VALUES(107, 'Serbian', 'sr');
INSERT INTO `languages` VALUES(108, 'Siswati', 'ss');
INSERT INTO `languages` VALUES(109, 'Sesotho', 'st');
INSERT INTO `languages` VALUES(110, 'Sundanese', 'su');
INSERT INTO `languages` VALUES(111, 'Swedish', 'sv');
INSERT INTO `languages` VALUES(112, 'Swahili', 'sw');
INSERT INTO `languages` VALUES(113, 'Tamil', 'ta');
INSERT INTO `languages` VALUES(114, 'Telugu', 'te');
INSERT INTO `languages` VALUES(115, 'Tajik', 'tg');
INSERT INTO `languages` VALUES(116, 'Thai', 'th');
INSERT INTO `languages` VALUES(117, 'Tigrinya', 'ti');
INSERT INTO `languages` VALUES(118, 'Turkmen', 'tk');
INSERT INTO `languages` VALUES(119, 'Tagalog', 'tl');
INSERT INTO `languages` VALUES(120, 'Setswana', 'tn');
INSERT INTO `languages` VALUES(121, 'Tonga', 'to');
INSERT INTO `languages` VALUES(122, 'Turkish', 'tr');
INSERT INTO `languages` VALUES(123, 'Tsonga', 'ts');
INSERT INTO `languages` VALUES(124, 'Tatar', 'tt');
INSERT INTO `languages` VALUES(125, 'Twi', 'tw');
INSERT INTO `languages` VALUES(126, 'Ukrainian', 'uk');
INSERT INTO `languages` VALUES(127, 'Urdu', 'ur');
INSERT INTO `languages` VALUES(128, 'Uzbek', 'uz');
INSERT INTO `languages` VALUES(129, 'Vietnamese', 'vi');
INSERT INTO `languages` VALUES(130, 'Volapuk', 'vo');
INSERT INTO `languages` VALUES(131, 'Wolof', 'wo');
INSERT INTO `languages` VALUES(132, 'Xhosa', 'xh');
INSERT INTO `languages` VALUES(133, 'Yoruba', 'yo');
INSERT INTO `languages` VALUES(134, 'Chinese', 'zh');
INSERT INTO `languages` VALUES(135, 'Zulu', 'zu');
mysql: [Warning] Using a password on the command line interface can be insecure.
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A

Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 11
Server version: 5.7.26 MySQL Community Server (GPL)

Copyright (c) 2000, 2019, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> 
mysql> ^C
mysql> ^C
mysql> ^C
mysql> ^C
mysql> ^C
mysql> ^C
mysql> 