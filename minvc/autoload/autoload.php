<?php

#function to deal with autoloading of classes
function __autoload($class_name)
{
    $class_file   = str_replace('_', '/', $class_name) . '.php';
    $class_folder = APP . "classes/";
    
    $class_path = $class_folder . $class_file;
    if (file_exists($class_path) && is_readable($class_path))
    {
        require_once $class_path;
    }
}
