/*Este archivo creará la base de datos de la aplicacion*/

/*Eliminamos la base de datos si existe para evitar problemas a la hora de resetaear la base de datos*/
DROP DATABASE IF EXISTS binarify;

/*Crearemos la base de datos de la aplicacion de Binarify, la cual tendrá 3 tablas, una para guardar los usuarios registrados en la app, otra para guardar
todos los switches creados y otra para guardar las suscripciones que se realicen de un usuario a un switch*/
CREATE DATABASE binarify;

USE binarify;

/*Tabla de usuarios, con los atributos, la clave primaria será el nombre de ususario y la contraseña deberá ser un campo obligatorio*/
CREATE TABLE usuarios(
    username VARCHAR(255) PRIMARY KEY,
    passwd VARCHAR(255) NOT NULL,
    email VARCHAR(255)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;


/*La tabla de switches contará con los siguientes atributos, cabe recalcar que tiene 3 id pero como clave se usa uno que se genera automaticamente de forma incremental. Por otra parte tiene atributos como el  'state' que representa si el switch esta apagado o encendido, por defecto cuando se crea está encendido, el 'owner' que actua como clave foranea, pues hace referencia al username de un usuario de la tabla usuarios, 'auto_off_time' es el numero de minutos que estará encendido hasta que se apague automaticamente y por último 'last_time' que representará la fecha exacta a la que el usuario ha encendido el switch por ultima vez, por defecto como el switch se enciende al crearlo, se pone la fecha actual
*/
CREATE TABLE switches(
    id INT AUTO_INCREMENT PRIMARY KEY,
    public_id VARCHAR(36) NOT NULL,
    private_id VARCHAR(36) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion VARCHAR(500) NOT NULL,
    owner VARCHAR(255) NOT NULL,
    auto_off_time INT,
    last_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner) REFERENCES usuarios(username)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;



/*La tabla de suscripciones solo guardara el usuario suscrito y el switch al que esta suscrito, teniendo en cuenta que ambos atributos son claves foraneas de sus respectivas tablas*/
CREATE TABLE suscripciones(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    switch_id INT NOT NULL,
    FOREIGN KEY (username) REFERENCES usuarios(username),
    FOREIGN KEY (switch_id) REFERENCES switches(id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;


/*Para crear la base de datos, primero crearemos un username para la misma, se hará la conexion en el archivo PHP, en concreto en /core/PDOConnection.php*/
CREATE USER 'binarifyuser'@'localhost' IDENTIFIED BY 'binarifypass';
GRANT ALL PRIVILEGES ON binarify.* TO 'binarifyuser'@'localhost' WITH GRANT OPTION;