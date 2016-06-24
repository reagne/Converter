<?php
session_start();

require_once(dirname(__FILE__) . "/Converter.php");

require_once(dirname(__FILE__) . "/dataTypesInterface/DataTypesInterface.php");

require_once(dirname(__FILE__) . "/dataTypes/HtmlTableDataType.php");
require_once(dirname(__FILE__) . "/dataTypes/HtmlListDataType.php");
require_once(dirname(__FILE__) . "/dataTypes/XmlDataType.php");
require_once(dirname(__FILE__) . "/dataTypes/CsvDataType.php");
require_once(dirname(__FILE__) . "/dataTypes/AsciiDataType.php");
require_once(dirname(__FILE__) . "/dataTypes/JsonDataType.php");

