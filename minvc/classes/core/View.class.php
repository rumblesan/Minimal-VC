<?php

class View
{
    protected $view;
    protected $type;
    protected $base;

    protected $file;

    protected $args = array();

    function __construct($view,
                         $type,
                         $base='./',
                         $args='')
    {
        $this->view = $view;
        $this->type = $type;
        $this->base = $base;

        $this->file = $base . $type . "/" . $view . ".php";

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

    function show_view()
    {
        extract($this->args);
        require($this->file);
    }

    function pack_view()
    {
        ob_start();
        extract($this->args);
        require($this->file);
        return ob_get_clean();
    }

}




