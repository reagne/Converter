<?php

require_once(dirname(__FILE__) . "/../connection.php");

class HtmlListDataType implements DataTypesInterface
{
    /**
     * @param $code
     * @param $headers
     * @return array
     */
    static public function GetInternalData ($code, $headers)
    {
        $DOM = new DOMDocument;
        $DOM->loadHTML($code);

        $tables = $DOM->getElementsByTagName('ul')->item(0);

        //get all rows from the table
        $rows = $tables->getElementsByTagName('ul');

        $table = [];

        foreach ($rows as $row) {
            // get each column by tag name
            $cols = $row->getElementsByTagName('li');

            $row = [];

            foreach ($cols as $node) {
                $row[] = $node->nodeValue;
            }

            $table[] = $row;
        }

        if ($headers) {
            $xml = simplexml_load_string($code, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $keys = json_decode($json,TRUE)['li'];

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
     * @param $internalData
     * @return string
     */
    static public function ConvertTo ($internalData)
    {
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

        return $convertedCode;
    }
};