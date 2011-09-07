<?php

class v_args extends View
{
    public function __construct()
    {
        $this->values['name'] = 'World';
        $this->values['age']  = '4.54 billion';
    }

    public function render()
    {
        $page    = new Template("main",    "layouts", TPATH);
        $sidebar = new Template("sidebar", "layouts", TPATH);
        $content = new Template("content", "test",    TPATH);
        $header  = new Template("header",  "test",    TPATH);

        $content->merge($this->values);

        $page->set("sidebar", $sidebar->package());

        $page->set("header",   $header->package());
        $page->set("content", $content->package());

        $page->render();
    }
}

