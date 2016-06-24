<?php

class XmlDataType
{
    /**
     * @param string $code
     */
    static public function getInternalData($code)
    {
        // TODO: Implement getInternalData() method.
    }

    /**
     * @param array $internalData
     * @param bool $headers
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
}