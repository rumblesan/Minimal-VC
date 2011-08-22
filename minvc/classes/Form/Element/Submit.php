<?php

    class Form_Element_Submit extends Form_Element_Abstract
    {
        public function __construct($name)
        {
            parent::__construct('submit', $name);

            $this->settings['value'] = '';
        }

        public function render()
        {
            $name  = $this->element_name;
            $value = $this->settings['value'];

            $html   = "<input type='submit' ";
            $html  .= "name='$name' ";
            $html  .= "value='$value'>\n";

            return $html;
        }

    }
