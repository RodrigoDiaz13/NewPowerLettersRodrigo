<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se incluye la clase para el modelo de datos de libros.
require_once('../../models/data/libros_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para el ID del libro, de lo contrario se muestra un mensaje.
if (isset($_GET['id_libro'])) {
    // Se instancian las entidades correspondientes.
    $libros = new LibroData;
    // Se establece el valor del ID del libro, de lo contrario se muestra un mensaje.
    if ($libros->setId($_GET['id_libro'])) {
        // Se verifica si el libro existe, de lo contrario se muestra un mensaje.
        if ($rowLibro = $libros->getReporteLibros($_GET['id_libro'])) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Detalles del Libro');
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(225);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Arial', 'B', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(20, 10, $pdf->encodeString('ID Libro'), 1, 0, 'C', 1);
            $pdf->cell(40, 10, $pdf->encodeString('Título'), 1, 0, 'C', 1);
            $pdf->cell(30, 10, $pdf->encodeString('Autor'), 1, 0, 'C', 1);
            $pdf->cell(30, 10, $pdf->encodeString('Editorial'), 1, 0, 'C', 1);
            $pdf->cell(30, 10, $pdf->encodeString('Género'), 1, 0, 'C', 1);
            $pdf->cell(30, 10, $pdf->encodeString('Clasificación'), 1, 0, 'C', 1);
            $pdf->cell(20, 10, $pdf->encodeString('Existencias'), 1, 0, 'C', 1);
            $pdf->cell(30, 10, $pdf->encodeString('Precio (US$)'), 1, 1, 'C', 1);

            // Se establece la fuente para los datos del libro.
            $pdf->setFont('Arial', '', 11);

            // Se imprimen las celdas con los datos del libro.
            foreach ($rowLibro as $row) {
                $pdf->cell(20, 10, $row['id_libro'], 1, 0);
                $pdf->cell(40, 10, $pdf->encodeString($row['titulo']), 1, 0);
                $pdf->cell(30, 10, $pdf->encodeString($row['autor']), 1, 0);
                $pdf->cell(30, 10, $pdf->encodeString($row['editorial']), 1, 0);
                $pdf->cell(30, 10, $pdf->encodeString($row['genero']), 1, 0);
                $pdf->cell(30, 10, $pdf->encodeString($row['clasificacion']), 1, 0);
                $pdf->cell(20, 10, $row['existencias'], 1, 0);
                $pdf->cell(30, 10, number_format($row['precio'], 2), 1, 1, 'R');
            }

            // Se envía el documento al navegador web.
            $pdf->output('I', 'detalles_libro.pdf');
        } else {
            $pdf->startReport('Error');
            $pdf->cell(0, 10, $pdf->encodeString('Libro inexistente'), 1, 1, 'C');
            $pdf->output('I', 'error.pdf');
        }
    } else {
        $pdf->startReport('Error');
        $pdf->cell(0, 10, $pdf->encodeString('ID de libro incorrecto'), 1, 1, 'C');
        $pdf->output('I', 'error.pdf');
    }
} else {
    $pdf->startReport('Error');
    $pdf->cell(0, 10, $pdf->encodeString('Debe seleccionar un libro'), 1, 1, 'C');
    $pdf->output('I', 'error.pdf');
}
