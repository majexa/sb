CREATE TABLE IF NOT EXISTS `storeCart` (
  `sessionId` varchar(255) NOT NULL DEFAULT '',
  `pageId` int(11) NOT NULL DEFAULT '0',
  `cartId` varchar(100) NOT NULL DEFAULT '0',
  `cnt` int(11) NOT NULL DEFAULT '1',
  `dateUpdate` datetime NOT NULL,
  PRIMARY KEY (`sessionId`,`pageId`,`cartId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
