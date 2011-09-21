<?php

    # BASE is the directory that Minimal VC is installed to
    # this is removed from the request URL to get the correct path
    define('BASE',  '/');

    # Paths to Minimal VC folder and APP folder
    define('ROOT',  dirname(dirname(dirname(__FILE__))));
    define('MINVC', ROOT . '/minvc/');
    define('APP',   ROOT . '/app/');

    # Paths to app controllers and views
    define('CPATH', APP . 'controllers/');
    define('MPATH', APP . 'models/');
    define('VPATH', APP . 'views/');

    # Turn autoloading on or off
    define('AUTOLOAD', True);

    # Load app config files
    require_once APP . 'cfg/cfg.php';

    # Get core Minimal VC files
    require_once MINVC . 'minvc_core.php';

    ini_set("include_path", APP);

    $paths = New Paths();
    $paths->set_path('controller', CPATH)
          ->set_path('view',       VPATH)
          ->set_path('model',      MPATH);


    $controller = New Router($_SERVER['REQUEST_URI'], BASE, 'main', $paths);

