<?php

require_once ("connection.php");

class Converter
{
    /**
     * @var array of available format types, which contains official name and also name of the class that supports the format type
     */
    static private $NameDataTypes = [
        "htmlTable" => ["HTML table", 'HtmlTableDataType'],
        "json"      => ["JSON", 'JsonDataType'],
        "xml"       => ["XML", 'XmlDataType'],
        "htmlList"  => ["HTML unordered list", 'HtmlListDataType'],
        "ascii"     => ["ASCII", 'AsciiDataType'],
        "csv"       => ["CSV", 'CsvDataType'],
    ];

    static public function SelectOptions ()
    {
        foreach (self::$NameDataTypes as $key => $name) {
            echo '<option value="' . $key . '">' . $name[0] . '</option><br>';
        };
    }

    private $inputType;
    private $outputType;
    private $headers;
    private $code;

    /**
     * Converter constructor.
     * @param string $newInputType
     * @param string $newOutputType
     * @param string $newCode
     * @param boolean $newHeaders
     */
    public function __construct($newInputType, $newOutputType, $newCode, $newHeaders)
    {
        $this->inputType = $newInputType;
        $this->outputType = $newOutputType;
        $this->code = $newCode;
        $this->headers = $newHeaders;
    }

    public function getInputType()
    {
        return $this->inputType;
    }

    public function getOutputType()
    {
        return $this->outputType;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setInputType($inputType)
    {
        $this->inputType = $inputType;
    }

    public function setOutputType($outputType)
    {
        $this->outputType = $outputType;
    }

    public function setCode($newCode)
    {
        return $this->code = $newCode;
    }

    /**
     * @param $inputType
     * @param $code
     * @param $headers
     * @return array
     */
    private function convertFrom($inputType, $code, $headers)
    {
        foreach (self::$NameDataTypes as $input => $classDataType) {
            if ($input == $inputType) {
                $myClass = $classDataType[1];

                return $myClass::GetInternalData($code, $headers);
            }
        }
    }

    /**
     * @param $inputType
     * @param $outputType
     * @param $code
     * @param $headers
     * @return string
     */
    public function convertTo($inputType, $outputType, $code, $headers)
    {
        foreach (self::$NameDataTypes as $output => $classDataType) {
            if ($output == $outputType) {
                $myClass = $classDataType[1];

                $internalData = self::convertFrom($inputType, $code, $headers);

                return $myClass::ConvertTo($internalData);
            }
        }
    }
}