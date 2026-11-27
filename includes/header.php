<?php
/*
 * includes/header.php
 * Encabezado común a todas las páginas. Inicia la sesión (para mensajes,
 * errores de validación y el token CSRF) e imprime la cabecera HTML y el menú.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/helpers.php';

$titulo = $titulo ?? 'Agenda de Contactos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($titulo) ?> · Agenda de Contactos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="encabezado">
    <div class="encabezado__contenido">
        <span class="encabezado__icono">📇</span>
        <div>
            <h1>Agenda de Contactos</h1>
            <p>Programación Web · Mini Proyecto 2 · Unidad 4 · PHP + MySQL (PDO)</p>
        </div>
    </div>
</header>
<?php include __DIR__ . '/menu.php'; ?>
<main class="contenido">
    <h2 class="titulo-pagina"><?= h($titulo) ?></h2>

    <?php if ($mensaje = $_GET['msg'] ?? null): ?>
        <?php
        $mensajes = [
            'creado'      => '✔ Contacto agregado correctamente.',
            'actualizado' => '✔ Contacto actualizado correctamente.',
            'eliminado'   => '🗑 Contacto eliminado correctamente.',
        ];
        ?>
        <?php if (isset($mensajes[$mensaje])): ?>
            <div class="alerta alerta--exito"><?= $mensajes[$mensaje] ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['errores'])): ?>
        <div class="alerta alerta--error">
            <strong>Revisa lo siguiente:</strong>
            <ul>
                <?php foreach ($_SESSION['errores'] as $error): ?>
                    <li><?= h($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errores']); ?>
    <?php endif; ?>
