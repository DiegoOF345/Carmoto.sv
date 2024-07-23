<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['idCliente'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/cliente_data.php');
    // Se instancian las entidades correspondientes.
    $cliente = new ClienteData;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($cliente->setId($_GET['idCliente'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowCliente = $cliente->readOne()) {
            // Encabezado, cambiar los datos como "nombre_marca" 
            $pdf->startReport('Pedidos de ' . $rowCliente['nombre_cliente']);
            // Verificacion de los productos existentes, si no encuentra ningun producto, dara un mensaje. Cambiar "$modelo" y el metodo
            if ($dataClientes = $cliente->ClientesPedidos()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(203, 13, 13);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(40, 10, 'ID del pedido', 1, 0, 'C', 1);
                $pdf->cell(60, 10, 'Estado del pedido', 1, 0, 'C', 1);
                $pdf->cell(70, 10, 'fecha de registro del pedido', 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                // Se recorren los registros fila por fila.
                foreach ($dataClientes as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(40, 10, $rowProducto['id_pedido'], 1, 0);
                    $pdf->cell(60, 10, $rowProducto['estado_pedidos'], 1, 0);
                    $pdf->cell(70, 10, $rowProducto['fecha_registro'], 1, 1);
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay pedidos de' . $rowCliente['nombre_cliente']), 1, 1);
            }
            // Se llama al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'categoria.pdf');
        } else {
            print('cliente inexistente');
        }
    } else {
        print('cliente incorrecto');
    }
} else {
    print('Debe seleccionar un cliente');
}
