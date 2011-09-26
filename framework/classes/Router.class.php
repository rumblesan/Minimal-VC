<?php

class Router
{
    /*stores the URI the webserver passes*/
    protected $uri;
    /*the BASE of the URI. needs to be removed before routing*/
    protected $uri_base;
    /*the default URI to use if the one passed is blank*/
    protected $uri_default;
    /*stores the uri after it has been split and formatted*/
    protected $uri_parts = array();

    /*stores the path object*/
    protected $paths;

    /*stores the name of the controller to be called*/
    protected $c_name;
    /*stores the path to the controller*/
    protected $c_path;
    
    function __construct($paths,
                         $uri='',
                         $uri_base='/',
                         $uri_default='main')
    {
        $this->paths       = $paths;
        
        $this->uri         = $uri;
        $this->uri_base    = $uri_base;
        $this->uri_default = $uri_default;

        if ($uri !=='')
        {
            $this->format_uri()
                 ->split_uri()
                 ->controller();
        }
    }

    /*
    remove the BASE string from the received URI
    */
    function format_uri()
    {
        if (strpos($this->uri, $this->uri_base) === 0)
        {
            $this->uri = substr($this->uri, strlen($this->uri_base));
        }
        return $this;
    }

    /*
    splits the URI up into it's parts, splitting on the slash '/'
    
    will change to URI to the default if the one given is blank
    */
    function split_uri()
    {
        $uri_parts = explode ('/', $this->uri);
        if ( ! (isset($uri_parts[0]) && $uri_parts[0]))
        {
            $uri_parts = explode ('/', $this->uri_default);
        }
        $this->uri_parts = $uri_parts;
        return $this;
    }

    /*
    takes the uri parts and searches for a controller
    
    can search arbitarilly deep through folders
    any array elements left after the controller name in
    the uri are assumed to be arguments
    */
    function find_controller()
    {
        $file_path = '';
        $uri_parts = $this->uri_parts;
        $c_path    = $this->paths->controller;
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
            $this->c_file = $c_file;
            $this->c_name = $c_name;
            $this->c_args = $c_args;
            return True;
        }
        return False;
    }

    /*
    if the controller file is found it will require the file
    
    once the file has been included check for the controller class
    if it ispresent, create a new object and call the run method
    */
    function controller()
    {
        if ($this->find_controller())
        {
            require_once($this->c_file);
            if (class_exists($this->c_name))
            {
                $controller = new $this->c_name($this->paths, $this->c_args);
                $controller->run();
                return True;
            }
        }
        return $this->error();
    }

    /*
    method is called when there is an error
    
    needs to be improved to be a general purpose exception handler
    ideally needs to have its own controller and views
    */
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
