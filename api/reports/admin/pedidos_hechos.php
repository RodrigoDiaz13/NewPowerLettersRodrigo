<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluye la clase para el modelo de datos de pedidos.
require_once('../../models/data/pedido_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de Pedidos Realizados');

// Se instancia el modelo de Pedidos para obtener los datos.
$pedido = new PedidoData;

// Se obtiene la lista de pedidos realizados.
if ($dataPedidos = $pedido->getPedidosRealizados()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(200);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(20, 10, 'ID Pedido', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Usuario', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Dirección', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Estado', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Fecha', 1, 0, 'C', 1);
    $pdf->cell(0, 10, 'Detalles', 1, 1, 'C', 1);

    // Se establece la fuente para los datos.
    $pdf->setFont('Arial', '', 10);

    // Se recorren los registros fila por fila.
    foreach ($dataPedidos as $rowPedido) {
        // Se imprimen las celdas con los datos de los pedidos.
        $pdf->cell(20, 10, $rowPedido['id_pedido'], 1, 0);
        $pdf->cell(60, 10, $rowPedido['nombre_usuario'] . ' ' . $rowPedido['apellido_usuario'], 1, 0);
        $pdf->cell(60, 10, $rowPedido['direccion_pedido'], 1, 0);
        $pdf->cell(30, 10, $rowPedido['estado'], 1, 0);
        $pdf->cell(30, 10, date('d-m-Y', strtotime($rowPedido['fecha_pedido'])), 1, 0);
        $pdf->cell(0, 10, $rowPedido['detalles_pedido'], 1, 1);
    }
} else {
    $pdf->cell(0, 10, 'No hay pedidos para mostrar', 1, 1, 'C');
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'pedidos_realizados.pdf');
