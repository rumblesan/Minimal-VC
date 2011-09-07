<?php

    function _index()
    {
        $dbI = new PDO('mysql:host=localhost;dbname=test', 'dbtest', 'dbtest');

        $user = new User($dbI);
    }

