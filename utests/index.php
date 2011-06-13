<?php

ini_set("include_path", ".:". $_SERVER['DOCUMENT_ROOT'] . "/dtest/utests");


require_once('simpletest/autorun.php');

class AllFileTests extends TestSuite
{
    function __construct()
    {
        parent::__construct();
        $dr = $_SERVER['DOCUMENT_ROOT'] . "/dtest/utests/";

        $this->addFile($dr . 'ControllerTests.php');
        $this->addFile($dr . 'ViewTests.php');

    }
}


