<?php

    #Used for easily parsing arguments in GET and POST requests
    class Parser_Request_Parser
    {
        private $arg_types = array('string'  => 'Parser_Request_Arg_String',
                                   'int'     => 'Parser_Request_Arg_Int',
                                   'boolean' => 'Parser_Request_Arg_Boolean',
                                   'date'    => 'Parser_Request_Arg_Date',
                                   'float'   => 'Parser_Request_Arg_Float',);

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

