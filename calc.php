<?php

include 'includes/calc.inc.php';

header('Content-Type: application/json');

if (!isset($_POST['num1'], $_POST['num2'], $_POST['cal'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters.']);
    exit();
}

$num1 = $_POST['num1'];
$num2 = $_POST['num2'];
$cal = $_POST['cal'];

if (!is_numeric($num1) || !is_numeric($num2)) {
    echo json_encode(['status' => 'error', 'message' => 'Inputs must be numbers.']);
} else {
    $operation = Operation::tryFrom($cal);

    if ($operation === null) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid operation.']);
    } else {
        $calculator = new Calc((float) $num1, (float) $num2, $operation);
        $result = $calculator->calcMethod();

        // Check if result contains error message (currently Calc returns string on div by zero)
        if (is_string($result) && str_starts_with($result, 'Error:')) {
            echo json_encode(['status' => 'error', 'message' => $result]);
        } else {
            echo json_encode(['status' => 'success', 'result' => $result]);
        }
    }
}