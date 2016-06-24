<?php

$formatTypes = [
    "htmlTable" => "HTML table",
    "json" => "JSON",
    "xml" => "XML",
    "htmlList" => "HTML unordered list",
    "ascii" => "ASCII",
    "csv" => "CSV",
];

foreach ($formatTypes as $key => $type) {
            echo '<option value="' . $key . '">' . $type . '</option><br>';
        };