<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../reports/public/invoice.php');

// Se incluye la clase para el modelo de datos de pedidos.
require_once('../../models/data/pedido_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para el ID del detalle del pedido, de lo contrario se muestra un mensaje.
if (isset($_GET['idDetalle'])) {
    $pedido = new PedidoData;
    
    // Se establece el valor del ID del detalle del pedido.
    if ($data = $pedido->getDetallesPorId($_GET['idDetalle'])) {
        // Se inicia el reporte con el encabezado del documento.
        $pdf->startReport('Factura de Compra');

        // Información del pedido
        $pdf->setFont('Arial', 'B', 12);
        $pdf->cell(0, 10, 'Factura de Compra', 0, 1, 'C');
        $pdf->ln(10);

        $pdf->setFont('Arial', '', 12);
        $pdf->cell(0, 10, 'Fecha del Pedido: ' . date('d/m/Y', strtotime($data['fecha_pedido'])), 0, 1);
        $pdf->cell(0, 10, 'Dirección de Envío: ' . $pdf->encodeString($data['direccion_pedido']), 0, 1);
        $pdf->cell(0, 10, 'Estado: ' . $pdf->encodeString($data['estado']), 0, 1);
        $pdf->ln(10);

        // Información del cliente
        $pdf->cell(0, 10, 'Cliente: ' . $pdf->encodeString($data['nombre_usuario']), 0, 1);
        $pdf->ln(10);

        // Detalles del libro
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(60, 10, 'Título', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Cantidad', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Precio (US$)', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Subtotal (US$)', 1, 1, 'C', 1);

        $pdf->setFont('Arial', '', 11);
        $subtotal = $data['cantidad'] * $data['precio'];
        $pdf->cell(60, 10, $pdf->encodeString($data['titulo']), 1, 0);
        $pdf->cell(30, 10, $data['cantidad'], 1, 0, 'C');
        $pdf->cell(30, 10, number_format($data['precio'], 2), 1, 0, 'R');
        $pdf->cell(30, 10, number_format($subtotal, 2), 1, 1, 'R');

        $pdf->ln(10);
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(120, 10, 'Total', 1, 0, 'C', 1);
        $pdf->cell(30, 10, number_format($subtotal, 2), 1, 1, 'R');

        // Se envía el documento al navegador web.
        $pdf->output('I', 'factura_compra.pdf');
    } else {
        $pdf->startReport('Error');
        $pdf->cell(0, 10, $pdf->encodeString('Detalle del pedido no encontrado'), 1, 1, 'C');
        $pdf->output('I', 'error.pdf');
    }
} else {
    $pdf->startReport('Error');
    $pdf->cell(0, 10, $pdf->encodeString('Debe seleccionar un detalle de pedido'), 1, 1, 'C');
    $pdf->output('I', 'error.pdf');
}
?>
