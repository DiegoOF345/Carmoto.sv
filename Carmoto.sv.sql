DROP USER if EXISTS 'CarmotoSQL'@'localhost';
CREATE USER 'CarmotoSQL'@'localhost' IDENTIFIED BY 'CARSMOTOSV';
GRANT ALL PRIVILEGES ON CARSMOTOSV. * TO 'CarmotoSQL'@'localhost';

SET lc_time_names = 'es_MX';
DROP DATABASE if EXISTS CARSMOTOSV;
CREATE DATABASE CARSMOTOSV;

USE CARSMOTOSV;

/* Tablas */

CREATE TABLE Clientes (
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,
    nombre_cliente VARCHAR(100) NOT NULL,
    apellido_cliente VARCHAR(100) NOT NULL,
    dui_cliente VARCHAR(15) NOT NULL,
    correo_cliente VARCHAR(150) NOT NULL,
    telefono_cliente VARCHAR(150) NOT NULL,
    nacimiento_cliente DATE NOT NULL,
    direccion_cliente VARCHAR(170) NOT NULL,
    contraseña_cliente VARCHAR(170) NOT NULL,
    estado_cliente tinyint(1) NOT NULL DEFAULT 1,
    fecha_cliente DATE DEFAULT NOW(),
     codigo_recuperacion VARCHAR(6) NOT NULL,
    CONSTRAINT dui_unico UNIQUE (dui_cliente)
);

CREATE TABLE Administradores (
    id_administrador INT PRIMARY KEY AUTO_INCREMENT,
    nombre_administrador VARCHAR(100) NOT NULL,
    apellido_administrador VARCHAR(100) NOT NULL,
    correo_administrador VARCHAR(100) NOT NULL,
    contrasenia_administrador VARCHAR(100) NOT NULL,
    fecha_registro DATE DEFAULT NOW()
);

CREATE TABLE Marcas_Cascos (
    id_marca_casco INT PRIMARY KEY AUTO_INCREMENT,
    nombre_marca VARCHAR(100) NOT NULL,
    descripcion_marca VARCHAR(300) NOT NULL
);

CREATE TABLE Modelos_de_Cascos (
    id_modelo_de_casco INT PRIMARY KEY AUTO_INCREMENT,
    nombre_modelo VARCHAR(100) NOT NULL,
    descripcion_modelo VARCHAR(300) NOT NULL,
    año_modelo VARCHAR(30) NOT NULL,
    id_marca_casco INT NOT null,
    CONSTRAINT fk_Marcas_Cascos_Modelos_de_Cascos FOREIGN KEY (id_marca_casco) REFERENCES Marcas_Cascos(id_marca_casco)
);

CREATE TABLE Cascos (
    id_casco INT PRIMARY KEY AUTO_INCREMENT,
    nombre_casco VARCHAR(100) NOT NULL,
    descripcion_casco VARCHAR(300) NOT NULL,
    imagen_casco VARCHAR(300) NOT NULL,
    precio_casco NUMERIC(5,2) NOT NULL,
    existencia_casco INT NOT NULL,
    id_modelo_de_casco INT,
    id_administrador INT,
    CONSTRAINT fk_Modelos_de_Cascos FOREIGN KEY (id_modelo_de_casco) REFERENCES Modelos_de_Cascos(id_modelo_de_casco),
    CONSTRAINT fk_Administradores_Cascos FOREIGN KEY (id_administrador) REFERENCES Administradores(id_administrador)
);

CREATE TABLE Pedidos (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_cliente INT NOT NULL,
    estado_pedidos ENUM("Pendiente","Cancelado","Entregado"),
    fecha_registro DATETIME DEFAULT NOW(),
    direccion_pedidos VARCHAR(255) NOT NULL,
    CONSTRAINT fk_cliente_pedido FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente)
);

CREATE TABLE detalle_pedidos (
    id_detalle_pedidos INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_casco INT NOT NULL,
    talla_casco ENUM("S","M","L"),
    cantidad_productos INT NOT NULL,
    precio_productos DECIMAL(5,2) NOT NULL,
    CONSTRAINT fk_pedido_cliente FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido),
    CONSTRAINT fk_detalle_pedidos FOREIGN KEY (id_casco) REFERENCES Cascos(id_casco)
);

