const MARCA_API = 'services/public/marca.php';
const MARCA = document.getElementById('Marcas_fill')

document.addEventListener('DOMContentLoaded', async () => {
    // Petición para obtener las categorías disponibles.
    const DATA = await fetchData(MARCA_API, 'readAll');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se establece la página web de destino con los parámetros.
            let url = `productos.html?id=${row.id_marca_casco}&nombre=${row.nombre_marca}`;
            // Se crean y concatenan las tarjetas con los datos de cada categoría.
            MARCA.innerHTML += `
                <li class="item">
                    <a title="" href="${url}">${row.nombre_marca}</a>
                </li>
            `;
        });
    } else {
        // Se asigna al título del contenido de la excepción cuando no existen datos para mostrar.
        document.getElementById('mainTitle').textContent = DATA.error;
    }
});
