<?php

class Router
{
    protected $uri;
    protected $uri_base;
    protected $uri_default;
    protected $c_path;
    protected $split_uri = array();

    protected $path;
    
    function __construct($uri='',
                         $uri_base='/',
                         $c_path='./',
                         $uri_default='index')
    {
        $this->uri         = $uri;
        $this->uri_base    = $uri_base;
        $this->uri_default = $uri_default;
        $this->c_path      = $c_path;

        if ($uri !=='')
        {
            $this->format_uri()
                 ->split_uri()
                 ->call_controller();
        }
    }

    function format_uri()
    {
        if (strpos($this->uri, $this->uri_base) === 0)
        {
            $this->uri = substr($this->uri, strlen($this->uri_base));
        }
        return $this;
    }

    function split_uri()
    {
        $split_uri = explode ('/', $this->uri);
        if (! (isset($split_uri[0]) && $split_uri[0]))
        {
            $split_uri = explode ('/', $this->uri_default);
        }
        $this->split_uri = $split_uri;
        return $this;
    }

    function call_controller()
    {
        $file_path = '';
        $uri_parts = $this->split_uri;
        $c_path    = $this->c_path;
        $c_file    = '';
        $c_name    = '';
        $c_args    = array();

        while ($section = array_shift($uri_parts))
        {
            $check_path = $c_path . $file_path . 'c_' . $section . '.php';
            if (file_exists($check_path))
            {
                $c_file = $check_path;
                $c_name = 'c_' . $section;
                $c_args = $uri_parts;
                break;
            }
            $file_path .= $section . '/';
        }

        if ($c_file !== '')
        {
            require_once($c_file);
            if (class_exists($c_name))
            {
                $controller = new $c_name($c_args);
                $controller->run();
                return True;
            }
        }
        return $this->error();
    }

    function error()
    {
        header('Refresh: 2; URL=' . $this->uri_base);
        $html  = '<html>';
        $html .= '<head><title>Uh Oh...</title></head>';
        $html .= '<body>';
        $html .= '<h1>Somethings gone wrong</h1>';
        $html .= 'Lets go back to the front page';
        $html .= '</body>';
        $html .= '</html>';
        echo $html;
        return False;
    }

}


