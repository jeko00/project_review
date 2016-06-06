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

