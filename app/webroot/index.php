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
    if (file_exists(APP . 'cfg/cfg.php'))
    {
        require_once APP . 'cfg/cfg.php';
    }

    /*
    Set the include path
    */
    ini_set("include_path", APP);

    /*
    The Path object holds the paths to the main folders
    within the app section
    Makes it easier to pass them around
    
    these paths for the controller, view and model need to be set
    */
    $paths = New Paths();
    $paths->set_path('controller', CPATH)
          ->set_path('view',       VPATH)
          ->set_path('model',      MPATH);

    /*
    The Router takes the URL, parses it and then runs the correct controller
    */
    $router = New Router($paths, $_SERVER['REQUEST_URI'], BASE, 'main');

