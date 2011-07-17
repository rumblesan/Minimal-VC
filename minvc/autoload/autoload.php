<?php

#function to deal with autoloading of classes
function __autoload($class_name)
{
    require_once APP . '/classes/' . $class_name . '.class.php';
}
