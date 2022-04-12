CREATE TABLE `users` (
    `id` int unsigned NOT NULL AUTO_INCREMENT, 
    `name` varchar(255) DEFAULT NULL, 
    `patronymic` varchar(255) DEFAULT NULL,
    `surname` varchar(255) DEFAULT NULL,
    `login` varchar(255) DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL, 
    `tel` varchar(255) DEFAULT NULL, 
    `gender` char(1) DEFAULT NULL,
    `date_of_birth` date DEFAULT NULL,
    `active` int DEFAULT NULL,
    `last_auth_date` DATETIME DEFAULT NULL,
    `register_date` date DEFAULT NULL,
    `nickname` varchar(255) DEFAULT NULL, 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;