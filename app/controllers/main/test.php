<?php

    function _test($args)
    {
        $age  = $args[1];
        $name = $args[0];
        $name = (isset($name) && $name) ? $name : "World";
        $age  = (isset($age)  && $age)  ? $age  : "4.54 billion";

        $page    = new View("main",    "layouts", VPATH);
        $header  = new View("header",  "test",    VPATH);
        $content = new View("content", "test",    VPATH);

        $content->set("name", $name);
        $content->set("age",  $age);

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());
        
        $page->show_view();
    }

