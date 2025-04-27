<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

// Parámetros recibidos
$data = json_decode(file_get_contents('php://input'), true);
$amount = $data['amount'] ?? null;
$pagoId = $data['pago_id'] ?? null;

if (!$amount || !$pagoId || !isset($_SESSION['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Tus credenciales sandbox
$clientId = 'ATP4BHRGpdnC0lembbaZT8GOcOA0NJ0KYUvVXgLwZYU1fxNC2XsyC0KZT2J5fGbet7F7s-qiO9uKLL1O
';
$clientSecret = 'EHkXhOBMablcC3jN1lTVAYUn_sS0sPfRHQeF4Rm2qOz2zPOchQL9fMXfPMagcq1n9-R0QKQjj_h-329YAQUI_TU_CLIENT_SECRET';

// 1. Obtener access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Accept-Language: en_US",
]);
$tokenResult = curl_exec($ch);
if (!$tokenResult) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo obtener token']);
    exit;
}
$token = json_decode($tokenResult, true)['access_token'];
curl_close($ch);

// 2. Crear la orden
$ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $token",
]);

$redirectBase = 'http://localhost/alumno/pagos';

$body = json_encode([
    'intent' => 'CAPTURE',
    'purchase_units' => [[
        'amount' => [
            'currency_code' => 'MXN',
            'value' => number_format($amount, 2, '.', ''),
        ],
        'description' => "Pago ID #$pagoId"
    ]],
    'application_context' => [
        'return_url' => "$redirectBase?success=1&pago_id=$pagoId",
        'cancel_url' => "$redirectBase?cancel=1"
    ]
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

$response = curl_exec($ch);
if (!$response) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear la orden']);
    exit;
}
$data = json_decode($response, true);
curl_close($ch);

// Buscar la URL de aprobación
$redirectUrl = null;
foreach ($data['links'] as $link) {
    if ($link['rel'] === 'approve') {
        $redirectUrl = $link['href'];
        break;
    }
}

if ($redirectUrl) {
    echo json_encode([
        'redirect_url' => $redirectUrl,
        'order_id' => $data['id'] ?? null
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se encontró la URL de PayPal']);
}
