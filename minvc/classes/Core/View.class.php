<?php

abstract class View
{
    protected $values = array();

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
        return new Template($name, $group, TPATH);
    }

    abstract public function render();

}

