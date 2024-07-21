<?php
// Se incluye la clase del modelo de producto.
require_once('../../Models/data/producto_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se instancia la clase correspondiente.
    $producto = new ProductoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);
    // Se compara la acción a realizar según la petición del controlador.
    switch ($_GET['action']) {
        case 'searchRows':
            if (!Validator::validateSearch($_POST['search'])) {
                $result['error'] = Validator::getSearchError();
            } elseif ($result['dataset'] = $producto->searchRows()) {
                $result['status'] = 1;
                $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
            } else {
                $result['error'] = 'No hay coincidencias';
            }
            break;
        case 'readAll':
            if ($result['dataset'] = $producto->readAll()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'No existen productos para mostrar';
            }
            break;
        case 'createRow':
            $_POST = Validator::validateForm($_POST);
            if (
                !$producto->setNombre($_POST['nombreProducto']) or
                !$producto->setDescripcion($_POST['descripcionProducto']) or
                !$producto->setPrecio($_POST['precioProducto']) or
                !$producto->setExistencias($_POST['existenciasProducto']) or
                !$producto->setModelo($_POST['modeloProducto']) or
                !$producto->setImagen($_FILES['imagenProducto'])
            ) {
                $result['error'] = $producto->getDataError();
            } elseif ($producto->createRow()) {
                $result['status'] = 1;
                $result['message'] = 'Producto creado correctamente';
                // Se asigna el estado del archivo después de insertar.
                $result['fileStatus'] = Validator::saveFile($_FILES['imagenProducto'], $producto::RUTA_IMAGEN);
            } else {
                $result['error'] = 'Ocurrió un problema al crear el producto';
            }
            break;
        case 'updateRow':
            $_POST = Validator::validateForm($_POST);
            if (
                !$producto->setId($_POST['idProducto']) or
                !$producto->setNombre($_POST['nombreProducto']) or
                !$producto->setDescripcion($_POST['descripcionProducto']) or
                !$producto->setPrecio($_POST['precioProducto']) or
                !$producto->setExistencias($_POST['existenciasProducto']) or
                !$producto->setModelo($_POST['modeloProducto']) or
                !$producto->setImagen($_FILES['imagenProducto'])
            ) {
                $result['error'] = $producto->getDataError();
            } else {
                $imagen = $producto->readOne()['imagen_casco'];
                if ($producto->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto modificado correctamente';
                    $result['fileStatus'] = Validator::changeFile($_FILES['imagenProducto'], $producto::RUTA_IMAGEN, $imagen);
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el producto';
                }
            }
            break;
        case 'readOne':
            if (!$producto->setId($_POST['idProducto'])) {
                $result['error'] = 'Producto incorrecto';
            } elseif ($result['dataset'] = $producto->readOne()) {
                $result['status'] = 1;
            } else {
                $result['error'] = 'Producto inexistente';
            }
            break;
        case 'deleteRow':
                if (!$producto->setId($_POST['idProducto'])) {
                   $result['error'] = $producto->getDataError();
               } else {
                    $imagen = $producto->readOne()['imagen_casco'];
                    $imageDeleteStatus = Validator::deleteFile($imagen, $producto::RUTA_IMAGEN);
                    if ($producto->deleteRow() && $imageDeleteStatus) {
                        $result['status'] = 1;
                        $result['message'] = 'Producto eliminado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el modelo';
                    }
                }
               break;
        case 'cantidadProductosMarcas':
                if ($result['dataset'] = $producto->cantidadProductosMarcas()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No hay datos disponibles';
                }
                break;
        case 'porcentajeProductosMarcas':
                    if ($result['dataset'] = $producto->porcentajeProductosMarcas()) {
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
