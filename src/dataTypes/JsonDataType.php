<?php

require_once(dirname(__FILE__) . "/../connection.php");

class JsonDataType implements DataTypesInterface
{
    /**
     * @param string $code
     * @param boolean $headers
     * @return array
     */
    static public function GetInternalData($code, $headers)
    {
        $table = json_decode($code);

        if($headers) {
            if (!empty($table[0])) {
                $empty = [];
                array_unshift($table, $empty);
            }
        } else {
            if (empty($table[0])) {
                $table = array_slice($table, 1);
            }
        }

        return $table;
    }

    /**
     * @param array $internalData
     * @return string
     */
    static public function ConvertTo ($internalData)
    {
        $convertedCode = json_encode($internalData);

        return $convertedCode;
    }
}