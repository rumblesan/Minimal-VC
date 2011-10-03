<?php

    class Form_Element_Text extends Form_Element_Abstract
    {
        public function __construct($name, $title='')
        {
            parent::__construct('text', $name, $title);

            $this->settings['size']     = 10;
            $this->settings['value']    = '';
            $this->settings['readonly'] = False;
        }

        public function render()
        {
            $name  = $this->element_name;
            $value = $this->settings['value'];
            $size  = $this->settings['size'];

            $html   = "<input type='text' ";
            $html  .= $this->get_css();
            $html  .= "name='$name' ";
            $html  .= "size='$size' ";
            if ($this->settings['readonly'])
            {
                $html  .= "readonly ";
            }
            $html  .= "value='$value'>\n";

            return $html;
        }

    }
