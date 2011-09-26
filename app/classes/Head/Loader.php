<?php

class Head_Loader
{
    public $js_path;
    public $css_path;

    public $load = array();
    public $vars = array();

    public function __construct($js_path='/js/', $css_path='/css/')
    {
        $this->js_path  = $js_path;
        $this->css_path = $css_path;
    }

    public function add_data($data, $type)
    {
        $info = array();
        $info['data'] = $data;
        $info['type'] = $type;

        $this->load[] = $info;
        return $this;
    }

    public function js_file($js_file)
    {
        return $this->add_data($js_file, 'js_file');
    }

    public function js_script($js_script)
    {
        return $this->add_data($js_script, 'js_script');
    }

    public function js_variable($var_name, $var_value)
    {
        $this->vars[$var_name] = $var_value;
        return $this;
    }

    public function css_file($css_file)
    {
        return $this->add_data($css_file, 'css_file');
    }



    public function render_js_file($js_file)
    {
        $html  = '<script type="text/Javascript" src="';
        $html .= $this->js_path . $js_file;
        $html .= '"></script>';
        return $html;
    }

    public function render_js_script($js_script)
    {
        return '<script>' . $js_script . '</script>';
    }

    public function render_css_file($css_file)
    {
        $html  = '<link type="text/css" href="';
        $html .= $this->css_path . $css_file;
        $html .= '" rel="stylesheet"></script>';
        return $html;
    }


    public function render()
    {
        $html = "\n";

        if (count($this->vars > 0))
        {
            $html .= '    <script>';
            $html .= "\n";
            foreach ($this->vars as $var_name => $var_value)
            {
                $html .= "        var $var_name = $var_value;";
                $html .= "\n";
            }
            $html .= '    </script>';
            $html .= "\n";
        }

        foreach ($this->load as $load_info)
        {
            $function = 'render_' . $load_info['type'];
            $html .= "\n    ";
            $html .= $this->$function($load_info['data']);
        }

        $html .= "\n";

        return $html;
    }

}

