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
    define('TPATH', APP . 'templates/');

    # Turn autoloading on or off
    define('AUTOLOAD', True);

    # Load app config files
    require_once APP . "cfg/cfg.php";

    # Get core Minimal VC files
    require_once MINVC . 'minvc_core.php';

    ini_set("include_path", APP);

    $loader = New Loader();
    $loader->set_path('c_path', CPATH)
           ->set_path('v_path', VPATH)
           ->set_path('m_path', MPATH)
           ->set_path('t_path', TPATH);


    $controller = New Router($_SERVER['REQUEST_URI'], BASE, 'main', $loader);

