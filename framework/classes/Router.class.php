<?php
/**
 *
 */
/**
 *The Router class takes a URI and finds the corresponding controller file
 *
 *The Router class performs a number of tasks
 * - strips off the BASE of the URI. for example, if
 *   the site address is http://foo.com/base/
 * - defaults to the default URI if the given one is blank
 * - splits the URI into its sub elements, splitting on '/'
 * - attempts to find the controller file, load it, create the
 *   controller class and then run it
 * - catches any exceptions thrown within the controller and
 *   calls an error handling page if necessary
 *
 *
 */

class Router
{
    /*beginning docblock template*/
    /**#@+
     * @access private
     * @var string
     */
    /**
     * Stores the URI the webserver passes
     */
    private $uri;
    /**
     * The GET arguments passed with the URI
     */
    private $get_args;
    /**
     * The BASE of the URI. Is removed before routing
     */
    private $uri_base;
    /**
     * Default URI to use if the one passed is blank
     */
    private $uri_default;
    /**
     * Stores the uri after it has been split and formatted
     * @var array
     */
    private $uri_parts = array();


    /**
     * Stores the name of the controller to be called
     */
    private $c_name = '';
    /**
     * Stores the path to the controller
     */
    private $c_path = '';
    /**
     * Stores the name of the controller file
     */
    private $c_file = '';
    /**
     * Stores the arguments to be passed to the controller
     * @var array
     */
    private $c_args = array();

    /**
     * Stores the name of the controller folder in the app directory
     */
    private $c_folder;

    /**

     * Stores the output of the PHP script before sending it 
     * to the output buffer. This is done to make error handling easier
     */

    private $page_output;
    /**#@-*/

    /**
     * Constructor of the Router class
     * 
     * Needs to be passed the main controller folder path, the URI, the BASE
     * and the default URI. After the object is created the run method needs
     * to be called.
     * 
     * @access public
     * @return Router
     * @param string c_folder the path to the controller folder
     * @param string uri the URI to be parsed. defaults to an empty string
     * @param string uri_base the uri BASE, defaults to '/'
     * @param string uri_default the URI to default to if the given one
     * is blank. defaults to 'main'
     * @return Router
     */
    public function __construct($c_folder,
                                $uri='',
                                $uri_base='/',
                                $uri_default='main')
    {
        $uri               = explode('?', $uri);
        $this->uri         = $uri[0];
        $this->get_args    = isset($uri[1]) ? $uri[1] : '';
        $this->uri_base    = $uri_base;
        $this->uri_default = $uri_default;


        $this->c_folder    = $c_folder;
    }

    /**
     * Run the Router and have it parse the URI data it received
     *
     * The URI will be formatted to remove the BASE URL section,
     * split into it's sub sections and then the Router attempts
     * to find a corresponding controller
     *
     * If a controller file is found it will be run, otherwise
     * the error method is called with a '404' argument to raise
     * a '404 Not Found' error page
     *
     * When the controller is run, its output will be stored in
     * the page_output attribute and then echoed out once the
     * controller has finished.
     *
     * If the controller file cannot be loaded or has an error
     * then the error method will be called with a '500' argument
     * to raise a '500 Internal Server Error' page
     * 
     * @access public
     * @return none
     * @uses format_uri
     * @uses split_uri
     * @uses find_controller
     * @uses run_controller
     * @uses error
     */







    public function run()
    {
        $page_found = $this->format_uri()
                           ->split_uri()
                           ->find_controller();
        
        $info = array();
        $info['uri']   = $this->uri;
        $info['file']  = $this->c_file;
        $info['class'] = $this->c_name;
        $info['path']  = $this->c_path;
        $info['args']  = $this->c_args;
        $info['get']   = $this->get_args;
        
        if ( ! $page_found )
        {
            /*
            the page couldn't be found
            raise a 404 error
            */
            $this->error('404', $info);
        }

        $page_loaded = $this->run_controller();
        if ( ! $page_loaded )
        {
            /*
            there was an error with the controller files
            raise a 500 error
            */
            $this->error('500', $info);
        }

        /*
        everything is ok so echo the controllers output
        */
        echo $this->page_output;
    }

    /**
     * Will attempt to find an error handler controller for the given
     * error when called.
     * 
     * The error controllers all need to be in <c_folder>/error with the
     * filename c_error_<name>.php and the class name c_error_<name>
     * 
     * The process for loading the error controllers is the same as
     * ordinary ones except in the event of a further error, at which point
     * the error_fallback method is called instead of another error
     * 
     * @access private
     * @return none
     * @uses split_uri
     * @uses find_controller
     * @uses run_controller
     * @uses error_fallback
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

    /**
     * Fallback function incase there is a problem with the main error
     * controllers
     *
     * Gives a very basic, generic error message incase the correct
     * error controllers can't be found or have issues
     * 
     * @access private
     * @return none
     */



    private function error_fallback()
    {
        header('HTTP/1.1 500 Internal Server Error');
        header('Connection: close');
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

    /**
     * Removes the BASE string from the received URI
     *

     * This removes the BASE section from the received URI and trims any
     * trailing slashes
     *
     * For example, if the site is to be situated at 'http://foo.com/site/'
     * then the BASE would be set to '/site/'. The URI '/site/test/bar/'
     * would be converted to 'test/bar'
     * 
     * @access private
     * @return Router reference to the parent object to allow
     * for method chaining
     */

    private function format_uri()
    {
        if (strpos($this->uri, $this->uri_base) === 0)
        {
            $this->uri = substr($this->uri, strlen($this->uri_base));
        }
        $this->uri = rtrim($this->uri, '/');
        return $this;
    }

    /**

     * Splits the URI up into it's sub parts, splitting on the slash '/'
     *

     * Will change to URI to the default if the one given is blank
     * 
     * @access private
     * @return this
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

    /**

     * Uses the uri parts and searches for a controller
     *

     * Can search arbitarilly deep through folders any array elements left

     * after the controller name in the uri are assumed to be arguments
     * It will start in the controller folder <c_folder>
     * passed in to the constructor
     *
     * All controller files have filenames in the format 'c_<name>.php and
     * the class name must be c_<name>
     *
     * For example, if the URI is 'foo/bar/test/1/2/3' and the controller
     * file is $c_folder/foo/bar/c_test.php then the Router will:-
     * 
     * First check to see if there is a file $c_folder/c_foo.php
     * Then check to see if there is a file $c_folder/foo/c_bar.php
     * Then check to see if there is a file $c_folder/foo/bar/c_test.php
     * 
     * It finds $c_folder/foo/bar/c_test.php, saves the 1, 2, 3 section in
     * the c_args attribute and then returns True
     * 
     * @access private
     * @return boolean
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
            $this->c_file = $this->c_path . $this->c_name . '.php';
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

    /**
     * Load the controller file if it's found, create the class and then run it
     * 
     * If the controller file is succesfully loaded and the class created then
     * the output from the controller being run will be saved to a buffer.
     * Once the controller has finished running, and assuming there are no
     * errors, the main run function will output the contents of the buffer.
     * 
     * This method also has exception handling to catch specific HttpError
     * exceptions and general Exceptions. This will also cause the error
     * method to be called so they can be handled by controllers
     * 
     * @access private
     * @return boolean True if controller runs ok, False otherwise
     * uses error
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
        catch (HttpError $e)
        {
            /*
            catch exceptions thrown as HTTP errors
            */
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
            $this->error('http', $e);
        }
        catch (Exception $e)
        {
            /*
            catch exceptions that aren't HTTP errors
            */
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
