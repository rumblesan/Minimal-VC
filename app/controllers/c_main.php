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
        
        $page    = $this->get_view('main',    'layouts');
        $sidebar = $this->get_view('sidebar', 'layouts');
        $content = $this->get_view('content', 'loading');
        $header  = $this->get_view('header',  'loading');

        $content->merge($test->get_var_data());

        $page->set("sidebar", $sidebar->package());

        $page->set("header",  $header->package());
        $page->set("content", $content->package());

        $page->render();

    }
}

