// Configuration object for different conversion types - changes the UI
const conversionConfig = {
    temperature: {
        title: '🌡️ Temperature Conversion',
        startLabel: 'Starting Fahrenheit Value:',
        endLabel: 'Ending Fahrenheit Value:',
        startDefault: 32,
        endDefault: 100,
        emoji: '🌡️'
    },
    distance: {
        title: '📏 Distance Conversion',
        startLabel: 'Starting Miles Value:',
        endLabel: 'Ending Miles Value:',
        startDefault: 1,
        endDefault: 10,
        emoji: '📏'
    },
    weight: {
        title: '⚖️ Weight Conversion',
        startLabel: 'Starting Pounds Value:',
        endLabel: 'Ending Pounds Value:',
        startDefault: 1,
        endDefault: 10,
        emoji: '⚖️'
    }
};

// Function to play the 'huh' sound when clicking the convert button
function playHuh() {
    const audio = new Audio('assets/sounds/huh.mp3');
    setTimeout(function(){
        audio.play();
    }, 750);
}

// Function to play the 'wow' sound when clicking the download button
function playWow() {
    const audio = new Audio('assets/sounds/wow.mp3');
        audio.play();
} 

// Run callback after DOM loads
document.addEventListener('DOMContentLoaded', () => {

    // Get DOM elements
    const conversionTypeSelect = document.getElementById('conversion-type');
    const pageTitle = document.getElementById('page-title');
    const startLabel = document.getElementById('start-label');
    const endLabel = document.getElementById('end-label');
    const startInput = document.getElementById('start');
    const endInput = document.getElementById('end');
    const form = document.getElementById('conversion-form');
    const submitButton = document.getElementById('submit_button');

    // Function to update UI based on conversion type - runs each time DOM loads & reloads
    function updateConversionUI() {
        // Get the selected conversion type
        const conversionType = conversionTypeSelect.value;
        // Append that value to conversionType at position - gives access to all properties
        const config = conversionConfig[conversionType];

        // Update page title with animation
        pageTitle.style.opacity = '0';
        setTimeout(() => {
            pageTitle.textContent = config.title;
            pageTitle.style.opacity = '1';
        }, 150);

        // Update form labels
        startLabel.textContent = config.startLabel;
        endLabel.textContent = config.endLabel;

        // Update input placeholders
        startInput.placeholder = `Enter starting ${config.startLabel.toLowerCase()}`;
        endInput.placeholder = `Enter ending ${config.endLabel.toLowerCase()}`;

        // Update default values
        startInput.value = config.startDefault;
        endInput.value = config.endDefault;

        // Clear previous results in output div
        document.getElementById('result').innerHTML = '';
    }

    // Function to get selected output format from radio buttons
    function getSelectedFormat() {
        const formatRadios = document.getElementsByName('format');
        // Loop through both radio buttons
        for (const radio of formatRadios) {
            if (radio.checked) {
                return radio.value;
            }
        }
        return 'html'; // Default to html if nothing else selected
    }

    // Function to handle form submission and make AJAX request
    function getConversions(event) {
        // Prevent default form submission
        event.preventDefault();

        // Get form values
        const start = startInput.value;
        const end = endInput.value;
        const conversionType = conversionTypeSelect.value;
        const format = getSelectedFormat();

        // Validate inputs - alert user if neither values are present
        if (!start || !end) {
            alert('Please enter both start and end values');
            return;
        }

        // Show loading state
        const resultDiv = document.getElementById('result');
        // Append loading spinner while while Ajax sends data to convert.php
        resultDiv.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--primary);"><div class="loading"></div><p style="margin-top: 1rem;">Converting...</p></div>';

        // Create new XMLHttpRequest
        const xhr = new XMLHttpRequest();

        // Open asynchronous POST request to the PHP script
        xhr.open('POST', 'scripts/convert.php', true);

        // Set request header for form data
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Handle response
        xhr.onreadystatechange = function() {
            // Check if request is complete and successful
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Display the response in the result div - converted from PHP
                resultDiv.innerHTML = xhr.responseText;
            } else if (xhr.readyState === 4) {
                // Handle errors
                resultDiv.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;">Error loading conversions. Please try again.</div>';
            }
        };

        // Send the request with URL parameters encoded for UTF-8 conversion in Param transfer
        const params = `start=${encodeURIComponent(start)}&end=${encodeURIComponent(end)}&conversion_type=${encodeURIComponent(conversionType)}&format=${encodeURIComponent(format)}`;
        xhr.send(params);
    }

    // Add event listener for conversion type changes, call updateConversioUI() and update the page based on a selection
    conversionTypeSelect.addEventListener('change', updateConversionUI);

    // Add event listener for form submission
    form.addEventListener('submit', getConversions);

    // Prevent default button click behavior
    submitButton.addEventListener('click', (event) => {
        // Form submit will handle this
        console.log('Button works!')
    });

    // Initialize UI on page load
    updateConversionUI();
});
