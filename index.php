<?php
// Punto de entrada de Feriando.
// Junta las partes de la página y carga los estilos y el programa principal.
require_once __DIR__ . '/config/configuracion.php';
require_once __DIR__ . '/partes/cabeza.php';
?>
<body class="min-h-screen flex flex-col justify-between selection:bg-amber-400 selection:text-slate-900">
    <?php require __DIR__ . '/partes/menu.php'; ?>

    <?php require __DIR__ . '/partes/pagina_principal.php'; ?>

    <?php require __DIR__ . '/partes/formularios/emprendedor.php'; ?>
    <?php require __DIR__ . '/partes/formularios/feria.php'; ?>
    <?php require __DIR__ . '/partes/formularios/aviso.php'; ?>

    <?php require __DIR__ . '/partes/ventanas.php'; ?>
    <?php require __DIR__ . '/partes/pie.php'; ?>
