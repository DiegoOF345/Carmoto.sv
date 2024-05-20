<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carsmotosv";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se han enviado los datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];

    // Consulta preparada para verificar las credenciales del usuario
    $stmt = $conn->prepare("SELECT * FROM Administradores WHERE correo_administrador = ? AND contrasenia_administrador = ?");
    $stmt->bind_param("ss", $correo, $contrasenia);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario autenticado correctamente
        echo "OK";
    } else {
        // Usuario no autenticado
        http_response_code(401);
        echo "ERROR";
    }
}

$stmt->close();
$conn->close();
?>
