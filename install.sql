CREATE TABLE wcf1_guthaben_thieflog
(
	thieflogID int(10) unsigned NOT NULL auto_increment,
	userID int(10) unsigned NOT NULL,
	preyID int(10) unsigned NOT NULL DEFAULT 0,
	thiefDate int(10) NOT NULL DEFAULT 0,
	ipAddress varchar(15) NOT NULL DEFAULT '',
	PRIMARY KEY (thieflogID),
	KEY userID (userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;