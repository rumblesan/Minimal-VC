<?php

    #load global variable file from generated config area
    $globals_path[] = $_ENV['CASE'];
    $globals_path[] = "generated-cfg";
    $globals_path[] = $_ENV['FID_SYSTEM_ID'];
    $globals_path[] = php_uname('n');
    $globals_path[] = "hsap_PhpGlobals.auto.cfg";

    $globals_path = join("/",$globals_path);

    require_once($globals_path);

