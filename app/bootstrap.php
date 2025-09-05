<?php 
require_once '../app/config/config.php';
require_once '../app/helpers/url_helper.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/Mailer.php';


spl_autoload_register(function ($class) {
    $paths = [
        '../app/libraries/',
        '../app/models/',
        '../app/repositories/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
