<?php

    function _index()
    {
        $content = new View("content", "main", VPATH);
        $header  = new View("header",  "main", VPATH);
        $page    = new View("main", "layouts", VPATH);

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());

        $page->show_view();
    }

