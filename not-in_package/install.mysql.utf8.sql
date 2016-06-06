CREATE TABLE IF NOT EXISTS `#__hshrndreview_projects` (
    `hshrndreview_project_id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                         VARCHAR(255)     NOT NULL,
    `slug`                         varchar(50)      DEFAULT NULL,
    `owner`	                       INT(11)          DEFAULT NULL,
	`ownerAL`                      INT(11)          DEFAULT NULL,
    `project_type`                 VARCHAR(255)     NOT NULL,
    `startDate`                    DATETIME         NOT NULL default '0000-00-00 00:00:00',
    `hshrndreview_fk_status_id`    INT(11) UNSIGNED DEFAULT NULL,	

    `enabled`                      TINYINT(1)       NOT NULL DEFAULT '1',
    `ordering`                     BIGINT(20) UNSIGNED NOT NULL,
    `created_on`                   DATETIME         NOT NULL default '0000-00-00 00:00:00',
    `created_by`                   INT(11)          NOT NULL DEFAULT 0,
    `modified_on`                  DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by`                  INT(11)          NOT NULL DEFAULT 0,
    `locked_on`                    DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    `locked_by`                    INT(11)          NOT NULL DEFAULT 0,
    `hits`                         BIGINT(20) UNSIGNED NOT NULL default '0',
    PRIMARY KEY (`hshrndreview_project_id`),
    KEY `idx_locked` (`locked_by`),
    KEY `idx_enabled` (`enabled`)
)
    ENGINE=InnoDB DEFAULT COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__hshrndreview_statuses` (
	`hshrndreview_status_id`        INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,	
    `hshrndreview_fk_project_id`    INT(11) UNSIGNED NOT NULL,

    `objective`                     mediumtext NOT NULL,
    `objectiveChanged`              int(11) DEFAULT NULL,

    `responsible`                   varchar(100) NOT NULL,
    `responsibleChanged`            int(11) DEFAULT NULL,

    `estimatedProjectCosts`         double NOT NULL,
    `estimatedProjectCostsChanged`  int(11) DEFAULT NULL,

    `projectStatus`                 varchar(50) NOT NULL,
    `projectStatusChanged`          int(11) DEFAULT NULL,

    `projectDuration`               varchar(30) DEFAULT NULL,
    `projectDurationChanged`        int(11) DEFAULT NULL,

    `msURSReleaseDate`              date NOT NULL,
    `msURSReleaseDateChanged`       int(11) DEFAULT NULL,
    `msURSReleasedReached`          int(11) DEFAULT NULL,

    `msFSReleaseDate`               date NOT NULL,
    `msFSReleaseDateChanged`        int(11) DEFAULT NULL,
    `msFSReleasedReached`           int(11) DEFAULT NULL,

    `msExpModelReleaseDate`         date NOT NULL,
    `msExpModelReleaseDateChanged`  int(11) DEFAULT NULL,
    `msExpModelReleasedReached`     int(11) DEFAULT NULL,

    `msPrototypeReleaseDate`        date NOT NULL,
    `msPrototypeReleaseDateChanged` int(11) DEFAULT NULL,
    `msPrototypeReleasedReached`    int(11) DEFAULT NULL,

    `msVolumeProductionDate`        date NOT NULL,
    `msVolumeProductionDateChanged` int(11) DEFAULT NULL,
    `msVolumeProductionReached`     int(11) DEFAULT NULL,

    `justificationForAChange`       mediumtext DEFAULT NULL,
  	`externalPartner`               mediumtext DEFAULT NULL,
  	`externalPartnerChanged`        int(11) DEFAULT NULL,
  	`projectAssessment`             mediumtext DEFAULT NULL,

    `enabled`                       TINYINT(1)       NOT NULL DEFAULT '1',
    `ordering`                      BIGINT(20) UNSIGNED NOT NULL,
    `created_on`                    DATETIME         NOT NULL default '0000-00-00 00:00:00',
    `created_by`                    INT(11)          NOT NULL DEFAULT 0,
    `modified_on`                   DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by`                   INT(11)          NOT NULL DEFAULT 0,
    `locked_on`                     DATETIME         NOT NULL DEFAULT '0000-00-00 00:00:00',
    `locked_by`                     INT(11)          NOT NULL DEFAULT 0,
    `hits`                          BIGINT(20) UNSIGNED NOT NULL default '0',

    PRIMARY KEY (`hshrndreview_status_id`),
    KEY `idx_locked` (`locked_by`),
    KEY `idx_enabled` (`enabled`)
)
    ENGINE=InnoDB DEFAULT COLLATE = utf8_general_ci;



