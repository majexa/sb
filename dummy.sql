CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `id2` int(11) NOT NULL DEFAULT '0',
  `uri` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `pageTitle` varchar(255) DEFAULT NULL,
  `text` text,
  `text_f` text,
  `ans` text,
  `dateCreate` datetime NOT NULL,
  `dateUpdate` datetime NOT NULL,
  `userId` bigint(11) DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  `ip` varchar(15) DEFAULT NULL,
  `ansId` int(11) NOT NULL,
  `ansUserId` int(10) DEFAULT NULL,
  `nick` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `ansUserId` (`ansUserId`),
  KEY `active` (`active`),
  KEY `userId` (`userId`),
  KEY `id2` (`id2`),
  KEY `parentId` (`parentId`),
  KEY `allPublic` (`id2`,`dateCreate`,`active`),
  KEY `pagePublic` (`parentId`,`id2`,`dateCreate`,`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comments_active` (
  `parentId` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`parentId`,`id2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `comments_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `id2` int(11) NOT NULL DEFAULT '0',
  `cnt` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`parentId`,`id2`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comments_srt` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  `parentId` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `userGroupId` int(10) DEFAULT NULL,
  KEY `id` (`id`,`active`,`parentId`,`id2`),
  KEY `userGroupId` (`userGroupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pageBlocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `colN` int(2) NOT NULL DEFAULT '0',
  `ownPageId` int(11) NOT NULL,
  `pageId` int(11) NOT NULL,
  `dateCreate` datetime NOT NULL,
  `dateUpdate` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  `global` int(1) NOT NULL DEFAULT '1',
  `settings` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `slices` (
  `id` varchar(255) NOT NULL,
  `pageId` int(11) NOT NULL DEFAULT '0',
  `dateCreate` datetime NOT NULL,
  `dateUpdate` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text,
  `type` varchar(255) NOT NULL DEFAULT 'html',
  `absolute` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=' INTO slices SET `id`=''afterTree_60'', `title`=''Под списком п';

CREATE TABLE IF NOT EXISTS `storeCart` (
  `sessionId` varchar(255) NOT NULL DEFAULT '',
  `pageId` int(11) NOT NULL DEFAULT '0',
  `cartId` varchar(100) NOT NULL DEFAULT '0',
  `cnt` int(11) NOT NULL DEFAULT '1',
  `dateUpdate` datetime NOT NULL,
  PRIMARY KEY (`sessionId`,`pageId`,`cartId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_pages` (
  `userId` int(11) NOT NULL DEFAULT '0',
  `pageId` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `dateCreate` datetime NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `privs` (
  `userId` int(11) NOT NULL,
  `pageId` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS `rating_dd_voted_ips` (
  `strName` varchar(50) NOT NULL,
  `itemId` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `voteDate` datetime NOT NULL,
  `votes` int(5) NOT NULL,
  PRIMARY KEY (`strName`,`itemId`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rating_dd_voted_users` (
  `strName` varchar(50) NOT NULL,
  `itemId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `voteDate` datetime NOT NULL,
  `votes` int(5) NOT NULL,
  PRIMARY KEY (`strName`,`itemId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rss_subscribes` (
  `channelId` int(11) NOT NULL,
  `pageId` int(11) NOT NULL,
  PRIMARY KEY (`channelId`,`pageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS `subsList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `useUsers` int(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `subs_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `subs_returns` (
  `subsId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` enum('users','emails') NOT NULL,
  `returnDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `subs_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `n` int(11) NOT NULL,
  `type` enum('users','emails') NOT NULL,
  `subsId` int(11) NOT NULL,
  `status` enum('','process','complete') NOT NULL,
  KEY `n` (`n`,`subsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `subs_subscribes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listId` int(11) NOT NULL,
  `text` text NOT NULL,
  `subsBeginDate` datetime NOT NULL,
  `subsEndDate` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `subs_users` (
  `userId` int(11) NOT NULL,
  `listId` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`listId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sound_play_time_log` (
  `strName` varchar(50) NOT NULL,
  `itemId` int(11) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `sec` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `level_items` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` enum('dd','comments') NOT NULL,
  `strName` varchar(50) NOT NULL,
  `weight` int(2) NOT NULL DEFAULT '1',
  `usedLevel` int(2) NOT NULL DEFAULT '0',
  `dateCreate` datetime NOT NULL,
  PRIMARY KEY (`id`,`userId`,`type`,`strName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `level_users` (
  `userId` int(11) NOT NULL,
  `level` int(2) NOT NULL,
  `nominateDate` datetime NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `onmap` int(1) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `onmap` (`onmap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `userStoreOrder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `data` text NOT NULL,
  `dateCreate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dateUpdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `userStoreOrderItems` (
  `itemId` int(11) DEFAULT NULL,
  `pageId` int(11) DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userStoreSettings` (
  `id` int(10) DEFAULT NULL,
  `settings` text,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `voting_log` (
  `pageId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `fieldName` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `dateCreate` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `indx` (
  `text` longtext NOT NULL,
  `id` int(15) NOT NULL,
  `strid` int(15) NOT NULL,
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pages_log` (
  `dateCreate` datetime NOT NULL,
  `pageId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `processTime` float NOT NULL,
  `memory` float NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pages_meta` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `titleType` enum('add','replace') NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `dateCreate` datetime NOT NULL,
  `dateUpdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_pages` (
  `userId` int(11) NOT NULL DEFAULT '0',
  `pageId` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `dateCreate` datetime NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
