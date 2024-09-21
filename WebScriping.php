<?php
function obtenerFechaVencimientoAPI($dominio) {
    $apiKey = 'at_wgZN2oQ0C4DVRNLYFMyXkCumZi6Vl'; // Obtén una API key de WhoisXML
    $url = "https://www.whoisxmlapi.com/whoisserver/WhoisService?apiKey=$apiKey&domainName=$dominio&outputFormat=JSON";

    $respuesta = file_get_contents($url);
    $datos = json_decode($respuesta, true);
    if (isset($datos['WhoisRecord']['expiresDate'])) {

        $Vencimiento = (new DateTime($datos['WhoisRecord']['expiresDate']))->format('Y-m-d');
        return $Vencimiento;

    } 
    else {
        if (isset($datos['WhoisRecord']['registryData']['expiresDate'])){

            $Vencimiento = (new DateTime($datos['WhoisRecord']['registryData']['expiresDate']))->format('Y-m-d');
            return $Vencimiento;

         }
         else{return "No se pudo obtener la información del dominio.";}
        
    }
}



function obtenerVencimientoSSL($dominio) {

        $origen = "ssl://{$dominio}:443";
    
        // Establecer contexto de conexión
        $context = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => true,
            ]
        ]);
    
        // Abrir la conexión de flujo de datos
        $client = stream_socket_client($origen, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        
        // Verificar si la conexión fue exitosa
        if (!$client) {
            echo "Error al conectar: $errstr ($errno)\n";
            return null;
        }
    
        // Obtener el certificado del servidor
        $params = stream_context_get_params($client);
        $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
    
        // Verificar si se obtuvo el certificado
        if ($cert && isset($cert['validTo_time_t'])) {
            // Convertir el tiempo de expiración a una fecha legible en UTC
            $date = new DateTime('@' . $cert['validTo_time_t']);  // Crear objeto DateTime desde el timestamp UNIX
            $date->setTimezone(new DateTimeZone('UTC'));           // Establecer la zona horaria en UTC
            return $date->format('Y-m-d');                   // Formatear la fecha en formato legible
        } else {
            return "No se pudo obtener la fecha de expiración del certificado SSL.";
        }
}
$dominios = $_POST['Dominio'];
$SSl = obtenerVencimientoSSL($dominios);
$Api = obtenerFechaVencimientoAPI($dominios);

echo json_encode(['VencimientoSSL' => $SSl, 'VencimientoDominio' => $Api]);

?>
