<?php

abstract class View
{
    protected $values = array();
    protected $paths;

    public function __construct($paths)
    {
        $this->paths = $paths;

        $this->defaults();
    }

    public function __get($key)
    {
        if (isset($this->values[$key]))
        {
            return $this->values[$key];
        }
    }

    public function __set($key, $val)
    {
        $this->values[$key] = $val;
        return $this;
    }

    public function get($key)
    {
        return $this->__get($key);
    }

    public function set($key, $val)
    {
        return $this->__set($key, $val);
    }

    public function merge($data_array)
    {
        foreach ($data_array as $key => $value)
        {
            $this->set($key, $value);
        }
        return $this;
    }

    public function get_template($name, $group)
    {
        return new Template($name, $group, $this->paths->template);
    }

    protected function defaults()
    {
    }

    abstract public function render();

}

