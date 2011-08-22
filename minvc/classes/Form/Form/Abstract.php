<?php

    abstract class Form_Form_Abstract
    {
        protected $action_url;
        protected $method;

        protected $method_list = array('GET', 'POST');

        protected $settings = array();

        protected $css = array('id' => '', 'class' => '');

        protected $elements  = array();

        public function __construct($url, $method='GET')
        {
            $this->action_url = $url;

            if ( ! in_array($method, $this->method_list) )
            {
                $method = 'GET';
            }
            $this->method = $method;
        }

        public function add_checkbox($name, $title='')
        {
            $this->elements[$name] = new Form_Element_CheckBox($name, $title);
            return $this->elements[$name];
        }

        public function add_hidden($name, $title='')
        {
            $this->elements[$name] = new Form_Element_Hidden($name, $title);
            return $this->elements[$name];
        }

        public function add_select($name, $title='')
        {
            $this->elements[$name] = new Form_Element_Select($name, $title);
            return $this->elements[$name];
        }

        public function add_textbox($name, $title='')
        {
            $this->elements[$name] = new Form_Element_Text($name, $title);
            return $this->elements[$name];
        }

        public function mod_element($el_name)
        {
            if ( isset($this->elements[$el_name]) )
            {
                return $this->elements[$el_name];
            }
        }

        public function setting($name, $value)
        {
            if ( isset($this->items[$name]) )
            {
                $this->items[$name] = $value;
            }
            return $this;
        }

        public function css($name, $value)
        {
            if ( isset($this->css[$name]) )
            {
                $this->css[$name] = $value;
            }
            return $this;
        }

        protected function get_css()
        {
            $css_id    = $this->css['id'];
            $css_class = $this->css['class'];

            $html  = "";
            if ($css_id != '')
            {
                $html .= "id='$css_id' ";
            }
            if ($css_class != '')
            {
                $html .= "class='$css_class' ";
            }
            return $html;
        }

        public function add_element($element)
        {
            $el_name = $element->get_name();
            $this->elements[$el_name] = $element;
            return $this;
        }

        abstract public function render();

    }
