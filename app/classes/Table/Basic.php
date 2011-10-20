<?php

class Table_Basic extends Table_Abstract
{
    private $rows    = array();

    public function add_caption($id='', $class='')
    {
        return $this->new_row('caption', $id, $class);
    }

    public function add_header($id='', $class='')
    {
        return $this->new_row('header', $id, $class);
    }

    public function add_row($id='', $class='')
    {
        return $this->new_row('data', $id, $class);
    }

    public function new_row($type, $id='', $class='')
    {
        $new_row = new Table_Element_Row($type, $id, $class);
        $this->rows[] = $new_row;
        return $new_row;
    }

    public function render()
    {
        $table_html  = "<table ";
        $table_html .= $this->get_css();
        $table_html .= ">\n";
        foreach ($this->rows as $row)
        {
            $table_html .= $row->render();
        }
        $table_html .= "</table>\n";
        return $table_html;
    }
}
