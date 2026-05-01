<?php

declare(strict_types=1);

$config = require __DIR__ . '/config/config.php';

require_once __DIR__ . '/core/helpers.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Flash.php';
require_once __DIR__ . '/core/Validator.php';

spl_autoload_register(static function (string $class): void {
    $paths = [
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

Auth::bootstrap($config);
