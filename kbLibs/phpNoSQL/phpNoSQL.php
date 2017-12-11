<?php

namespace phpNoSQL;

require_once ( __DIR__ . "/config.php" );
require_once (__DIR__."/table.php");

spl_autoload_register(function ($entity) {
    // Explode is faster than substr & strstr also more control
    $module = explode('\\',$entity,2);
    if ($module[0] !== 'phpNoSQL') {
        /**
         * Not a part of phpFastCache file
         * then we return here.
         */
        return;
    }

    $entity = str_replace('\\', '/', $module[1]);
    $path = __DIR__ . '/' . $entity . '.php';
    if (is_readable($path)) {
        require_once($path);
    }
});

