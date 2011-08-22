<?php

    #Abstract Arg class
    #All other arg classes inherit from this
    abstract class AbstractArg
    {
        protected $arg_name;
        protected $default;

        protected $settings = array();

        protected $arg_value;

        protected $protocol;

        public function __construct($protocol, $arg_name, $default='')
        {
            $this->arg_name  = $arg_name;
            $this->default   = $default;

            $this->protocol  = $protocol;
        }

        #this function checks to see if the named arg exists
        #in the argument arrays. If they don't exist then use
        #the default value
        protected function validate()
        {
            if ( $this->protocol === 'GET' &&
                 isset($_GET[$this->arg_name]) )
            {
                $arg_value = $_GET[$this->arg_name];
            }
            else if ( $this->protocol === 'POST' &&
                      isset($_POST[$this->arg_name]) )
            {
                $arg_value = $_POST[$this->arg_name];
            }
            else
            {
                $arg_value = $this->default;
            }
            return $arg_value;
        }

        public function assign_value()
        {
            $this->arg_value = $this->validate();
        }

        #used to change settings for the args
        #currently only used to set format for the date arg class
        public function setting($name, $value)
        {
            if ( isset($this->settings[$name]) )
            {
                $this->settings[$name] = $value;
            }
        }

        public function get_value()
        {
            return $this->arg_value;
        }

        public function get_name()
        {
            return $this->arg_name;
        }
    }

    #StringArg class
    #Used for parsing string arguments
    class StringArg extends AbstractArg
    {
        public function assign_value()
        {
            $this->arg_value = strval($this->validate());
        }
    }

    #IntArg class
    #Used for parsing integer arguments
    class IntArg extends AbstractArg
    {
        public function assign_value()
        {
            $this->arg_value = intval($this->validate());
        }
    }

    #FloatArg class
    #Used for parsing float arguments
    class FloatArg extends AbstractArg
    {
        public function assign_value()
        {
            $this->arg_value = floatval($this->validate());
        }
    }

    #DateArg class
    #Used for parsing date arguments
    class DateArg extends AbstractArg
    {
        #overriding the abstract classes constructor to define settings
        public function __construct($protocol, $arg_name, $default='')
        {
            parent::__construct($protocol, $arg_name, $default);

            $this->settings['format'] = 'Y-m-d';
        }

        #this checks that the date is a valid date
        #then returns in in the correct format
        private function check_date($date_value)
        {
            $format = $this->settings['format'];
            $date_value = strval($date_value);
            $date_obj   = new DateTime($date_value);

            $date_value = $date_obj->format($format);
            return $date_value;
        }

        public function assign_value()
        {
            $this->arg_value = $this->check_date($this->validate());
        }
    }

    #BooleanArg class
    #Used for parsing boolean arguments
    class BooleanArg extends AbstractArg
    {
        #argument values from the GET/POST array are always strings
        #this function converts strings to Boolean Values
        private function make_bool($value)
        {
            $value = intval($value);
            if ( $value === 1 )
            {
                $this->arg_value = True;
            }
            else
            {
                $this->arg_value = False;
            }
        }

        public function assign_value()
        {
            $this->arg_value = $this->make_bool($this->validate());
        }
    }


    #Main ArgParser class.
    #Used for easily parsing arguments in GET and POST requests
    class ArgParser
    {
        private $arg_types = array('string'  => 'StringArg',
                                   'int'     => 'IntArg',
                                   'boolean' => 'BooleanArg',
                                   'date'    => 'DateArg',
                                   'float'   => 'FloatArg',);

        private $protocols  = array('GET', 'POST');
        private $protocol;

        private $arg_values = array();

        public function __construct($protocol='GET')
        {
            if ( ! in_array($protocol, $this->protocols) )
            {
                $protocol = 'GET';
            }
            $this->protocol  = $protocol;
        }

        public function add_arg($arg_name, $arg_type='string', $default='')
        {
            if ( ! isset($this->types[$arg_type]) )
            {
                $arg_type = 'string';
            }

            $arg_class = $this->arg_types[$arg_type];
            $this->arg_values[$arg_name] = new $arg_class($this->protocol,
                                                          $arg_name,
                                                          $default);
            return $this;
        }

        public function arg_setting($arg_name, $setting, $value)
        {
            if ( isset($this->arg_values[$arg_name]) )
            {
                $this->arg_values[$arg_name]->setting($setting, $value);
            }
        }

        public function get_arg_value($arg_name)
        {
            if ( isset($this->arg_values[$arg_name]) )
            {
                $this->arg_values[$arg_name]->assign_value();
                return $this->arg_values[$arg_name]->get_value();
            }
        }

        public function __get($arg_name)
        {
            return $this->get_arg_value($arg_name);
        }

    }

