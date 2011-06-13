<?php

    function _index($args)
    {
        $name = (isset($args[0]) && $args[0]) ? $args[0] : "World";
        $age  = (isset($args[1]) && $args[1]) ? $args[1] : "4.54 billion";

        $page    = new View("layouts", "main", VPATH);
        $header  = new View("test", "header",  VPATH);
        $content = new View("test", "content",  VPATH);

        $content->set("name", $name);
        $content->set("age",  $age);

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());
        
        $main->show_view();
    }

