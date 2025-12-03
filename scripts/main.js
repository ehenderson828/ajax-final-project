// Run callback after DOM loads:
document.addEventListener('DOMContentLoaded', () => {
    function getConversions() {
        let start = document.getElementById("start").value;
        let end = document.getElementById("end").value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "convert.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("result").innerHTML = xhr.responseText;
            }
         };
        xhr.send("start=" + start + "&end=" + end + "&format=html");
    }

    document.querySelector('#submit_button').addEventListener('click')
});