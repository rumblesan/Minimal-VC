<?php

class Template
{
    protected $template;
    protected $type;
    protected $base;

    protected $file;

    protected $args = array();

    function __construct($template,
                         $type,
                         $base='./',
                         $args='')
    {
        $this->template = $template;
        $this->type     = $type;
        $this->base     = $base;

        $this->file = $base . $type . "/" . $template . ".php";

        if (is_array($args))
        {
            foreach ($args as $key => $val)
            {
                $this->args[$key] = $val;
            }
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




