<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/cliente_data.php');

// Se llama a la clase del reporte
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Mayores compradores');
// Se instancia el módelo Categoría para obtener los datos.
$cliente = new ClienteData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataCliente = $cliente->MayoresCompradoresReporte()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(203, 13, 13);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(40, 10, 'Nombre del cliente', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Correo del cliente', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Total de pedidos hechos', 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(240);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataCliente as $rowCliente) {
        // Se instancia el módelo Producto para procesar los datos.
        $pdf->cell(40, 10, $pdf->encodeString($rowCliente['nombre_cliente']), 1, 0);
        $pdf->cell(60, 10, $rowCliente['correo_cliente'], 1, 0);
        $pdf->cell(60, 10, $rowCliente['cantidad'], 1, 1);
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay ganancias que mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'productos.pdf');
