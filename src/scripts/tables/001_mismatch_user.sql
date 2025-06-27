CREATE TABLE `mismatch_user` (
  `user_id` INT AUTO_INCREMENT,
  `join_date` DATETIME,
  `first_name` VARCHAR(32),
  `last_name` VARCHAR(32),
  `gender` VARCHAR(1),
  `birthdate` DATE,
  `city` VARCHAR(32),
  `state` VARCHAR(2),
  `picture` VARCHAR(32),
  PRIMARY KEY (`user_id`)
);

