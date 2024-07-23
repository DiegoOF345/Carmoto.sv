<?php
// Se incluye la clase del modelo.
require_once('../../Models/data/pedido_data.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedido = new PedidoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_administrador']) or true) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                // Buscar
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $pedido->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
                // Leer todos
            case 'readAll':
                if ($result['dataset'] = $pedido->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No hay pedidos registrados';
                }
                break;
                // Estado
            /*case 'changeState':
                if (
                    !$pedido->setIdPedido($_POST['idPedido'])
                ) {
                    $result['error'] = $pedido->getDataError();
                } elseif ($pedido->changeState()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estado del pedido cambiado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al alterar el estado del pedido';
                }
                break; */
            case 'porcentajeEstadoPedidos':
                    if ($result['dataset'] = $pedido->porcentajeEstadoPedidos()) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'No hay datos disponibles';
                    }
                    break;
                    case 'cantidadProductosMarcas':
                        if ($result['dataset'] = $producto->cantidadProductosMarcas()) {
                            $result['status'] = 1;
                        } else {
                            $result['error'] = 'No hay datos disponibles';
                        }
                        break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
