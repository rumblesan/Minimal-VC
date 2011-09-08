<?php

class c_loading extends Controller
{
    public function _get()
    {
        $arg_parser = new Parser_Request_Parser();
        $arg_parser->add_arg('nickname', 'string', 'Guy')
                   ->add_arg('birth','date', '2000-01-01')
                   ->add_arg('penguins', 'int', 42)
                   ->add_arg('pies', 'float', 3.14159)
                   ->add_arg('awesome', 'boolean', False);

        $view = $this->get_view('tests/loading');
        $view->set('nickname', $arg_parser->nickname)
             ->set('birth',    $arg_parser->birth)
             ->set('penguins', $arg_parser->penguins)
             ->set('pies',     $arg_parser->pies)
             ->set('awesome',  $arg_parser->awesome);
        
        $view->render();
    }
}

