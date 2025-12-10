<?php
    // Get and validate request parameters
    $start = isset($_REQUEST['start']) ? (float)$_REQUEST['start'] : 0;
    $end = isset($_REQUEST['end']) ? (float)$_REQUEST['end'] : 0;
    $format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
    $conversionType = isset($_REQUEST['conversion_type']) ? $_REQUEST['conversion_type'] : 'temperature';

    // Ensure start is less than or equal to end
    if ($start > $end) {
        $temp = $start;
        $start = $end;
        $end = $temp;
    }

    // Define conversion functions and labels for each type
    $conversions = [
        'temperature' => [
            'fromLabel' => 'Fahrenheit',
            'toLabel' => 'Celsius',
            'fromSymbol' => '°F',
            'toSymbol' => '°C',
            'convert' => function($f) {
                return round(($f - 32) * 5 / 9, 1); // Rounded to a single sig. fig.
            }
        ],
        'distance' => [
            'fromLabel' => 'Miles',
            'toLabel' => 'Kilometers',
            'fromSymbol' => 'mi',
            'toSymbol' => 'km',
            'convert' => function($miles) {
                return round($miles * 1.60934, 1); // Rounded to a single sig. fig.
            }
        ],
        'weight' => [
            'fromLabel' => 'Pounds',
            'toLabel' => 'Kilograms',
            'fromSymbol' => 'lbs',
            'toSymbol' => 'kg',
            'convert' => function($lbs) {
                return round($lbs * 0.453592, 1); // Rounded to a single sig. fig.
            }
        ]
    ];

    // Get the conversion configuration
    $config = $conversions[$conversionType] ?? $conversions['temperature'];

    // CSV Format Output
    if ($format === 'csv') {
        // Check to see if this is the actual download request in URL params
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'preview';
        if($action === 'download') {
            // Send headers for CSV download
            header('Content-Type: text/csv');
            // Append the conversionType string to the file name
            header('Content-Disposition: attachment; filename="' . $conversionType . '_conversions.csv"');

            // Open output stream in write mode
            $output = fopen('php://output', 'w');

            // Write header row - starting and ending values
            fputcsv($output, [$config['fromLabel'], $config['toLabel']]);

            // Generate and write conversion rows
            for ($value = $start; $value <= $end; $value++) {
                $converted = $config['convert']($value);
                fputcsv($output, [$value, $converted]);
            }
            // Close file handler
            fclose($output);
            exit;
        }

        else {
          // PREVIEW - Show download button
          echo "<style>
              .download-ready {
                  text-align: center;
                  padding: 3rem 2rem;
                  border-radius: 16px;
                  animation: scaleIn 0.5s ease-out;
              }
              .download-ready h3 {
                  color: var(--primary);
                  font-size: 1.5rem;
                  margin-bottom: 1rem;
                  font-weight: 700;
              }
              .download-ready p {
                  color: var(--gray-700);
                  margin-bottom: 2rem;
                  font-size: 1.1rem;
              }
              .download-button {
                  display: inline-block;
                  padding: 1rem 2.5rem;
                  background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
                  color: var(--white);
                  text-decoration: none;
                  border-radius: 12px;
                  font-weight: 700;
                  font-size: 1.1rem;
                  transition: all 0.3s ease;
                  box-shadow: var(--shadow-lg);
                  text-transform: uppercase;
                  letter-spacing: 1px;
              }
              .download-button:hover {
                  transform: translateY(-3px);
                  box-shadow: var(--shadow-xl);
              }
              .download-button:active {
                  transform: translateY(-1px);
              }
          </style>";

          echo "<div class='download-ready card'>";
          echo "<h3>✅ Conversion CSV</h3>";
          echo "<p>Your conversion table has been generated and is ready to download.</p>";

          // Build the download URL with all parameters
          $downloadUrl = 'scripts/convert.php?' . http_build_query([
              'start' => $start,
              'end' => $end,
              'conversion_type' => $conversionType,
              'format' => 'csv',
              'action' => 'download'
            ]);

          // Render download button
          echo "<a href='{$downloadUrl}' class='download-button' onclick='playWow()'>📥 Download CSV File</a>";
          echo "</div>";
        }
    }
    // HTML Table Format Output
    else {
        // Output inline CSS for the table
        echo "<style>
            .conversion-table {
                width: 100%;
                border-collapse: collapse;
                background: var(--white);
                border-radius: 16px;
                overflow: hidden;
                box-shadow: var(--shadow-xl);
                animation: scaleIn 0.5s ease-out;
            }
            .conversion-table th {
                background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                color: white;
                padding: 1.25rem;
                text-align: center;
                font-weight: 700;
                font-size: 1.1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            .conversion-table td {
                padding: 1rem 1.25rem;
                text-align: center;
                border-bottom: 1px solid #e5e7eb;
                font-size: 1rem;
                font-weight: 500;
                color: #374151;
                transition: all 150ms ease-in-out;
            }
            .conversion-table tr:nth-child(even) {
                background-color: #f9fafb;
            }
            .conversion-table tr:hover td {
                background: linear-gradient(90deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
                transform: scale(1.02);
                color: #111827;
            }
            .conversion-table tr:last-child td {
                border-bottom: none;
            }
            @keyframes scaleIn {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
        </style>";

        // Output table structure
        echo "<table class='conversion-table'>";

        // Table header
        echo "<tr>";
        echo "<th>{$config['fromLabel']} ({$config['fromSymbol']})</th>";
        echo "<th>{$config['toLabel']} ({$config['toSymbol']})</th>";
        echo "</tr>";

        // Generate and output conversion rows
        for ($value = $start; $value <= $end; $value++) {
            $converted = $config['convert']($value);
            echo "<tr>";
            echo "<td>" . number_format($value, 1) . "</td>";
            echo "<td>" . number_format($converted, 1) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
?>
