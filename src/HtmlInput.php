<?php

require_once ("Converter.php");

class HtmlInput extends Converter
{
    public function getInternalData ($code) {
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
};