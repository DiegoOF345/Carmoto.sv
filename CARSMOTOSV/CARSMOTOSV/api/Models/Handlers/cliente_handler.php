<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class ClienteHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    protected $apellido = null;
    protected $dui = null;
    protected $correo = null;
    protected $telefono = null;
    protected $nacimiento = null;
    protected $direccion = null;
    protected $clave = null;
    protected $fecha = null;
    
    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../Imagenes/productos/';

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchPrice()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, nacimiento_cliente, direccion_cliente, contraseña_cliente, fecha_cliente
                FROM Clientes
                WHERE nombre_cliente LIKE ? 
                ORDER BY nombre_cliente';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO Clientes(nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, nacimiento_cliente, direccion_cliente, contraseña_cliente, fecha_cliente)
                VALUES(?,?,?,?,?,?,?,?,CURRENT_DATE())';
        $params = array($this->nombre, $this->descripcion);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, nacimiento_cliente, direccion_cliente, contraseña_cliente, fecha_cliente
                FROM Clientes';
        return Database::getRows($sql);
    }

    public function updateRow()
    {
        $sql = 'UPDATE Clientes
                SET nombre_cliente = ?, apellido_cliente = ?, dui_cliente = ?, correo_cliente = ?, telefono_cliente = ?, nacimiento_cliente = ?, direccion_cliente = ?, contraseña_cliente = ?, fecha_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->descripcion,  $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM Clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*public function readProductosModelos()
    {
        $sql = 'SELECT nombre_casco, descripcion_casco, imagen_casco, precio_casco, existencia_casco
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)
                WHERE id_modelo_de_casco = ?
                ORDER BY nombre_casco';
        $params = array($this->modelo);
        return Database::getRows($sql, $params);
    }*/

    /*
    *   Métodos para generar gráficos.
    */
    public function cantidadProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY cantidad DESC LIMIT 5';
        return Database::getRows($sql);
    }

    public function porcentajeProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM producto)), 2) porcentaje
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    /*
    *   Métodos para generar reportes.
    */
    /*public function productosCategoria()
    {
        $sql = 'SELECT nombre_producto, precio_producto, estado_producto
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ?
                ORDER BY nombre_producto';
        $params = array($this->modelo);
        return Database::getRows($sql, $params);
    }*/

    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_cliente
                FROM Clientes
                WHERE dui_cliente = ? OR correo_cliente = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }
}