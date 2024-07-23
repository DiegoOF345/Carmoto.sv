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
                WHERE nombre_marca LIKE ? OR descripcion_marca LIKE ?
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

     //metodo para los graficos de los mejores productos
     public function readTopProductos()
     {
         $sql = 'SELECT nombre_marca, SUM(cantidad_productos) total
                FROM detalle_pedidos, Cascos, Marcas_Cascos, Modelos_de_Cascos
                WHERE detalle_pedidos.id_casco = cascos.id_casco AND cascos.id_modelo_de_casco = modelos_de_cascos.id_modelo_de_casco
                AND marcas_cascos.id_marca_casco = modelos_de_cascos.id_marca_casco
                GROUP BY nombre_marca
                ORDER BY total DESC
                LIMIT 3';
         return Database::getRows($sql);
     }

     //metodo para el reporte de las marcas
    public function productosMarcas()
    {
        $sql = 'SELECT nombre_casco, descripcion_casco, precio_casco, existencia_casco
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)
                WHERE id_marca_casco = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
     
}