CREATE TABLE IF NOT EXISTS `#__hshrndreview_partnerships` (
	  `hshrndreview_partnership_id`     INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,	
	  `hshrndreview_fk_collaborator_id` int(11) NOT NULL,
	  `owner_id`                        int(11) NOT NULL,
	  `slug`                            varchar(50) DEFAULT NULL,
	  `enabled`                         tinyint(1) NOT NULL DEFAULT '1',
	  `ordering`                        bigint(20) unsigned NOT NULL,
	  `created_on`                      datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `created_by`                      int(11) NOT NULL DEFAULT '0',
	  `modified_on`                     datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `modified_by`                     int(11) NOT NULL DEFAULT '0',
	  `locked_on`                       datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `locked_by`                       int(11) NOT NULL DEFAULT '0',
	  `hits`                            bigint(20) unsigned NOT NULL DEFAULT '0',
       PRIMARY KEY (`hshrndreview_partnership_id`),
       KEY `idx_locked` (`locked_by`),
       KEY `idx_enabled` (`enabled`)
) 
    ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__hshrndreview_collaborators` (
	  `hshrndreview_collaborator_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,	
	  `name` varchar(255) NOT NULL,
	  `slug` varchar(50) DEFAULT NULL,
	  `enabled` tinyint(1) NOT NULL DEFAULT '1',
	  `ordering` bigint(20) unsigned NOT NULL,
	  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `created_by` int(11) NOT NULL DEFAULT '0',
	  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `modified_by` int(11) NOT NULL DEFAULT '0',
	  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `locked_by` int(11) NOT NULL DEFAULT '0',
	  `hits` bigint(20) unsigned NOT NULL DEFAULT '0',
       PRIMARY KEY (`hshrndreview_collaborator_id`),
       KEY `idx_locked` (`locked_by`),
       KEY `idx_enabled` (`enabled`)	
) 
    ENGINE=InnoDB DEFAULT CHARSET=utf8;



















