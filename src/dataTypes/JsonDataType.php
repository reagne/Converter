<?php

class JsonDataType
{
    static public function getInternalData($code)
    {
        return $table = json_decode($code);
    }

    static public function ConvertTo ($internalData, $headers)
    {
        if($headers) {
            if (!empty($internalData[0])) {
                $empty = [];
                array_unshift($internalData, $empty);
            }
        } else {
            if (empty($internalData[0])) {
                $internalData = array_slice($internalData, 1);
            }
        }

        $convertedCode = json_encode($internalData);

        return $convertedCode;
    }
}