<?php
/**
 * Redirect otomatis ke folder /public dengan aman
 * Digunakan untuk lingkungan lokal (Laragon / XAMPP)
 */

// Tentukan target folder
$target = __DIR__ . '/public';

// Pastikan folder public ada
if (!is_dir($target)) {
    http_response_code(500);
    echo "<h2 style='color:red;'>Error:</h2> Folder <b>/public</b> tidak ditemukan.";
    exit;
}

// Buat URL base otomatis (agar fleksibel di berbagai host)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'];
$path   = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

$redirectUrl = $scheme . '://' . $host . $path . '/public/';

// Lakukan redirect permanen (status 302 agar aman untuk dev)
header('Location: ' . $redirectUrl, true, 302);
exit;