CREATE TABLE valoraciones (
    id_valoracion INT PRIMARY KEY AUTO_INCREMENT,
    id_detalle_pedidos INT NOT NULL,
    CONSTRAINT fk_valoracion_pieza FOREIGN KEY (id_detalle_pedidos) REFERENCES detalle_pedidos(id_detalle_pedidos)
);

INSERT INTO Marcas_Cascos(nombre_marca,descripcion_marca) VALUES ("AGV","Que lindo");
INSERT INTO Marcas_Cascos(nombre_marca,descripcion_marca) VALUES ("ILM","Ta bonito");
INSERT INTO Marcas_Cascos(nombre_marca,descripcion_marca) VALUES ("Scorpion","Esta bien");
INSERT INTO Marcas_Cascos(nombre_marca,descripcion_marca) VALUES ("Schuberth","Esta bien");

INSERT INTO Modelos_de_Cascos(nombre_modelo,descripcion_modelo, año_modelo,id_marca_casco) VALUES ("ECE 22.06","AGV modelo","2-12-24",1);
INSERT INTO Modelos_de_Cascos(nombre_modelo,descripcion_modelo, año_modelo,id_marca_casco) VALUES ("JK313","Buen modelo","2-12-24",2);
INSERT INTO Modelos_de_Cascos(nombre_modelo,descripcion_modelo, año_modelo,id_marca_casco) VALUES ("EXO-R420","Gran modelo","2-12-24",3);
INSERT INTO Modelos_de_Cascos(nombre_modelo,descripcion_modelo, año_modelo,id_marca_casco) VALUES ("C5","Modelo pequeño","2-12-24",4);

INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("Casco 9291","Resistente","casco.jpg",24.00,10,1);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV Pista GP RR ","Resistente","casco.jpg",25.00,10,1);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV K6 S Slashcut","Resistente","casco.jpg",21.00,10,1);

INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("Casco 9291","Resistente","casco.jpg",24.00,10,2);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV Pista GP RR ","Resistente","casco.jpg",25.00,10,2);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV K6 S Slashcut","Resistente","casco.jpg",21.00,10,2);


INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("Casco 9291","Resistente","casco.jpg",24.00,10,3);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV Pista GP RR ","Resistente","casco.jpg",25.00,10,3);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV K6 S Slashcut","Resistente","casco.jpg",21.00,10,3);

INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("Casco 9291","Resistente","casco.jpg",24.00,10,4);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV Pista GP RR ","Resistente","casco.jpg",25.00,10,4);
INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco)
VALUES ("AGV K6 S Slashcut","Resistente","casco.jpg",21.00,10,4);

INSERT INTO Clientes (nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, nacimiento_cliente, direccion_cliente, contraseña_cliente)
VALUES
  ('Juan', 'Pérez', '123456789', 'juan.perez@email.com', '+503 1234 5678', '1990-01-01', 'Calle Principal 123, San Salvador', 'contraseña123'),
  ('María', 'Gómez', '987654321', 'maria.gomez@email.com', '+503 4321 7654', '1995-07-14', 'Avenida San Miguel 456, Apopa', 'contraseña456'),
  ('Pedro', 'López', '876543210', 'pedro.lopez@email.com', '+503 7654 3210', '2000-12-25', 'Calle Libertad 789, Soyapango', 'contraseña789'),
  ('Ana', 'Martínez', '765432109', 'ana.martinez@email.com', '+503 3210 7654', '2005-04-08', 'Pasaje San José 1234, Mejicanos', 'contraseña1234'),
  ('Carlos', 'Hernández', '654321098', 'carlos.hernandez@email.com', '+503 7654 1230', '1985-03-19', 'Boulevard Los Próceres 5678, San Salvador', 'contraseña5678'),
  ('Isabel', 'Flores', '543210987', 'isabel.flores@email.com', '+503 1234 3210', '1992-11-22', 'Calle Arce 123, Santa Tecla', 'contraseña3210'),
  ('David', 'Ramos', '432109876', 'david.ramos@email.com', '+503 4321 5678', '1980-02-05', 'Urbanización Metrocentro 456, Antiguo Cuscatlán', 'contraseña5678'),
  ('Susana', 'Mejía', '321098765', 'susana.mejia@email.com', '+503 7654 9876', '1998-08-10', 'Residencial Las Cumbres 789, Santa Ana', 'contraseña9876'),
  ('José', 'García', '210987654', 'jose.garcia@email.com', '+503 3210 5678', '1975-06-15', 'Alameda Roosevelt 1234, San Salvador', 'contraseña5678'),
  ('Andrea', 'Rodríguez', '109876543', 'andrea.rodriguez@email.com', '+503 7654 3210', '2003-10-27', 'Calle Delgado 5678, San Salvador', 'contraseña3210');

