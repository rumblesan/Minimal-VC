<?php

    #Used for parsing integer arguments
    class Parser_Request_Arg_Int extends Parser_Request_Arg_Abstract
    {
        public function assign_value()
        {
            $this->arg_value = intval($this->validate());
        }
    }

