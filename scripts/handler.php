<?php
    $start = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
    $end   = isset($_REQUEST['end']) ? (int)$_REQUEST['end'] : 0;
    $format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'html';

    if ($start > $end) {
        $temp = $start;
        $start = $end;
        $end = $temp;
    }

    if ($format === 'csv') {
        // Send headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="conversions.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Fahrenheit', 'Celsius']);
        for ($f = $start; $f <= $end; $f++) {
            $c = round(($f - 32) * 5 / 9, 2);
            fputcsv($output, [$f, $c]);
        }
        fclose($output);
        exit;
    } 
    else {
        // Styled HTML table
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
        echo "<table class='conversion-table'>";
        echo "<tr><th>Fahrenheit</th><th>Celsius</th></tr>";
        for ($f = $start; $f <= $end; $f++) {
            $c = round(($f - 32) * 5 / 9, 2);
            echo "<tr><td>$f</td><td>$c</td></tr>";
        }
        echo "</table>";
    }
?>