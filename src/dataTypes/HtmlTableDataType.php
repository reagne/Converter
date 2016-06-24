<?php

class HtmlTableDataType
{
    /**
     * @param string $code
     * @return array
     */
    static public function getInternalData ($code)
    {
        $DOM = new DOMDocument;
        $DOM->loadHTML($code);

        $tables = $DOM->getElementsByTagName('table');

        //get all rows from the table
        $rows = $tables->item(0)->getElementsByTagName('tr');

        // get each column headers by tag name
        $headers = $rows->item(0)->getElementsByTagName('th');
        $row_headers = NULL;
        foreach ($headers as $node) {
            $row_headers[] = $node->nodeValue;
        }

        $table = [];

        foreach ($rows as $row)
        {
            // get each column by tag name
            $cols = $row->getElementsByTagName('td');
            $row = [];
            $i = 0;
            foreach ($cols as $node) {
                if($row_headers==NULL) {
                    $row[] = $node->nodeValue;
                } else {
                    $row[$row_headers[$i]] = $node->nodeValue;
                }
                $i++;
            }
            $table[] = $row;
        }

        return $table;
    }

    /**
     * @param array $internalData
     * @param boolean $headers
     * @return string
     */
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

        return $convertedCode;
    }
};