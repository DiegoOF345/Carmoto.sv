<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class ModeloHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    protected $año = null;
    protected $marca = null;


    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../imagenes/productos/';

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_modelo_de_casco, nombre_modelo, descripcion_modelo, año_modelo, id_marca_casco
                FROM Modelos_de_Cascos
                INNER JOIN Marcas_Cascos USING(id_marca_casco)
                WHERE nombre_modelo LIKE ? OR descripcion_modelo LIKE ?
                ORDER BY nombre_modelo';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO Modelos_de_Cascos(nombre_modelo, descripcion_modelo, año_modelo, id_marca_casco)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->descripcion, $this->año, $this->marca);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_modelo_de_casco, nombre_modelo, descripcion_modelo, año_modelo, nombre_marca
                FROM Modelos_de_Cascos
                INNER JOIN Marcas_Cascos USING(id_marca_casco)
                ORDER BY nombre_modelo';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_modelo_de_casco, nombre_modelo, descripcion_modelo, año_modelo, id_marca_casco
                FROM Modelos_de_Cascos
                WHERE id_modelo_de_casco = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE Modelos_de_Cascos
                SET nombre_modelo = ?, descripcion_modelo = ?, año_modelo = ?, id_marca_casco = ?
                WHERE id_modelo_de_casco = ?';
        $params = array($this->nombre, $this->descripcion, $this->año, $this->marca, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM Modelos_de_Cascos
                WHERE id_modelo_de_casco = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    
    //Métodos para los reportes de los modelos
    
    public function productosModelo()
    {
        $sql = 'SELECT nombre_casco, precio_casco, existencia_casco
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)
                WHERE id_modelo_de_casco = ?';
        $params = array($this->id);
       return Database::getRows($sql, $params);
    }
}
