/*!40101 SET NAMES binary*/;
/*!40014 SET FOREIGN_KEY_CHECKS=0*/;

CREATE TABLE `addon` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `addon` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `addon_addon_unique` (`addon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
