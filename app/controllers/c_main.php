<?php

class c_loading extends Controller
{
    public function _get()
    {
        $page    = new View("main",    "layouts", VPATH);
        $sidebar = new View("sidebar", "layouts", VPATH);
        $content = new View("content", "loading", VPATH);
        $header  = new View("header",  "loading", VPATH);

        $test    = new Test('test 1');

        $test->add_var()
             ->add_var();

        $test->set('var3', 200);

        $var_list = $test->get_var_data();
        $content->merge($var_list);

        $page->set("sidebar", $sidebar->pack_view());

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());

        $page->show_view();
    }
}

