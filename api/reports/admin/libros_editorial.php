<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se incluye la clase para el modelo de datos de libros.
require_once('../../models/data/libros_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para el ID de la editorial, de lo contrario se muestra un mensaje.
if (isset($_GET['idEditorial'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/editoriales_data.php'); // Asegúrate de tener esta clase para obtener la editorial
    $libros = new LibroData;
    $editorial = new EditorialData;

    // Se establece el valor del ID de la editorial.
    if ($editorial->setId($_GET['idEditorial'])) {
        // Se verifica si la editorial existe, de lo contrario se muestra un mensaje.
        if ($rowEditorial = $editorial->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Listado de libros en la editorial ' . $rowEditorial['nombre']);
            
            // Se obtienen los libros para la editorial especificada.
            if ($dataLibros = $libros->getLibrosPorEditorial($_GET['idEditorial'])) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(225);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(60, 10, 'Título', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Autor', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Precio (US$)', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Existencias', 1, 1, 'C', 1);
                
                // Se establece la fuente para los datos de los libros.
                $pdf->setFont('Arial', '', 11);
                
                // Se recorren los registros fila por fila.
                foreach ($dataLibros as $rowLibros) {
                    // Se imprimen las celdas con los datos de los libros.
                    $pdf->cell(60, 10, $pdf->encodeString($rowLibros['titulo']), 1, 0);
                    $pdf->cell(40, 10, $pdf->encodeString($rowLibros['autor']), 1, 0);
                    $pdf->cell(30, 10, number_format($rowLibros['precio'], 2), 1, 0, 'R');
                    $pdf->cell(30, 10, $rowLibros['existencias'], 1, 1, 'C');
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay libros para esta editorial'), 1, 1, 'C');
            }

            // Se envía el documento al navegador web.
            $pdf->output('I', 'libros_por_editorial.pdf');
        } else {
            $pdf->startReport('Error');
            $pdf->cell(0, 10, $pdf->encodeString('Editorial inexistente'), 1, 1, 'C');
            $pdf->output('I', 'error.pdf');
        }
    } else {
        $pdf->startReport('Error');
        $pdf->cell(0, 10, $pdf->encodeString('ID de editorial incorrecto'), 1, 1, 'C');
        $pdf->output('I', 'error.pdf');
    }
} else {
    $pdf->startReport('Error');
    $pdf->cell(0, 10, $pdf->encodeString('Debe seleccionar una editorial'), 1, 1, 'C');
    $pdf->output('I', 'error.pdf');
}
?>
