<?php

class c_main extends Controller
{
    public function _get()
    {
        $view = $this->get_view('main');

        $test    = new Test('test 1');

        $test->add_var()
             ->add_var();

        $test->set('var3', 200);

        $var_list = $test->get_var_data();
        $view->merge($var_list);

        $view->render();
    }
}

