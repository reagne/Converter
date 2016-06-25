<?php
require_once("./src/connection.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 ">

                    <h1>Table Converter</h1>

                    <form action="index.php" method="post">

                        <legend>Choose input format:
                            <select name="typeInput" id="typeInput">
                                <?php
                                Converter::SelectOptions();
                                ?>
                            </select>
                        </legend>

                        <legend>Do you want column headers in your table?
                            <select name="head">
                                <option value=0>No</option>
                                <option value=1>Yes</option>
                            </select>
                        </legend>

                        <?php
                        echo('<textarea rows="10" cols="50" name="codeInput" id="codeInput" placeholder="Paste your code here...">');
                            if (!isset($_SESSION["currentCode"])) {
                                $_SESSION["currentCode"] = $_POST["codeInput"];
                            } else if ($_SESSION["currentCode"] != $_POST["codeInput"]) {
                                $_SESSION["currentCode"] = $_POST["codeInput"];
                            }

                            echo ($_SESSION["currentCode"]);

                        echo('</textarea>');
                        ?>

                        <legend>Choose format you want your table to convert:
                            <select name="typeOutput" id="typeOutput">
                                <?php
                                Converter::SelectOptions();
                                ?>
                            </select>
                        </legend>

                        <input type="submit" value="Convert" name="convert" id="convert" class="btn btn-default btn-lg center-block">
                    </form>

                    <legend>Get your new code!</legend>
                    <?php
                    echo('<textarea rows="10" cols="50" name="codeOutput" placeholder="Your converted code...">');
                        if($_SERVER["REQUEST_METHOD"] == "POST") {
                            $typeInput = $_POST["typeInput"];
                            $typeOutput = $_POST["typeOutput"];
                            $headers = $_POST["head"];

                            $code = $_POST["codeInput"];
                            $code = trim($code);

                            if (strlen($code) > 10) {
                                $converter = new Converter($typeInput, $typeOutput, $code, $headers);

                                echo $converter->convertTo($typeInput, $typeOutput, $code, $headers);
                            } else {
                                echo("Your code is too short.");
                            }
                        }
                    echo('</textarea>');
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
