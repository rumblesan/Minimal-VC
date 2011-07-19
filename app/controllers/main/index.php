<?php

    function _index()
    {
        $page    = new View("main",    "layouts", VPATH);
        $sidebar = new View("sidebar", "layouts", VPATH);
        $content = new View("content", "main",    VPATH);
        $header  = new View("header",  "main",    VPATH);

        $page->set("sidebar", $sidebar->pack_view());

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());

        $page->show_view();
    }

