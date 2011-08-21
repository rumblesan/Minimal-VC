<?php

# requires the core Minimal VC classes
require_once MINVC . '/classes/core/Router.class.php';
require_once MINVC . '/classes/core/View.class.php';

if (file_exists(MINVC . '/functions/functions.php'))
{
    require_once MINVC . '/functions/functions.php';
}

if (AUTOLOAD)
{
    require_once MINVC . '/autoload/autoload.php';
}

