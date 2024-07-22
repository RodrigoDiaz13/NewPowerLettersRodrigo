<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluye la clase para el modelo de datos de pedidos.
require_once('../../models/data/pedido_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport2('Reporte de Pedidos Realizados');

// Se instancia el modelo de Pedidos para obtener los datos.
$pedido = new PedidoData;

// Se obtiene la lista de pedidos realizados.
if ($dataPedidos = $pedido->getPedidosRealizados()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(0, 102, 204); // Azul oscuro
    // Se establece el color del texto del encabezado.
    $pdf->setTextColor(255, 255, 255); // Blanco
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(20, 10, 'ID Pedido', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Usuario', 1, 0, 'C', 1); // Ajuste de ancho
    $pdf->cell(50, 10, 'Direccion', 1, 0, 'C', 1); // Ajuste de ancho
    $pdf->cell(30, 10, 'Estado', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Fecha', 1, 0, 'C', 1);
    $pdf->cell(70, 10, 'Detalles', 1, 1, 'C', 1); // Ajuste de ancho

    // Se establece la fuente para los datos.
    $pdf->setFont('Arial', '', 10);
    // Se restablece el color del texto a negro.
    $pdf->setTextColor(0, 0, 0);

    // Variable para alternar el color de fondo de las filas.
    $fill = false;
    // Se recorren los registros fila por fila.
    foreach ($dataPedidos as $rowPedido) {
        // Se alterna el color de fondo de las filas.
        $pdf->setFillColor($fill ? 230 : 255, $fill ? 240 : 255, $fill ? 255 : 255); // Azul claro y blanco alternados
        $fill = !$fill;

        // Se imprimen las celdas con los datos de los pedidos.
        $pdf->cell(20, 10, $rowPedido['id_pedido'], 1, 0, 'C', 1);
        $pdf->cell(40, 10, $rowPedido['nombre_usuario'] . ' ' . $rowPedido['apellido_usuario'], 1, 0, 'C', 1);
        $pdf->cell(50, 10, $rowPedido['direccion_pedido'], 1, 0, 'C', 1);
        $pdf->cell(30, 10, $rowPedido['estado'], 1, 0, 'C', 1);
        $pdf->cell(30, 10, date('d-m-Y', strtotime($rowPedido['fecha_pedido'])), 1, 0, 'C', 1);
        $pdf->cell(70, 10, $rowPedido['detalles_pedido'], 1, 1, 'C', 1);
    }
} else {
    // En caso de no haber registros, se genera un reporte de error.
    $pdf->startReport('Error');
    $pdf->cell(0, 10, 'No hay pedidos para mostrar', 1, 1, 'C');
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'pedidos_realizados.pdf');
?>
