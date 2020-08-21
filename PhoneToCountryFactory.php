<?php

require_once('GeoNames.php');

class PhoneToCountryFactory
{
    public static function create()
    {
        return new GeoNames();
    }
}

?>