<?php

    abstract class Form_Element_Abstract
    {
        protected $element_name;
        protected $element_title;
        protected $element_type;

        protected $settings = array();

        protected $css = array('id' => '', 'class' => '');

        public function __construct($type, $name, $title='')
        {
            $this->element_type = $type;

            $this->element_name = $name;

            $this->css['id']    = $name;

            if ($title === '')
            {
                $title = $name;
            }
            $this->element_title = $title;
        }

        public function setting($name, $value)
        {
            if ( isset($this->settings[$name]) )
            {
                $this->settings[$name] = $value;
            }
        }

        public function css($name, $value)
        {
            if ( isset($this->css[$name]) )
            {
                $this->css[$name] = $value;
            }
        }

        protected function get_css()
        {
            $css_id    = $this->css['id'];
            $css_class = $this->css['class'];

            $html  = "id='$css_id' ";
            if ($css_class != '')
            {
                $html  .= "class='$css_class' ";
            }
            return $html;
        }

        public function get_name()
        {
            return $this->element_name;
        }

        public function get_title()
        {
            return $this->element_title;
        }

        public function get_type()
        {
            return $this->element_type;
        }

        abstract public function render();
    }
