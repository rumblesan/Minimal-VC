<?php

abstract class Controller
{
    //stores the arguments passed to the controller
    protected $args        = array();
    
    //stores a function name for each request type
    protected $requests    = array();
    
    //the request method used to call the controller
    protected $req_method;

    function __construct($args='', $req_method='')
    {
        /*
        $args is assumed to be an array
        */
        $this->parse_args($args);
        
        /*
        generally the request method arg will be left to it's default
        it can be set manually to make testing easier
        */
        if ($req_method === '')
        {
            $req_method = $_SERVER['REQUEST_METHOD'];
        }
        $this->req_method = $req_method;

        /*
        each of these is the name of a method we can create to handle requests
        
        when the controller is called with the specified request method
        the correct method will be called if it exists
        if it doesn't then the request_error method is called
        */
        $this->requests['GET']     = '_get';
        $this->requests['POST']    = '_post';
        $this->requests['PUT']     = '_put';
        $this->requests['DELETE']  = '_delete';
        $this->requests['HEAD']    = '_head';
        $this->requests['OPTIONS'] = '_options';
    }

    /*
    this function can be over riden in the controller
    to do automatic parsing of input arguments
    */
    protected function parse_args($args)
    {
        $this->args = $args;
    }

    /*
    an HTTP HEAD request is just supposed to send back the HEADER
    
    *TODO* not sure this is meant to work like this
           need to read the spec
    */
    private function _head()
    {
        header('HTTP/1.0 200 OK');
    }

    /*
    an HTTP OPTIONS request should send back information on
    what request types are available
    
    this method will automatically check which request methods have
    been created and return a their names
    
    *TODO* need to find out what the correct format for this is
    */
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

    /*
    run the controller
    
    checks to see if the correct method for the request is available
    if it is call it
    if it isn't, call the request_error method
    */
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

    /*
    called on a request error
    
    *TODO* should probablly raise an exception
    */
    private function request_error()
    {
        echo "ERROR";
    }

}

