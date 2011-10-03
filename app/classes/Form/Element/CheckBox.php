<?php

    class Form_Element_CheckBox extends Form_Element_Abstract
    {
        public function __construct($name, $title='')
        {
            parent::__construct('checkbox', $name, $title);

            $this->settings['value']   = True;
            $this->settings['checked'] = False;
        }

        public function render()
        {
            $name    = $this->element_name;
            $value   = $this->settings['value'];
            $checked = $this->settings['checked'];

            $html   = "<input type='checkbox' ";
            $html  .= $this->get_css();
            $html  .= "name='$name' ";
            $html  .= "value='$value' ";
            if ($checked == True)
            {
                $html  .= "checked ";
            }
            $html  .= ">\n";

            return $html;
        }
    }
