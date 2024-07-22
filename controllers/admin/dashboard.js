// Constante para completar la ruta de la API.
const PRODUCTO_API = 'services/admin/producto.php';
const CLIENTE_API = 'services/admin/cliente.php';
const PEDIDOS_API = 'services/admin/pedido.php';


// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Constante para obtener el número de horas.
    const HOUR = new Date().getHours();
    // Se define una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (HOUR < 12) {
        greeting = 'Buenos días';
    } else if (HOUR < 19) {
        greeting = 'Buenas tardes';
    } else if (HOUR <= 23) {
        greeting = 'Buenas noches';
    }
    // Llamada a la función para mostrar el encabezado y pie del documento.
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = `${greeting}, bienvenido`;
    // Llamada a la funciones que generan los gráficos en la página web.
    graficoBarrasMarcas();
    graficoPastelMarcas();
    graficoPastelPedido();    
    graficoBarrasCliente();
});

const graficoBarrasMarcas = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(PRODUCTO_API, 'cantidadProductosMarcas');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let marca = [];
        let cantidades = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            marca.push(row.nombre_marca);
            cantidades.push(row.cantidad);
        });
        // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
        barGraph('chart1', marca, cantidades, 'Cantidad de productos', 'Cantidad de productos por marca');
    } else {
        document.getElementById('chart1').remove();
        console.log(DATA.error);
    }
}

const graficoPastelMarcas = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(PRODUCTO_API, 'porcentajeProductosMarcas');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a gráficar.
        let marca = [];
        let porcentajes = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            marca.push(row.nombre_marca);
            porcentajes.push(row.porcentaje);
        });
        // Llamada a la función para generar y mostrar un gráfico de pastel. Se encuentra en el archivo components.js
        pieGraph('chart2', marca, porcentajes, 'Porcentaje de productos por marca');
    } else {
        document.getElementById('chart2').remove();
        console.log(DATA.error);
    }
}

const graficoPastelPedido = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(PEDIDOS_API, 'porcentajeEstadoPedidos');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a gráficar.
        let estados = [];
        let porcentajes = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            estados.push(row.estado_pedidos);
            porcentajes.push(row.porcentaje);
        });
        // Llamada a la función para generar y mostrar un gráfico de pastel. Se encuentra en el archivo components.js
        pieGraph('chart3', estados, porcentajes, 'Porcentaje de los estados de los pedidos');
    } else {
        document.getElementById('chart3').remove();
        console.log(DATA.error);
    }
}

const graficoBarrasCliente = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(CLIENTE_API, 'MayoresCompradores');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let cliente = [];
        let cantidades = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            cliente.push(row.nombre_cliente);
            cantidades.push(row.cantidad);
        });
        // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
        barGraph('chart4', cliente, cantidades, 'Cantidad de compras', 'Los clientes que más compraron');
    } else {
        document.getElementById('chart4').remove();
        console.log(DATA.error);
    }
}
