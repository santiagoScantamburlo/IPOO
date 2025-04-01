<?php

spl_autoload_register(function ($className) {
    // Project-specific namespace prefix
    $prefix = 'Ipoo\\';

    // Base directory for the namespace prefix
    $baseDir = __DIR__ . '/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relativeClass = substr($className, $len);

    // Replace namespace separators with directory separators
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
