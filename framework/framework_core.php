<?php

    /*
    requires the core framework classes
    */
    require_once FRAMEWORK . '/classes/Controller.class.php';
    require_once FRAMEWORK . '/classes/Router.class.php';
    require_once FRAMEWORK . '/classes/Model.class.php';
    require_once FRAMEWORK . '/classes/View.class.php';

    /*
    load any files in the function directory
    */
    $function_dir = FRAMEWORK . '/functions/';
    if ( is_dir($function_dir) )
    {
        $function_files = glob($function_dir . '*.php');
        foreach ( $function_files as $function_file)
        {
            require_once $function_file;
        }
    }

    /*
    if autoloading is turned on and the autoload function
    file exists then load it
    */
    if (AUTOLOAD && file_exists(FRAMEWORK . '/autoload/autoload.php'))
    {
        require_once FRAMEWORK . '/autoload/autoload.php';
    }
