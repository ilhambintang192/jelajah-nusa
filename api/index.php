<?php

/**
 * Entry point untuk deployment di Vercel Serverless Function.
 */

// Sembunyikan pesan deprecation notices di lingkungan serverless PHP 8.4+
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Siapkan folder sementara yang writable di /tmp untuk Laravel view & cache
$writableDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($writableDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Teruskan ke public/index.php
require __DIR__ . '/../public/index.php';
