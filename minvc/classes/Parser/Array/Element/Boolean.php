<?php

    #Used for parsing boolean arguments
    class Parser_Array_Element_Boolean extends Parser_Array_Element_Abstract
    {
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

