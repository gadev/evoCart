CREATE TABLE IF NOT EXISTS `{PREFIX}evocart_orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `short_txt` text NOT NULL,
    `content` text,
    `price` float NOT NULL,
    `currency` varchar(255) NOT NULL,
    `date` datetime DEFAULT NULL,
    `sentdate` datetime DEFAULT NULL,
    `note` text NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(255) NOT NULL,
    `payment` varchar(128) NOT NULL,
    `delivery` varchar(128) DEFAULT NULL,
    `address` text,
    `discount` text,
    `payed` int(11) DEFAULT NULL,
    `status` int(11) NOT NULL,
    `userid` int(11) DEFAULT NULL,
    `managerid` int(11) DEFAULT NULL,
    `1c_exchange` int(10) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `{PREFIX}evocart_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL,
  `managerid` int(11) NOT NULL,
  `action` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `{PREFIX}discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `container` varchar(255) DEFAULT NULL,
  `config` varchar(255) NOT NULL,
  `values` mediumtext NOT NULL,
  `visible` tinyint(1) unsigned DEFAULT '1',
  `index` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `document_id` (`user_id`,`container`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `{PREFIX}system_eventnames` (name, service, groupname)
SELECT * FROM (SELECT 'OnBeforeEvoCartRender', 7, 'evoCart') AS tmp
WHERE NOT EXISTS (
    SELECT name FROM `{PREFIX}system_eventnames` WHERE name = 'OnBeforeEvoCartRender'
) LIMIT 1;
