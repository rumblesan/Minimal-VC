<?php

ini_set("include_path", ".:". $_SERVER['DOCUMENT_ROOT'] . "/dtest");

require_once("utests/simpletest/autorun.php");
require_once("app/core/Controller.class.php");


class TestingController extends Controller
{
    function __get($key)
    {
        if (isset($this->$key))
        {
            return $this->$key;
        }
    }

    function __set($key, $val)
    {
        $this->$key = $val;
        return $this;
    }

    function get($key)
    {
        return $this->__get($key);
    }

    function set($key, $val)
    {
        return $this->__set($key, $val);
    }

    function error()
    {
        echo "ERROR";
    }
}

class ControllerTests extends UnitTestCase
{
    private $controller = "controllers";
    private $function   = "index";
    private $uribase    = "/dtest/utests/";
    private $con_dir;


    function setUp()
    {
        $this->con_dir  = $_SERVER['DOCUMENT_ROOT'] . $this->uribase;
    }

    function testCreateNewController()
    {
        $controller = new Controller();
        $this->assertisA($controller, 'Controller');
    }

    function testDoFormatUri()
    {
        $uri     = "/test/1234/uritest";
        $uribase = "/test/";

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);
        $controller->format_uri();

        $this->assertIdentical("1234/uritest", $controller->uri);
    }

    function testDontFormatUri()
    {
        $uri     = "/blah/1234/uritest";
        $uribase = "/test/";

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);
        $controller->format_uri();

        $this->assertIdentical($uri, $controller->uri);
    }

    function testSplitUri()
    {
        $uri     = "/test/blah/1234/uritest";
        $uribase = "/test/";

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);
        $controller->format_uri()->split_uri();

        $this->assertIdentical("blah", $controller->controller);
        $this->assertIdentical("1234", $controller->function);
        $this->assertIdentical(array("uritest"), $controller->args);
    }

    function testSplitUriNoArgs()
    {
        $uri  = "/test/blah/1234";
        $uribase = "/test/";

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);
        $controller->format_uri()->split_uri();

        $this->assertIdentical("blah", $controller->controller);
        $this->assertIdentical("1234", $controller->function);
        $this->assertIdentical(array(), $controller->args);
    }

    function testSplitUriEndingSlash()
    {
        $uri     = "/test/blah/1234/";
        $uribase = "/test/";

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);
        $controller->format_uri()->split_uri();

        $this->assertIdentical("blah", $controller->controller);
        $this->assertIdentical("1234", $controller->function);
        $this->assertIdentical(array(""), $controller->args);
    }

    function testFormatArgsNormStyle()
    {
        $uri     = "/test/check/blah/1/2";
        $uribase = "/test/";
        $argsout = array("1", "2");

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);
        $controller->format_uri()
                   ->split_uri();

        $this->assertIdentical($argsout, $controller->args);
    }

    function testFormatArgsGetStyle()
    {
        $uri     = "/test/check/blah/?test=1&blah=2";
        $uribase = "/test/";
        $argsout = array("?test=1&blah=2");

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);
        $controller->format_uri()
                   ->split_uri();

        $this->assertIdentical("check", $controller->controller);
        $this->assertIdentical("blah", $controller->function);
        $this->assertIdentical($argsout, $controller->args);
    }

    function testCallFuncs()
    {
        $uri     = $this->controller . "/" . $this->function;
        $uribase = $this->uribase;
        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase)
                   ->set("path", $this->con_dir);

        ob_start();
        $controller->format_uri()
                   ->split_uri()
                   ->call_funcs();
        $output = ob_get_clean();

        $this->assertPattern('/INDEX/', $output);
    }

    function testCallFuncsQuick()
    {
        $uri     = $this->controller . "/" . $this->function;
        $uribase = $this->uribase;

        ob_start();
        $controller = new TestingController($uri, $uribase, $this->con_dir);
        $output = ob_get_clean();

        $this->assertPattern('/INDEX/', $output);
    }

    function testFuncArgs()
    {
        $uri     = $this->controller . "/" . $this->function . "/BLAH";
        $uribase = $this->uribase;

        ob_start();
        $controller = new TestingController($uri, $uribase, $this->con_dir);
        $output = ob_get_clean();

        $this->assertPattern('/INDEX BLAH/', $output);
    }

    function testError()
    {
        $uri     = "/test/check/blah/?test=1&blah=2";
        $uribase = "/test/";

        $controller = new TestingController();
        $controller->set("uri", $uri)
                   ->set("uribase", $uribase);

        ob_start();
        $controller->format_uri()
                   ->split_uri()
                   ->call_funcs();
        $output = ob_get_clean();

        $this->assertPattern('/ERROR/', $output);
    }

    function testErrorQuick()
    {
        $uri     = "/test/check/blah/?test=1&blah=2";
        $uribase = "/test/";

        ob_start();
        $controller = new TestingController($uri, $uribase);
        $output = ob_get_clean();

        $this->assertPattern('/ERROR/', $output);
    }

}