INSERT INTO `#__hshrndreview_projects` (`hshrndreview_project_id`, `name`, `slug`, `owner`, `ownerAL`, `project_type`, `startDate`, `hshrndreview_fk_status_id`, `enabled`, `ordering`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`, `hits`) VALUES
(1, 'Carbon Fork', '', 16, 7, 'Development', '2015-12-03 00:00:00', 4, 1, 0, '2016-04-01 08:04:20', 128, '2016-04-01 08:16:00', 128, '0000-00-00 00:00:00', 0, 0),
(2, 'Servo Steering System for E-Bike ', '', 16, 7, 'Development', '2016-03-22 00:00:00', 5, 1, 0, '2016-04-01 08:09:49', 128, '2016-04-01 08:23:59', 128, '0000-00-00 00:00:00', 0, 0),
(3, 'DC-DC Converter', '', 15, 8, 'Development', '2015-12-03 00:00:00', 7, 1, 0, '2016-04-01 08:34:29', 129, '2016-04-01 08:40:32', 129, '0000-00-00 00:00:00', 0, 0);


INSERT INTO `#__hshrndreview_statuses` (`hshrndreview_status_id`, `hshrndreview_fk_project_id`, `objective`, `objectiveChanged`, `responsible`, `responsibleChanged`, `estimatedProjectCosts`, `estimatedProjectCostsChanged`, `projectStatus`, `projectStatusChanged`, `projectDuration`, `projectDurationChanged`, `msURSReleaseDate`, `msURSReleaseDateChanged`, `msURSReleasedReached`, `msFSReleaseDate`, `msFSReleaseDateChanged`, `msFSReleasedReached`, `msExpModelReleaseDate`, `msExpModelReleaseDateChanged`, `msExpModelReleasedReached`, `msPrototypeReleaseDate`, `msPrototypeReleaseDateChanged`, `msPrototypeReleasedReached`, `msVolumeProductionDate`, `msVolumeProductionDateChanged`, `msVolumeProductionReached`, `justificationForAChange`, `externalPartner`, `externalPartnerChanged`, `projectAssessment`, `enabled`, `ordering`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`, `hits`) VALUES
(1, 1, 'This project aims at designing and manufacturing a new carbon fork for the race bike series', 0, 'Andy Achse', 0, 500000, 0, 'Active', 0, 'P0Y5M28D', 0, '2016-01-22', 0, NULL, '2016-02-17', 0, NULL, '2016-03-17', 0, NULL, '2016-04-29', 0, NULL, '2016-05-31', 0, NULL, '', 'KAZ Kunstoff Ausbildungszentrum Langenthal', 0, NULL, 1, 0, '2016-03-01 08:04:20', 128, '2016-04-01 08:04:35', 128, '2016-04-01 08:04:35', 128, 0),
(2, 1, 'This project aims at designing and manufacturing a new carbon fork for the race bike series', 0, 'Andy Achse', 0, 400000, -1, 'Active', 0, 'P0Y5M28D', 0, '2016-01-22', 0, 1, '2016-02-17', 0, 1, '2016-03-17', 0, NULL, '2016-04-29', 0, NULL, '2016-05-31', 0, NULL, 'We found a cheaper carbon supplier ', 'KAZ Kunstoff Ausbildungszentrum Langenthal', 0, NULL, 1, 0, '2016-03-27 08:04:46', 128, '2016-04-01 08:15:00', 128, '2016-04-01 08:15:00', 128, 0),
(3, 2, 'For our E-Bike high performance series we will develop a servo steering system that will allow to control the bike steering with an IPhone.', 0, 'Andy Achse', 0, 1000000, 0, 'Active', 0, 'P0Y3M8D', 0, '2016-04-30', 0, NULL, '2016-05-12', 0, NULL, '2016-05-29', 0, NULL, '2016-05-31', 0, NULL, '2016-06-30', 0, NULL, '', 'Apple California', 0, NULL, 1, 0, '2016-03-21 08:09:49', 128, '2016-04-01 08:22:00', 128, '2016-04-01 08:22:00', 128, 0),
(4, 1, 'This project aims at designing and manufacturing a new carbon fork for the race bike series', 0, 'Andy Achse', 0, 400000, 0, 'Active', 0, 'P0Y5M26D', -1, '2016-01-22', 0, 1, '2016-02-17', 0, 1, '2016-03-17', 0, 1, '2016-04-29', 0, NULL, '2016-05-29', -1, NULL, 'Thanks to the new carbon glue manufacturing is gonna become much more simple', 'KAZ Kunstoff Ausbildungszentrum Langenthal', 0, NULL, 1, 0, '2016-04-01 08:16:00', 128, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0),
(5, 2, 'For our E-Bike high performance series we will develop a servo steering system that will allow to control the bike steering with an IPhone or with Android phone.', 1, 'Andy Achse', 0, 1009000, 1, 'Active', 0, 'P0Y3M8D', 0, '2016-03-30', -1, 1, '2016-05-12', 0, NULL, '2016-05-29', 0, NULL, '2016-05-31', 0, NULL, '2016-06-30', 0, NULL, 'In order to support android with had to partner with samsung in addition. The android support added to the overall project costs ', 'Apple California\r\nSamsung', 1, NULL, 1, 0, '2016-04-01 08:23:59', 128, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0),
(6, 3, 'For the our mobile solar panel system MSP320 a new DC/DC Converter with an efficiency of 120% should be developed.', 0, 'Susi Sonne', 0, 10000, 0, 'Active', 0, 'P0Y4M27D', 0, '2016-01-08', 0, 1, '2016-02-17', 0, 1, '2016-03-10', 0, NULL, '2016-04-01', 0, NULL, '2016-04-30', 0, NULL, '', NULL, 0, NULL, 1, 0, '2016-03-01 08:34:29', 129, '2016-04-01 08:37:33', 129, '2016-04-01 08:37:33', 129, 0),
(7, 3, 'For the our mobile solar panel system MSP320 a new DC/DC Converter with an efficiency of 120% should be developed.', 0, 'Susi Sonne', 0, 10000, 0, 'Active', 0, 'P0Y5M27D', 1, '2016-01-08', 0, 1, '2016-02-17', 0, 1, '2016-04-10', 1, NULL, '2016-05-01', 1, NULL, '2016-05-30', 1, NULL, 'For some reason we failed so far to reach 120% efficiency. We are at  90% so far. We had to subcontract an engineering office to get some support on this.  ', 'Cheating & Partner Zurich', 1, NULL, 1, 0, '2016-04-01 08:40:32', 129, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0);


