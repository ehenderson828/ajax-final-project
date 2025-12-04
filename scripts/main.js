// Run callback after DOM loads:
document.addEventListener('DOMContentLoaded', () => {
    function getConversions() {
        // Variable declared to store the starting value
        let start = document.getElementById("start").value;
        // Variable declared to store the ending value
        let end = document.getElementById("end").value;
        // New xhr declaration
        let xhr = new XMLHttpRequest();
        // Asynchronous request opened
        xhr.open("POST", "convert.php", true);
        // Format request as url param
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        // Check for completion
        xhr.onreadystatechange = function() {
            // Check to see if Ajax request is ready, and http request returns 200
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Append the value of the response to the result div
                document.getElementById("result").innerHTML = xhr.responseText;
            }
         };
        // Send said request
        xhr.send("start=" + start + "&end=" + end + "&format=html");
    }

    document.querySelector('#submit_button').addEventListener('click')
});