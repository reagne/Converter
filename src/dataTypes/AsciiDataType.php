<?php

require_once(dirname(__FILE__) . "/../connection.php");

class AsciiDataType implements DataTypesInterface
{
    /**
     * @param string $code
     * @param boolean $headers
     * @return array
     */
    static public function GetInternalData($code, $headers)
    {
        $code = mb_convert_encoding($code, 'HTML-ENTITIES', 'ASCII');

        $table = [];

        $rows = explode("\r\n", $code);

        foreach ($rows as $row) {
            $newRow = preg_replace('/[|\+\-_\*]/', '#', $row);
            $cells = explode("#", $newRow);
            $cells = array_filter($cells, function($val){ return $val; });

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

            if (empty($table[0])) {
                $newTable = array_slice($newTable, 1);
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
        $newCode = "";
        $padding = 0;
        $array = [];

        if (empty($internalData[0])) {
            $headers = next($internalData);

            $array[] = array_keys($headers);

            for ($i = 0; $i < count($array); $i++) {
                if ($padding < strlen($array[$i])) {
                    $padding = strlen($array[$i]);
                }
            }
        }

        foreach ($internalData as $row) {
            if (!empty($row)) {
                $cells = [];

                foreach ($row as $cell) {
                    $cells[] = $cell;

                    if ($padding < strlen($cell)) {
                        $padding = strlen($cell);
                    }
                }
                $array[] = $cells;
            }
        }

        foreach ($array as $items) {
            $newCode .= "|";

            foreach ($items as $item) {
                $newCode .= str_pad($item, $padding) . "|";
            }
            $newCode .= PHP_EOL;
        }

        return $newCode;
    }
}