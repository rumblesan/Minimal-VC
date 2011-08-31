<?php

abstract class Controller
{
    protected $values   = array();

    protected $req_args = array();
    protected $url_args = array();

    function __construct($args)
    {
        $this->url_args = $args;
    }

    public function get($name)
    {
        return $this->values[$name];
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {
        if ( isset($this->values[$name]) )
        {
            $this->values[$name] = $value;
        }
        return $this;
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    abstract public function run();

}

