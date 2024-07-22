<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluye el modelo para acceder a los datos de los libros.
require_once('../../models/data/libros_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Libros en Existencia');

// Se instancia el modelo Libro para obtener los datos.
$libro = new LibroData;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataLibros = $libro->getLibrosEnExistencia()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(200);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(100, 10, 'Titulo', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Existencias', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Precio (US$)', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los libros.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataLibros as $rowLibro) {
        // Se imprimen las celdas con los datos de los libros.
        $pdf->cell(100, 10, $pdf->encodeString($rowLibro['titulo']), 1, 0);
        $pdf->cell(40, 10, $rowLibro['existencias'], 1, 0);
        $pdf->cell(40, 10, $rowLibro['precio'], 1, 1);
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay libros en existencia para mostrar'), 1, 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'libros_existencia.pdf');
?>
