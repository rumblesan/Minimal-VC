<?php

    #Used for parsing boolean arguments
    class Parser_Request_Arg_Boolean extends Parser_Request_Arg_Abstract
    {
        #argument values from the GET/POST array are always strings
        #this function converts strings to Boolean Values
        private function make_bool($value)
        {
            $value = intval($value);
            if ( $value === 1 )
            {
                $value = True;
            }
            else
            {
                $value = False;
            }
            return $value;
        }

        public function assign_value()
        {
            $this->arg_value = $this->make_bool($this->validate());
        }
    }

