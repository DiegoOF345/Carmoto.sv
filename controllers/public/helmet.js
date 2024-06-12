const PRODUCTO_API = 'services/public/producto.php';
const PEDIDO_API = 'services/public/pedido.php';
// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);
// Constante para establecer el formulario de agregar un producto al carrito de compras.
const SHOPPING_FORM = document.getElementById('shoppingForm');
const MAIN_TITLE = document.getElementById('MainTitle');

document.addEventListener('DOMContentLoaded', async () => {
    // Se establece el título del contenido principal.
    
    
    MAIN_TITLE.textContent = 'Detalles del producto';
    // Constante tipo objeto con los datos del producto seleccionado.
    const FORM = new FormData();
    FORM.append('idProducto', PARAMS.get('id'));
    console.log();
    // Petición para solicitar los datos del producto seleccionado.
    const DATA = await fetchData(PRODUCTO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        console.log(DATA.dataset.id_casco);
        // Se colocan los datos en la página web de acuerdo con el producto seleccionado previamente.
        document.getElementById('imagen_casco').src = SERVER_URL.concat('Imagenes/productos/', DATA.dataset.imagen_casco);
        document.getElementById('nombre_casco').textContent = DATA.dataset.nombre_casco;
        document.getElementById('precio_casco').textContent = DATA.dataset.precio_casco;
        document.getElementById('existencias_casco').textContent = DATA.dataset.existencia_casco;
        document.getElementById('idProducto').value = DATA.dataset.id_casco;
    } else {
        // Se presenta un mensaje de error cuando no existen datos para mostrar.
        console.log(DATA.error);
        // Se limpia el contenido cuando no hay datos para mostrar.
        
    }
});

SHOPPING_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SHOPPING_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PEDIDO_API, 'createDetail', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
    if (DATA.status) {
        sweetAlert(1, DATA.message, false, 'shopping_car.html');
    } else if (DATA.session) {
        sweetAlert(2, DATA.error, false);
    } else {
        sweetAlert(3, DATA.error, true, 'login.html');
    }
});