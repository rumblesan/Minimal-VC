<?php

    #Abstract Arg class
    #All other arg classes inherit from this
    abstract class Parser_Request_Arg_Abstract
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

