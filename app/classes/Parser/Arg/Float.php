<?php

    #Used for parsing float arguments
    class Parser_Arg_Float extends Parser_Arg_Abstract
    {
        public function assign_value()
        {
            $this->arg_value = floatval($this->validate());
        }
    }

