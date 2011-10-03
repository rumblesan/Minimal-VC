<?php

class Table_Element_Data extends Table_Element_Abstract
{
    private $content;

    public function __construct($content, $id='', $class='')
    {
        parent::__construct($id, $class);

        $this->content = $content;
    }

    public function render()
    {
        $cell_html  = "<td ";
        $cell_html .= $this->get_css();
        $cell_html .= ">";
        $cell_html .= $this->content;
        $cell_html .= "</td>\n";
        return $cell_html;
    }
}
