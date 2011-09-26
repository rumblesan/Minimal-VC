<?php

/*
This class is meant to be used to contain paths to folders
it's passed through the Router to the Controller

means that global Defines aren't needed when creating Views and Models
should make it easier to test everything
*/
class Paths
{
    protected $paths = array();

    public function __get($pathname)
    {
        if (isset($this->paths[$pathname]))
        {
            return $this->paths[$pathname];
        }
    }

    public function __set($pathname, $path)
    {
        $this->paths[$pathname] = $path;
        return $this;
    }

    public function get_path($pathname)
    {
        return $this->__get($pathname);
    }

    public function set_path($pathname, $path)
    {
        return $this->__set($pathname, $path);
    }
}

