<?php

    class Form_Basic extends Form_Abstract
    {
        private $no_labels = array();

        public function __construct($url, $method='GET')
        {
            parent::__construct($url, $method);

            $this->settings['labels'] = False;
            $this->no_labels = array('hidden', 'submit');
        }

        private function render_label($element)
        {
            $name  = $element->get_name();
            $title = $element->get_title();
            $type  = $element->get_type();

            $html  = '';

            if ( ! in_array($type, $this->no_labels) )
            {
                $html  = "<label for='$name'>$title</label>\n";
            }
            return $html;
        }

        public function render()
        {
            $action = $this->action_url;
            $method = $this->method;

            $labels = $this->settings['labels'];

            $form_html  = "<form ";
            $form_html .= $this->get_css();

            $form_html .= "action='$action' ";
            $form_html .= "method='$method'>\n";

            foreach ($this->elements as $element)
            {
                if ($labels)
                {
                    $this->render_label($element);
                }

                $form_html .= $element->render();
            }
            $form_html .= "<input type='submit'>\n";
            $form_html .= "</form>\n";
        }
    }
