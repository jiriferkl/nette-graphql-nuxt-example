CREATE USER 'maxwell'@'%' IDENTIFIED BY 'maxwell1234'; -- this should not be here for security reasons but for simplicity of this example it is here
CREATE USER 'maxwell'@'localhost' IDENTIFIED BY 'maxwell1234'; -- this should not be here for security reasons but for simplicity of this example it is here

GRANT ALL ON maxwell.* TO 'maxwell'@'%';
GRANT ALL ON maxwell.* TO 'maxwell'@'localhost';

GRANT SELECT, REPLICATION CLIENT, REPLICATION SLAVE ON *.* TO 'maxwell'@'%';
GRANT SELECT, REPLICATION CLIENT, REPLICATION SLAVE ON *.* TO 'maxwell'@'localhost';
