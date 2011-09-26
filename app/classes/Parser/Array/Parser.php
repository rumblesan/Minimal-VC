<?php

    #Main Array Parser class.
    #Used for easily parsing arguments in GET and POST requests
    class Parser_Array_Parser
    {
        private $arg_types = array('string'  => 'Parser_Array_Element_String',
                                   'int'     => 'Parser_Array_Element_Int',
                                   'boolean' => 'Parser_Array_Element_Boolean',
                                   'date'    => 'Parser_Array_Element_Date',
                                   'float'   => 'Parser_Array_Element_Float');

        private $array;

        private $arg_values = array();

        public function __construct($array)
        {
            $this->array  = $array;
        }

        public function add_arg($arg_key,
                                $arg_name,
                                $arg_type='string',
                                $default='')
        {
            if ( ! isset($this->arg_types[$arg_type]) )
            {
                $arg_type = 'string';
            }

            $arg_class = $this->arg_types[$arg_type];
            $this->arg_values[$arg_name] = new $arg_class($this->array,
                                                          $arg_key,
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

