<?php

class Router
{
    protected $uri;
    protected $uribase;
    protected $group;
    protected $controller_file;
    protected $args = array();

    protected $path;
    
    function __construct($uri='',
                         $uribase='/',
                         $path='.',
                         $controller='main')
    {
        $this->uri        = $uri;
        $this->uribase    = $uribase;
        $this->controller = $controller;
        $this->path       = $path;

        if ($uri !=='')
        {
            $this->format_uri()
                 ->split_uri()
                 ->call_funcs();
        }
    }

    function format_uri()
    {
        if (strpos($this->uri, $this->uribase) === 0)
        {
            $this->uri = substr($this->uri, strlen($this->uribase));
        }

        return $this;
    }

    function split_uri()
    {
        $split = explode ("/", $this->uri);
        if (isset($split[0]) && $split[0])
        {
            $this->controller = $split[0];
        }
        if (count($split) > 1)
        {
            $this->args       = array_slice($split, 1);
        }

        return $this;
    }

    function call_funcs()
    {
        $cfile = $this->path . "/" . $this->controller . ".php";
        $cname = 'c_' . $this->controller;

        if (file_exists($cfile))
        {
            require_once($cfile);
            $controller = new $cname($this->args);
            $controller->run();
            return $this;
        }
        $this->error();
    }

    function error()
    {
        header('Refresh: 2; URL=' . $this->uribase);
        $html  = "<html>";
        $html .= "<head><title>Uh Oh...</title></head>";
        $html .= "<body>";
        $html .= "<h1>Somethings gone wrong</h1>";
        $html .= "Lets go back to the front page";
        $html .= "</body>";
        $html .= "</html>";
        echo $html;
    }

}



