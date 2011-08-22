<?php

    class Form_Form_Table extends Form_Form_Abstract
    {
        private $no_headers = array();

        public function __construct($url, $method='GET')
        {
            parent::__construct($url, $method);
            $this->no_headers = array('hidden', 'submit');
        }

        private function render_header($element)
        {
            $name  = $element->get_name();
            $title = $element->get_title();

            $html  = "<th><label for='$name'>$title</label></th>\n";

            return $html;
        }

        public function render()
        {
            $header_html  = '';
            $element_html = '';

            $submit_html = '';

            foreach ($this->elements as $element)
            {
                $type  = $element->get_type();

                if ( ! in_array($type, $this->no_headers) )
                {
                    $header_html  .= $this->render_header($element);
                }
                if ($type == 'submit')
                {
                    $submit_html = $element->render();
                }
                elseif ($type == 'hidden')
                {
                    $element_html .= $element->render();
                }
                else
                {
                    $element_html .= "<td>";
                    $element_html .= $element->render();
                    $element_html .= "</td>";
                }
            }

            $action = $this->action_url;
            $method = $this->method;

            $form_html  = "<form ";
            $form_html .= $this->get_css();

            $form_html .= "action='$action' ";
            $form_html .= "method='$method'>\n";


            $form_html .= "<table>\n";
            $form_html .= "<tr>$header_html</tr>\n";
            $form_html .= "<tr>$element_html</tr>\n";
            $form_html .= "</table>\n";
            $form_html .= "<tr><td><input type='submit'></td></tr>\n";
            $form_html .= "</form>\n";

            return $form_html;
        }
    }
