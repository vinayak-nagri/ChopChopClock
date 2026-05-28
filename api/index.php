<?php

$root = dirname(__DIR__);
$storagePath = '/tmp/laravel-storage';
$cachePath = '/tmp/laravel-cache';

$paths = [
    $storagePath,
    "{$storagePath}/app",
    "{$storagePath}/app/private",
    "{$storagePath}/app/public",
    "{$storagePath}/framework",
    "{$storagePath}/framework/cache",
    "{$storagePath}/framework/cache/data",
    "{$storagePath}/framework/sessions",
    "{$storagePath}/framework/testing",
    "{$storagePath}/framework/views",
    "{$storagePath}/logs",
    $cachePath,
];

foreach ($paths as $path) {
    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

$setEnv = static function (string $key, string $value): void {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
};

$setEnv('LARAVEL_STORAGE_PATH', $storagePath);
$setEnv('VIEW_COMPILED_PATH', "{$storagePath}/framework/views");
$setEnv('APP_CONFIG_CACHE', "{$cachePath}/config.php");
$setEnv('APP_EVENTS_CACHE', "{$cachePath}/events.php");
$setEnv('APP_PACKAGES_CACHE', "{$cachePath}/packages.php");
$setEnv('APP_ROUTES_CACHE', "{$cachePath}/routes.php");
$setEnv('APP_SERVICES_CACHE', "{$cachePath}/services.php");

$_SERVER['SCRIPT_FILENAME'] = "{$root}/public/index.php";
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require "{$root}/public/index.php";
