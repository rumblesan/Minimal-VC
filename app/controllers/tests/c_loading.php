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
                   ->add_arg('awesome', 'boolean', True);

        $form = new Form_Form_Table('/classtest/index/');

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

        $table = new Table_Table();
        $caption = $table->add_caption('A Table of information');

        $header  = $table->add_header();
        $header->add_cell('Question');
        $header->add_cell('Answer');

        $header  = $table->add_row();
        $header->add_cell('Who Are You?');
        $header->add_cell($arg_parser->nickname);

        $header  = $table->add_row();
        $header->add_cell('Your Birthday Is?');
        $header->add_cell($arg_parser->birth);

        $header  = $table->add_row();
        $header->add_cell('How many Penguins do you own?');
        $header->add_cell($arg_parser->penguins);

        $header  = $table->add_row();
        $header->add_cell('How many Pies did you eat?');
        $header->add_cell($arg_parser->pies);

        $header  = $table->add_row();
        $header->add_cell('Is this Awesome?');
        if ( $arg_parser->awesome )
        {
            $header->add_cell('Hell Yeah');
        }
        else
        {
            $header->add_cell('Not Really');
        }

        $page    = new View("main",    "layouts", VPATH);
        $sidebar = new View("sidebar", "layouts", VPATH);
        $header  = new View("header",  "classtest",    VPATH);
        $content = new View("content", "classtest",    VPATH);


        $content->set("table_html", $table->render());
        $content->set("form_html", $form->render());

        $page->set("sidebar", $sidebar->pack_view());

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());
        
        $page->show_view();
    }
}

