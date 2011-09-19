<?php

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

