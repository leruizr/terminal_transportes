<?php
// app/includes/env.php
// Mini-loader de variables de entorno desde un archivo .env
// (equivalente sencillo de python-dotenv, sin dependencias externas).

function cargar_env($ruta) {
    if (!is_readable($ruta)) {
        die('No se encontró el archivo .env en: ' . htmlspecialchars($ruta)
            . '. Copia .env.example como .env y configura las credenciales.');
    }

    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $linea = trim($linea);
        if ($linea === '' || $linea[0] === '#') continue;
        if (strpos($linea, '=') === false) continue;

        list($clave, $valor) = explode('=', $linea, 2);
        $clave = trim($clave);
        $valor = trim($valor);

        // Quitar comillas envolventes si las hay
        if (strlen($valor) >= 2) {
            $primero = $valor[0];
            $ultimo = $valor[strlen($valor) - 1];
            if (($primero === '"' && $ultimo === '"') || ($primero === "'" && $ultimo === "'")) {
                $valor = substr($valor, 1, -1);
            }
        }

        $_ENV[$clave] = $valor;
        putenv("$clave=$valor");
    }
}

cargar_env(__DIR__ . '/../../.env');
