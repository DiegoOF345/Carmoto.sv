<?php
// Se incluye la clase del modelo.
require_once ('../../Models/data/cliente_data.php');


// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    if (!isset($_SESSION['clienteRecup']) || empty($_SESSION['clienteRecup'])) {
        error_log('ID de cliente en la sesión no está establecido o está vacío');
    }
    
    // Se instancia la clase correspondiente.
    $cliente = new ClienteData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'recaptcha' => 0, 'message' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['idCliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getUser':
                if (isset($_SESSION['correoCliente'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['correoCliente'];
                } else {
                    $result['error'] = 'Correo de usuario indefinido';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $cliente->readProfile()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ocurrió un problema al leer el perfil';
                }
                break;
                case 'editProfile':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$cliente->setNombre($_POST['nombreCliente']) or
                        !$cliente->setApellido($_POST['apellidoCliente']) or
                        !$cliente->setCorreo($_POST['correoCliente']) or
                        !$cliente->setDireccion($_POST['direccionCliente']) or
                        !$cliente->setDUI($_POST['duiCliente']) or
                        !$cliente->setTelefono($_POST['telefonoCliente'])
                    ) {
                        $result['error'] = $cliente->getDataError();
                    } elseif ($cliente->editProfile()) {
                        $result['status'] = 1;
                        $result['message'] = 'Perfil modificado correctamente';
                        $_SESSION['correoCliente'] = $_POST['correoCliente'];
                    } else {
                        $result['error'] = 'Ocurrió un problema al modificar el perfil';
                    }
                    break;
                    case 'changePassword':
                        $_POST = Validator::validateForm($_POST);
                        if (!$cliente->checkPassword($_POST['contraseñaActual'])) {
                            $result['error'] = 'Contraseña actual incorrecta';
                        } elseif ($_POST['contraseñaNueva'] != $_POST['confirmarContraseña']) {
                            $result['error'] = 'Confirmación de contraseña diferente';
                        } elseif (!$cliente->setClave($_POST['contraseñaNueva'])) {
                            $result['error'] = $cliente->getDataError();
                        } elseif ($cliente->changePassword()) {
                            $result['status'] = 1;
                            $result['message'] = 'Contraseña cambiada correctamente';
                        } else {
                            $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                        }
                        break;
                        case 'readOne':
                            if (!$cliente->setId($_POST['idCliente'])) {
                                $result['error'] = 'cliente incorrecto';
                            } elseif ($result['dataset'] = $cliente->readOne()) {
                                $result['status'] = 1;
                            } else {
                                $result['error'] = 'cliente inexistente';
                            }
                            break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'signUp':
                $_POST = Validator::validateForm($_POST);
                if (!isset($_POST['condicion'])) {
                    $result['error'] = 'Debe marcar la aceptación de términos y condiciones';
                } elseif (
                    !$cliente->setNombre($_POST['nombreCliente']) or
                    !$cliente->setApellido($_POST['apellidoCliente']) or
                    !$cliente->setCorreo($_POST['correoCliente']) or
                    !$cliente->setDireccion($_POST['direccionCliente']) or
                    !$cliente->setDUI($_POST['duiCliente']) or
                    !$cliente->setNacimiento($_POST['nacimientoCliente']) or
                    !$cliente->setTelefono($_POST['telefonoCliente']) or
                    !$cliente->setClave($_POST['contraseñaCliente'])
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($_POST['contraseñaCliente'] != $_POST['confirmarContraseña']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($cliente->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cuenta registrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar la cuenta';
                }
                break;

            case 'signUpMovil':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$cliente->setNombre($_POST['nombreCliente']) or
                    !$cliente->setApellido($_POST['apellidoCliente']) or
                    !$cliente->setCorreo($_POST['correoCliente']) or
                    !$cliente->setDireccion($_POST['direccionCliente']) or
                    !$cliente->setDUI($_POST['duiCliente']) or
                    !$cliente->setNacimiento($_POST['nacimientoCliente']) or
                    !$cliente->setTelefono($_POST['telefonoCliente']) or
                    !$cliente->setClave($_POST['contraseñaCliente'])
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($_POST['contraseñaCliente'] != $_POST['confirmarContraseña']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($cliente->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cuenta registrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar la cuenta';
                }
                break;

            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                if ($cliente->checkUser($_POST['correo'], $_POST['contrasenia'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                } else {
                    $result['error'] = 'Datos incorrectos';
                }
                break;
                case 'changePassword':
                    if (!$cliente->setId($_SESSION['clienteRecup'])) {
                        $result['error'] = 'Acción no disponible';
                    } elseif ($_POST['claveNueva'] != $_POST['confirmarClave']) {
                        $result['error'] = 'Contraseñas diferentes';
                    } elseif (!$cliente->setClave($_POST['claveNueva'])) {
                        $result['error'] = $cliente->getDataError();
                    } elseif ($cliente->changePasswordRecu()) {
                        $result['status'] = 1;
                        $result['message'] = 'Contraseña modificada correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                    }
                    break;
                    case 'verifUs':
                        // Verificar si el correoCliente está definido en la solicitud
                        if (isset($_POST['correoCliente'])) {
                            if (!$cliente->setCorreo($_POST['correoCliente'])) {
                                $result['error'] = $cliente->getDataError();
                            } elseif ($result['dataset'] = $cliente->verifUs()) {
                                $result['status'] = 1;
                                $_SESSION['clienteRecup'] = $result['dataset']['id_cliente'];
                            } else {
                                $result['error'] = 'Correo inexistente';
                            }
                        } else {
                            // Si el correoCliente no está definido, retornar un error
                            $result['error'] = 'El correo no está definido';
                        }
                        break;
                                    
                    
                case 'sendPin':
                    $correoCliente = $_POST['correo_cliente'] ?? '';
                    $codigoRecuperacion = $_POST['codigo_recuperacion'] ?? '';
    
                    if ($correoCliente && $codigoRecuperacion) {
                        if ($cliente->setCorreo($correoCliente)) {
                            if ($cliente->guardarCodigoRecuperacion($codigoRecuperacion)) {
                                $result['status'] = 1;
                                $result['message'] = 'Correo enviado correctamente';
                            } else {
                                $result['error'] = 'Error al guardar el código de recuperación';
                            }
                        } else {
                            $result['error'] = 'Correo inválido';
                        }
                    } else {
                        $result['error'] = 'Datos incompletos';
                    }
                    break;
                case 'verifPin':
                    $correoCliente = $_POST['correo_cliente'] ?? '';
                    $pinCliente = $_POST['codigo_recuperacion'] ?? '';
    
                    if ($correoCliente && $pinCliente) {
                        $cliente->setCorreo($correoCliente);
                        if ($cliente->verificarCodigoRecuperacion($pinCliente)) {
                            $result['status'] = 1;
                            $result['message'] = 'Codigo de recuperación verificado correctamente';
                        } else {
                            $result['error'] = 'Codigo de recuperación incorrecto';
                        }
                    } else {
                        $result['error'] = 'Datos incompletos';
                    }
                    break;
        }
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
