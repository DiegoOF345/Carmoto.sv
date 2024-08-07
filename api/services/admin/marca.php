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

        case 'searchRows':
            if (!Validator::validateSearch($_POST['search'])) {
                $result['error'] = Validator::getSearchError();
            } elseif ($result['dataset'] = $marca->searchRows()) {
                $result['status'] = 1;
                $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
            } else {
                $result['error'] = 'No hay coincidencias';
            }
            break;

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
                !$marca->setNombre($_POST['nombre_marca']) or
                !$marca->setDescripcion($_POST['Descripcion_marca'])                
            ) {
                $result['error'] = $marca->getDataError();
            } elseif ($marca->createRow()) {
                $result['status'] = 1;
                $result['message'] = 'Marca creada correctamente';
                // Se asigna el estado del archivo después de insertar.
            } else {
                $result['error'] = 'Ocurrió un problema al crear el marca';
            }
            break;

            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$marca->setId($_POST['idMarca']) or
                    !$marca->setNombre($_POST['nombre_marca']) or
                    !$marca->setDescripcion($_POST['Descripcion_marca']) 
                ) {
                    $result['error'] = $marca->getDataError();
                } elseif ($marca->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Marca modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el marca';
                }
                break;
    
            case 'readOne':
                if (!$marca->setId($_POST['idMarca'])) {
                    $result['error'] = 'Marca incorrecto';
                } elseif ($result['dataset'] = $marca->readOne()) {
                        $result['status'] = 1;
                } else {
                    $result['error'] = 'Marca inexistente';
                }
                break;
                
            case 'deleteRow':
                    if (!$marca->setId($_POST['idMarca'])) {
                        $result['error'] = $marca->getDataError();
                    } elseif ($marca->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Marca eliminado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el marca';
                    }
                    break;
            case 'readTopProductos':
                if ($result['dataset'] = $marca->readTopProductos()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No hay datos disponibles';
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
