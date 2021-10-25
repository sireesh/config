<?php

spl_autoload_register ( function ($class) {

    $sources = array(__DIR__."/../lib/$class.php", __DIR__."/../lib/Parser/$class.php" );

    foreach ($sources as $source) {
        if (file_exists($source)) {
            require_once $source;
        }
    }

});