<?php
/*
 * guardar.php
 * CREATE (lógica): procesa el formulario de crear.php.
 * Orden: solo acepta POST -> valida el token CSRF -> valida los campos en el
 * servidor -> sube la foto -> inserta con PDO usando una sentencia preparada.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: crear.php');
    exit;
}

if (!csrf_valido($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    die('Solicitud no válida (token CSRF). Vuelve a intentarlo desde el formulario.');
}

// ---------- 1. Validación de los campos de texto en el servidor ----------
// (la validación con required/type/pattern del HTML5 ya ocurrió en el navegador;
//  esta es la segunda barrera, obligatoria, porque el cliente se puede evadir)
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

// ---------- 2. Validación y subida de la foto ----------
$nombreArchivo = null;
$tiposPermitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
    $errores[] = 'La foto del contacto es obligatoria.';
} elseif ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    $errores[] = 'Ocurrió un problema al subir la foto. Inténtalo de nuevo.';
} else {
    $tipoReal = mime_content_type($_FILES['foto']['tmp_name']); // tipo real del archivo, no el que dice el navegador
    if (!isset($tiposPermitidos[$tipoReal])) {
        $errores[] = 'La foto debe ser una imagen JPG, PNG o WEBP.';
    } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        $errores[] = 'La foto no debe pesar más de 2 MB.';
    } else {
        $extension = $tiposPermitidos[$tipoReal];
        $nombreArchivo = uniqid('contacto_', true) . '.' . $extension; // nombre único, nunca el que mandó el usuario
        $destino = __DIR__ . '/archivos/' . $nombreArchivo;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $errores[] = 'No se pudo guardar la foto en el servidor.';
        }
    }
}

// ---------- 3. Si hubo errores, regresar al formulario sin perder lo escrito ----------
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos_anteriores'] = compact('nombre', 'apellido', 'telefono', 'email', 'direccion', 'notas');
    header('Location: crear.php');
    exit;
}

// ---------- 4. Insertar con PDO usando una sentencia preparada ----------
$sql = 'INSERT INTO contactos (nombre, apellido, telefono, foto, email, direccion, notas)
        VALUES (:nombre, :apellido, :telefono, :foto, :email, :direccion, :notas)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nombre'    => $nombre,
    'apellido'  => $apellido,
    'telefono'  => $telefono,
    'foto'      => $nombreArchivo,
    'email'     => $email !== '' ? $email : null,
    'direccion' => $direccion !== '' ? $direccion : null,
    'notas'     => $notas !== '' ? $notas : null,
]);

header('Location: index.php?msg=creado');
exit;
