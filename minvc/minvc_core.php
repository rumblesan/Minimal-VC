<?php

# requires the core Minimal VC classes
require_once MINVC . '/classes/Core/Controller.class.php';
require_once MINVC . '/classes/Core/Template.class.php';
require_once MINVC . '/classes/Core/Paths.class.php';
require_once MINVC . '/classes/Core/Router.class.php';
require_once MINVC . '/classes/Core/Model.class.php';
require_once MINVC . '/classes/Core/View.class.php';

if (file_exists(MINVC . '/functions/functions.php'))
{
    require_once MINVC . '/functions/functions.php';
}

if (AUTOLOAD)
{
    require_once MINVC . '/autoload/autoload.php';
}

