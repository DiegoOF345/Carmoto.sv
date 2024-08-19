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

    protected $pin = null;

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
            $_SESSION['idCliente'] = $data['id_cliente'];
            $_SESSION['correoCliente'] = $data['correo_cliente'];
            return true;
        } else {
            return false;
        }
    }

    public function editProfile()
    {
        $sql = 'UPDATE clientes
                SET nombre_cliente = ?, apellido_cliente = ?, correo_cliente = ?,direccion_cliente = ?, dui_cliente = ?, telefono_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->direccion, $this->dui, $this->telefono,  $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }


    public function editProfileCorreo()
    {
        $sql = 'UPDATE cliente
                SET nombre_cliente = ?, apellido_cliente = ?, correo_cliente = ?, telefono_cliente = ?, direccion_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->telefono, $this->direccion, $this->id);
        return Database::executeRow($sql, $params);
    }


     public function changeStatus()
    {
        $sql = 'UPDATE cliente
                SET estado_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, nacimiento_cliente, direccion_cliente, contraseña_cliente, fecha_cliente
                FROM clientes
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
                FROM clientes
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


     //esta parte va unida a cliente.php
    public function changePasswordRecu()
    {
        $sql = 'UPDATE clientes
                SET contraseña_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->contraseña, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function checkPassword($contraseña)
    {
        $sql = 'SELECT contraseña_cliente
                FROM clientes
                WHERE id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($contraseña, $data['contraseña_cliente'])) {
            return true;
        } else {
            return false;
        }
    }

    public function readProfile() 
    {
        $sql = 'SELECT  id_cliente, nombre_cliente, apellido_cliente, correo_cliente, direccion_cliente,dui_cliente, telefono_cliente
                FROM clientes   
                WHERE id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        return Database::getRow($sql, $params);
    }


     //Recuperacion de contraseña 

    /*GENERAR PIN*/

    public function verifUs()
    {
        $sql = 'SELECT * FROM clientes 
                WHERE correo_cliente = ?';
        $params = array($this->correo);
        return Database::getRow($sql, $params);
    }

    public function verifPin()
    {
        $sql = 'SELECT * FROM clientes 
                WHERE codigo_recuperacion = ? AND id_cliente = ?';
        $params = array($this->pin, $_SESSION['clienteRecup']);
        return Database::getRow($sql, $params);
    }

    // Guardar el PIN en la base de datos
    public function guardarCodigoRecuperacion($codigo)
    {
        error_log('Correo en guardarCodigoRecuperacion: ' . $this->correo); // Registro de depuración
        error_log('Código en guardarCodigoRecuperacion: ' . $codigo); // Registro de depuración

        $sql = 'UPDATE clientes 
            SET codigo_recuperacion = ? 
            WHERE correo_cliente = ?';
        $params = array($codigo, $this->correo);
        return Database::executeRow($sql, $params);
    }

    // Verificar el PIN en la base de datos
    public function verificarCodigoRecuperacion($codigo)
    {
        $sql = 'SELECT id_cliente 
            FROM clientes 
            WHERE id_cliente = ? 
            AND codigo_recuperacion = ?';
        $params = array($_SESSION['clienteRecup'], $codigo);

        // Agregar logs para depurar
        error_log("SQL: " . $sql);
        error_log("Params: " . print_r($params, true));

        $result = Database::getRow($sql, $params);

        // Log del resultado
        error_log("Resultado: " . print_r($result, true));

        return $result !== false;
    }


   
 

}