INSERT INTO Pedidos (id_cliente, fecha_registro, estado_pedidos, direccion_pedidos) VALUES
(1, '2023-06-01 10:30:00', 'Pendiente', '123 Calle Falsa, Ciudad Ejemplo'),
(2, '2023-06-02 14:45:00', 'Entregado', '456 Avenida Siempreviva, Ciudad Ejemplo'),
(3, '2023-06-03 09:15:00', 'Entregado', '789 Calle Verdadera, Ciudad Ejemplo'),
(4, '2023-08-04 11:50:00', 'Pendiente', '1011 Calle Principal, Ciudad Ejemplo'),
(5, '2023-08-05 13:30:00', 'Pendiente', '1213 Avenida Secundaria, Ciudad Ejemplo'),
(6, '2023-08-06 16:20:00', 'Pendiente', '1415 Calle Tercera, Ciudad Ejemplo'),
(7, '2023-02-07 08:05:00', 'Cancelado', '1617 Calle Cuarta, Ciudad Ejemplo'),
(8, '2023-02-08 12:10:00', 'Pendiente', '1819 Calle Quinta, Ciudad Ejemplo'),
(9, '2023-05-09 15:40:00', 'Entregado', '2021 Calle Sexta, Ciudad Ejemplo'),
(10, '2023-05-10 17:25:00', 'Pendiente', '2223 Avenida Séptima, Ciudad Ejemplo'),
(1, '2023-06-01 10:30:00', 'Entregado', '123 Calle Falsa, Ciudad Ejemplo'),
(2, '2023-06-02 14:45:00', 'Entregado', '456 Avenida Siempreviva, Ciudad Ejemplo'),
(3, '2023-06-03 09:15:00', 'Entregado', '789 Calle Verdadera, Ciudad Ejemplo'),
(4, '2023-08-04 11:50:00', 'Pendiente', '1011 Calle Principal, Ciudad Ejemplo'),
(5, '2023-08-05 13:30:00', 'Entregado', '1213 Avenida Secundaria, Ciudad Ejemplo'),
(6, '2023-08-06 16:20:00', 'Pendiente', '1415 Calle Tercera, Ciudad Ejemplo'),
(7, '2023-02-07 08:05:00', 'Cancelado', '1617 Calle Cuarta, Ciudad Ejemplo'),
(8, '2023-02-08 12:10:00', 'Pendiente', '1819 Calle Quinta, Ciudad Ejemplo'),
(9, '2023-05-09 15:40:00', 'Entregado', '2021 Calle Sexta, Ciudad Ejemplo'),
(10, '2023-05-10 17:25:00', 'Pendiente', '2223 Avenida Séptima, Ciudad Ejemplo');

