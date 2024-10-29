<?php
// eliminar_producto.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$apiUrl = 'http://localhost:8080/api/products'; // Cambia esto si es necesario
$apiKey = '7WRJ5IIDEL9SPJHS3F7USYACD1BQ8B8D'; // Cambia esto si es necesario

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . "/$productId?ws_key=$apiKey");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    header("Location: productos.php");
    exit;
} else {
    echo "No se ha proporcionado un ID de producto.";
    exit;
}
?>