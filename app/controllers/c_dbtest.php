<?php

class c_dbtest extends Controller
{
    public function _get()
    {
        $dbI = getdbh()

        $user = new User($dbI);
    }
}

