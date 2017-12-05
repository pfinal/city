DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `code` int NOT NULL PRIMARY KEY,
  `name` varchar(50) NOT NULL,
  `parent_code` int NOT NULL,
  `lng` varchar(50) NOT NULL DEFAULT '', -- lng	经度值
  `lat` varchar(50) NOT NULL DEFAULT '', -- lat	纬度值
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8 COMMENT='区域';
