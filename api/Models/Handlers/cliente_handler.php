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
    protected $apellido = null;
    protected $dui = null;
    protected $correo = null;
    protected $telefono = null;
    protected $nacimiento = null;
    protected $direccion = null;
    protected $contraseña = null;

    protected $fecha = null;

    protected $estado = null;

    
    
    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../Imagenes/productos/';

   /*
    *   Métodos para gestionar la cuenta del cliente.
    */
    public function checkUser($mail, $password)
    {
        $sql = 'SELECT id_cliente, correo_cliente, contraseña_cliente, estado_cliente
                FROM Clientes
                WHERE correo_cliente = ?';
        $params = array($mail);
        if(!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['contraseña_cliente'])) {
            $this->id = $data['id_cliente'];
            $this->correo = $data['correo_cliente'];
            $this->estado = $data['estado_cliente'];
            return true;
        } else {
            return false;
        }
    }

    public function checkStatus()
    {
        if ($this->estado) {
            $_SESSION['idCliente'] = $this->id;
            $_SESSION['correoCliente'] = $this->correo;
            return true;
        } else {
            return false;
        }
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
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
        $params = array($this->nombre, $this->apellido, $this->dui, $this->correo, $this->telefono, $this->nacimiento, $this->direccion, $this->contraseña);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, nacimiento_cliente, direccion_cliente, contraseña_cliente, fecha_cliente
                FROM Clientes';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, nacimiento_cliente, direccion_cliente, contraseña_cliente, fecha_cliente
                FROM Clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE Clientes
                SET nombre_cliente = ?, apellido_cliente = ?, dui_cliente = ?, correo_cliente = ?, telefono_cliente = ?, nacimiento_cliente = ?, direccion_cliente = ?, contraseña_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->dui, $this->correo, $this->telefono, $this->nacimiento, $this->direccion, $this->contraseña, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM Clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    

    //metodo para el grafico de los clientes que mas pedidos tienen
    public function MayoresCompradores()
    {
        $sql = 'SELECT nombre_cliente, COUNT(id_cliente) cantidad
                FROM Clientes
                INNER JOIN Pedidos USING(id_cliente)
                GROUP BY nombre_cliente ORDER BY cantidad DESC LIMIT 5';
        return Database::getRows($sql);
    }



    //metodo usado para el reporte de los mayores compradores
    public function MayoresCompradoresReporte()
    {
        $sql = 'SELECT nombre_cliente, correo_cliente, COUNT(id_cliente) cantidad
                FROM Clientes
                INNER JOIN Pedidos USING(id_cliente)
                GROUP BY nombre_cliente ORDER BY cantidad DESC';
        return Database::getRows($sql);
    }

    //metodo usado para los reportes que permite saber que cliente corresponde cada pedido y el estado
    public function ClientesPedidos()
    {
        $sql = 'SELECT id_pedido, estado_pedidos, fecha_registro
                FROM Pedidos
                INNER JOIN Clientes USING (id_cliente)
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }


    //otros metodos
    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_cliente
                FROM Clientes
                WHERE dui_cliente = ? OR correo_cliente = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }
    public function changePassword()
    {
        $sql = 'UPDATE cliente
                SET contraseña_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->contraseña, $this->id);
        return Database::executeRow($sql, $params);
    }
}
