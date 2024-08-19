<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once('../helpers/database.php');
require_once('./PHPMailer/src/Exception.php');
require_once('./PHPMailer/src/PHPMailer.php');
require_once('./PHPMailer/src/SMTP.php');

header('Content-type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}

// Verificación de datos
$pin = isset($_POST['codigo_recuperacion']) ? trim($_POST['codigo_recuperacion']) : '';
$email = isset($_POST['correo_cliente']) ? trim($_POST['correo_cliente']) : '';

// Validación de datos
if (empty($pin) || empty($email)) {
    $response = ['status' => false, 'message' => 'Datos incompletos'];
    echo json_encode($response);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = ['status' => false, 'message' => 'Correo electrónico inválido'];
    echo json_encode($response);
    exit();
}

try {
    // Guardar el código de recuperación en la base de datos
    $query = "UPDATE clientes SET codigo_recuperacion = ? WHERE correo_cliente = ?";
    $values = [$pin, $email];
    $result = Database::executeRow($query, $values);

    if (!$result) {
        $response = ['status' => false, 'message' => 'Error al guardar el código de recuperación'];
        echo json_encode($response);
        exit();
    }

    // Configuración y envío del correo electrónico
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';

    $mail->setFrom("carsmotosvz503@gmail.com", "Carmoto.sv"); // Nombre del remitente opcional
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; // TLS
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587; // Puerto para TLS
    $mail->Username = "carsmotosvz503@gmail.com";
    $mail->Password = "iondqbcqmkzuohnm"; // Usa una contraseña de aplicación si tienes 2FA habilitado

    $mail->addAddress($email);
    $mail->Subject = 'Recuperación de contraseña';

    $mail->isHTML(true);
    $mail->CharSet = 'utf-8';
    $html = "<html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #d1cdb8;
                    color: #000;
                    text-align: center;
                    padding: 50px;
                }
                .container {
                    background-color: #f0f0f0;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    max-width: 400px;
                    margin: auto;
                }
                .header {
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                .pin {
                    font-size: 36px;
                    letter-spacing: 10px;
                    font-weight: bold;
                    margin: 20px 0;
                }
                .message {
                    font-size: 18px;
                    margin-top: 20px;
                }
                .footer {
                    font-size: 14px;
                    color: #888;
                    margin-top: 30px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>Recuperación de contraseña</div>
                <p>Hola, <strong>$email</strong>.</p>
                <p>Este es tu código de recuperación: <strong class='pin'>$pin</strong></p>
                <p class='message'>Si no solicitó este código, ignore este mensaje.</p>
                <div class='footer'>SAAR - Sistema de Administración Automotriz Rodríguez</div>
            </div>
        </body>
        </html>";
    $mail->Body = $html;
    $mail->send();

    $response = ['status' => true, 'message' => 'Correo enviado correctamente'];
} catch (Exception $e) {
    $response = ['status' => false, 'message' => 'Error al enviar el correo: ' . $e->getMessage()];
}

// Envía la respuesta JSON al cliente
echo json_encode($response);
