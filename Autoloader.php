<?php
spl_autoload_register(function ($class) {
    $prefix = 'Employee11\\';
    $baseDir = __DIR__ . '/';

    if (strpos($class, $prefix) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            die("Autoloader: Class $class not found in path $file");
        }
    }
});
?>
