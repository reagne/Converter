<?php

require_once ("Converter.php");

class HtmlListInput extends Converter
{
    public function getInternalData ($code) {
        $DOM = new DOMDocument;
        $DOM->loadHTML($code);

        $tables = $DOM->getElementsByTagName('ul')->item(0);

        // get each column headers by tag name
        //TODO
//        $headers = $DOM->getElementsByTagName('ul > li');
//        $headersValues = [];
//        var_dump($headers->item(0));
//        foreach ($headers as $header) {
//
//            if($header->getElementByTagName('ul') != NULL) {
//                $headersValues[] = $header->nodeValue;
//            }
//        }

        //get all rows from the table
        $rows = $tables->getElementsByTagName('ul');

        $table = [];

        foreach ($rows as $row)
        {
            // get each column by tag name
            $cols = $row->getElementsByTagName('li');

            $row = [];
            $i = 0;
            foreach ($cols as $node) {
                $row[] = $node->nodeValue;
//                if(empty($headers)) {
//                    $row[] = $node->nodeValue;
//                } else {
//                    $row[$headers[$i]] = $node->nodeValue;
//                }
//                $i++;
            }

            $table[] = $row;
        }
        return $table;
    }
};