<?php

    #StringArg class
    #Used for parsing string arguments
    class Parser_Request_Arg_String extends Parser_Request_Arg_Abstract
    {
        public function assign_value()
        {
            $this->arg_value = strval($this->validate());
        }
    }

