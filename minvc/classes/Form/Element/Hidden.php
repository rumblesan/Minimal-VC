<?php

    class Form_Element_Hidden extends Form_Element_Abstract
    {
        public function __construct($name)
        {
            parent::__construct('hidden', $name);

            $this->settings['value'] = '';
        }

        public function render()
        {
            $name  = $this->element_name;
            $value = $this->settings['value'];

            $html   = "<input type='hidden' ";
            $html  .= "name='$name' ";
            $html  .= "value='$value'>\n";

            return $html;
        }

    }
