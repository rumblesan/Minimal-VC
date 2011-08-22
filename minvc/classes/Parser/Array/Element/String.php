<?php

    #Used for parsing string arguments
    class Parser_Array_Element_String extends Parser_Array_Element_Abstract
    {
        public function assign_value()
        {
            $this->arg_value = strval($this->validate());
        }
    }

