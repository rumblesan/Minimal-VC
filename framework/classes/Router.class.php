<?php

class Router
{
    /*stores the URI the webserver passes minus the GET args*/
    private $uri;
    /*the GET arguments passed with the URI*/
    private $get_args;
    /*the BASE of the URI. needs to be removed before routing*/
    private $uri_base;
    /*the default URI to use if the one passed is blank*/
    private $uri_default;
    /*stores the uri after it has been split and formatted*/
    private $uri_parts = array();

    /*stores the name of the controller class*/
    private $c_name = '';
    /*stores the folder path to the controller file*/
    private $c_path = '';
    /*stores the name of the controller file*/
    private $c_file = '';
    /*stores the arguments for the controller*/
    private $c_args = array();

    /*stores the name of the controller folder in the app directory*/
    private $c_folder;

    /*
    stores the output of the PHP script before sending it 
    to the output buffer. This is done to make error handling easier
    */
    private $page_output;

    public function __construct($c_folder,
                                $uri='',
                                $uri_base='/',
                                $uri_default='main')
    {
        $uri               = explode('?', $uri);
        $this->uri         = $uri[0];
        $this->get_args    = isset($uri[1]) ? $uri[1] : array();
        $this->uri_base    = $uri_base;
        $this->uri_default = $uri_default;
        
        $this->c_folder    = $c_folder;
    }

    /*
    get the router to find the controller for the given URI
    if a controller is found then its output is stored in the page_output
    as long as there are no errors then the page_output will be echoed

    if a controller isn't found then check for the 404 error page
    */
    public function run()
    {
        $page_found = $this->format_uri()
                           ->split_uri()
                           ->find_controller();
        if ( ! $page_found )
        {
            /*
            the page couldn't be found
            raise a 404 error
            */
            $this->error('404');
        }

        $page_loaded = $this->run_controller();
        if ( ! $page_loaded )
        {
            /*
            there was an error with the controller files
            raise a 500 error
            */
            $this->error('500');
        }

        /*
        everything is ok so echo the controllers output
        */
        echo $this->page_output;
    }

    /*
    set the uri to the path for the 404 error page
    if it's found then call it

    if not then use the fallback error method
    */
    private function error($error_name, $args='')
    {
        $this->uri = 'error/error_' . $error_name;

        $page_found = $this->split_uri()
                           ->find_controller();
        if ( ! $page_found )
        {
            /*
            a controller for the specified error couldn't be found
            use the fallback error method to display a page
            */
            $this->error_fallback();
        }

        /*
        this allows different arguments to be passed to the error page
        for example, an exception object
        */
        if ( is_array($args) )
        {
            $this->c_args = $args;
        }
        elseif ( $args !== '' )
        {
            $this->c_args = array($args);
        }
        else
        {
            $this->c_args = array();
        }

        $page_loaded = $this->run_controller();
        if ( ! $page_loaded )
        {
            /*
            there was an error with the controller files
            use the fallback error method

            we could raise a 500 error here but if the problem
            is with the 500 error controller file then we end up with 
            a circular problem
            */
            $this->error_fallback();
        }

        /*
        everything is ok so echo the controllers output
        */
        echo $this->page_output;
        die;
    }

    /*
    if the controller for the error page can't be found then call this method
    */
    private function error_fallback()
    {
        $html  = '<html>';
        $html .= '<head><title>Uh Oh...</title></head>';
        $html .= '<body>';
        $html .= '<h1>Uh Oh...</h1>';
        $html .= '<h2>Somethings gone wrong</h2>';
        $html .= '</body>';
        $html .= '</html>';
        echo $html;
        die;
    }

    /*
    remove the BASE string from the received URI
    */
    private function format_uri()
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
    private function split_uri()
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
    private function find_controller()
    {
        $found   = False;

        $file_path = '';
        $uri_parts = $this->uri_parts;

        while ($path_section = array_shift($uri_parts))
        {
            $this->c_path = $this->c_folder . $file_path;
            $this->c_name = 'c_' . $path_section;
            $this->c_file = $c_path . $this->c_name . '.php';
            $this->c_args = $uri_parts;
            
            if (file_exists($this->c_file))
            {
                $found = True;
                break;
            }
            $file_path .= $path_section . '/';
        }

        return $found;
    }

    /*
    will include the file and then check for the controller class
    if it is present, create a new object and call the run method
    */
    private function run_controller()
    {
        require_once($this->c_file);
        
        if ( ! class_exists($this->c_name) )
        {
            return False;
        }

        try
        {
            $controller = new $this->c_name($this->c_args);
            /*
            save the output of the controller into a buffer
            this is done so that when an error is raised we can
            send the headers we want without having already sent output
            */
            ob_start();
            $controller->run();
            $this->page_output = ob_get_clean();
            return True;
        }
        catch (Exception $e)
        {
            /*
            call ob_end_clean here to finish getting the buffer contents
            we don't need it anymore so we can discard it
            */
            ob_end_clean();

            /*
            call the error method
            find the exception error controller
            pass it the exception object so it can pull out the information
            */
            $this->error('exception', $e);
        }
    }

}
