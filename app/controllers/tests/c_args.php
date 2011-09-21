<?php

class c_args extends Controller
{
    public function _get()
    {
        if (isset($this->args[0]) && $this->args[0])
        {
            $name = $this->args[0];
        }
        else
        {
            $name = 'World';
        }

        if (isset($this->args[1]) && $this->args[1])
        {
            $age  = $this->args[1];
        }
        else
        {
            $name = '4.54 billion';
        }

        $page    = $this->get_view('main',    'layouts');
        $sidebar = $this->get_view('sidebar', 'layouts');
        $content = $this->get_view'content', 'test');
        $header  = $this->get_view('header',  'test');

        $content->set('name', $name)
                ->set('age',  $age);

        $page->set('sidebar', $sidebar->package());

        $page->set('header',   $header->package());
        $page->set('content', $content->package());

        $page->render();
    }
}
