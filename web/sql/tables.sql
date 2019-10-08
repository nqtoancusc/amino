drop database if exists amino;
create database amino default charset=utf8 collate=utf8_swedish_ci;
use amino;
grant all privileges on amino.* to amino@localhost identified by 'amino';

CREATE TABLE `service_livetv_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) NOT NULL COMMENT 'UUID identifier',
  `source_id` int(11) NOT NULL COMMENT 'Metadata provider id',
  `short_name` varchar(30) NOT NULL COMMENT 'Short name for the channel',
  `full_name` varchar(128) NOT NULL COMMENT 'Full name for the channel',
  `time_zone` varchar(30) NOT NULL,
  `primary_language` varchar(2) DEFAULT NULL COMMENT 'Two character description for the channel',
  `weight` int(4) DEFAULT '0' COMMENT 'Listing weight for the channel',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `source_id` (`source_id`),
  UNIQUE KEY `short_name` (`short_name`),
  UNIQUE KEY `uuid-unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*LOCK TABLES `service_livetv_channel` WRITE;*/

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (1,'14a2a6f3-bd0d-4ea7-ac0d-c3e237930bdc',17,'yle_tv1','Yle TV1','EEST','fi',1,'2016-07-01 09:14:42','2016-08-18 08:17:29');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (2,'7fbee3b8-3c32-4e42-9b60-167f16eccd9e',33,'yle_tv2','Yle TV2','EEST','fi',2,'2016-07-01 09:14:43','2016-08-18 08:17:30');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (3,'f453efda-f5c0-4165-b6c7-a4a2d27bb502',81,'yle_fem','Yle Fem','EEST','fi',3,'2016-07-01 09:14:43','2016-08-18 08:17:30');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (4,'553467fe-2532-4226-99ab-0bd090e7cb28',113,'yle_teema','Yle Teema','EEST','fi',4,'2016-07-01 09:14:44','2016-08-18 08:17:31');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (5,'3f794752-4624-402b-b4c4-deb643484753',155,'ava','AVA','EEST','fi',5,'2016-07-01 09:14:45','2016-08-18 08:17:32');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (6,'0d0bb6ce-8cdf-4ea5-b7f6-106cb826e90b',49,'mtv3','MTV3','EEST','fi',6,'2016-07-01 09:14:45','2016-08-18 08:17:32');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (7,'10e2c1c2-abe2-461b-a859-a7cfe3ff9d02',65,'nelonen','Nelonen','EEST','fi',7,'2016-07-01 09:14:46','2016-08-18 08:17:33');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (8,'27993c8d-051d-4e28-8fba-d4f0cc49bd6e',97,'subtv','Sub','EEST','fi',8,'2016-07-01 09:14:46','2016-08-18 08:17:33');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (9,'9e43e5c3-ebf5-4f16-9224-f2f95a7256ad',177,'liv','Liv','EEST','fi',9,'2016-07-01 09:14:47','2016-08-18 08:17:34');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (10,'98945f2b-c2da-40aa-b83a-45e52781aea0',8193,'estradi','Estradi','EEST','fi',10,'2016-07-01 09:14:48','2016-08-18 08:17:34');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (11,'6cc27404-e690-463b-a557-6159fd5d7835',451,'frii','Frii','EEST','fi',11,'2016-07-01 09:14:48','2016-08-18 08:17:35');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (12,'a40558fd-5432-4fb5-a676-86baf13f2475',529,'fox','FOX','EEST','fi',12,'2016-07-01 09:14:49','2016-08-18 08:17:35');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (13,'0705f1df-d679-43c3-8e92-9d1d0b1acc09',817,'iskelma_harju_pontinen','Iskelmä/Harju&Pöntinen','EEST','fi',13,'2016-07-01 09:14:50','2016-08-18 08:17:36');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (14,'00daf6a0-779b-4b3d-bc77-1304ce433694',129,'jim','Jim','EEST','fi',14,'2016-07-01 09:14:50','2016-08-18 08:17:37');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (15,'266fb2f4-8262-494c-8dee-0c2eaeb79c06',161,'tv5','TV5','EEST','fi',15,'2016-07-01 09:14:51','2016-08-18 08:17:38');

INSERT INTO `service_livetv_channel` (`id`, `uuid`, `source_id`, `short_name`, `full_name`, `time_zone`, `primary_language`, `weight`, `created_at`, `updated_at`)
VALUES (16,'ead33252-0d4b-4bf6-9121-f09dc76491e5',178,'kutonen','Kutonen','EEST','fi',16,'2016-07-01 09:14:51','2016-08-18 08:17:39');

/*UNLOCK TABLES;*/

CREATE TABLE `service_livetv_program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ext_program_id` bigint(20) unsigned NOT NULL COMMENT 'Metadata provider program id',
  `show_type` enum('movie','series','other') NOT NULL COMMENT 'Program show type',
  `long_title` varchar(255) NOT NULL COMMENT 'Program long title',
  `grid_title` varchar(15) DEFAULT NULL COMMENT 'Program grid title',
  `original_title` varchar(255) DEFAULT NULL COMMENT 'Program original title',
  `duration` int(11) unsigned DEFAULT NULL COMMENT 'Program duration',
  `iso_2_lang` varchar(2) DEFAULT NULL COMMENT 'Program language',
  `eidr_id` varchar(50) DEFAULT NULL COMMENT 'Program Entertainment Identifier Registry',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_ext_program_id` (`ext_program_id`),
  FULLTEXT KEY `indx_long_title` (`long_title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `service_livetv_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ext_schedule_id` bigint(20) unsigned NOT NULL COMMENT 'Metadata provider schedule id',
  `channel_id` int(11) NOT NULL COMMENT 'Channel source/channel id',
  `start_time` int(11) unsigned NOT NULL COMMENT 'Schedule start time',
  `end_time` int(11) unsigned NOT NULL COMMENT 'Schedule end time',
  `run_time` int(11) unsigned DEFAULT NULL COMMENT 'Schedule duration/run time',
  `program_id` int(11) NOT NULL COMMENT 'Schedule program id',
  `is_live` tinyint(1) DEFAULT NULL COMMENT 'Is schedule a live broadcast',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_ext_schedule_id` (`ext_schedule_id`),
  UNIQUE KEY `index_channel_schedule` (`channel_id`,`start_time`,`end_time`),
  KEY `channel_id` (`channel_id`),
  KEY `program_id` (`program_id`),
  CONSTRAINT `fk_service_livetv_schedule_channel_id` FOREIGN KEY (`channel_id`) REFERENCES `service_livetv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_service_livetv_schedule_program_id` FOREIGN KEY (`program_id`) REFERENCES `service_livetv_program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
