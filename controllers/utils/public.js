/*
*   Controlador es de uso general en las páginas web del sitio público.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'services/public/cliente.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '75px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');
// Se establece el título de la página web.
document.querySelector('title').textContent = 'CARSMOTOSV';
// Constante para establecer el elemento del título principal.


/*  Función asíncrona para cargar el encabezado y pie del documento.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const loadTemplate = async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const DATA = await fetchData(USER_API, 'getUser');
    // Se comprueba si el usuario está autenticado para establecer el encabezado respectivo.
    if (DATA.session) {
        // Se verifica si la página web no es el inicio de sesión, de lo contrario se direcciona a la página web principal.
        if (!location.pathname.endsWith('login.html')) {
            // Se agrega el encabezado de la página web antes del contenido principal.
            MAIN.insertAdjacentHTML('beforebegin', `
                <header class="header">
                    <div class="header-container">
                        <div class="header-content">
                            <a href="./index.html">
                            <div class="logo-img" alt="carmotosv logo"></div>
                            </a>
                            <a href="./about_us.html" class="title-navigation title">
                            <h2>¿Quiénes somos?</h2>
                            </a>
                            <a href="./promociones.html" class="title-navigation title">
                            <h2>Promociones</h2>
                            </a>
                        </div>
                    </div>
                    <div class="action-header-container">
                        <div id="menu">
                            <ul>
                                <li class="has-sub">
                                    <div class="hamburger-container">
                                        <button class="hamburger actions-btn-no-background" id="hamburger">
                                        <img src="../../api/Imagenes/img/icons/hamburger.png" class="icons hamburger" alt="hamburger" />
                                        </button>
                                        <h2 class="action-title title">Marcas</h2>
                                    </div>
                                    <ul id="Marcas_fill">
              
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <h2 class="action-title title" id="MainTitle"></h2>
                        <button class="shopping-car-btn actions-btn-no-background" id="shopping-car">
                        <a href="./shopping_car.html"><img src="../../api/Imagenes/img/icons/shopping car.png" class="icons"
                        alt="shopping car" /></a>
                        </button>
                    </div>
                </header>
            `);
        } else {
            location.href = 'home.html';
        }
    } else {
        // Se agrega el encabezado de la página web antes del contenido principal.
        MAIN.insertAdjacentHTML('beforebegin', `
            <header>
                <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
                    <div class="container">
                        <a class="navbar-brand" href="index.html"><img src="../../resources/img/logo.png" height="50" alt="CoffeeShop"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav ms-auto">
                                <a class="nav-link" href="index.html"><i class="bi bi-shop"></i> Catálogo</a>
                                <a class="nav-link" href="signup.html"><i class="bi bi-person"></i> Crear cuenta</a>
                                <a class="nav-link" href="login.html"><i class="bi bi-box-arrow-right"></i> Iniciar sesión</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
        `);
    }
    // Se agrega el pie de la página web después del contenido principal.
    MAIN.insertAdjacentHTML('afterend', `
        <footer>
            <nav class="navbar fixed-bottom bg-body-tertiary">
                <div class="container">
                    <div>
                        <h6>CoffeeShop</h6>
                        <p><i class="bi bi-c-square"></i> 2018-2024 Todos los derechos reservados</p>
                    </div>
                    <div>
                        <h6>Contáctanos</h6>
                        <p><i class="bi bi-envelope"></i> dacasoft@outlook.com</p>
                    </div>
                </div>
            </nav>
        </footer>
    `);
}