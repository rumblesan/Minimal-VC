<?php

    #All other arg classes inherit from this
    abstract class Parser_Arg_Abstract
    {
        protected $arg_name;
        protected $default;

        protected $settings = array();

        protected $element_value;

        protected $array;
        protected $array_key;

        public function __construct($array, $array_key, $arg_name, $default='')
        {
            $this->array     = $array;
            $this->array_key = $array_key;
            $this->arg_name  = $arg_name;
            $this->default   = $default;
        }

        #this function checks to see if the named arg exists
        #in the argument arrays. If they don't exist then use
        #the default value
        protected function validate()
        {
            if ( isset($this->array[$this->array_key]) )
            {
                $arg_value = $this->array[$this->array_key];
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

