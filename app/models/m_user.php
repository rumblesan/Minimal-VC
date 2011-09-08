<?php

class User extends Model
{
    function __construct($uid='')
    {
        parent::__construct('uid','users');
        $this->rs['uid'] = '';
        $this->rs['username'] = '';
        $this->rs['password'] = '';
        if ($uid != '')
        {
            $this->retrieve($uid);
        }
    }

}
