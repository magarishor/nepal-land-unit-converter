document.addEventListener('DOMContentLoaded', function () {
    const convertButton = document.getElementById('convert-button');

    if (convertButton) {
        convertButton.addEventListener('click', function () {
            const conversionRates = {
                ropani: 5476,
                aana: 342.25,
                paisa: 85.56,
                daam: 21.39,
                sqft: 1,
            };

            const value = parseFloat(document.getElementById('value').value);
            const fromUnit = document.getElementById('from-unit').value;
            const toUnit = document.getElementById('to-unit').value;

            if (isNaN(value)) {
                alert("Please enter a valid number.");
                return;
            }

            const fromRate = conversionRates[fromUnit];
            const toRate = conversionRates[toUnit];

            const result = (value * fromRate) / toRate;
            document.getElementById('conversion-result').textContent = 
                `${value} ${fromUnit} = ${result.toFixed(2)} ${toUnit}`;
        });
    }
});
