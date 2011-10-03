<?php

class Table_Element_Header extends Table_Element_Abstract
{
    private $content;

    public function __construct($content, $id='', $class='')
    {
        parent::__construct($id, $class);

        $this->content = $content;
    }

    public function render()
    {
        $cell_html  = "<th ";
        $cell_html .= $this->get_css();
        $cell_html .= ">";
        $cell_html .= $this->content;
        $cell_html .= "</th>\n";
        return $cell_html;
    }
}
