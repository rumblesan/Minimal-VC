<?php

class Controller
{
    protected $uri;
    protected $uribase;
    protected $controller;
    protected $function;
    protected $args = array();

    protected $path;
    
    function __construct($uri='',
                         $uribase='/',
                         $path='.',
                         $controller='main',
                         $function='index')
    {
        $this->uri        = $uri;
        $this->uribase    = $uribase;
        $this->controller = $controller;
        $this->function   = $function;
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
        if (isset($split[1]) && $split[1])
        {
            $this->function   = $split[1];
        }
        if (count($split) > 2)
        {
            $this->args       = array_slice($split, 2);
        }

        return $this;
    }

    function call_funcs()
    {
        $cfile     = $this->path  . $this->controller;
        $cfile    .= "/" . $this->function . ".php";
        $func_name = "_" . $this->function;

        if (file_exists($cfile))
        {
            include_once($cfile);
            if (function_exists($func_name))
            {
                call_user_func($func_name, $this->args);
                return $this;
            }
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



