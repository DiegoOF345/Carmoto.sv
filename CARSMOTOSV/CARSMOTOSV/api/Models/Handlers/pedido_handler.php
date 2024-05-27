<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class PedidoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un pedido 
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_pedido AS ID, fecha_registro AS FECHA, direccion_pedidos AS DIRECCION, CASE 
        WHEN estado_pedidos = 1 THEN "Entregado"
        WHEN estado_pedidos = 0 THEN "Cancelado"
        END AS ESTADO FROM Pedidos
                WHERE fecha_registro LIKE ?
                ORDER BY FECHA;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los pedido
    public function readAll()
    {
        $sql = 'SELECT id_pedido AS ID, fecha_registro AS FECHA, direccion_pedidos AS DIRECCION, CASE 
        WHEN estado_pedidos = 1 THEN "Entregado"
        WHEN estado_pedidos = 0 THEN "Cancelado"
        END AS ESTADO FROM Pedidos
        ORDER BY FECHA;';
        return Database::getRows($sql);
    }

    //Función para cambiar el estado de un pedido.
    public function changeState()
    {
        $sql = 'CALL cambiar_estado_pedido(?);';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}