<?php

require_once(dirname(__FILE__) . "/../connection.php");

class XmlDataType implements DataTypesInterface
{
    /**
     * @param string $code
     * @param boolean $headers
     * @return array
     */
    static public function GetInternalData($code, $headers)
    {
        $xml = simplexml_load_string($code, "SimpleXMLElement", LIBXML_NOCDATA);
        var_dump($xml);
        $json = json_encode($xml);

        $table = json_decode($json, TRUE);


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
            // If table have headings there will be empty element in the array at the beginning. If there is empty element create <thead> at the beginning of the code
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

        $convertedCode = "<xmlTable>" . PHP_EOL . $newCode . "</xmlTable>";

        return $convertedCode;
    }
}