CREATE TABLE `posts` (
`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
`title` varchar(255) DEFAULT NULL,
`preview_img` varchar(255) DEFAULT NULL,
`create_date` date DEFAULT NULL,
`preview` varchar(255) DEFAULT NULL,
`content` varchar(255) DEFAULT NULL,
`author_id` int unsigned NOT NULL,
PRIMARY KEY (`id`), 
FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
ON UPDATE RESTRICT
ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;