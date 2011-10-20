<?php

    #Used for parsing date arguments
    class Parser_Arg_Date extends Parser_Arg_Abstract
    {
        #overriding the abstract classes constructor to define settings
        public function __construct($array, $array_key, $arg_name, $default='')
        {
            parent::__construct($array, $array_key, $arg_name, $default);

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

