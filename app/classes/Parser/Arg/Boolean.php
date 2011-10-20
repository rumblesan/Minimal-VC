<?php

    #Used for parsing boolean arguments
    class Parser_Arg_Boolean extends Parser_Arg_Abstract
    {
        #this function converts strings to Boolean Values
        private function make_bool($value)
        {
            $value = intval($value);
            if ( $value === 1 )
            {
                $arg_value = True;
            }
            else
            {
                $arg_value = False;
            }
            return $arg_value;
        }

        public function assign_value()
        {
            $this->arg_value = $this->make_bool($this->validate());
        }
    }

