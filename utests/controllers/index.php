<?php

function _index($args)
{
    if (!$args)
    {
        echo "INDEX";
    }
    else
    {
        echo "INDEX " . $args[0];
    }
}

