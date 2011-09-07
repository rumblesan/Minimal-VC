<?php

class c_args extends Controller
{
    public function _get()
    {
        $view = $this->get_view('tests/args');

        if (isset($this->args[0]) && $this->args[0])
        {
            $view->name = $this->args[0];
        }

        if (isset($this->args[1]) && $this->args[1])
        {
            $view->age  = $this->args[1];
        }

        $view->render();
    }
}
