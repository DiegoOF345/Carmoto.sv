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
    id_Administrador INT PRIMARY KEY AUTO_INCREMENT,
    Nombre_Administrador VARCHAR(100) NOT NULL,
    Apellido_Administrador VARCHAR(100) NOT NULL,
    Correo_Administrador VARCHAR(100) NOT NULL,
    Contrasenia_Administrador VARCHAR(100) NOT NULL,
    Fecha_Registro DATE NOT NULL
);

CREATE TABLE Marcas_Cascos (
    id_Marcas_Cascos INT PRIMARY KEY AUTO_INCREMENT,
    Nombres_Marcas VARCHAR(100) NOT NULL,
    Descripcion_Marcas VARCHAR(300) NOT NULL
);

CREATE TABLE Modelos_de_Cascos (
    id_Modelos_de_Cascos INT PRIMARY KEY AUTO_INCREMENT,
    Nombre_Modelo VARCHAR(100) NOT NULL,
    Descripcion_Modelo VARCHAR(300) NOT NULL,
    Año_Modelo VARCHAR(30) NOT NULL,
    id_Marcas_Cascos INT,
    CONSTRAINT fk_Marcas_Cascos_Modelos_de_Cascos FOREIGN KEY (id_Marcas_Cascos) REFERENCES Marcas_Cascos(id_Marcas_Cascos)
);

CREATE TABLE Cascos (
    id_Cascos INT PRIMARY KEY AUTO_INCREMENT,
    Nombres_Cascos VARCHAR(100) NOT NULL,
    Descripcion_Cascos VARCHAR(300) NOT NULL,
    Imagen_Cascos VARCHAR(300) NOT NULL,
    id_Modelos_de_Cascos INT,
    id_administrador INT NOT NULL,
    CONSTRAINT fk_Modelos_de_Cascos FOREIGN KEY (id_Modelos_de_Cascos) REFERENCES Modelos_de_Cascos(id_Modelos_de_Cascos)
);

CREATE TABLE pedidos (
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
    id_Cascos INT NOT NULL,
    cantidad_productos INT NOT NULL,
    precio_productos NUMERIC(5,2) NOT NULL,
    CONSTRAINT fk_pedido_cliente FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    CONSTRAINT fk_detalle_pedidos FOREIGN KEY (id_Cascos) REFERENCES Cascos(id_Cascos)
);

CREATE TABLE valoraciones (
    id_valoracion INT PRIMARY KEY AUTO_INCREMENT,
    id_detalle_pedidos INT NOT NULL,
    CONSTRAINT fk_valoracion_pieza FOREIGN KEY (id_detalle_pedidos) REFERENCES detalle_pedidos(id_detalle_pedidos)
);
