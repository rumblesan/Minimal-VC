<?php

    #Used for parsing string arguments
    class Parser_Arg_String extends Parser_Arg_Abstract
    {
        public function assign_value()
        {
            $this->arg_value = strval($this->validate());
        }
    }