INSERT INTO detalle_pedidos (id_pedido,id_casco,talla_casco,cantidad_productos,precio_productos) VALUES
(1,1,"S",1,40.00),
(2,5,"S",1,30.00),
(3,3,"S",1,20.00),
(4,5,"S",1,20.00),
(4,6,"S",1,60.00),
(4,10,"S",1,20.00),
(5,5,"S",1,20.00),
(7,5,"S",1,50.00),
(8,5,"S",1,50.00),
(9,5,"S",1,20.00),
(10,5,"S",1,50.00),
(11,1,"S",1,20.00),
(12,5,"S",1,20.00),
(13,3,"S",1,50.00),
(14,5,"S",1,20.00),
(14,6,"S",1,30.00),
(14,10,"S",1,50.00),
(15,5,"S",1,50.00),
(17,5,"S",1,20.00),
(18,5,"S",1,30.00),
(19,5,"S",1,20.00),
(20,5,"S",1,30.00);

SELECT * FROM Administradores;
                    
SELECT * FROM clientes;

SELECT * FROM detalle_pedidos;

SELECT * FROM Pedidos;

DELIMITER $$
CREATE PROCEDURE cambiar_estado_pedido(IN pedido_id INT)
BEGIN
    DECLARE pedido_estado BOOLEAN;
    -- Obtener el estado actual del administrador
    SELECT estado_pedidos INTO pedido_estado
    FROM Pedidos
    WHERE id_pedido = pedido_id;
    -- Actualizar el estado del administrador
    IF pedido_estado = 1 THEN
        UPDATE Pedidos
        SET estado_pedidos = 0
        WHERE id_pedido = pedido_id;
    ELSE
        UPDATE Pedidos
        SET estado_pedidos = 1
        WHERE id_pedido = pedido_id;
    END IF;
END $$

SELECT nombre_marca, COUNT(id_casco) cantidad
                FROM Cascos, Modelos_de_Cascos, Marcas_Cascos
                WHERE Cascos.id_modelo_de_casco = modelos_de_cascos.id_modelo_de_casco
                AND marcas_cascos.id_marca_casco = modelos_de_cascos.id_marca_casco
                GROUP BY nombre_marca ORDER BY cantidad DESC LIMIT 5;
                
SELECT nombre_marca, ROUND((COUNT(id_casco) * 100.0 / (SELECT COUNT(id_casco) FROM Cascos)), 2) porcentaje
                FROM Cascos, Modelos_de_Cascos, Marcas_Cascos
                WHERE Cascos.id_modelo_de_casco = modelos_de_cascos.id_modelo_de_casco
                AND marcas_cascos.id_marca_casco = modelos_de_cascos.id_marca_casco
                GROUP BY nombre_marca ORDER BY porcentaje DESC;
                
SELECT estado_pedidos, ROUND((COUNT(estado_pedidos) * 100.0 / (SELECT COUNT(estado_pedidos) FROM Pedidos)), 2) porcentaje
                FROM Pedidos
                GROUP BY estado_pedidos ORDER BY porcentaje DESC;

SELECT nombre_cliente, COUNT(id_cliente) cantidad
                FROM Clientes
                INNER JOIN Pedidos USING(id_cliente)
                GROUP BY nombre_cliente ORDER BY cantidad DESC LIMIT 5;
                
SELECT nombre_marca, SUM(cantidad_productos) total
                FROM detalle_pedidos, Cascos, Marcas_Cascos, Modelos_de_Cascos
                WHERE detalle_pedidos.id_casco = cascos.id_casco AND cascos.id_modelo_de_casco = modelos_de_cascos.id_modelo_de_casco
                AND marcas_cascos.id_marca_casco = modelos_de_cascos.id_marca_casco
                GROUP BY nombre_marca
                ORDER BY total DESC
                LIMIT 3;

SELECT nombre_casco, precio_casco, existencia_casco
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)
                WHERE id_modelo_de_casco = 1;

SELECT MONTHNAME(fecha_registro) AS Mes,
                SUM(detalle_pedidos.precio_productos) AS Total
                FROM Pedidos, detalle_pedidos
                WHERE YEAR(fecha_registro) = "2023" AND pedidos.id_pedido = detalle_pedidos.id_pedido
                AND pedidos.estado_pedidos = "Entregado"
                GROUP BY Mes;
                
SELECT id_pedido, estado_pedidos, fecha_registro
					 FROM Pedidos
					 INNER JOIN Clientes USING (id_cliente)
					 WHERE id_cliente = 1;


