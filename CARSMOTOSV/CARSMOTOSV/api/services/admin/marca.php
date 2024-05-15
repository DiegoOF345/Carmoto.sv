<?php
// Se incluye la clase del modelo.
require_once('../../Models/data/marca_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se instancia la clase correspondiente.
    $marca = new MarcaData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);
    // Se compara la acción a realizar según la petición del controlador.
    switch ($_GET['action']) {
        case 'readAll':
            if ($result['dataset'] = $marca->readAll()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'No existen productos para mostrar';
            }
            break;
        case 'createRow':
            $_POST = Validator::validateForm($_POST);
            if (
                !$marca->setNombre($_POST['Nombre_Producto']) or
                !$marca->setDescripcion($_POST['Descripcion'])
                
            ) {
                $result['error'] = $producto->getDataError();
            } elseif ($producto->createRow()) {
                $result['status'] = 1;
                $result['message'] = 'Producto creado correctamente';
                // Se asigna el estado del archivo después de insertar.
                $result['fileStatus'] = Validator::saveFile($_FILES['formFile'], $producto::RUTA_IMAGEN);
            } else {
                $result['error'] = 'Ocurrió un problema al crear el producto';
            }
            break;
        default:
            $result['error'] = 'Acción no disponible';
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print (json_encode($result));
} else {
    print (json_encode('Recurso no disponible'));
}
