<?php
/*
 * includes/menu.php
 * Menú de navegación común, con un formulario de búsqueda que envía por GET
 * a index.php (así la búsqueda también se puede compartir como link).
 */
$paginaActual = basename($_SERVER['PHP_SELF']);
$qActual = $_GET['q'] ?? '';
?>
<nav class="menu">
    <div class="menu__contenido">
        <ul>
            <li><a href="index.php" class="<?= $paginaActual === 'index.php' ? 'activo' : '' ?>">📋 Contactos</a></li>
            <li><a href="crear.php" class="<?= $paginaActual === 'crear.php' ? 'activo' : '' ?>">➕ Nuevo contacto</a></li>
        </ul>
        <form class="menu__busqueda" action="index.php" method="get">
            <input type="search" name="q" placeholder="Buscar por nombre, apellido o teléfono…" value="<?= h($qActual) ?>">
            <button type="submit">🔍</button>
        </form>
    </div>
</nav>
