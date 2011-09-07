<?php

class c_args extends Controller
{
    public function _get()
    {
        $age  = $this->args[1];
        $name = $this->args[0];
        $name = (isset($name) && $name) ? $name : "World";
        $age  = (isset($age)  && $age)  ? $age  : "4.54 billion";

        $page    = new View("main",    "layouts", VPATH);
        $sidebar = new View("sidebar", "layouts", VPATH);
        $header  = new View("header",  "test",    VPATH);
        $content = new View("content", "test",    VPATH);

        $content->set("name", $name);
        $content->set("age",  $age);

        $page->set("sidebar", $sidebar->pack_view());

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());

        $page->show_view();
    }
}
