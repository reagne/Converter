<?php

require_once(dirname(__FILE__) . "/../connection.php");

interface DataTypesInterface
{
    /**
     * @param string $code
     * @param boolean $headers
     * @return array
     */
    static public function GetInternalData($code, $headers);
    // Remember to add at the beginning of the return array, empty array if $headers is true.

    /**
     * @param array $internalData
     * @return string
     */
    static public function ConvertTo($internalData);
}