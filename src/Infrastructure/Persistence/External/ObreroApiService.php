<?php
namespace Infrastructure\Persistence\External;

use Domain\Services\ObreroApiServiceInterface;
use Domain\Entities\Obrero;

class ObreroApiService implements ObreroApiServiceInterface {
    
    // 1. URL base de la API externa
    private $apiUrl = API_URL;

    public function consultarPorCedula(string $cedula, string $token): ?Obrero {
        
        // 2. Construir la URL completa con los parámetros (query string)
        $queryParams = http_build_query([
            'pin'   => $cedula, // La API espera 'pin'
            'token' => $token   // La API espera 'token'
        ]);
        $url = $this->apiUrl . '?' . $queryParams;

        // 3. Inicializar cURL para hacer la petición a la API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // Timeout de conexión
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);        // Timeout total

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // 4. Validar la respuesta de la API
        if ($curlError) {
            error_log("Error de cURL conectando a $url: " . $curlError);
            return null; // Hubo un error de red
        }

        // Si el token es inválido o no se encuentra el PIN,
        // la API debería devolver un 401, 403, 404, etc.
        if ($httpCode !== 200) {
            error_log("API devolvió HTTP $httpCode para $url. Respuesta: $response");
            return null;
        }

        if ($response === false) {
            return null; // No se obtuvo respuesta
        }

        // 5. Decodificar la respuesta JSON de la API
        $jsonData = json_decode($response, true);
        
        if (!isset($jsonData[0]['data']) || !is_array($jsonData[0]['data']) || empty($jsonData[0]['data'])) {
            // No se encontró o la respuesta JSON no es la esperada
            error_log("Respuesta JSON de API no válida o vacía: $response");
            return null;
        }

        // 7. Recorrer los resultados y buscar el que coincida con el PIN
        $foundObreroData = null;
        foreach ($jsonData[0]['data'] as $item) {
            if (isset($item['pin']) && $item['pin'] === $cedula) {
                $foundObreroData = $item;
                break; // Encontramos el obrero, salimos del bucle
            }
        }

        // 8. Mapear la respuesta al objeto Obrero si se encontró
        if ($foundObreroData && isset($foundObreroData['profile_id'], $foundObreroData['fullname'], $foundObreroData['pin'], $foundObreroData['type_str'])) {
            return new Obrero(
                (int)$foundObreroData['profile_id'],
                $foundObreroData['firstnames'],
                $foundObreroData['lastnames'],
                $foundObreroData['pin'],
                implode(",", $foundObreroData['type_str']),
                null
            );
        } else {
            error_log("Respuesta JSON de API no tiene los campos esperados.");
            return null;
        }

    }
    
} 