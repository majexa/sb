CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` varchar(11) NOT NULL DEFAULT '0',
  `pids` varchar(255) NOT NULL,
  `oid` int(11) unsigned DEFAULT '0',
  `menuId` int(10) NOT NULL DEFAULT '0',
  `userId` int(15) NOT NULL DEFAULT '0',
  `active` int(1) unsigned DEFAULT '0',
  `folder` int(1) NOT NULL,
  `title` varchar(255) DEFAULT '0',
  `fullTitle` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `path` varchar(255) NOT NULL,
  `pathData` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT '0',
  `dateCreate` datetime NOT NULL,
  `dateUpdate` datetime NOT NULL,
  `home` int(1) DEFAULT '0',
  `onMenu` int(1) DEFAULT '0',
  `onMap` int(1) DEFAULT '0',
  `isLock` int(1) DEFAULT '0',
  `counter` int(6) NOT NULL DEFAULT '0',
  `initSettings` text NOT NULL,
  `settings` text NOT NULL,
  `controller` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `strName` varchar(20) NOT NULL,
  `mysite` int(1) NOT NULL DEFAULT '0',
  `slave` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `home` (`home`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `slices` (
  `id` varchar(255) NOT NULL,
  `dateCreate` datetime NOT NULL,
  `dateUpdate` datetime NOT NULL,
  `text` text,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
