<?php

    function _index()
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

        $page    = new View("main",    "layouts", VPATH);
        $sidebar = new View("sidebar", "layouts", VPATH);
        $header  = new View("header",  "classtest",    VPATH);
        $content = new View("content", "classtest",    VPATH);


        $content->set("nickname", $arg_parser->nickname);
        $content->set("birth", $arg_parser->birth);
        $content->set("penguins", $arg_parser->penguins);
        $content->set("pies", $arg_parser->pies);
        if ( $arg_parser->awesome )
        {
            $awesomeorno = 'really';
        }
        else
        {
            $awesomeorno = 'not';
        }
        $content->set("awesomeorno", $awesomeorno);
        $content->set("form_html", $form->render());

        $page->set("sidebar", $sidebar->pack_view());

        $page->set("header",   $header->pack_view());
        $page->set("content", $content->pack_view());
        
        $page->show_view();
    }

