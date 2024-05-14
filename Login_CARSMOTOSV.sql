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
    fecha_cliente DATE NOT NULL,
    CONSTRAINT dui_unico UNIQUE (dui_cliente)
);

CREATE TABLE Administradores (
    id_administrador INT PRIMARY KEY AUTO_INCREMENT,
    nombre_administrador VARCHAR(100) NOT NULL,
    apellido_administrador VARCHAR(100) NOT NULL,
    correo_administrador VARCHAR(100) NOT NULL,
    contrasenia_administrador VARCHAR(100) NOT NULL,
    fecha_registro DATE NOT NULL
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
    estado_pedidos ENUM('Activo', 'Finalizado'),
    fecha_registro DATE NOT NULL,
    direccion_pedidos VARCHAR(255) NOT NULL,
    CONSTRAINT fk_cliente_pedido FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente)
);

CREATE TABLE detalle_pedidos (
    id_detalle_pedidos INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_casco INT NOT NULL,
    cantidad_productos INT NOT NULL,
    precio_total_productos NUMERIC(5,2) NOT NULL,
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

INSERT INTO Cascos(nombre_casco,descripcion_casco,imagen_casco,precio_casco,existencia_casco,id_modelo_de_casco,id_administrador)
VALUES ("Casco 9291","Resistente","casco.png",24.00,10,1,1);