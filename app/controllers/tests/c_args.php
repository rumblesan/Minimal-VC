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
        $page    = $this->get_view('main',    'layouts');
        $sidebar = $this->get_view('sidebar', 'layouts');
        $content = $this->get_view('content', 'test');
        $header  = $this->get_view('header',  'test');

        $content->set('name', $this->args->name)
                ->set('age',  $this->args->age);

        $page->set('sidebar', $sidebar->package());

        $page->set('header',   $header->package());
        $page->set('content', $content->package());

        $page->render();
    }
}
