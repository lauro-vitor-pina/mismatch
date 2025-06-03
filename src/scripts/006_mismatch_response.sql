
CREATE TABLE `mismatch_response`(
    `response_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `topic_id` INT NOT NULL,
    `response` CHAR(4) NOT NULL,
    PRIMARY KEY(`response_id`)
);

ALTER TABLE `mismatch_response`
ADD CONSTRAINT `fk_mismatch_response_x_mismatch_user`
FOREIGN KEY(`user_id`) REFERENCES `mismatch_user`(`user_id`) 
ON DELETE RESTRICT 
ON UPDATE RESTRICT;


ALTER TABLE `mismatch_response` 
ADD CONSTRAINT `fk_mismatch_response_x_mismatch_topic`
FOREIGN KEY (`topic_id`) REFERENCES  `mismatch_topic`(`topic_id`) 
ON DELETE RESTRICT 
ON UPDATE RESTRICT;
