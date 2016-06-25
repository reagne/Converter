<?php

require_once(dirname(__FILE__) . "/../connection.php");

class HtmlTableDataType implements DataTypesInterface
{
    /**
     * @param string $code
     * @param boolean $headers
     * @return array
     */
    static public function GetInternalData ($code, $headers)
    {
        $DOM = new DOMDocument;
        $DOM->loadHTML($code);

        $tables = $DOM->getElementsByTagName('table');

        //get all rows from the table
        $rows = $tables->item(0)->getElementsByTagName('tr');

        // get each column headers by tag name
        $headColumn = $rows->item(0)->getElementsByTagName('th');
        $rowHeaders = NULL;

        foreach ($headColumn as $node) {
            $rowHeaders[] = $node->nodeValue;
        }

        $table = [];

        foreach ($rows as $row) {
            // get each column by tag name
            $cols = $row->getElementsByTagName('td');
            $row = [];
            $i = 0;

            foreach ($cols as $node) {
                if($rowHeaders == NULL) {
                    $row[] = $node->nodeValue;
                } else {
                    $row[$rowHeaders[$i]] = $node->nodeValue;
                }
                $i++;
            }

            $table[] = $row;
        }

        if ($headers) {
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
        $newCode = "";

        foreach ($internalData as $row) {
            $cells = "";
            // If table have headings there will be empty element in the array at the beginning. If there is emty element create <thead> at the beginning of the code
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

        return $convertedCode;
    }
};