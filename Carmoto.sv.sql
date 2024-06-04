DROP DATABASE IF EXISTS CARSMOTOSV;
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
    id_administrador INT NOT NULL,
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
    precio_total_productos DECIMAL(5,2) NOT NULL,
    CONSTRAINT fk_pedido_cliente FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido),
    CONSTRAINT fk_detalle_pedidos FOREIGN KEY (id_casco) REFERENCES Cascos(id_casco)
);

CREATE TABLE valoraciones (
    id_valoracion INT PRIMARY KEY AUTO_INCREMENT,
    id_detalle_pedidos INT NOT NULL,
    CONSTRAINT fk_valoracion_pieza FOREIGN KEY (id_detalle_pedidos) REFERENCES detalle_pedidos(id_detalle_pedidos)
);

INSERT INTO Marcas_Cascos(nombre_marca,descripcion_marca) VALUES ("AGV","Que lindo");

INSERT INTO Modelos_de_Cascos(nombre_modelo,descripcion_modelo, año_modelo,id_marca_casco) VALUES ("AGV","Buen modelo","2-12-24",1);

INSERT INTO Administradores(nombre_administrador,apellido_administrador,correo_administrador,contrasenia_administrador,fecha_registro)
VALUES ("Carlos","Ramon","a@gmail.com","Maceta",CURRENT_DATE());

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
(1, '2023-05-01 10:30:00', 'Pendiente', '123 Calle Falsa, Ciudad Ejemplo'),
(2, '2023-05-02 14:45:00', 'Entregado', '456 Avenida Siempreviva, Ciudad Ejemplo'),
(3, '2023-05-03 09:15:00', 'Pendiente', '789 Calle Verdadera, Ciudad Ejemplo'),
(4, '2023-05-04 11:50:00', 'Pendiente', '1011 Calle Principal, Ciudad Ejemplo'),
(5, '2023-05-05 13:30:00', 'Pendiente', '1213 Avenida Secundaria, Ciudad Ejemplo'),
(6, '2023-05-06 16:20:00', 'Pendiente', '1415 Calle Tercera, Ciudad Ejemplo'),
(7, '2023-05-07 08:05:00', 'Cancelado', '1617 Calle Cuarta, Ciudad Ejemplo'),
(8, '2023-05-08 12:10:00', 'Pendiente', '1819 Calle Quinta, Ciudad Ejemplo'),
(9, '2023-05-09 15:40:00', 'Entregado', '2021 Calle Sexta, Ciudad Ejemplo'),
(10, '2023-05-10 17:25:00', 'Pendiente', '2223 Avenida Séptima, Ciudad Ejemplo');

INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco,id_administrador)
VALUES ("Casco 9291","Resistente","casco.png",24.00,10,1,1);


INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco,id_administrador)
VALUES ("Casco 1413","wow","casco.png",24.00,10,1,1);


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
