CREATE DATABASE IF NOT EXISTS `easyhadoop` charset utf8 COLLATE utf8_general_ci;
USE easyhadoop;
CREATE TABLE IF NOT EXISTS `ehm_pha_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `password` varchar(100) NOT NULL,
  `onlydb` text NOT NULL,
  `role` varchar(50) NOT NULL,
  `reduce` int(10) NOT NULL,
  `access_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
INSERT INTO `ehm_pha_user` (`id`, `username`, `password`, `role`, `description`) VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'Superadmin');
INSERT INTO `ehm_pha_user` (`id`, `username`, `password`, `onlydb`, `role`, `description`) VALUES (2, 'user', 'e10adc3949ba59abbe56e057f20f883e', 'default', 'user', 'default user');

CREATE TABLE IF NOT EXISTS `ehm_pha_history_job` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `fingerprint` varchar(200) NOT NULL,
  `access_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ehm_pha_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `t_name` varchar(100) NOT NULL,
  `t_content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;