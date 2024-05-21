// Constantes para completar las rutas de la API.
const PRODUCTO_API = 'services/admin/producto.php';
const MODELO_API = 'services/admin/modelo.php';
// Constante para establecer el formulario de buscar.
const SEARCH_PRICE = document.getElementById('searchForm');
// Constantes para establecer los elementos del componente Modal.
const ADD_MODAL = new bootstrap.Modal('#exampleModal0'),
    EDIT_MODAL = new bootstrap.Modal('#Actualizar');
//    MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_PRODUCTO = document.getElementById('idProducto'),
    NOMBRE_PRODUCTO = document.getElementById('Nombre_Producto'),
    DESCRIPCION_PRODUCTO = document.getElementById('Descripcion'),
    PRECIO_PRODUCTO = document.getElementById('Precio'),
    MODELO_PRODUCTO = document.getElementById('Modelo_Casco'),
    EXISTENCIAS_PRODUCTO = document.getElementById('En_existencias1');

const Cerrar = document.getElementById('Cerrar');

const PARAMS = new URLSearchParams(location.search);
const PRODUCTOS = document.getElementById('Cards_Read');


// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
  // Llamada a la función para llenar la tabla con los registros existentes.
  
    fillTable();
});


  




// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    const action = (ID_PRODUCTO.value) ? 'updateRow' : 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PRODUCTO_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        ADD_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    const FORM = new FormData();
    FORM.append('id_modelo_de_casco', PARAMS.get('id'));
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PRODUCTO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {

        // Se inicializa el contenedor de productos.
        PRODUCTOS.innerHTML = '';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las tarjetas con los datos de cada producto.
            PRODUCTOS.innerHTML += `
            <div class="col-sm-6 mb-6 mb-sm-0">
                <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex">
                            <h4 class="card-title card_titulo">${row.nombre_casco}</h4>
                            <h4 class="card-text ms-auto">$${row.precio_casco}</h4>
                        </div>
                        <p class="card-text d-flex justify-content-center">${row.id_casco} | ${row.existencia_casco}</p>
                        <center><img src="${SERVER_URL}/Imagenes/productos/${row.imagen_casco}" class="fixed" alt="${row.nombre_casco}" width="200"></center>
                    </div>
                    <button type="button" class="btn btn-light d-flex justify-content-center mx-auto" style="justify-tracks: left;" onclick="openUpdate(${row.id_casco})">
                        Editar Producto
                    </button>
                </div>
            </div>
            `;
        });
    }
    else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    ADD_MODAL.show();
    // Se prepara el formulario.
    SAVE_FORM.reset();
    fillSelect(MODELO_API, 'readAll', 'id_Producto');
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idProducto', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(PRODUCTO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        EDIT_MODAL.show();
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_PRODUCTO.value = ROW.id_casco;
        NOMBRE_PRODUCTO.value = ROW.nombre_casco;
        DESCRIPCION_PRODUCTO.value = ROW.descripcion_casco;
        PRECIO_PRODUCTO.value = ROW.precio_casco;
        fillSelect(MODELO_API, 'readAll', 'Modelo_Casco', ROW.id_modelo_de_casco)
        EXISTENCIAS_PRODUCTO.value = ROW.existencias_producto;
        
        
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el producto de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idProducto', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(PRODUCTO_API, 'deleteRow', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}


/*
*   Función para abrir un reporte automático de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openReport = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/productos.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}