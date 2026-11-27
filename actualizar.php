<?php
/*
 * actualizar.php
 * UPDATE (lógica): procesa el formulario de editar.php.
 * Si el usuario subió una foto nueva, reemplaza el archivo anterior;
 * si no, conserva el nombre de archivo que ya tenía (foto_actual).
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!csrf_valido($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    die('Solicitud no válida (token CSRF). Vuelve a intentarlo desde el formulario.');
}

$id = (int) ($_POST['id'] ?? 0);

// Confirmar que el contacto existe antes de intentar actualizarlo
$stmt = $pdo->prepare('SELECT foto FROM contactos WHERE id = :id');
$stmt->execute(['id' => $id]);
$existente = $stmt->fetch();

if (!$existente) {
    header('Location: index.php');
    exit;
}

$nombre    = trim($_POST['nombre'] ?? '');
$apellido  = trim($_POST['apellido'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$email     = trim($_POST['email'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$notas     = trim($_POST['notas'] ?? '');

$errores = [];
if ($nombre === '' || mb_strlen($nombre) > 60) {
    $errores[] = 'El nombre es obligatorio (máximo 60 caracteres).';
}
if ($apellido === '' || mb_strlen($apellido) > 60) {
    $errores[] = 'El apellido es obligatorio (máximo 60 caracteres).';
}
if ($telefono === '' || !preg_match('/^[0-9+\s-]{7,20}$/', $telefono)) {
    $errores[] = 'El teléfono es obligatorio y debe tener entre 7 y 20 dígitos (puede incluir espacios, + o -).';
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El formato del correo electrónico no es válido.';
}

// La foto es opcional al editar: por default se conserva la que ya había
$nombreArchivo = trim($_POST['foto_actual'] ?? $existente['foto']);
$tiposPermitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tipoReal = mime_content_type($_FILES['foto']['tmp_name']);
    if (!isset($tiposPermitidos[$tipoReal])) {
        $errores[] = 'La foto debe ser una imagen JPG, PNG o WEBP.';
    } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        $errores[] = 'La foto no debe pesar más de 2 MB.';
    } else {
        $extension = $tiposPermitidos[$tipoReal];
        $nuevoArchivo = uniqid('contacto_', true) . '.' . $extension;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/archivos/' . $nuevoArchivo)) {
            $fotoAnterior = $nombreArchivo;
            $nombreArchivo = $nuevoArchivo;
            // Borra el archivo anterior para no acumular fotos huérfanas
            @unlink(__DIR__ . '/archivos/' . $fotoAnterior);
        } else {
            $errores[] = 'No se pudo guardar la nueva foto en el servidor.';
        }
    }
}

if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos_anteriores'] = compact('nombre', 'apellido', 'telefono', 'email', 'direccion', 'notas');
    header('Location: editar.php?id=' . $id);
    exit;
}

$sql = 'UPDATE contactos
        SET nombre = :nombre, apellido = :apellido, telefono = :telefono,
            foto = :foto, email = :email, direccion = :direccion, notas = :notas
        WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nombre'    => $nombre,
    'apellido'  => $apellido,
    'telefono'  => $telefono,
    'foto'      => $nombreArchivo,
    'email'     => $email !== '' ? $email : null,
    'direccion' => $direccion !== '' ? $direccion : null,
    'notas'     => $notas !== '' ? $notas : null,
    'id'        => $id,
]);

header('Location: index.php?msg=actualizado');
exit;
