<?php

    #Used for parsing arguments in GET and POST requests
    class Parser_Request
    {
        private $arg_types = array('string'  => 'Parser_Arg_String',
                                   'int'     => 'Parser_Arg_Int',
                                   'boolean' => 'Parser_Arg_Boolean',
                                   'date'    => 'Parser_Arg_Date',
                                   'float'   => 'Parser_Arg_Float',);

        private $protocols  = array('GET', 'POST');
        private $protocol;

        private $arg_array = array();

        private $arg_values = array();

        public function __construct($protocol='GET')
        {
            if ( ! in_array($protocol, $this->protocols) )
            {
                $protocol  = 'GET';
                $arg_array = $_GET;
            }
            else if ( $protocol == 'GET' )
            {
                $arg_array = $_GET;
            }
            else if ( $protocol == 'POST' )
            {
                $arg_array = $_POST;
            }
            else
            {
                $arg_array = $this->protocols[$protocol];
            }

            $this->protocol  = $protocol;
            $this->arg_array = $arg_array;
        }

        public function add_arg($arg_name, $arg_type='string', $default='')
        {
            if ( ! isset($this->arg_types[$arg_type]) )
            {
                $arg_type = 'string';
            }

            $arg_class = $this->arg_types[$arg_type];
            $this->arg_values[$arg_name] = new $arg_class($this->arg_array,
                                                          $arg_name,
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
            return $this;
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

