<?php

    function _index()
    {
        $content = new View("main",   "content", VPATH);
        $header  = new View("main",   "header",  VPATH);
        $page    = new View("header", "main",    VPATH);

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());

        $page->show_view();
    }

