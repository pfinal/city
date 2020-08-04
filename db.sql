
CREATE TABLE `region` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL,
  `parent_id` varchar(32) NOT NULL DEFAULT '',
  `lng` varchar(50) NOT NULL DEFAULT '',
  `lat` varchar(50) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='区域';
