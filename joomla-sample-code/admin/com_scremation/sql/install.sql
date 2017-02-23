CREATE TABLE IF NOT EXISTS `#__scremation_caskets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `price` double(10,2) NOT NULL,
  `image_s` varchar(255) NOT NULL,
  `image_m` varchar(255) NOT NULL,
  `image_l` varchar(255) NOT NULL,
  `manufacture` varchar(255) NOT NULL,
  `meterial` varchar(255) NOT NULL,
  `interior` varchar(255) NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

