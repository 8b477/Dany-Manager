CREATE DATABASE IF NOT EXISTS `taskManager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `taskManager`;

CREATE TABLE IF NOT EXISTS `user` ( 
id INT NOT NULL AUTO_INCREMENT, 
    email VARCHAR(128) NOT NULL, 
    username VARCHAR(128) NOT NULL, 
password VARCHAR(512) NOT NULL, 
    PRIMARY KEY (id), 
UNIQUE KEY email_unique (email) 
); 
CREATE TABLE IF NOT EXISTS `userSession` ( 
id INT AUTO_INCREMENT PRIMARY KEY, 
    token VARCHAR(512) NOT NULL, 
    expiration DATETIME NOT NULL, 
    userId INT NOT NULL, 
FOREIGN KEY (userId) REFERENCES `user`(id) 
); 
CREATE TABLE IF NOT EXISTS `category` ( 
id INT NOT NULL AUTO_INCREMENT, 
name VARCHAR(128) NOT NULL, 
position INT NOT NULL DEFAULT 0, 
    PRIMARY KEY (id) 
); 
CREATE TABLE IF NOT EXISTS `card` ( 
id INT NOT NULL AUTO_INCREMENT, 
name VARCHAR(255) NOT NULL, 
    description TEXT, 
position INT NOT NULL DEFAULT 0, 
    categoryId INT NOT NULL, 
    userId INT NOT NULL, 
    PRIMARY KEY (id), 
FOREIGN KEY (categoryId) REFERENCES `category`(id), 
FOREIGN KEY (userId) REFERENCES `user`(id) 
); 
INSERT INTO `category` (name, position) VALUES 
('To Do', 0), 
('In Progress', 1), 
('Done', 2); 