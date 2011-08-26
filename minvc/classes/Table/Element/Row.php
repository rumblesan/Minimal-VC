<?php

class Table_Element_Row extends Table_Element_Abstract
{
    private $cell_types = array('data'    => 'Table_Element_Data',
                                'header'  => 'Table_Element_Header',
                                'caption' => 'Table_Element_Caption');

    private $cells     = array();
    private $cell_type;

    public function __construct($cell_type, $id='', $class='')
    {
        parent::__construct($id, $class);

        if ( ! isset($this->cell_types[$cell_type]) )
        {
            $cell_type = 'data';
        }
        $this->cell_type = $this->cell_types[$cell_type];
    }

    public function add_cell($content, $id='', $class='')
    {
        $this->cells[] = new $this->cell_type($content, $id, $class);
        return $this;
    }

    public function render()
    {
        $row_html  = "<tr ";
        $row_html .= $this->get_css();
        $row_html .= ">\n";
        foreach ($this->cells as $cell)
        {
            $row_html .= $cell->render();
        }
        $row_html .= "</tr>\n";
        return $row_html;
    }
}
