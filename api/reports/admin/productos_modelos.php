<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['idModelo'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/modelo_data.php');
    // Se instancian las entidades correspondientes.
    $modelo = new ModeloData;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($modelo->setId($_GET['idModelo'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowModelo = $modelo->readOne()) {
            // Encabezado, cambiar los datos como "nombre_marca" 
            $pdf->startReport('Productos del modelo ' . $rowModelo['nombre_modelo']);
            // Verificacion de los productos existentes, si no encuentra ningun producto, dara un mensaje. Cambiar "$modelo" y el metodo
            if ($dataProductos = $modelo->productosModelo()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(203, 13, 13);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(126, 10, 'Nombre', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Precio (US$)', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Existencias', 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                // Se recorren los registros fila por fila.
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(126, 10, $pdf->encodeString($rowProducto['nombre_casco']), 1, 0);
                    $pdf->cell(30, 10, $rowProducto['precio_casco'], 1, 0);
                    $pdf->cell(30, 10, $rowProducto['existencia_casco'], 1, 1);
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay productos para el modelo'), 1, 1);
            }
            // Se llama al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'categoria.pdf');
        } else {
            print('Modelo inexistente');
        }
    } else {
        print('Modelo incorrecta');
    }
} else {
    print('Debe seleccionar un modelo');
}
