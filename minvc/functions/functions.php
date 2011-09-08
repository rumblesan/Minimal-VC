<?php

    function href($page)
    {
        echo BASE . $page;
    }

    function getdbh()
    {
        if (!isset($GLOBALS['dbI']))
        {
            try
            {
                $GLOBALS['dbI'] = new PDO('mysql:host=localhost;dbname=dbname', 'username', 'password');
            }
            catch (PDOException $e)
            {
                die('Connection failed: '.$e->getMessage());
            }
        }
        return $GLOBALS['dbI'];
    }

