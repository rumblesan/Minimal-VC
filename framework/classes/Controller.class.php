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

    //stores the URI of the controller
    protected $uri             = array();

    //the request method used to call the controller
    protected $req_method;

    //stores the HTTP status response value output
    //defaults to 200
    protected $sent_status_code = 200;
   
    function __construct($uri, $req_method, $args='')
    {
        /*
        the uri is assumed to be the full uri used to reach this controller
        including protocol, hostname and BASE
        */
        $this->uri = $uri;

        /*
        $args is assumed to be an array
        */
        $this->parse_args($args);

        /*
        Router class handles passing in the correct request method
        */
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

        $this->sent_status_code = $code;
    }

    /*
    Returns the value of the last http status header sent by the conroller
    */
    public function get_http_status()
    {
        return $this->sent_status_code;
    }

    /*
    an HTTP HEAD request is just supposed to send back the HEADERs
    The request needs to send back the same HEADERs as a GET request
    */
    private function _head()
    {
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
    if it isn't return a 405 error
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
            $this->response_header(405);
        }
    }

}

