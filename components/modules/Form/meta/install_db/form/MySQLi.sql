CREATE TABLE IF NOT EXISTS `[prefix]form` (
  `id` int(11) NOT NULL,
  `date` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `[prefix]form`
ADD PRIMARY KEY (`id`);

ALTER TABLE `[prefix]form`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
