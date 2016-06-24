<?php
require_once("./src/connection.php");
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
</head>
<body>
<h1>Table Converter</h1>
<ol>
    <li>Choose a type in which your table is written</li>
    <li>Paste the code into the field</li>
    <li>Choose a type you want your code convert into</li>
</ol>

<form action="index.php" method="post">
    <select name="typeInput" id="typeInput">
        <?php include("./src/selectTypes.php") ?>
    </select>
    <legend>Do you want column headers in your table?
    <select name="head"><option value=0>No</option><option value=1>Yes</option></select>
    </legend>
    <?php
    echo('<textarea rows="10" cols="50" name="codeInput" id="codeInput" placeholder="Paste your code here...">');
        if(!isset($_SESSION["currentCode"])) {
            $_SESSION["currentCode"] = $_POST["codeInput"];
        } else if ($_SESSION["currentCode"] != $_POST["codeInput"]) {
            $_SESSION["currentCode"] = $_POST["codeInput"];
        }
        echo $_SESSION["currentCode"];
    echo('</textarea>');
    ?>
    <select name="typeOutput" id="typeOutput">
        <?php include("./src/selectTypes.php") ?>
    </select>
    <input type="submit" value="Convert" name="convert" ">
</form>

<?php
echo('<textarea rows="10" cols="50" name="codeOutput" placeholder="Your converted code...">');
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $typeInput = $_POST["typeInput"];
        $typeOutput = $_POST["typeOutput"];
        $headers = $_POST["head"];

        $code = $_POST["codeInput"];
        $code = trim($code);

        if (strlen($code) > 10) { //tutaj powinny być switche
            switch ($typeInput) {
                case "htmlTable" :
                    $htmlInput = new HtmlInput($code);
                    $internalData = $htmlInput->getInternalData($code);
                    break;

                case "htmlList" :
                    $htmlListInput = new HtmlListInput($code);
                    $internalData = $htmlListInput->getInternalData($code);
                    break;

                case "json" :
                    $jsonInput = new JsonInput($code);
                    $internalData = $jsonInput->getInternalData($code);
                    break;

                case "xml" :
                    $xmlInput = new XmlInput($code);
                    $internalData = $xmlInput->getInternalData($code);
                    break;

                case "csv" :
                    $csvInput = new CsvInput($code);
                    $internalData = $csvInput->getInternalData($code);
                    break;

                case "ascii" :
                    $asciiInput = new AsciiInput($code);
                    $internalData = $asciiInput->getInternalData($code);
                    break;
            }
            echo HtmlInput::ConvertTo($internalData, $typeOutput, $headers);
        } else {
            echo("Your code is too short.");
        }
    }
echo('</textarea>');
?>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>
<?php
