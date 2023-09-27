<?php

namespace SB\Site;

/**
 * Автозагрузчик классов
 */
spl_autoload_register(function ($class) {
    $classDir      = '/classes/';
    $fileExtension = '.php';
    if (strpos($class, __NAMESPACE__ . '\\') !== false) {
        $class     = str_replace(__NAMESPACE__ . '\\', '', $class);
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $filePath  = __DIR__ . $classDir . $className . $fileExtension;

        if (file_exists($filePath)) {
            include $filePath;
        }
    }
});
