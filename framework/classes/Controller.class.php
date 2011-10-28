<?php

abstract class Controller
{
    //stores the arguments passed to the controller
    protected $args            = array();

    //stores the $_POST array
    protected $post_args       = array();

    //stores the $_GET array
    protected $get_args        = array();

    //stores a function name for each request type
    protected $request_methods = array();

    //stores the http response header codes and strings
    protected $status_codes    = array();

    //set to true if request uses HEAD method
    //will cause the framework to kill the script
    //after the correct headers have been set so
    //we only send them back and not any content
    protected $head_request    = False;

    //the request method used to call the controller
    protected $req_method;

    //stores the path object
    protected $paths;

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
        $this->requests['PATCH']   = '_patch';
        $this->requests['DELETE']  = '_delete';
        $this->requests['HEAD']    = '_head';
        $this->requests['OPTIONS'] = '_options';

        $this->setup_codes();
    }

    /*
    this function can be over riden in the controller
    to do automatic parsing of input arguments
    */
    protected function parse_args($args)
    {
        $this->args      = $args;
        $this->post_args = $_POST;
        $this->get_args  = $_GET;
    }

    /*
       Setup the headers attribute array to contain HTTP return HEADER
       text kyed on the return code
    */
    protected function setup_codes()
    {
        //Success codes
        $this->status_codes[200] = 'OK';
        $this->status_codes[201] = 'Created';

        //Redirect codes
        $this->status_codes[304] = 'Not Modified';

        //Client Error codes
        $this->status_codes[400] = 'Bad Request';
        $this->status_codes[401] = 'Unauthorized';
        $this->status_codes[403] = 'Forbidden';
        $this->status_codes[404] = 'Not Found';
        $this->status_codes[405] = 'Method Not Allowed';

        //Server Error codes
        $this->status_codes[500] = 'Internal Server Error';
        $this->status_codes[501] = 'Not Implemented';
        $this->status_codes[503] = 'Service Unavailable';
    }

    protected function response_header($code, $close=True)
    {
        if ( ! isset($this->status_codes[$code]) )
        {
            $code = 200;
        }
        $codetext   = $this->status_codes[$code];
        $headertext = 'HTTP/1.1 ' . $code . ' ' . $codetext;
        header($headertext);
        if ($close === True)
        {
            header('Connection: close');
        }

        if ($this->head_request === True)
        {
            exit;
        }
    }

    /*
    an HTTP HEAD request is just supposed to send back the HEADERs
    The request needs to send back the same HEADERs as a GET request
    */
    private function _head()
    {
        $this->head_request = True;
        $this->req_method   = 'GET';
        $this->run();
    }

    /*
    an HTTP OPTIONS request should send back information on
    what request types are available
    
    this method will automatically check which request methods have
    been created and return a their names
    
    *TODO* need to find out what the correct format for this is
    */
    protected function _options()
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

