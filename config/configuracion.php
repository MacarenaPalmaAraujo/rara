<?php
// config/configuracion.php
// Configuración general del sitio.
// index.php carga este archivo antes de armar la página.

$pageTitle = 'Feriando - Agenda y Descubrimiento de Ferias';
$institutionCopyright = 'E.T. N°26 "Confederación Suiza"';

// Ruta base del proyecto. Se calcula automáticamente para que funcione
// tanto en http://localhost/4.15-2026-expotech-feriando/ como en otro subdirectorio.
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($basePath === '' || $basePath === '.') {
    $basePath = '';
}

// Valores útiles para las vistas (por si se usan en el futuro).
$siteName = 'Feriando';
$siteTagline = 'Feria que ves, feria que vas';
