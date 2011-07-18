<?php

    function _index()
    {
        $content = new View("content", "loading", VPATH);
        $header  = new View("header",  "loading", VPATH);
        $page    = new View("main",    "layouts", VPATH);

        $test    = new Test('test 1');

        $test->add_var()
             ->add_var();

        $test->set('var3', 200);

        $var_list = $test->get_var_data();

        foreach ($var_list as $name => $value)
        {
            $content->set($name, $value);
        }

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());

        $page->show_view();
    }

