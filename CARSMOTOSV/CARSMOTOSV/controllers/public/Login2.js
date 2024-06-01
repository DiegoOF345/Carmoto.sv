function validarInicioSesion() {
    var correo = document.getElementById("correo").value;
    var contrasenia = document.getElementById("contrasenia").value;

    // Verificar si los campos no están vacíos
    if (correo === "" || contrasenia === "") {
        alert("Por favor, ingresa tu correo y contrasena.");
        return false;
    }

    // Enviar solicitud AJAX al archivo PHP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "Login2.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                if (xhr.responseText === "OK") {
                    alert("Inicio de sesión exitoso");
                    // Redirigir a la página de productos
                    window.location.href = "../../views/public/home/home.html";
                } else {
                    alert("Usuario o contrasena incorrectos");
                }
            } else if (xhr.status == 401) {
                alert("Usuario o contrasena incorrectos");
            } else {
                alert("Error en la solicitud: " + xhr.statusText);
            }
        }
    };
    xhr.send("correo=" + correo + "&contrasenia=" + contrasenia);

    // Prevenir el comportamiento de envío de formulario predeterminado
    return false;
}
