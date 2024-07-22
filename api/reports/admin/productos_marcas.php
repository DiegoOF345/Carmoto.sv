<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['idMarca'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/marca_data.php');
    // Se instancian las entidades correspondientes.
    $marca = new MarcaData;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($marca->setId($_GET['idMarca'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowMarca = $marca->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Productos de la marca ' . $rowMarca['nombre_marca']);
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $marca->productosMarcas()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(225);
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
                $pdf->cell(0, 10, $pdf->encodeString('No hay productos para la categoría'), 1, 1);
            }
            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'categoria.pdf');
        } else {
            print('Categoría inexistente');
        }
    } else {
        print('Categoría incorrecta');
    }
} else {
    print('Debe seleccionar una categoría');
}
