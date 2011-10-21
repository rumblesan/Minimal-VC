<?php

    /*
    BASE is the directory that the framework is installed to
    this is removed from the request URL to get the correct path
    for example, if it were installed to :-
        http://foo.com/bar/
    then BASE need to be set to bar
    
    otherwise it needs to be set to a single slash  '/'
    */
    define('BASE',  '/');

    /*
    Paths to framework folder and APP folder
    */
    define('ROOT',      dirname(dirname(dirname(__FILE__))));
    define('FRAMEWORK', ROOT . '/framework/');
    define('APP',       ROOT . '/app/');

    /*
    Paths to app controllers and views
    */
    define('CPATH', APP . 'controllers/');
    define('MPATH', APP . 'models/');
    define('VPATH', APP . 'views/');

    /*
    Turn autoloading on or off
    */
    define('AUTOLOAD', True);

    /*
    Load core file
    */
    require_once FRAMEWORK . 'framework_core.php';

    /*
    Load app config files if it exists
    */
    $cfg_dir = APP . 'cfg/';
    if ( is_dir($cfg_dir) )
    {
        $cfg_files = glob($cfg_dir . '*.php');
        foreach ( $cfg_files as $cfg_file)
        {
            require_once $cfg_file;
        }
    }

    /*
    Set the include path
    */
    ini_set("include_path", APP);

    define('DEFAULT_URI', 'main');
    /*
    The Router takes the URL, parses it and then runs the correct controller
    */
    $router = New Router(CPATH, $_SERVER['REQUEST_URI'], BASE, DEFAULT_URI);
    $router->run();

