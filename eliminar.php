<?php
/*
 * eliminar.php
 * DELETE: por GET solo muestra una pantalla de confirmación (nunca borra
 * con una petición GET); el borrado real ocurre únicamente cuando se envía
 * el formulario por POST, con el token CSRF validado.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        die('Solicitud no válida (token CSRF). Vuelve a intentarlo.');
    }

    $id = (int) ($_POST['id'] ?? 0);

    // Primero se obtiene el nombre de la foto para poder borrar el archivo físico
    $stmt = $pdo->prepare('SELECT foto FROM contactos WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $contacto = $stmt->fetch();

    if ($contacto) {
        $stmt = $pdo->prepare('DELETE FROM contactos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        @unlink(__DIR__ . '/archivos/' . $contacto['foto']);
    }

    header('Location: index.php?msg=eliminado');
    exit;
}

// ----- GET: pantalla de confirmación -----
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM contactos WHERE id = :id');
$stmt->execute(['id' => $id]);
$contacto = $stmt->fetch();

$titulo = 'Eliminar contacto';
include __DIR__ . '/includes/header.php';

if (!$contacto): ?>
    <div class="alerta alerta--error">No se encontró ningún contacto con ese id.</div>
    <a class="boton" href="index.php">← Volver al listado</a>
<?php else: ?>
    <div class="tarjeta tarjeta-confirmar">
        <img src="archivos/<?= h($contacto['foto']) ?>" alt="Foto de <?= h($contacto['nombre']) ?>" class="tarjeta-confirmar__foto">
        <h3>¿Eliminar a <?= h($contacto['nombre']) ?> <?= h($contacto['apellido']) ?>?</h3>
        <p>Esta acción no se puede deshacer. Se borrará el registro y su foto del servidor.</p>
        <form action="eliminar.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int) $contacto['id'] ?>">
            <div class="formulario__acciones">
                <button type="submit" class="boton boton--peligro">🗑 Sí, eliminar</button>
                <a href="ver.php?id=<?= (int) $contacto['id'] ?>" class="boton boton--secundario">Cancelar</a>
            </div>
        </form>
    </div>
<?php endif;

include __DIR__ . '/includes/footer.php';
