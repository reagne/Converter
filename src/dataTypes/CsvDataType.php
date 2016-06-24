<?php

class CsvDataType
{
    static public function getInternalData($code)
    {
        // TODO: Implement getInternalData() method.
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

        $array = [];

        // If table have headings first element in the array will be empty. If it's empty we will take headings from next element and put it in an array
        if (empty($internalData[0])) {
            $headers = next($internalData);

            $firstRow = array_keys($headers);

            $array[] = implode(',', $firstRow);
        }

        foreach ($internalData as $row) {
            if (!empty($row)) {
                $cells = [];
                foreach ($row as $cell) {
                    $cells[] = $cell;
                }
                $array[] = implode(',', $cells);
            }
        }

        $convertedCode = implode(PHP_EOL, $array);

        return $convertedCode;
    }
}