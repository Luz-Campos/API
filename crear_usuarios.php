<?php
// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// URL de la API y clave
$apiUrl = 'http://localhost:8080/api/products';
$apiKey = '7WRJ5IIDEL9SPJHS3F7USYACD1BQ8B8D';

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$contraseña = $_POST['contraseña']; 
$activo = isset($_POST['activo']) ? 1 : 0;

// Crear un nuevo cliente en formato XML
$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://localhost:8080/api/xlink">
    <customer>
        <firstname><![CDATA[$nombre]]></firstname>
        <lastname><![CDATA[$apellidos]]></lastname>
        <email><![CDATA[$email]]></email>
        <passwd><![CDATA[$contraseña]]></passwd>
        <active><![CDATA[$activo]]></active>
        <id_default_group><![CDATA[3]]></id_default_group> 
    </customer>
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
    echo "Error al crear el cliente: <br>";
    echo htmlentities($response);
} else {
    header("Location: usuarios.php");
    exit(); 
}

header("refresh:1;url=usuarios.php");
echo "<br>Redirigiendo a la lista de clientes...";
?>