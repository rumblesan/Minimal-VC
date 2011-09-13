<?php

class v_args extends View
{
    protected function defaults()
    {
        $this->values['name'] = 'World';
        $this->values['age']  = '4.54 billion';
    }

    public function render()
    {
        $page    = $this->get_template('main',    'layouts');
        $sidebar = $this->get_template('sidebar', 'layouts');
        $content = $this->get_template('content', 'test');
        $header  = $this->get_template('header',  'test');

        $content->merge($this->values);

        $page->set("sidebar", $sidebar->package());

        $page->set("header",   $header->package());
        $page->set("content", $content->package());

        $page->render();
    }
}

