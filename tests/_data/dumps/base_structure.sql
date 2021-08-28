-- MySQL dump 10.13  Distrib 8.0.26, for Linux (x86_64)
--
-- Host: localhost    Database: db_prpl_base
-- ------------------------------------------------------
-- Server version	8.0.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `abonents`
--

DROP TABLE IF EXISTS `abonents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abonents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `surname` varchar(64) DEFAULT NULL COMMENT 'Фамилия абонента',
  `name` varchar(64) DEFAULT NULL COMMENT 'Имя абонента',
  `patronymic` varchar(64) DEFAULT NULL COMMENT 'Отчество абонента',
  `phone` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Флаг активности',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания абонента',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления абонента',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx-abonents-phone` (`phone`),
  KEY `idx-abonents-deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `billing_journal`
--

DROP TABLE IF EXISTS `billing_journal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `billing_journal` (
  `id` char(36) NOT NULL,
  `rel_abonents_to_products_id` int NOT NULL COMMENT 'Связь с продуктом и абонентом',
  `price` decimal(8,2) NOT NULL COMMENT 'Величина списания',
  `status_id` tinyint NOT NULL COMMENT 'Статус списания',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `i_billing_journal_to_rel_abonents_to_products` (`rel_abonents_to_products_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT 'Название партнера',
  `inn` varchar(12) NOT NULL COMMENT 'ИНН партнера',
  `category_id` int NOT NULL COMMENT 'id категории партнера',
  `phone` varchar(11) DEFAULT NULL COMMENT 'Телефон поддержки партнера',
  `email` varchar(255) DEFAULT NULL COMMENT 'Почтовый адрес поддержки партнера',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания партнера',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Флаг активности',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления партнера',
  `comment` text COMMENT 'Комментарий',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx-partners-inn` (`inn`),
  KEY `idx-partners-deleted` (`deleted`),
  KEY `idx-partners-name` (`name`),
  KEY `fk-partners-category_id` (`category_id`),
  CONSTRAINT `fk-partners-category_id` FOREIGN KEY (`category_id`) REFERENCES `ref_partners_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) NOT NULL COMMENT 'Телефон',
  `create_date` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата регистрации',
  `status` int DEFAULT NULL COMMENT 'Статус',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `status` (`status`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT 'Название продукта',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) NOT NULL COMMENT 'Описание продукта',
  `ext_description` text NOT NULL COMMENT 'Полное описание продукта',
  `type_id` int DEFAULT NULL COMMENT 'id типа (подписка, бандл и т.д)',
  `user_id` int NOT NULL COMMENT 'id пользователя, создателя',
  `partner_id` int NOT NULL COMMENT 'id партнера, к кому привязан',
  `start_date` datetime DEFAULT NULL COMMENT 'Дата начала действия продукта',
  `end_date` datetime DEFAULT NULL COMMENT 'Дата окончания действия продукта',
  `payment_period` tinyint(1) DEFAULT '0' COMMENT 'Периодичность списания',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Флаг активности',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания партнера',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления продукта',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx-name-partner_id-type_id` (`name`,`partner_id`,`type_id`),
  KEY `fk-products-user_id` (`user_id`),
  KEY `fk-products-type_id` (`type_id`),
  KEY `fk-products-partner_id` (`partner_id`),
  KEY `idx-products-deleted` (`deleted`),
  KEY `idx-partners-payment_period` (`payment_period`),
  CONSTRAINT `fk-products-partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-products-user_id` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products_journal`
--

DROP TABLE IF EXISTS `products_journal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products_journal` (
  `id` char(36) NOT NULL,
  `rel_abonents_to_products_id` int NOT NULL,
  `status_id` int NOT NULL,
  `expire_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ps_to_rel_abonents_to_products` (`rel_abonents_to_products_id`),
  CONSTRAINT `fk_ps_to_rel_abonents_to_products` FOREIGN KEY (`rel_abonents_to_products_id`) REFERENCES `relation_abonents_to_products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue` (
  `id` char(36) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `job` blob NOT NULL,
  `pushed_at` int NOT NULL,
  `ttr` int NOT NULL,
  `delay` int NOT NULL DEFAULT '0',
  `priority` int unsigned NOT NULL DEFAULT '1024',
  `reserved_at` int DEFAULT NULL,
  `attempt` int DEFAULT NULL,
  `done_at` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `channel` (`channel`),
  KEY `reserved_at` (`reserved_at`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_partners_categories`
--

DROP TABLE IF EXISTS `ref_partners_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_partners_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ref_partners_categories_deleted` (`deleted`),
  KEY `ref_partners_categories_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relation_abonents_to_products`
--

DROP TABLE IF EXISTS `relation_abonents_to_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `relation_abonents_to_products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `abonent_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation_abonents_to_products_abonent_id_product_id` (`abonent_id`,`product_id`),
  KEY `fk_ratp_to_products` (`product_id`),
  CONSTRAINT `fk_ratp_to_abonents` FOREIGN KEY (`abonent_id`) REFERENCES `abonents` (`id`),
  CONSTRAINT `fk_ratp_to_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relation_ticket_to_billing`
--

DROP TABLE IF EXISTS `relation_ticket_to_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `relation_ticket_to_billing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` char(36) NOT NULL,
  `billing_id` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_relation_ticket_to_billing_to_ticket` (`ticket_id`),
  KEY `fk_relation_ticket_to_billing_to_billing_journal` (`billing_id`),
  CONSTRAINT `fk_relation_ticket_to_billing_to_billing_journal` FOREIGN KEY (`billing_id`) REFERENCES `billing_journal` (`id`),
  CONSTRAINT `fk_relation_ticket_to_billing_to_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relation_users_to_phones`
--

DROP TABLE IF EXISTS `relation_users_to_phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `relation_users_to_phones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `phone_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation_users_to_phones_user_id_phone_id` (`user_id`,`phone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL COMMENT 'id продукта',
  `trial_count` int NOT NULL DEFAULT '0',
  `units` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Единица измерения триального периода',
  PRIMARY KEY (`id`),
  KEY `fk-subscriptions-product_id` (`product_id`),
  CONSTRAINT `fk-subscriptions-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_exceptions`
--

DROP TABLE IF EXISTS `sys_exceptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_exceptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `code` int DEFAULT NULL,
  `statusCode` int DEFAULT NULL COMMENT 'HTTP status code',
  `file` varchar(255) DEFAULT NULL,
  `line` int DEFAULT NULL,
  `message` text,
  `trace` text,
  `get` text COMMENT 'GET',
  `post` text COMMENT 'POST',
  `known` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Known error',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file_storage`
--

DROP TABLE IF EXISTS `sys_file_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file_storage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `model_name` varchar(255) DEFAULT NULL,
  `model_key` int DEFAULT NULL,
  `at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `daddy` int DEFAULT NULL,
  `delegate` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_file_storage_path` (`path`),
  KEY `sys_file_storage_model_name_model_key` (`model_name`,`model_key`),
  KEY `sys_file_storage_daddy` (`daddy`),
  KEY `sys_file_storage_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file_storage_tags`
--

DROP TABLE IF EXISTS `sys_file_storage_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file_storage_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file` int NOT NULL,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_file_storage_tags_file_tag` (`file`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_history`
--

DROP TABLE IF EXISTS `sys_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user` int DEFAULT NULL,
  `model_class` varchar(255) DEFAULT NULL,
  `model_key` int DEFAULT NULL,
  `old_attributes` blob COMMENT 'Old serialized attributes',
  `new_attributes` blob COMMENT 'New serialized attributes',
  `relation_model` varchar(255) DEFAULT NULL,
  `scenario` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `operation_identifier` varchar(255) DEFAULT NULL,
  `delegate` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `model_class` (`model_class`),
  KEY `relation_model` (`relation_model`),
  KEY `model_key` (`model_key`),
  KEY `event` (`event`),
  KEY `operation_identifier` (`operation_identifier`),
  KEY `model_class_model_key` (`model_class`,`model_key`),
  KEY `delegate` (`delegate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_history_tags`
--

DROP TABLE IF EXISTS `sys_history_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_history_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `history` int NOT NULL,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `history_tag` (`history`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_import`
--

DROP TABLE IF EXISTS `sys_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_import` (
  `id` int NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `domain` int NOT NULL,
  `data` blob,
  `processed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `processed` (`processed`),
  KEY `domain` (`domain`),
  KEY `model` (`model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_notifications`
--

DROP TABLE IF EXISTS `sys_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL DEFAULT '0' COMMENT 'Тип уведомления',
  `initiator` int DEFAULT NULL COMMENT 'автор уведомления, null - система',
  `receiver` int DEFAULT NULL COMMENT 'получатель уведомления, null - определяется типом',
  `object_id` int DEFAULT NULL COMMENT 'идентификатор объекта уведомления, null - определяется типом',
  `comment` text,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_receiver_object_id` (`type`,`receiver`,`object_id`),
  KEY `type` (`type`),
  KEY `initiator` (`initiator`),
  KEY `receiver` (`receiver`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_options`
--

DROP TABLE IF EXISTS `sys_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `option` varchar(256) NOT NULL COMMENT 'Option name',
  `value` blob COMMENT 'Serialized option value',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_options_option` (`option`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_permissions`
--

DROP TABLE IF EXISTS `sys_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT 'Название доступа',
  `controller` varchar(255) DEFAULT NULL COMMENT 'Контроллер, к которому устанавливается доступ, null для внутреннего доступа',
  `action` varchar(255) DEFAULT NULL COMMENT 'Действие, для которого устанавливается доступ, null для всех действий контроллера',
  `verb` varchar(255) DEFAULT NULL COMMENT 'REST-метод, для которого устанавливается доступ',
  `module` varchar(255) DEFAULT NULL,
  `comment` text COMMENT 'Описание доступа',
  `priority` int NOT NULL DEFAULT '0' COMMENT 'Приоритет использования (больше - выше)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `controller_action_verb` (`controller`,`action`,`verb`),
  KEY `priority` (`priority`),
  KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_permissions_collections`
--

DROP TABLE IF EXISTS `sys_permissions_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_permissions_collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT 'Название группы доступа',
  `comment` text COMMENT 'Описание группы доступа',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Включение группы по умолчанию',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `sys_permissions_collections_default` (`default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_relation_permissions_collections_to_permissions`
--

DROP TABLE IF EXISTS `sys_relation_permissions_collections_to_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_relation_permissions_collections_to_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `collection_id` int NOT NULL COMMENT 'Ключ группы доступа',
  `permission_id` int NOT NULL COMMENT 'Ключ правила доступа',
  PRIMARY KEY (`id`),
  UNIQUE KEY `collection_id_permission_id` (`collection_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_relation_permissions_collections_to_permissions_collections`
--

DROP TABLE IF EXISTS `sys_relation_permissions_collections_to_permissions_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_relation_permissions_collections_to_permissions_collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `master_id` int NOT NULL,
  `slave_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ssions_collections_to_permissions_collections_master_id_slave_id` (`master_id`,`slave_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_relation_users_to_permissions`
--

DROP TABLE IF EXISTS `sys_relation_users_to_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_relation_users_to_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'Ключ объекта доступа',
  `permission_id` int NOT NULL COMMENT 'Ключ правила доступа',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_permission_id` (`user_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_relation_users_to_permissions_collections`
--

DROP TABLE IF EXISTS `sys_relation_users_to_permissions_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_relation_users_to_permissions_collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'Ключ объекта доступа',
  `collection_id` int NOT NULL COMMENT 'Ключ группы доступа',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_collection_id` (`user_id`,`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_relation_users_tokens_to_tokens`
--

DROP TABLE IF EXISTS `sys_relation_users_tokens_to_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_relation_users_tokens_to_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL,
  `child_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_relation_users_tokens_to_tokens_parent_id_child_id` (`parent_id`,`child_id`),
  KEY `fk_rel_tokens_to_child_token` (`child_id`),
  CONSTRAINT `fk_rel_tokens_to_child_token` FOREIGN KEY (`child_id`) REFERENCES `sys_users_tokens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_tokens_to_parent_token` FOREIGN KEY (`parent_id`) REFERENCES `sys_users_tokens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_status`
--

DROP TABLE IF EXISTS `sys_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `model_name` varchar(255) DEFAULT NULL,
  `model_key` int DEFAULT NULL,
  `status` int NOT NULL,
  `at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `daddy` int DEFAULT NULL,
  `delegate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `model_name_model_key` (`model_name`,`model_key`),
  KEY `daddy` (`daddy`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_users`
--

DROP TABLE IF EXISTS `sys_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT 'Отображаемое имя пользователя',
  `login` varchar(64) NOT NULL COMMENT 'Логин',
  `password` varchar(255) NOT NULL COMMENT 'Хеш пароля',
  `salt` varchar(255) DEFAULT NULL COMMENT 'Unique random salt hash',
  `restore_code` varchar(255) DEFAULT NULL,
  `is_pwd_outdated` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ожидается смена пароля',
  `email` varchar(255) NOT NULL COMMENT 'email',
  `comment` text COMMENT 'Служебный комментарий пользователя',
  `create_date` datetime NOT NULL COMMENT 'Дата регистрации',
  `daddy` int DEFAULT NULL COMMENT 'ID зарегистрировавшего/проверившего пользователя',
  `deleted` tinyint(1) DEFAULT '0' COMMENT 'Флаг удаления',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`),
  KEY `username` (`username`),
  KEY `daddy` (`daddy`),
  KEY `deleted` (`deleted`),
  KEY `is_pwd_outdated` (`is_pwd_outdated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_users_tokens`
--

DROP TABLE IF EXISTS `sys_users_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_users_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'user id foreign key',
  `auth_token` varchar(40) NOT NULL COMMENT 'Bearer auth token',
  `type_id` tinyint NOT NULL COMMENT 'Тип токена',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Таймстамп создания',
  `valid` timestamp NULL DEFAULT NULL COMMENT 'Действует до',
  `ip` varchar(255) DEFAULT NULL COMMENT 'Адрес авторизации',
  `user_agent` varchar(255) DEFAULT NULL COMMENT 'User-Agent',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_auth_token` (`user_id`,`auth_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket` (
  `id` char(36) NOT NULL,
  `type` tinyint NOT NULL,
  `stage_id` int NOT NULL,
  `status` smallint NOT NULL,
  `journal_data` json DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ticket_subscription`
--

DROP TABLE IF EXISTS `ticket_subscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_subscription` (
  `id` char(36) NOT NULL,
  `action` tinyint NOT NULL,
  `rel_abonents_to_products_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ticket_product_subscription_to_abonents_products` (`rel_abonents_to_products_id`),
  CONSTRAINT `fk_ticket_product_subscription_to_abonents_products` FOREIGN KEY (`rel_abonents_to_products_id`) REFERENCES `relation_abonents_to_products` (`id`),
  CONSTRAINT `fk_ticket_product_subscription_to_ticket` FOREIGN KEY (`id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_options`
--

DROP TABLE IF EXISTS `users_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL COMMENT 'System user id',
  `option` varchar(256) NOT NULL COMMENT 'Option name',
  `value` blob COMMENT 'Serialized option value',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_options_user_id_option` (`user_id`,`option`),
  KEY `users_options_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-26 14:51:38
