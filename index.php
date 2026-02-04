<!DOCTYPE html>
<html>

<head>
    <title>Calculator</title>
</head>

<body>
    <form action="calc.php" method="POST" id="calcForm">
        <input type="text" name="num1">
        <input type="text" name="num2">
        <select name="cal">
            <option value="+">Add (+)</option>
            <option value="-">Subtract (-)</option>
            <option value="*">Multiply (*)</option>
            <option value="/">Divide (/)</option>
        </select>
        <button type="submit">Calculate</button>
    </form>
    
    <div id="result-container" style="margin-top: 20px; font-weight: bold;"></div>

    <script>
        document.getElementById('calcForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultContainer = document.getElementById('result-container');
            
            fetch('calc.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    resultContainer.innerText = 'Result: ' + data.result;
                    resultContainer.style.color = 'black';
                } else {
                    resultContainer.innerText = data.message;
                    resultContainer.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultContainer.innerText = 'An unexpected error occurred.';
                resultContainer.style.color = 'red';
            });
        });
    </script>

    <script data-slug="calculator">
        (function () {
            var slug = document.currentScript.getAttribute('data-slug');
            var link = document.createElement('a');
            link.href = 'https://ajlato.com/projects/' + slug;
            link.innerHTML = '← Back to Portfolio';
            link.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: #222; color: #fff; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-family: system-ui, sans-serif; font-size: 14px; z-index: 10000; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: transform 0.2s;';
            link.onmouseover = function () { link.style.transform = 'scale(1.05)'; };
            link.onmouseout = function () { link.style.transform = 'scale(1)'; };
            document.body.appendChild(link);
        })();
    </script>
</body>

</html>