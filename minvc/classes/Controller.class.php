<?php

abstract class Controller
{
    protected $args        = array();
    protected $requests    = array();
    protected $req_method;

    protected $paths;

    function __construct($paths, $args='', $req_method='')
    {
        $this->paths = $paths;
        $this->parse_args($args);
        
        if ($req_method === '')
        {
            $req_method = $_SERVER['REQUEST_METHOD'];
        }
        $this->req_method = $req_method;

        $this->requests['GET']     = '_get';
        $this->requests['POST']    = '_post';
        $this->requests['PUT']     = '_put';
        $this->requests['DELETE']  = '_delete';
        $this->requests['HEAD']    = '_head';
        $this->requests['OPTIONS'] = '_options';
    }

    protected function parse_args($args)
    {
        //this function can be over riden in the controller
        //to do automatic parsing of input arguments
        $this->args = $args;
    }

    private function _head()
    {
        header('HTTP/1.0 200 OK');
    }

    private function _options()
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
        $request_method = $this->requests[$this->req_method];
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

    private function request_error()
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

