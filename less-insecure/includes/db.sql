CREATE TABLE csis2440_user (
    id int AUTO_INCREMENT PRIMARY KEY,
	username varchar(255),
	password varchar(255));
	loginCount int deafault 0;
    
INSERT INTO csis2440_user (`username`, `password`) VALUES 
('beavis','password'),
('butthead','password2'),
('dana','alien'),
('fox','believe');