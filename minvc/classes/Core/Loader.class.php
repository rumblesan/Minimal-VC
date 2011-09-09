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

    public function create_class($prefix, $classpath, $folder, $args='')
    {
        if ($args == '')
        {
            $args = array();
        }
        elseif(is_scalar($args))
        {
            $args = array($args);
        }
        elseif( ! is_array($args) )
        {
            $args = array();
        }
        
        $splitpath = explode('/', $classpath);
        $classname = $prefix . array_pop($splitpath);
        $classfile = $classname . '.php';
        $fullpath  = $folder . implode('/', $splitpath) . '/' . $classfile;

        if (file_exists($fullpath))
        {
            require_once($fullpath);
            if (class_exists($classname))
            {
                $reflection = new ReflectionClass($classname);
                $newclass = $reflection->newInstanceArgs($args);
                return $newclass;
            }
        }
        return False;
    }

    public function get_view($viewpath, $args='')
    {
        return $this->create_class('v_', $viewpath, $this->v_path, $args)
    }

    public function get_model($modelpath, $args='')
    {
        return $this->create_class('m_', $viewpath, $this->m_path, $args)
    }
    
    public function get_controller($controllerpath, $args='')
    {
        return $this->create_class('c_', $controllerpath, $this->c_path, $args)
    }

    public function get_template($templatepath, $args='')
    {
        $splitpath  = explode('/', $templatepath);
        $templtname = array_pop($splitpath);
        $templtpath = implode('/', $splitpath);

        return new Template($templtname, $templtpath, $this->t_path, $args);
    }

    abstract public function render();

}

