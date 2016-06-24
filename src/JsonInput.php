<?php
require_once ("Converter.php");

class JsonInput extends Converter
{
    public function getInternalData($code)
    {
        return $table = json_decode($code);
    }
}