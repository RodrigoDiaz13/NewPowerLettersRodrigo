<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../reports/public/invoice.php');

// Se incluye la clase para el modelo de datos de pedidos.
require_once('../../models/data/pedido_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Factura de Compra');

// Se verifica si existe un valor para el ID del detalle del pedido, de lo contrario se muestra un mensaje.
if (isset($_SESSION['idPedido'])) {
    $pedido = new PedidoData;
    // Se establece el valor del ID del detalle del pedido.
    if ($data = $pedido->getDetallesPorId()) {
        
        // Información del pedido
        $pdf->setFont('Arial', 'B', 12);
        $pdf->cell(0, 10, 'Datos del pedido', 0, 1, 'C');
        $pdf->ln(10);

        $pdf->setFont('Arial', '', 12);
        $pdf->cell(0, 10, 'Fecha del pedido: ' . date('d/m/Y', strtotime($data[0]['fecha_pedido'])), 0, 1);
        $pdf->cell(0, 10, 'Direccion de envio: ' . $pdf->encodeString($data[0]['direccion_pedido']), 0, 1);
        $pdf->cell(0, 10, 'Estado: ' . $pdf->encodeString($data[0]['estado']), 0, 1);
        $pdf->ln(10);

        // Información del cliente
        $pdf->cell(0, 10, 'Cliente: ' . $pdf->encodeString($data[0]['nombre_usuario']), 0, 1);
        $pdf->ln(10);

        // Detalles del libro
        $pdf->setFillColor(0, 102, 204); // Azul oscuro
        $pdf->setTextColor(255, 255, 255); // Blanco
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(60, 10, 'Titulo', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Cantidad', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Precio (US$)', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Subtotal (US$)', 1, 1, 'C', 1);

        $pdf->setFillColor(255, 255, 255); // Blanco
        $pdf->setTextColor(0, 0, 0); // Negro
        $pdf->setFont('Arial', '', 11);
        $total = 0;
        foreach($data as $libro) {
            $subtotal = $libro['cantidad'] * $libro['precio'];
            $total += $subtotal;
            $pdf->cell(60, 10, $pdf->encodeString($libro['titulo']), 1, 0);
            $pdf->cell(30, 10, $pdf->encodeString($libro['cantidad']), 1, 0, 'C');
            $pdf->cell(30, 10, $pdf->encodeString(number_format($libro['precio'], 2)), 1, 0, 'R');
            $pdf->cell(30, 10, $pdf->encodeString(number_format($subtotal, 2)), 1, 1, 'R');
        }
        $pdf->ln(10);
        $pdf->setFont('Arial', 'B', 11);
        $pdf->cell(120, 10, 'Total', 1, 0, 'C', 1);
        $pdf->cell(30, 10, $pdf->encodeString(number_format($total, 2)), 1, 1, 'R');

        // Se envía el documento al navegador web.
        $pdf->output('I', 'factura_compra.pdf');
    } else {
        $pdf->startReport('Error');
        $pdf->setFillColor(255, 0, 0); // Rojo
        $pdf->setTextColor(255, 255, 255); // Blanco
        $pdf->cell(0, 10, $pdf->encodeString('Detalle del pedido no encontrado'), 1, 1, 'C', 1);
        $pdf->output('I', 'error.pdf');
    }
} else {
    $pdf->startReport('Error');
    $pdf->setFillColor(255, 0, 0); // Rojo
    $pdf->setTextColor(255, 255, 255); // Blanco
    $pdf->cell(0, 10, $pdf->encodeString('Debe iniciar un nuevo pedido'), 1, 1, 'C', 1);
    $pdf->output('I', 'error.pdf');
}
?>
