<?php
/*
 * includes/helpers.php
 * Funciones pequeñas reutilizadas por varias páginas: generación y
 * verificación del token CSRF, y un envoltorio corto para htmlspecialchars().
 */

// Devuelve el token CSRF de la sesión actual, generando uno nuevo si no existe
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Compara el token recibido del formulario contra el de la sesión
function csrf_valido(?string $tokenRecibido): bool
{
    return isset($_SESSION['csrf_token'], $tokenRecibido)
        && hash_equals($_SESSION['csrf_token'], $tokenRecibido);
}

// Atajo para no escribir htmlspecialchars(..., ENT_QUOTES, 'UTF-8') en todo el proyecto
function h(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}
