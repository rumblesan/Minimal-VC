<?php

class c_loading extends Controller
{
    protected function parse_args($args)
    {
        $this->args = new Parser_Request();
        $this->args->add_arg('nickname', 'string', 'Guy')
                   ->add_arg('birth','date', '2000-01-01')
                   ->add_arg('penguins', 'int', 42)
                   ->add_arg('pies', 'float', 3.14159)
                   ->add_arg('awesome', 'boolean', False);
    }

    public function _get()
    {

        //create the form
        $form = new Form_Table('tests/loading/');

        $name_el = $form->add_textbox('nickname', 'Nickname');
        $name_el->setting('value', $this->args->nickname);

        $birth_el = $form->add_textbox('birth', 'Date');
        $birth_el->setting('value', $this->args->birth);

        $penguins_el = $form->add_select('penguins', 'Penguins');
        for ($i = 0; $i < 10; $i++)
        {
            $penguins_el->add_item($i, "$i penguins");
        }
        $penguins_el->setting('selected', $this->args->penguins);

        $pies_el = $form->add_textbox('pies', 'Pies');
        $pies_el->setting('value', $this->args->pies);

        $awesome_el = $form->add_checkbox('awesome', 'Awesome?');
        $awesome_el->setting('checked', $this->args->awesome);


        //create the table
        $table = new Table_Basic();
        $caption = $table->add_caption('A Table of information');

        $header  = $table->add_header();
        $header->add_cell('Question');
        $header->add_cell('Answer');

        $new_row  = $table->add_row();
        $new_row->add_cell('Who Are You?');
        $new_row->add_cell($this->args->nickname);

        $new_row  = $table->add_row();
        $new_row->add_cell('Your Birthday Is?');
        $new_row->add_cell($this->args->birth);

        $new_row  = $table->add_row();
        $new_row->add_cell('How many Penguins do you own?');
        $new_row->add_cell($this->args->penguins);

        $new_row  = $table->add_row();
        $new_row->add_cell('How many Pies did you eat?');
        $new_row->add_cell($this->args->pies);

        $new_row  = $table->add_row();
        $new_row->add_cell('Is this Awesome?');
        if ( $this->args->awesome )
        {
            $new_row->add_cell('Hell Yeah');
        }
        else
        {
            $new_row->add_cell('Not Really');
        }

        // get the views
        $page    = new View('main',    'layouts',   VPATH);
        $sidebar = new View('sidebar', 'layouts',   VPATH);
        $content = new View('content', 'classtest', VPATH);
        $header  = new View('header',  'classtest', VPATH);

        //insert data into views
        $content->set("table_html", $table->render());
        $content->set("form_html", $form->render());

        $page->set("sidebar", $sidebar->package());

        $page->set("header",   $header->package());
        $page->set("content", $content->package());

        //render final view
        $page->render();
    }
}

