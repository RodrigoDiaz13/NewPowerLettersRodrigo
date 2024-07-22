<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se incluye la clase para el modelo de datos de usuario.
require_once('../../models/data/usuario_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se instancian las entidades correspondientes.
$usuario = new UsuarioData;

// Se obtiene la lista de usuario registrados.
if ($datausuario = $usuario->getClientesRegistrados()) {
    // Se inicia el reporte con el encabezado del documento.
    $pdf->startReport2('Listado de usuario Registrados');
    
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(0, 102, 204); // Azul oscuro
    // Se establece el color del texto del encabezado.
    $pdf->setTextColor(255, 255, 255); // Blanco
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(10, 10, 'ID Cliente', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(50, 10, 'Correo', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Telefono', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Direccion', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Fecha Registro', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los usuario.
    $pdf->setFont('Arial', '', 11);
    // Se restablece el color del texto a negro.
    $pdf->setTextColor(0, 0, 0);

    // Variable para alternar el color de fondo de las filas.
    $fill = false;
    // Se recorren los registros fila por fila.
    foreach ($datausuario as $rowUsuario) {
        // Se alterna el color de fondo de las filas.
        $pdf->setFillColor($fill ? 230 : 255, $fill ? 240 : 255, $fill ? 255 : 255); // Azul claro y blanco alternados
        $fill = !$fill;

        // Se imprimen las celdas con los datos del cliente.
        $pdf->cell(10, 10, $rowUsuario['id_usuario'], 1, 0, 'C', 1);
        $pdf->cell(40, 10, $pdf->encodeString($rowUsuario['nombre_usuario']), 1, 0, 'C', 1);
        $pdf->cell(30, 10, $pdf->encodeString($rowUsuario['apellido_usuario']), 1, 0, 'C', 1);
        $pdf->cell(50, 10, $pdf->encodeString($rowUsuario['correo_usuario']), 1, 0, 'C', 1);
        $pdf->cell(30, 10, $rowUsuario['telefono_usuario'], 1, 0, 'C', 1);
        $pdf->cell(60, 10, $pdf->encodeString($rowUsuario['direccion_usuario']), 1, 0, 'C', 1);
        $pdf->cell(30, 10, date('d/m/Y', strtotime($rowUsuario['fecha_registro'])), 1, 1, 'C', 1);
    }

    // Se envía el documento al navegador web.
    $pdf->output('I', 'usuario_registrados.pdf');
} else {
    // En caso de no haber registros, se genera un reporte de error.
    $pdf->startReport('Error');
    $pdf->cell(0, 10, $pdf->encodeString('No hay usuario registrados para mostrar'), 1, 1, 'C');
    $pdf->output('I', 'error.pdf');
}
?>
