<?php

    /*
    BASE is the directory that the framework is installed to
    this is removed from the request URL to get the correct path
    for example, if it were installed to :-
        http://foo.com/bar/
    then BASE need to be set to bar
    
    otherwise it needs to be set to a single slash  /
    */
    define('BASE',  '/');

    /*
    Paths to framework folder and APP folder
    */
    define('ROOT',  dirname(dirname(dirname(__FILE__))));
    define('MINVC', ROOT . '/minvc/');
    define('APP',   ROOT . '/app/');

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
    Load app config files
    */
    require_once APP . 'cfg/cfg.php';

    /*
    Load core file
    */
    require_once MINVC . 'minvc_core.php';

    /*
    Setthe include path
    */
    ini_set("include_path", APP);

    /*
    The Path object holds the paths to the main folders
    within the app section
    Makes it easier to pass them around
    */
    $paths = New Paths();
    $paths->set_path('controller', CPATH)
          ->set_path('view',       VPATH)
          ->set_path('model',      MPATH);

    /*
    The Router takes the URL, parses it and then runs the correct controller
    */
    $router = New Router($paths, $_SERVER['REQUEST_URI'], BASE, 'main');

