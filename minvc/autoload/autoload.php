<?php

#function to deal with autoloading of classes
function __autoload($class_name)
{
    $class_file = str_replace('_', '/', $class) . '.php';
    $class_folders = array(APP, MINVC);
    foreach ($class_folders as $folder)
    {
        $class_path = $folder . "classes/" . $class_file;
        if (file_exists($class_path) && is_readable($class_path))
        {
            require_once $class_path;
        }
    }
}
