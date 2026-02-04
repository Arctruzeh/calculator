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
    <title>Nature Calculator</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="calc-container">
        <div class="content-wrapper">
            <h1>Simple Calculator</h1>

            <form action="" method="POST" novalidate id="calcForm">
                <div class="form-group">
                    <label for="num1">First Number:</label>
                    <div class="input-wrapper">
                        <input type="number" name="num1" id="num1" step="any" required placeholder="e.g. 10"
                            value="<?php echo htmlspecialchars((string) $num1); ?>" autofocus autocomplete="off">
                        <div class="spinner-controls">
                            <button type="button" class="spinner-btn up" data-input="num1" tabindex="-1">▲</button>
                            <button type="button" class="spinner-btn down" data-input="num1" tabindex="-1">▼</button>
                        </div>
                    </div>
                    <div class="field-error" id="num1-error"></div>
                </div>

                <div class="form-group custom-select-wrapper">
                    <label for="cal">Operation:</label>
                    <select name="cal" id="cal" style="display: none;"> <!-- Hide native select -->
                        <option value="+" <?php if ($cal === '+')
                            echo 'selected'; ?>>Add (+)</option>
                        <option value="-" <?php if ($cal === '-')
                            echo 'selected'; ?>>Subtract (-)</option>
                        <option value="*" <?php if ($cal === '*')
                            echo 'selected'; ?>>Multiply (*)</option>
                        <option value="/" <?php if ($cal === '/')
                            echo 'selected'; ?>>Divide (/)</option>
                    </select>

                    <div class="custom-select" id="customSelect">
                        <div class="select-selected" id="selectedOption" tabindex="0">Select Operation</div>
                        <div class="select-items select-hide" id="selectItems">
                            <div data-value="+" tabindex="0">Add (+)</div>
                            <div data-value="-" tabindex="0">Subtract (-)</div>
                            <div data-value="*" tabindex="0">Multiply (*)</div>
                            <div data-value="/" tabindex="0">Divide (/)</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="num2">Second Number:</label>
                    <div class="input-wrapper">
                        <input type="number" name="num2" id="num2" step="any" required placeholder="e.g. 5"
                            value="<?php echo htmlspecialchars((string) $num2); ?>" autocomplete="off">
                        <div class="spinner-controls">
                            <button type="button" class="spinner-btn up" data-input="num2" tabindex="-1">▲</button>
                            <button type="button" class="spinner-btn down" data-input="num2" tabindex="-1">▼</button>
                        </div>
                    </div>
                    <div class="field-error" id="num2-error"></div>
                </div>

                <button type="submit" class="calc-btn">Calculate</button>
            </form>

            <div class="output-section">
                <?php if ($result !== null): ?>
                    <div class="result-success">Result: <?php echo htmlspecialchars((string) $result); ?></div>
                <?php elseif ($error !== null): ?>
                    <div class="result-error"><?php echo htmlspecialchars($error); ?></div>
                <?php else: ?>
                    <div class="result-placeholder">&nbsp;</div>
                <?php endif; ?>
            </div>

            <a href="https://ajlato.com/projects/calculator" class="back-link">← Back to Portfolio</a>
        </div>
    </main>

    <script>
        document.getElementById('calcForm').addEventListener('submit', function (event) {
            let isValid = true;
            const num1 = document.getElementById('num1');
            const num2 = document.getElementById('num2');
            const num1Error = document.getElementById('num1-error');
            const num2Error = document.getElementById('num2-error');

            // Reset errors
            num1Error.textContent = '';
            num2Error.textContent = '';
            num1.classList.remove('input-invalid');
            num2.classList.remove('input-invalid');

            // Validate Num1
            if (!num1.value) {
                num1Error.textContent = 'Please fill out this field.';
                num1.classList.add('input-invalid');
                isValid = false;
            }

            // Validate Num2
            if (!num2.value) {
                num2Error.textContent = 'Please fill out this field.';
                num2.classList.add('input-invalid');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });

        // Custom Select Logic
        const customSelect = document.getElementById('customSelect');
        const selectedOption = document.getElementById('selectedOption');
        const selectItems = document.getElementById('selectItems');
        const nativeSelect = document.getElementById('cal');
        const items = selectItems.getElementsByTagName('div');

        // Initialize display based on PHP value
        const currentVal = nativeSelect.value;
        if (currentVal) {
            for (let i = 0; i < nativeSelect.options.length; i++) {
                if (nativeSelect.options[i].value === currentVal) {
                    selectedOption.textContent = nativeSelect.options[i].text;
                    break;
                }
            }
        }

        selectedOption.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleSelect();
        });

        // Keyboard support for triggering dropdown
        selectedOption.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleSelect();
            }
        });

        function toggleSelect() {
            selectItems.classList.toggle('select-hide');
            selectedOption.classList.toggle('select-arrow-active');
        }

        for (let i = 0; i < items.length; i++) {
            items[i].addEventListener('click', function (e) {
                selectOption(this);
            });

            // Keyboard support for options
            items[i].addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectOption(this);
                }
            });
        }

        function selectOption(item) {
            const value = item.getAttribute('data-value');
            const text = item.textContent;

            // Update native select
            nativeSelect.value = value;

            // Update custom display
            selectedOption.textContent = text;

            // Close dropdown
            selectItems.classList.add('select-hide');
            selectedOption.classList.remove('select-arrow-active');

            // Return focus to trigger
            selectedOption.focus();
        }

        // Close if clicked outside
        document.addEventListener('click', function (e) {
            if (!customSelect.contains(e.target)) {
                selectItems.classList.add('select-hide');
                selectedOption.classList.remove('select-arrow-active');
            }
        });

        // Spinner Logic
        const spinnerBtns = document.querySelectorAll('.spinner-btn');
        spinnerBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const inputId = this.getAttribute('data-input');
                const input = document.getElementById(inputId);
                let currentVal = parseFloat(input.value) || 0;

                if (this.classList.contains('up')) {
                    input.value = currentVal + 1;
                } else {
                    input.value = currentVal - 1;
                }
            });
        });
    </script>
</body>

</html>