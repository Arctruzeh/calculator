<?php

include 'includes/calc.inc.php';

if (!isset($_POST['num1'], $_POST['num2'], $_POST['cal'])) {
    echo "Error: Missing parameters.";
    echo '<br><br><a href="index.php">back</a>';
    exit();
}

$num1 = $_POST['num1'];
$num2 = $_POST['num2'];
$cal = $_POST['cal'];

if (!is_numeric($num1) || !is_numeric($num2)) {
    echo "Error: Inputs must be numbers.";
} else {
    $operation = Operation::tryFrom($cal);

    if ($operation === null) {
        echo "Error: Invalid operation.";
    } else {
        $calculator = new Calc((float) $num1, (float) $num2, $operation);
        $result = $calculator->calcMethod();

        // Sanitize for output
        $s_num1 = htmlspecialchars($num1);
        $s_cal = htmlspecialchars($cal);
        $s_num2 = htmlspecialchars($num2);
        $s_result = htmlspecialchars($result);

        echo "$s_num1 $s_cal $s_num2 = $s_result";
    }
}

echo '<br><br><a href="index.php">back</a>';