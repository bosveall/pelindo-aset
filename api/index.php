<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$storagePath = sys_get_temp_dir() . '/pelindo-aset/storage';
foreach ([
    $storagePath . '/app/private',
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
] as $directory) {
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException("Unable to create Laravel runtime directory: {$directory}");
    }
}

$appKey = getenv('APP_KEY') ?: '';
if ($appKey === '') {
    throw new RuntimeException('Set APP_KEY in the Vercel project environment variables.');
}

if ($appKey !== '' && !str_starts_with($appKey, 'base64:')) {
    putenv("APP_KEY=base64:{$appKey}");
    $_ENV['APP_KEY'] = "base64:{$appKey}";
    $_SERVER['APP_KEY'] = "base64:{$appKey}";
}

require __DIR__ . '/../vendor/autoload.php';

/** @var Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);
$app->handleRequest(Request::capture());
