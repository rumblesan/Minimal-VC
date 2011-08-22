<?php

    #IntArg class
    #Used for parsing integer arguments
    class Parser_Array_Element_Int extends Parser_Array_Element_Abstract
    {
        public function assign_value()
        {
            $this->arg_value = intval($this->validate());
        }
    }

