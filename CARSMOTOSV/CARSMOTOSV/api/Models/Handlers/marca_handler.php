<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class MarcaHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    
    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../Imagenes/productos/';

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_marca_casco, nombre_marca, descripcion_marca
                FROM Marcas_Cascos
                WHERE nombre_marca LIKE ? 
                ORDER BY nombre_marca';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_marca_casco, nombre_marca, descripcion_marca
                FROM Marcas_Cascos
                WHERE id_marca_casco = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO Marcas_Cascos(nombre_marca, descripcion_marca)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->descripcion);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_marca_casco, nombre_marca, descripcion_marca
                FROM Marcas_Cascos';
        return Database::getRows($sql);
    }

    public function updateRow()
    {
        $sql = 'UPDATE Marcas_Cascos
                SET nombre_marca = ?, descripcion_marca = ?
                WHERE id_marca_casco = ?';
        $params = array($this->nombre, $this->descripcion,  $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM Marcas_Cascos
                WHERE id_marca_casco = ?';
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
}
