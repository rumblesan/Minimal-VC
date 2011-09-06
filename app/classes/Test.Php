<?php

    class Test
    {
        protected $vars = array();

        protected $name;

        function __construct($name)
        {
            $this->name = $name;

            $this->vars['var1'] = rand(0, 100);
            $this->vars['var2'] = rand(0, 100);
        }

        function __get($key)
        {
            return $this->get($key);
        }

        function __set($key, $val)
        {
            return $this->set($key, $val);
        }

        function get($key)
        {
            if (isset($this->vars[$key]))
            {
                return $this->vars[$key];
            }
        }

        function set($key, $val)
        {
            $this->vars[$key] = $val;
            return $this;
        }

        function add_var()
        {
            $var_num = count($this->vars);
            $new_var = 'var' . ($var_num + 1);
            $this->vars[$new_var] = rand(0, 100);

            return $this;
        }

        function list_vars()
        {
            return array_keys($this->vars);
        }

        function get_var_data()
        {
            return $this->vars;
        }

    }

