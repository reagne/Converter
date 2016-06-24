<?php

class AsciiDataType
{
    static public function getInternalData($code)
    {
        // TODO: Implement getInternalData() method.
    }

    static public function ConvertTo ($internalData, $headers)
    {
        if ($headers) {
            if (!empty($internalData[0])) {
                $empty = [];
                array_unshift($internalData, $empty);
            }
        } else {
            if (empty($internalData[0])) {
                $internalData = array_slice($internalData, 1);
            }
        }

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