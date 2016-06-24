<?php

abstract class Converter
{
    // array $internalData
    // $string $type
    static public function ConvertTo ($internalData, $type, $headers)
    {
        // If you want to add another format, add it to switch list. The case must be the same as $key in selectTypes.php
        $convertedCode = "";

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

        switch ($type) {
            case "json":
                $convertedCode = json_encode($internalData);
                break;

            case "htmlList":
                $newCode = "";
                $firstRow = [];

                // If table have headings first element in the array will be empty. If it's empty we will take headings from next element and put it in a new array
                if (empty($internalData[0])) {
                    $headers = next($internalData);

                    $firstRow = array_keys($headers);
                }

                $cells = "";

                // If table doesn't have headings create normal list
                if (empty($firstRow)) {
                    foreach ($internalData as $row) {
                        $cells .= "<li>" . PHP_EOL . "<ul>" . PHP_EOL;
                        foreach ($row as $key => $cell) {
                            $cells .= "<li>" . $cell . "</li>" . PHP_EOL;
                        }
                        $cells .= "</ul>" . PHP_EOL . "</li>" . PHP_EOL;
                    }
                    // If table do have headings create a nested list
                } else {
                    for ($i = 0; $i < count($firstRow); $i++) {
                        $cells .= "<li>" . $firstRow[$i] . PHP_EOL . "<ul>" . PHP_EOL;

                        foreach ($internalData as $row) {
                            if (!empty($row)) { // only add rows that are not empty
                                foreach ($row as $key => $cell) {
                                    if ($firstRow[$i] == $key) {
                                        $cells .= "<li>" . $cell . "</li>" . PHP_EOL;
                                    }
                                }
                            }
                        }
                        $cells .= "</ul>" . PHP_EOL . "</li>" . PHP_EOL;
                    }
                }

                if ($cells) {
                    $newCode .= $cells;
                }

                $convertedCode = "<ul>" . PHP_EOL . $newCode . "</ul>";
                break;

            case "htmlTable":
            case "xml":
                $newCode = "";

                foreach ($internalData as $row) {
                    $cells = "";
                    // If table have headings there will be empty element in the array at the beggining. If there is emty element create <thead> at the beginning of the code
                    if (empty($row)) {
                        $firstRow = "";

                        $headers = next($internalData);

                        foreach ($headers as $key => $cell) {
                            $firstRow .= "<th>" . $key . "</th>" . PHP_EOL;
                        }

                        $newCode = "<thead>" . PHP_EOL . "<tr>" . PHP_EOL . $firstRow . "</tr>" . PHP_EOL . "</thead>" . PHP_EOL;
                    }

                    // Create normal rows in table
                    foreach ($row as $key => $cell) {
                        $cells .= "<td>" . $cell . "</td>" . PHP_EOL;
                    }

                    // Add cells that are not empty
                    if ($cells) {
                        $newCode .= "<tr>" . PHP_EOL . $cells . "</tr>" . PHP_EOL;
                    }
                }
                $convertedCode = "<table>" . PHP_EOL . $newCode . "</table>";
                break;

            case "csv":
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
                break;

            case "ascii":
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

                $convertedCode = $newCode;
                break;
        }

        return $convertedCode;
    }

    // string $code
    abstract public function getInternalData($code);
    // these class turn input data into an array.

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($newCode)
    {
        return $this->code = $newCode;
    }
}