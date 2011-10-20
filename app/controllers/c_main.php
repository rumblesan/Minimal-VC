<?php

class c_main extends Controller
{
    public function _get()
    {
        $test    = new Test('test 1');

        $test->add_var()
             ->add_var();

        $test->set('var3', 200);

        $var_list = $test->get_var_data();

        $page    = new View('main',    'layouts', VPATH);
        $sidebar = new View('sidebar', 'layouts', VPATH);
        $content = new View('content', 'loading', VPATH);
        $header  = new View('header',  'loading', VPATH);

        $content->merge($test->get_var_data());

        $page->set("sidebar", $sidebar->package());

        $page->set("header",  $header->package());
        $page->set("content", $content->package());

        $page->render();

    }
}
