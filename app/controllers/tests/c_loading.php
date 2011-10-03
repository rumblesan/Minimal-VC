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
        
        
        //create the form
        $form = new Form_Form_Table('tests/loading/');

        $name_el = $form->add_textbox('nickname', 'Nickname');
        $name_el->setting('value', $arg_parser->nickname);

        $birth_el = $form->add_textbox('date', 'Date');
        $birth_el->setting('value', $arg_parser->birth);

        $penguins_el = $form->add_select('penguins', 'Penguins');
        for ($i = 0; $i < 10; $i++)
        {
            $penguins_el->add_item($i, "$i penguins");
        }
        $penguins_el->setting('selected', $arg_parser->penguins);

        $pies_el = $form->add_textbox('pies', 'Pies');
        $pies_el->setting('value', $arg_parser->pies);

        $awesome_el = $form->add_checkbox('awesome', 'Awesome?');
        $awesome_el->setting('checked', $arg_parser->awesome);

        //create the table
        $table = new Table_Table();
        $caption = $table->add_caption('A Table of information');

        $header  = $table->add_header();
        $header->add_cell('Question');
        $header->add_cell('Answer');

        $new_row  = $table->add_row();
        $new_row->add_cell('Who Are You?');
        $new_row->add_cell($arg_parser->nickname);

        $new_row  = $table->add_row();
        $new_row->add_cell('Your Birthday Is?');
        $new_row->add_cell($arg_parser->birth);

        $new_row  = $table->add_row();
        $new_row->add_cell('How many Penguins do you own?');
        $new_row->add_cell($arg_parser->penguins);

        $new_row  = $table->add_row();
        $new_row->add_cell('How many Pies did you eat?');
        $new_row->add_cell($arg_parser->pies);

        $new_row  = $table->add_row();
        $new_row->add_cell('Is this Awesome?');
        if ( $arg_parser->awesome )
        {
            $new_row->add_cell('Hell Yeah');
        }
        else
        {
            $new_row->add_cell('Not Really');
        }

        $page    = $this->get_view('main',    'layouts');
        $sidebar = $this->get_view('sidebar', 'layouts');
        $header  = $this->get_view('header',  'classtest');
        $content = $this->get_view('content', 'classtest');


        $content->set("table_html", $table->render());
        $content->set("form_html", $form->render());

        $page->set("sidebar", $sidebar->package());

        $page->set("header",   $header->package());
        $page->set("content", $content->package());
        
        $page->render();
    }
}

