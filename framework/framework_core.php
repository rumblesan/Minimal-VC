<?php

    /*
    requires the core framework classes
    */
    require_once FRAMEWORK . '/classes/Controller.class.php';
    require_once FRAMEWORK . '/classes/Paths.class.php';
    require_once FRAMEWORK . '/classes/Router.class.php';
    require_once FRAMEWORK . '/classes/Model.class.php';
    require_once FRAMEWORK . '/classes/View.class.php';

    /*
    if there is a functions file then load it
    */
    if (file_exists(FRAMEWORK . '/functions/functions.php'))
    {
        require_once FRAMEWORK . '/functions/functions.php';
    }

    /*
    if autoloading is turned on and the autoload function
    file exists then load it
    */
    if (AUTOLOAD && file_exists(FRAMEWORK . '/autoload/autoload.php'))
    {
        require_once FRAMEWORK . '/autoload/autoload.php';
    }
