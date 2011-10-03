<?php

    class Form_Element_Select extends Form_Element_Abstract
    {
        private $items = array();

        public function __construct($name, $title='')
        {
            parent::__construct('select', $name, $title);

            $this->settings['selected'] = '';
            $this->settings['size']     = 0;
        }

        public function add_item($item_value, $item_name='')
        {
            if ($item_name === '')
            {
                $item_name = $item_value;
            }
            $this->items[$item_name] = $item_value;
        }

        public function render()
        {
            $name      = $this->element_name;
            $size      = $this->settings['size'];
            $selected  = $this->settings['selected'];

            $html   = "<select ";
            $html  .= $this->get_css();
            $html  .= "name='$name' ";
            $html  .= "size='$size'>\n";
            foreach ($this->items as $item_name => $item_value)
            {
                $html  .= "<option value='$item_value' ";
                if ($item_value == $selected)
                {
                    $html .= "selected='selected' ";
                }
                $html .= ">";
                $html .= "$item_name";
                $html .= "</option>\n";
            }
            $html  .= "</select>\n";

            return $html;
        }

    }
