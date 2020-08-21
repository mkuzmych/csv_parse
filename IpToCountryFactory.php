<?php

require_once('IpStack.php');

class IpToCountryFactory
{
    public static function create()
    {
        return new IpStack();
    }
}

?>