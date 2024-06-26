const PRODUCTO_API = 'services/public/producto.php';
// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);
const PRODUCTOS = document.getElementById('productos');
const MAIN_TITLE = document.getElementById('MainTitle');

document.addEventListener('DOMContentLoaded', async () => {
    // Se define un objeto con los datos de la categoría seleccionada.
    
    const idMarca = PARAMS.get('id');
    console.log(idMarca);
    const action = idMarca !== null  ?  'readProductosMarcas' : 'readAll';
    console.log(action);
    const form = new FormData(); 
    form.append("idMarca",idMarca);
    const DATA = await fetchData(PRODUCTO_API, action,form);
    console.log(DATA);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        MAIN_TITLE.textContent = `${PARAMS.get('nombre') || "Todos"}`;
        // Se inicializa el contenedor de productos.
        PRODUCTOS.innerHTML = '';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            console.log(row.id_casco);           
            // Se crean y concatenan las tarjetas con los datos de cada producto.
            PRODUCTOS.innerHTML += `
                <article class="product-card">
                    <div class="product-information-container">
                        <div class="information">
                            <h2 class="title semi-bold">${row.nombre_casco}</h2>
                            <p>Certificado</p>
                        </div>
                        <p class="semi-bold">${row.precio_casco}</p>
                    </div>
                    <div class="img-container">
                        <img
                        src="${SERVER_URL}Imagenes/productos/${row.imagen_casco}"
                        alt="AGV product"
                        />
                    </div>
                    <button class="check-details">
                        <a href="helmet.html?id=${row.id_casco}" class="link">Ver detalles</a>
                    </button>
                </article>
            `;
        });
    } else {
        // Se presenta un mensaje de error cuando no existen datos para mostrar.
        sweetAlert(4, DATA.error, true);
        console.log(DATA.error);
    }
});