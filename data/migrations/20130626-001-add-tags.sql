CREATE TABLE `Tag_Types` (
  `Tag_Type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tag_Type` tinytext NOT NULL,
  PRIMARY KEY (`Tag_Type_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `Tags` (
  `Tag_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tag` tinytext NOT NULL,
  `Tag_Type_ID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Tag_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `Items_Tags` (
  `Item_ID` int(11) NOT NULL DEFAULT '0',
  `Tag_ID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Item_ID`,`Tag_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;