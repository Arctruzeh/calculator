<?php

include 'includes/calc.inc.php';

$num1 = $_POST['num1'];
$num2 = $_POST['num2'];
$cal = $_POST['cal'];

$calculator = new Calc($num1, $num2, $cal);

echo "$num1 " . "$cal " . "$num2 " . "= " . $calculator->calcMethod();

echo '<br><br><a href="http://ajcloud.rf.gd/calculator/">back</a>';