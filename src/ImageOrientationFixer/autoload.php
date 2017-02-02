<?php

spl_autoload_register(function($class) {
    $class = str_replace('DSentker\\ImageOrientationFixer', '', $class);
    $path = sprintf('%s%s%s.php', __DIR__, DIRECTORY_SEPARATOR, $class);
    if(file_exists($path)) {
        require $path;
    }
});