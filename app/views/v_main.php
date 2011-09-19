<?php

class v_main extends View
{
    protected function defaults()
    {
        $this->values['var1'] = 0;
        $this->values['var2'] = 0;
        $this->values['var3'] = 0;
        $this->values['var4'] = 0;
    }

    public function render()
    {
        $page    = $this->get_template('main',    'layouts');
        $sidebar = $this->get_template('sidebar', 'layouts');
        $content = $this->get_template('content', 'loading');
        $header  = $this->get_template('header',  'loading');

        $content->merge($this->values);

        $page->set("sidebar", $sidebar->package());

        $page->set("header",  $header->package());
        $page->set("content", $content->package());

        $page->render();
    }
}

