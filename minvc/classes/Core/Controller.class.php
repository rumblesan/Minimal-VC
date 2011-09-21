<?php

abstract class Controller
{
    protected $args      = array();
    protected $requests  = array();

    protected $paths;

    function __construct($paths, $args)
    {
        $this->paths = $paths;
        $this->args  = $args;

        $this->requests['GET']     = '_get';
        $this->requests['POST']    = '_post';
        $this->requests['PUT']     = '_put';
        $this->requests['DELETE']  = '_delete';
        $this->requests['HEAD']    = '_head';
        $this->requests['OPTIONS'] = '_options';
    }

    public function _head()
    {
        header('HTTP/1.0 200 OK');
    }

    public function _options()
    {
        $options = array();
        foreach ($this->requests as $request => $method)
        {
            if ( method_exists($this, $method) &&
                 is_callable(array($this, $method)) )
            {
                $options[] = $request;
            }
        }
        $options = implode(",", $options);
        echo $options . "\n";
    }

    public function run()
    {
        $request_type   = $_SERVER['REQUEST_METHOD'];
        $request_method = $this->requests[$request_type];
        if ( method_exists($this, $request_method) &&
             is_callable(array($this, $request_method)) )
        {
            $this->$request_method();
        }
        else
        {
            $this->request_error();
        }
    }

    public function request_error()
    {
        echo "ERROR";
    }

    public function get_view($name, $group)
    {
        return new View($name, $group, $this->paths->view);
    }

    public function get_model($modelpath, $modelargs='')
    {
        $m_path    = $this->paths->model;
        $splitpath = explode('/', $modelpath);
        $modelname = 'm_' . array_pop($splitpath);
        $modelfile = $modelname . '.php';
        $fullpath  = $m_path . implode('/', $splitpath) . '/' . $modelfile;
        
        if (file_exists($fullpath))
        {
            require_once($fullpath);
            if (class_exists($modelname))
            {
                $model = new $modelname($modelargs);
                return $model;
            }
        }
        return False;
    }

}

