<?php

ini_set("include_path", ".:". $_SERVER['DOCUMENT_ROOT'] . "/dtest");

require_once("utests/simpletest/autorun.php");
require_once("app/core/View.class.php");

class TestView extends View
{

    function get_args()
    {
        return $this->args;
    }

    function get_file()
    {
        return $this->file;
    }

}

class ViewTests extends UnitTestCase
{

    private $view_name = "view";
    private $view_type = "views";
    private $base      = "/dtest/utests/";
    private $view_dir;


    function setUp()
    {
        $this->view_dir  = $_SERVER['DOCUMENT_ROOT'] . $this->base;
    }

    function testCreateNewView()
    {
        $View = new TestView('', '');
        $this->assertisA($View, 'View');
    }

    function testSetArgs()
    {
        $view = new TestView('', '', './');
        $view->set("test", 3);

        $this->assertIdentical(3, $view->get("test"));
    }

    function testSetAllArgs()
    {
        $args = array("test1" => 1, "test2" => 2);

        $view = new TestView('', '', './', $args);

        $this->assertIdentical($args, $view->get_args());
    }

    function testGetArgs()
    {
        $args = array("test1" => 1, "test2" => 2);

        $view = new TestView('', '', './', $args);

        $this->assertIdentical($args["test1"], $view->get("test1"));
    }

    function testFileName()
    {
        $file_name = $this->view_dir . $this->view_type . "/" . $this->view_name . ".php";
        $view      = new TestView($this->view_name, $this->view_type, $this->view_dir, $args);
        $output    = $view->get_file();

        $this->assertIdentical($file_name, $output);

    }

    function testFilePack()
    {
        $var       = "blah blah";
        $args      = array("var" => $var);
        $check     = "test html here $var";

        $view   = new TestView($this->view_name, $this->view_type, $this->view_dir, $args);
        $output = $view->pack_view();

        $this->assertIdentical($check, $output);

    }

    function testFileOutput()
    {
        $var       = "blah blah";
        $html      = 'test html here <?php echo $var?>';
        $args      = array("var" => $var);
        $check     = "test html here $var";

        $view   = new TestView($this->view_name, $this->view_type, $this->view_dir, $args);
        ob_start();
        $output = $view->show_view();
        $output = ob_get_clean();

        $this->assertIdentical($check, $output);

    }

}



