<?php

    #Used for parsing arguments in arrays
    class Parser_Array
    {
        private $arg_types = array('string'  => 'Parser_Arg_String',
                                   'int'     => 'Parser_Arg_Int',
                                   'boolean' => 'Parser_Arg_Boolean',
                                   'date'    => 'Parser_Arg_Date',
                                   'float'   => 'Parser_Arg_Float');

        private $arg_array;

        private $arg_values = array();

        public function __construct($arg_array)
        {
            $this->arg_array = $arg_array;
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
            $this->arg_values[$arg_name] = new $arg_class($this->arg_array,
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

