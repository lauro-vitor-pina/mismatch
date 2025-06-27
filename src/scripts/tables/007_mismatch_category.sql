
CREATE TABLE mismatch_category(
    category_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(48) NOT NULL,
    PRIMARY KEY(category_id)
);

ALTER TABLE mismatch_topic 
DROP COLUMN category;

ALTER TABLE mismatch_topic
ADD  COLUMN category_id INT;

ALTER TABLE mismatch_topic
ADD CONSTRAINT fk_mismatch_topic_x_mismatch_category
FOREIGN KEY(category_id) REFERENCES mismatch_category(category_id)
ON DELETE RESTRICT
ON UPDATE RESTRICT;
