<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class ProductoHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    protected $precio = null;
    protected $existencias = null;
    protected $imagen = null;
    
    protected $modelo = null;

    protected $marca = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../Imagenes/productos/';

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_casco, imagen_casco, nombre_casco, descripcion_casco, precio_casco, existencia_casco, nombre_modelo
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)
                WHERE precio_casco LIKE ? or nombre_casco LIKE ? 
                ORDER BY nombre_casco';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO Cascos(nombre_casco, descripcion_casco, precio_casco, existencia_casco, imagen_casco, id_modelo_de_casco, id_administrador)
                VALUES(?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->descripcion, $this->precio, $this->existencias, $this->imagen, $this->modelo, 1);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_casco, imagen_casco, nombre_casco, descripcion_casco, precio_casco, existencia_casco, nombre_modelo
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_casco, nombre_casco, descripcion_casco, precio_casco, existencia_casco, imagen_casco, id_modelo_de_casco
                FROM Cascos
                WHERE id_casco = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readFilename()
    {
        $sql = 'SELECT imagen_casco
                FROM Cascos
                WHERE id_casco = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE Cascos
                SET imagen_casco = ?, nombre_casco = ?, descripcion_casco = ?, precio_casco = ?, existencia_casco = ?, id_modelo_de_casco = ?
                WHERE id_casco = ?';
        $params = array($this->imagen, $this->nombre, $this->descripcion, $this->precio, $this->existencias, $this->modelo, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM Cascos
                WHERE id_casco = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readProductosMarcas()
    {
        $sql = 'SELECT id_casco, nombre_casco, descripcion_casco, imagen_casco, precio_casco, existencia_casco
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)
                WHERE id_marca_casco = ?
                ORDER BY nombre_casco';
        $params = array($this->marca);
        return Database::getRows($sql, $params);
    }

    /*
    *   Métodos para generar gráficos.
    */
    public function cantidadProductosMarcas()
    {
        $sql = 'SELECT nombre_marca, COUNT(id_casco) cantidad
                FROM Cascos, Modelos_de_Cascos, Marcas_Cascos
                WHERE Cascos.id_modelo_de_casco = modelos_de_cascos.id_modelo_de_casco
                AND marcas_cascos.id_marca_casco = modelos_de_cascos.id_marca_casco
                GROUP BY nombre_marca ORDER BY cantidad DESC LIMIT 5';
        return Database::getRows($sql);
    }

    public function porcentajeProductosMarcas()
    {
        $sql = 'SELECT nombre_marca, ROUND((COUNT(id_casco) * 100.0 / (SELECT COUNT(id_casco) FROM Cascos)), 2) porcentaje
                FROM Cascos, Modelos_de_Cascos, Marcas_Cascos
                WHERE Cascos.id_modelo_de_casco = modelos_de_cascos.id_modelo_de_casco
                AND marcas_cascos.id_marca_casco = modelos_de_cascos.id_marca_casco
                GROUP BY nombre_marca ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    /*
    *   Métodos para generar reportes.
    */
    public function productosMarcas()
    {
        $sql = 'SELECT nombre_casco, descripcion_casco, precio_casco, existencia_casco
                FROM Cascos
                INNER JOIN Modelos_de_Cascos USING(id_modelo_de_casco)
                WHERE id_marca_casco = ?';
        $params = array($this->marca);
        return Database::getRows($sql, $params);
    }
}
