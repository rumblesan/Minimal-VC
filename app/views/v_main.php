<?php

class v_main extends View
{
    public function __construct()
    {
        $this->values['var1'] = 0;
        $this->values['var2'] = 0;
        $this->values['var3'] = 0;
        $this->values['var4'] = 0;
    }

    public function render()
    {
        $page    = new Template("main",    "layouts", TPATH);
        $sidebar = new Template("sidebar", "layouts", TPATH);
        $content = new Template("content", "loading", TPATH);
        $header  = new Template("header",  "loading", TPATH);

        $content->merge($this->values);

        $page->set("sidebar", $sidebar->package());

        $page->set("header",   $header->package());
        $page->set("content", $content->package());

        $page->render();
    }
}

