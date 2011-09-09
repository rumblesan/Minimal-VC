<?php

class Template
{
    protected $template;
    protected $path;
    protected $base;

    protected $file;

    protected $args = array();

    function __construct($template,
                         $path,
                         $base='./',
                         $args='')
    {
        $this->template = $template;
        $this->path     = $path;
        $this->base     = $base;

        $this->file = $base . $path . "/" . $template . ".php";

        if (is_array($args))
        {
            $this->merge($args);
        }
    }

    function __get($key)
    {
        if (isset($this->args[$key]))
        {
            return $this->args[$key];
        }
    }

    function __set($key, $val)
    {
        $this->args[$key] = $val;
        return $this;
    }

    function get($key)
    {
        return $this->__get($key);
    }

    function set($key, $val)
    {
        return $this->__set($key, $val);
    }

    function merge($data_array)
    {
        foreach ($data_array as $key => $value)
        {
            $this->set($key, $value);
        }
        return $this;
    }

    function render()
    {
        extract($this->args);
        require($this->file);
    }

    function package()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }

}




