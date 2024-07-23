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
    $pdf->setFillColor(0, 102, 204); // Azul oscuro
    // Se establece el color del texto del encabezado.
    $pdf->setTextColor(255, 255, 255); // Blanco
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(100, 10, 'Título', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Existencias', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Precio (US$)', 1, 1, 'C', 1);

    // Se restablece el color del texto a negro para los datos.
    $pdf->setTextColor(0, 0, 0);
    // Se establece la fuente para los datos de los libros.
    $pdf->setFont('Arial', '', 11);

    // Variable para alternar el color de fondo de las filas.
    $fill = false;
    // Se recorren los registros fila por fila.
    foreach ($dataLibros as $rowLibro) {
        // Se alterna el color de fondo de las filas.
        $pdf->setFillColor($fill ? 240 : 255, $fill ? 255 : 255, $fill ? 255 : 255); // Blanco y gris muy claro alternados
        $fill = !$fill;   

        // Se imprimen las celdas con los datos de los libros.
        $pdf->cell(100, 10, $pdf->encodeString($rowLibro['titulo']), 1, 0, '', 1);
        $pdf->cell(40, 10, $rowLibro['existencias'], 1, 0, 'C', 1);
        $pdf->cell(40, 10, number_format($rowLibro['precio'], 2), 1, 1, 'C', 1);
    }
} else {
    // En caso de no haber registros, se imprime un mensaje.
    $pdf->setFillColor(255, 0, 0); // Rojo
    $pdf->setTextColor(255, 255, 255); // Blanco
    $pdf->cell(0, 10, $pdf->encodeString('No hay libros en existencia para mostrar'), 1, 1, 'C', 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'libros_existencia.pdf');
?>
