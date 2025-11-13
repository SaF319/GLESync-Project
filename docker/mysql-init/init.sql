CREATE USER 'Admin'@'%' IDENTIFIED BY 'Admin_1234';
GRANT ALL PRIVILEGES ON planazo.* TO 'Admin'@'%' WITH GRANT OPTION;

CREATE USER 'Gestor'@'%' IDENTIFIED BY 'Gestor_1234';
GRANT SELECT, INSERT, UPDATE ON planazo.* TO 'Gestor'@'%';

CREATE USER 'Consultor'@'%' IDENTIFIED BY 'Consultor_1234';
GRANT SELECT ON planazo.* TO 'Consultor'@'%';

CREATE USER 'Replicador'@'%' IDENTIFIED BY 'Replicador_1234';
GRANT REPLICATION SLAVE ON *.* TO 'Replicador'@'%';
GRANT REPLICATION CLIENT ON *.* TO 'Replicador'@'%';
GRANT SELECT ON performance_schema.* TO 'Replicador'@'%';

FLUSH PRIVILEGES;
