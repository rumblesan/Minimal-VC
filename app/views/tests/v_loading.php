<?php

class v_loading extends View
{
    protected function defaults()
    {
        $this->values['nickname'] = 'Guy';
        $this->values['birth']    = '2000-01-01';
        $this->values['penguins'] = 42;
        $this->values['pies']     = 3.14159;
        $this->values['awesome']  = False;
    }

    public function render()
    {
        $form = new Form_Form_Table('tests/loading/');

        $name_el = $form->add_textbox('nickname', 'Nickname');
        $name_el->setting('value', $this->nickname);

        $birth_el = $form->add_textbox('date', 'Date');
        $birth_el->setting('value', $this->birth);

        $penguins_el = $form->add_select('penguins', 'Penguins');
        for ($i = 0; $i < 10; $i++)
        {
            $penguins_el->add_item($i, "$i penguins");
        }
        $penguins_el->setting('selected', $this->penguins);

        $pies_el = $form->add_textbox('pies', 'Pies');
        $pies_el->setting('value', $this->pies);

        $awesome_el = $form->add_checkbox('awesome', 'Awesome?');
        $awesome_el->setting('checked', $this->awesome);

        $table = new Table_Table();
        $caption = $table->add_caption('A Table of information');

        $header  = $table->add_header();
        $header->add_cell('Question');
        $header->add_cell('Answer');

        $header  = $table->add_row();
        $header->add_cell('Who Are You?');
        $header->add_cell($this->nickname);

        $header  = $table->add_row();
        $header->add_cell('Your Birthday Is?');
        $header->add_cell($this->birth);

        $header  = $table->add_row();
        $header->add_cell('How many Penguins do you own?');
        $header->add_cell($this->penguins);

        $header  = $table->add_row();
        $header->add_cell('How many Pies did you eat?');
        $header->add_cell($this->pies);

        $header  = $table->add_row();
        $header->add_cell('Is this Awesome?');
        if ( $this->awesome )
        {
            $header->add_cell('Hell Yeah');
        }
        else
        {
            $header->add_cell('Not Really');
        }

        $page    = $this->get_template('main',    'layouts');
        $sidebar = $this->get_template('sidebar', 'layouts');
        $header  = $this->get_template('header',  'classtest');
        $content = $this->get_template('content', 'classtest');

        $content->set("table_html", $table->render());
        $content->set("form_html", $form->render());

        $page->set("sidebar", $sidebar->package());

        $page->set("header",   $header->package());
        $page->set("content", $content->package());
        
        $page->render();
    }
}

