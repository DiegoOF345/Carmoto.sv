function validarInicioSesion() {
    var correo = document.getElementById("username").value;
    var contrasenia = document.getElementById("password").value;

    // Verificar si los campos no están vacíos
    if (correo === "" || contrasenia === "") {
        alert("Por favor, ingresa tu correo y contraseña.");
        return;
    }

    // Enviar solicitud AJAX al archivo PHP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "login.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText === "OK") {
                alert("Inicio de sesión exitoso");
                // Redirigir a la página de productos
                window.location.href = "productos.html";
            } else {
                alert("Usuario o contraseña incorrectos");
            }
        }
    };
    xhr.send("correo=" + correo + "&contrasenia=" + contrasenia);
}




