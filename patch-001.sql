CREATE TABLE IF NOT EXISTS `ehm_pha_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `t_name` varchar(100) NOT NULL,
  `t_content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;