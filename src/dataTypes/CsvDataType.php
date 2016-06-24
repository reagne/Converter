<?php

require_once(dirname(__FILE__) . "/../connection.php");

class CsvDataType implements DataTypesInterface
{
    /**
     * @param string $code
     * @param boolean $headers
     * @return array
     */
    static public function GetInternalData($code, $headers)
    {
        $table = [];

        $rows = explode("\r\n", $code);

        foreach ($rows as $row) {
            $cells = explode(",", $row);
            $table[] = $cells;
        }

        if ($headers) {
            $keys = $table[0];

            $table = array_slice($table, 1);

            $newTable = [[]];
            foreach ($table as $tab) {
                array_push($newTable, array_combine($keys, $tab));
            }
        } else {
            $newTable = $table;
            if (empty($newTable[0])) {
                array_slice($newTable, 1);
            }
        }

        return $newTable;
    }

    /**
     * @param array $internalData
     * @return string
     */
    static public function ConvertTo ($internalData)
    {
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