<?php

class Loader
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

    public function create_class($prefix, $folder, $classpath, $args='')
    {
        $splitpath = explode('/', $classpath);
        $classname = $prefix . array_pop($splitpath);
        $classfile = $classname . '.php';
        $fullpath  = $folder . implode('/', $splitpath) . '/' . $classfile;

        if (file_exists($fullpath))
        {
            require_once($fullpath);
            if (class_exists($classname))
            {
                if ($args != '')
                {
                    $newclass = new $classname($args);
                }
                else
                {
                    $newclass = new $classname();
                }
                return $newclass;
            }
        }
        return False;
    }

    public function get_view($viewpath, $args='')
    {
        return $this->create_class('v_', $this->v_path, $viewpath, $args)
    }

    public function get_model($modelpath, $args='')
    {
        return $this->create_class('m_', $this->m_path, $viewpath, $args)
    }

    public function get_template($name, $group)
    {
        return new Template($name, $group, $this->t_path);
    }

    abstract public function render();

}

