<?php
declare(strict_types=1);
include 'includes/calc.inc.php';

$num1 = '';
$num2 = '';
$cal = '';
$result = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $cal = $_POST['cal'] ?? '';

    if (!is_numeric($num1) || !is_numeric($num2)) {
        $error = "Please enter valid numbers.";
    } else {
        $operation = Operation::tryFrom($cal);
        if ($operation === null) {
            $error = "Invalid operation selected.";
        } else {
            $calculator = new Calc((float) $num1, (float) $num2, $operation);
            $result = $calculator->calcMethod();
            // Check for division by zero error string from class
            if (is_string($result) && str_starts_with($result, 'Error:')) {
                $error = $result;
                $result = null;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
</head>

<body>
    <h1>Simple Calculator</h1>

    <form action="" method="POST">
        <div>
            <label for="num1">First Number:</label>
            <input type="number" name="num1" id="num1" step="any" required placeholder="e.g. 10"
                value="<?php echo htmlspecialchars((string) $num1); ?>" autofocus>
        </div>
        <br>
        <div>
            <label for="cal">Operation:</label>
            <select name="cal" id="cal">
                <option value="+" <?php if ($cal === '+')
                    echo 'selected'; ?>>Add (+)</option>
                <option value="-" <?php if ($cal === '-')
                    echo 'selected'; ?>>Subtract (-)</option>
                <option value="*" <?php if ($cal === '*')
                    echo 'selected'; ?>>Multiply (*)</option>
                <option value="/" <?php if ($cal === '/')
                    echo 'selected'; ?>>Divide (/)</option>
            </select>
        </div>
        <br>
        <div>
            <label for="num2">Second Number:</label>
            <input type="number" name="num2" id="num2" step="any" required placeholder="e.g. 5"
                value="<?php echo htmlspecialchars((string) $num2); ?>">
        </div>
        <br>
        <button type="submit">Calculate</button>
    </form>

    <hr>

    <?php if ($result !== null): ?>
        <h2>Result:
            <?php echo htmlspecialchars((string) $result); ?>
        </h2>
    <?php endif; ?>

    <?php if ($error !== null): ?>
        <h2 style="color: red;">
            <?php echo htmlspecialchars($error); ?>
        </h2>
    <?php endif; ?>

    <br><br>
    <a href="https://ajlato.com/projects/calculator">← Back to Portfolio</a>

</body>

</html>