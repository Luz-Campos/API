<?php
// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$apiUrl = 'http://localhost:8080/api/products';
$apiKey = '7WRJ5IIDEL9SPJHS3F7USYACD1BQ8B8D';

// Obtener los datos del formulario
$name = $_POST['name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$reference = $_POST['reference'];
$category_id = $_POST['category_id'];
$active = isset($_POST['active']) ? 1 : 0; // Activo si se marca

// Crear un nuevo producto en formato XML
$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://localhost:8080/api/xlink">
    <product>
        <name>
            <language id="1"><![CDATA[$name]]></language>
        </name>
        <price><![CDATA[$price]]></price>
        <reference><![CDATA[$reference]]></reference>
        <id_category_default><![CDATA[$category_id]]></id_category_default>
        <active><![CDATA[$active]]></active>
        <associations>
            <stock_availables>
                <stock_available>
                    <quantity><![CDATA[$quantity]]></quantity>
                </stock_available>
            </stock_availables>
        </associations>
    </product>
</prestashop>
XML;

// Inicializar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

// Ejecutar la solicitud
$response = curl_exec($ch);

// Manejar errores de cURL
if (curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Cerrar cURL
curl_close($ch);

// Mostrar la respuesta de la API
if (strpos($response, '<errors>') !== false) {
    echo "Error al crear el producto: <br>";
    echo htmlentities($response);
} else {
    // Al ser exitoso, redirigir a la lista de productos
    header("Location: productos.php");
    exit(); // Asegúrate de llamar a exit() después de redirigir
}
?>
