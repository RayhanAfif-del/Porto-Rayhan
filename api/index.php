<?php

// Pastikan direktori sementara di /tmp tersedia untuk Laravel (filesystem Vercel read-only)
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Forward request ke public/index.php Laravel
require __DIR__.'/../public/index.php';
