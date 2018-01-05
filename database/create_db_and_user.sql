DROP SCHEMA IF EXISTS `ssn`;
CREATE SCHEMA `ssn`;

DROP USER IF EXISTS 'ssn-app'@'localhost';
FLUSH PRIVILEGES; 

CREATE USER 'ssn-app'@'localhost' IDENTIFIED BY 'thisisapassword'; 
GRANT ALL PRIVILEGES ON ssn.* TO 'ssn-app'@'localhost'