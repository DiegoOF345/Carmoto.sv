<?php
// Se incluye la clase del modelo.
require_once('../../Models/data/modelo_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se instancia la clase correspondiente.
    $modelo = new ModeloData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);
    // Se compara la acción a realizar según la petición del controlador.
    switch ($_GET['action']) {
        case 'searchRows':
            if (!Validator::validateSearch($_POST['search'])) {
                $result['error'] = Validator::getSearchError();
            } elseif ($result['dataset'] = $modelo->searchRows()) {
                $result['status'] = 1;
                $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
            } else {
                $result['error'] = 'No hay coincidencias';
            }
            break;

        case 'readAll':
            if ($result['dataset'] = $modelo->readAll()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'No existen modelos para mostrar';
            }
            break;

        case 'createRow':
            $_POST = Validator::validateForm($_POST);
            if (
                !$modelo->setNombre($_POST['Nombre_Modelo']) or
                !$modelo->setDescripcion($_POST['Descripcion_modelo']) or
                !$modelo->setAño($_POST['Año_modelo']) or
                !$modelo->setMarca($_POST['id_Marca'])                 
            ) {
                $result['error'] = $modelo->getDataError();
            } elseif ($modelo->createRow()) {
                $result['status'] = 1;
                $result['message'] = 'Modelo creado correctamente';
                // Se asigna el estado del archivo después de insertar.
            } else {
                $result['error'] = 'Ocurrió un problema al crear el modelo';
            }
            break;

        case 'updateRow':
            $_POST = Validator::validateForm($_POST);
            if (
                !$modelo->setId($_POST['idModelo']) or
                !$modelo->setNombre($_POST['Nombre_Modelo']) or
                !$modelo->setDescripcion($_POST['Descripcion_modelo']) or
                !$modelo->setAño($_POST['Año_modelo']) or
                !$modelo->setMarca($_POST['id_Marca'])
            ) {
                $result['error'] = $modelo->getDataError();
            } elseif ($modelo->updateRow()) {
                $result['status'] = 1;
                $result['message'] = 'Modelo modificado correctamente';
            } else {
                $result['error'] = 'Ocurrió un problema al modificar el modelo';
            }
            break;

            case 'readOne':
                if (!$modelo->setId($_POST['idModelo'])) {
                    $result['error'] = 'Modelo incorrecto';
                } elseif ($result['dataset'] = $modelo->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Modelo inexistente';
                }
                break;
            
            case 'deleteRow':
                    if (!$modelo->setId($_POST['idModelo'])) {
                       $result['error'] = $modelo->getDataError();
                   } elseif ($modelo->deleteRow()) {
                       $result['status'] = 1;
                       $result['message'] = 'Modelo eliminado correctamente';
                   } else {
                       $result['error'] = 'Ocurrió un problema al eliminar el modelo';
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
