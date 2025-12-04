<!-- This script converts farenheit temperatures to celcius within a specified range. Results are output as either a downloadable CSV file or a rendered HTML table -->
<?php
    // Each variable declaration checks if a para was sent, casts it to an int, or defaults to 0
    $start = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0; // Check for a 'start' param
    $end = isset($_REQUEST['end']) ? (int)$_REQUEST['end'] : 0; // Checks for an 'end' param
    // Check for a format param, defaults to html if not specified
    $format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
    // Check to see if start ≤ end
    if ($start > $end) { // Check to see if an error was made, and the 'start' value and greater than the 'end' 
        // If so, assign the value of 'start' to 'temp'
        $temp = $start;
        // Assign the value of 'end' to 'start'
        $start = $end;
        // Assign the original value of 'start' ('temp') to 'end'
        $end = $temp;
    } // This logic guaruntees the value of 'start' will always be ≤ 'end'

    // Check to see if the csv format was requested
    if ($format === 'csv') {
        // Send headers for CSV download - tell the browser a csv file will be provided
        header('Content-Type: text/csv');
        // Force the browser to download the csv
        header('Content-Disposition: attachment; filename="conversions.csv"');
        // Variable declared with an open csv handler as the value in 'write' mode
        $output = fopen('php://output', 'w');
        // Write the header row with column names
        fputcsv($output, ['Fahrenheit', 'Celsius']);
        // Loop through each 'start' and 'end'
        for ($f = $start; $f <= $end; $f++) {
            // Convert fahrenheit to celsius - rounded to two decimal places
            $c = round(($f - 32) * 5 / 9, 2);
            // Write fahrenheit and converted celcius values as rows in the csv
            fputcsv($output, [$f, $c]);
        }
        // Close the file handler once loop is finished
        fclose($output);
        // Stop script execution
        exit;
    } 
    // If 'format' is set to !'csv'
    else {
        // Render styled html table::
        // Table CSS
        echo "<style> 
                .conversion-table {
                    border-collapse: collapse;
                    width: 60%;
                    margin: 20px auto;
                    font-family: Arial, sans-serif;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                .conversion-table th {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px;
                    text-align: center;
                }
                .conversion-table td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                }
                .conversion-table tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .conversion-table tr:hover {
                    background-color: #f1f1f1;
                }
            </style>";
        // Render the styled table
        echo "<table class='conversion-table'>";
        // Render the column names
        echo "<tr>
                <th>Fahrenheit</th>
                <th>Celsius</th>
              </tr>
        ";
        // Loop through each fahrenheit value and convert to celcius
        for ($f = $start; $f <= $end; $f++) {
            $c = round(($f - 32) * 5 / 9, 2);
            // Render the starting and converted values to the table
            echo "<tr>
                    <td>$f</td>
                    <td>$c</td>
                  </tr>
            ";
        }
        echo "</table>";
    }
?>