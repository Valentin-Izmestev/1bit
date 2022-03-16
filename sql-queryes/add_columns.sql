ALTER TABLE `users`
ADD COLUMN `date_of_birth` date DEFAULT NULL,
ADD COLUMN `gender` char(1) DEFAULT NULL, 
ADD COLUMN `email` varchar(255) DEFAULT NULL, 
ADD COLUMN `tel` bigint DEFAULT NULL;