<?php

abstract class Table_Element_Abstract
{
    protected $css_id;
    protected $css_class;

    public function __construct($id='', $class='')
    {
        $this->css_id    = $id;
        $this->css_class = $class;
    }

    protected function get_css()
    {
        $id    = $this->css_id;
        $class = $this->css_class;

        $css_html = "";
        if ($id !== '')
        {
            $css_html .= "id='$id' ";
        }

        if ($class !== '')
        {
            $css_html .= "class='$class' ";
        }
        return $css_html;
    }

    abstract public function render();
}
