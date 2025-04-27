<?php
// Cargar DOMPDF manualmente
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php'; // Ajusta tu ruta si es necesario

use Dompdf\Dompdf;
use Dompdf\Options;

// --- Datos de ejemplo que llenarás dinámicamente ---
$fechaGeneracion = date('d/m/Y');
$mesReporte = 'Abril 2025';

$pagoSemanal = [
    ['periodo' => '01-07', 'metodo' => 'Efectivo', 'cantidad' => 30162.00],
    ['periodo' => '01-07', 'metodo' => 'Transferencia', 'cantidad' => 2700.00],
];

$pagosPorGrupo = [
    [
        'nombre' => 'Segundo Cuatrimestre Universidad Enero-Abril 2025',
        'pagos' => [
            ['estudiante' => 'Daniel Eduardo', 'fecha' => 'April 7, 2025', 'monto' => 1350, 'metodo' => 'Efectivo'],
            ['estudiante' => 'Arturo Castillero', 'fecha' => 'April 7, 2025', 'monto' => 1350, 'metodo' => 'Transferencia'],
        ]
    ],
    [
        'nombre' => 'Universidad Tesis Enero-Abril 2025',
        'pagos' => [
            ['estudiante' => 'Jorge Emiliano', 'fecha' => 'April 6, 2025', 'monto' => 1350, 'metodo' => 'Transferencia'],
            ['estudiante' => 'Michelle Alexandra', 'fecha' => 'April 7, 2025', 'monto' => 1350, 'metodo' => 'Efectivo'],
        ]
    ]
];

// --- Iniciar Dompdf ---
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Para cargar imágenes externas si pones logo

$dompdf = new Dompdf($options);

// --- Construir HTML ---
ob_start();
?>
<style>
body { font-family: Arial, sans-serif; }
h1, h2, h3 { text-align: center; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
th, td { border: 1px solid #000; padding: 5px; font-size: 12px; text-align: center; }
thead { background-color: #f2f2f2; }
</style>

<h1>Reporte General de Ingresos</h1>
<h2><?= htmlspecialchars($mesReporte) ?></h2>
<p>Fecha de generación: <?= htmlspecialchars($fechaGeneracion) ?></p>

<h3>Resumen Semanal</h3>
<table>
    <thead>
        <tr>
            <th>Fechas</th>
            <th>Concepto</th>
            <th>Método de Pago</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pagoSemanal as $pago): ?>
        <tr>
            <td><?= htmlspecialchars($pago['periodo']) ?></td>
            <td>Pagos Realizados</td>
            <td><?= htmlspecialchars($pago['metodo']) ?></td>
            <td>$<?= number_format($pago['cantidad'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php foreach ($pagosPorGrupo as $grupo): ?>
    <h3><?= htmlspecialchars($grupo['nombre']) ?></h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                <th>Fecha</th>
                <th>Mensualidad</th>
                <th>Método de Pago</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($grupo['pagos'] as $i => $pago): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($pago['estudiante']) ?></td>
                <td><?= htmlspecialchars($pago['fecha']) ?></td>
                <td>$<?= number_format($pago['monto'], 2) ?></td>
                <td><?= htmlspecialchars($pago['metodo']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

<?php
$html = ob_get_clean();

// --- Generar PDF ---
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_general_ingresos.pdf", ["Attachment" => false]); // false para mostrarlo en navegador
?>
