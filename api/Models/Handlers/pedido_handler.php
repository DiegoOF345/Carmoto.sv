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
    protected $id_pedido = null;
    protected $id_detalle = null;
    protected $cliente = null;
    protected $producto = null;
    protected $cantidad = null;
    protected $estado = null;
    protected $talla = null;

    protected $id_cliente = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un pedido 
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_pedido AS ID, fecha_registro AS FECHA, direccion_pedidos AS DIRECCION, estado_pedidos AS Estado
        FROM Pedidos
                WHERE fecha_registro LIKE ?
                ORDER BY FECHA;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los pedido
    public function readAll()
    {
        $sql = 'SELECT id_pedido AS ID, fecha_registro AS FECHA, direccion_pedidos AS DIRECCION, estado_pedidos AS ESTADO FROM Pedidos
        ORDER BY FECHA;';
        return Database::getRows($sql);
    }

    public function readAllofOne()
    {
        $sql = 'SELECT id_pedido, fecha_registro, estado_pedidos 
        FROM Pedidos
        WHERE id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        return Database::getRows($sql, $params);
    }

    public function getOrder()
    {
        $this->estado = 'Pendiente';
        $sql = 'SELECT id_pedido
                FROM Pedidos
                WHERE estado_pedidos = ? AND id_cliente = ?';
        $params = array($this->estado, $_SESSION['idCliente']);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['idPedido'] = $data['id_pedido'];
            return true;
        } else {
            return false;
        }
    }

    // Método para iniciar un pedido en proceso.
    public function startOrder()
    {
        if ($this->getOrder()) {
            return true;
        } else {
            $sql = 'INSERT INTO Pedidos(direccion_pedidos, id_cliente, estado_pedidos)
                    VALUES((SELECT direccion_cliente FROM Clientes WHERE id_cliente = ?), ?,?)';
            $params = array($_SESSION['idCliente'], $_SESSION['idCliente'], 'Pendiente');
            // Se obtiene el ultimo valor insertado de la llave primaria en la tabla pedido.
            if ($_SESSION['idPedido'] = Database::getLastRow($sql, $params)) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Método para agregar un producto al carrito de compras.
    public function createDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $sql = 'INSERT INTO detalle_pedidos(id_casco, precio_productos, talla_casco, cantidad_productos, id_pedido)
                VALUES(?, (SELECT precio_casco FROM Cascos WHERE id_casco = ?), ?, ?, ?)';
        $params = array($this->producto, $this->producto, $this->talla, $this->cantidad, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener los productos que se encuentran en el carrito de compras.
    public function readDetail()
    {
        $sql = 'SELECT id_detalle_pedidos, nombre_casco, talla_casco, c.precio_casco, dp.cantidad_productos
                FROM detalle_pedidos AS dp
                INNER JOIN Pedidos AS p USING(id_pedido)
                INNER JOIN Cascos AS c USING(id_casco)
                WHERE id_pedido = ?';
        $params = array($_SESSION['idPedido']);
        return Database::getRows($sql, $params);
    }

    // Método para finalizar un pedido por parte del cliente.
    public function finishOrder()
    {
        $this->estado = 'En camino';
        $sql = 'UPDATE Pedidos
                SET estado_pedidos = ?
                WHERE id_pedido = ?';
        $params = array($this->estado, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para actualizar la cantidad de un producto agregado al carrito de compras.
    public function updateDetail()
    {
        $sql = 'UPDATE detalle_pedidos
                SET cantidad_productos = ?
                WHERE id_detalle_pedidos = ? AND id_pedido = ?';
        $params = array($this->cantidad, $this->id_detalle, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un producto que se encuentra en el carrito de compras.
    public function deleteDetail()
    {
        $sql = 'DELETE FROM detalle_pedidos
                WHERE id_detalle_pedidos = ? AND id_pedido = ?';
        $params = array($this->id_detalle, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    //Metodo para  el grafico de paster de los estados de los pedidos
    public function porcentajeEstadoPedidos()
    {
        $sql = 'SELECT estado_pedidos, ROUND((COUNT(estado_pedidos) * 100.0 / (SELECT COUNT(estado_pedidos) FROM Pedidos)), 2) porcentaje
                FROM Pedidos
                GROUP BY estado_pedidos ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    public function GananciaMes(){
        $sql = 'SELECT MONTHNAME(fecha_registro) AS Mes,
                SUM(detalle_pedidos.precio_productos) AS Total
                FROM Pedidos, detalle_pedidos
                WHERE YEAR(fecha_registro) = "2023" AND pedidos.id_pedido = detalle_pedidos.id_pedido
                AND pedidos.estado_pedidos = "Entregado"
                GROUP BY Mes';
        return Database::getRows($sql);
    }
    public function getHistorialCompras()
{
    $sql = 'SELECT p.id_pedido, c.nombre_casco, p.fecha_registro, p.estado_pedidos, 
                   d.talla_casco, d.cantidad_productos, d.precio_productos
            FROM Pedidos p
            JOIN detalle_pedidos d ON p.id_pedido = d.id_pedido
            JOIN Cascos c ON d.id_casco = c.id_casco
            WHERE p.id_cliente = ? 
            ORDER BY p.fecha_registro DESC';
    $params = array($_SESSION['idCliente']);
    return Database::getRows($sql, $params);
}

    
    
}
