<?php

    #Used for parsing integer arguments
    class Parser_Arg_Int extends Parser_Arg_Abstract
    {
        public function assign_value()
        {
            $this->arg_value = intval($this->validate());
        }
    }

