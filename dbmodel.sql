
-- ------
-- BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
-- highseason implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

-- Example 1: create a standard "card" table to be used with the "Deck" tools (see example game "hearts"):

-- CREATE TABLE IF NOT EXISTS `card` (
--   `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `card_type` varchar(16) NOT NULL,
--   `card_type_arg` int(11) NOT NULL,
--   `card_location` varchar(16) NOT NULL,
--   `card_location_arg` int(11) NOT NULL,
--   PRIMARY KEY (`card_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Example 2: add a custom field to the standard "player" table
-- ALTER TABLE `player` ADD `player_my_custom_field` INT UNSIGNED NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `pending` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `player_id` int(10) NULL,  
  `function` varchar(50) NULL,
  `target` varchar(50) NULL,
  `arg` varchar(50) NULL,  
  `arg2` varchar(50) NULL,
  `arg3` varchar(50) NULL,
  `arg4` varchar(50) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;

CREATE TABLE IF NOT EXISTS `dice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `valeur` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `multiaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NULL,
  `action` varchar(50) NULL,
  `arg` int(2) NOT NULL DEFAULT '0',
  `arg2` int(2) NOT NULL DEFAULT '0',
  `ordre` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `hotel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `player_id` int(10) NULL,  
  `porte` int(2) NOT NULL DEFAULT '0',
  `etat` int(2) NOT NULL DEFAULT '0',
  `couleur` int(2) NOT NULL DEFAULT '0',
  `niveau` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `player_id` int(10) NULL,
  `pos` int(2) NOT NULL DEFAULT '0',
  `type` int(2) NOT NULL DEFAULT '0',
  `prix` int(2) NOT NULL DEFAULT '0',
  `etat` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `player` ADD `hotel` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `staff` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `moneygain` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `moneyuse` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `credituse` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `emperor1` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `emperor2` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `emperor3` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonusemperor1` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `malussemperor1` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonusemperor2` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `malussemperor2` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonusemperor3` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `malussemperor3` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `first` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `turn` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `c1` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `c2` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `c3` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `c4` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `c5` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `c6` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `c7` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l1` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l2` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l3` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l4` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l5` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l6` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l7` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `l8` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpstaff` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpstafftotal` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpgroupe` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `compteurgroupe` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpligne4` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpligne3` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpligne2` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpligne1` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `vpemperor` int(2) DEFAULT 0;
ALTER TABLE `player` ADD `vpetage` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `permstaff1` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `permstaff2` int(2) unsigned DEFAULT 0;


