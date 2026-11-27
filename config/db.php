<?php
/*
 * config/db.php
 * Conexión a la base de datos con PDO.
 *
 * IMPORTANTE: cambia estos 4 valores por los que te da tu hosting.
 * En InfinityFree los encuentras en el panel, sección "MySQL Databases":
 *   DB_HOST -> normalmente algo como sqlXXX.infinityfree.com
 *   DB_NAME -> nombre completo que te asigna (ej. if0_12345678_agenda)
 *   DB_USER -> igual al nombre de la base
 *   DB_PASS -> la contraseña que configuraste
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'agenda_contactos');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            // Que un error de SQL lance una excepción en vez de un warning silencioso
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Las filas se devuelven como arreglo asociativo (['nombre' => ...])
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Fuerza sentencias preparadas reales del servidor MySQL, no emuladas por PHP
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Requisito de seguridad: nunca mostrar el error real de SQL al usuario final
    error_log('Error de conexión a la base de datos: ' . $e->getMessage());
    die('No se pudo conectar con la base de datos. Intenta más tarde.');
}
