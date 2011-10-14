<?php

class c_args extends Controller
{
    protected function parse_args($args)
    {
        $this->args = new Parser_Array($args);
        $this->args->add_arg(0, 'name', 'string', 'World')
                   ->add_arg(1, 'age',  'string', '4.5 Billion');
    }

    public function _get()
    {
        $page    = new View('main',    'layouts', VPATH);
        $sidebar = new View('sidebar', 'layouts', VPATH);
        $content = new View('content', 'test',    VPATH);
        $header  = new View('header',  'test',    VPATH);

        $content->set('name', $this->args->name)
                ->set('age',  $this->args->age);

        $page->set('sidebar', $sidebar->package());

        $page->set('header',   $header->package());
        $page->set('content', $content->package());

        $page->render();
    }
}
