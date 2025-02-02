<?php
include '../LidhjaDatabaza/dbcontact.php'; // lidhja me njeren databaz

// funksioni per me i marr te dhenat prej krejt tabelave
function getTables($conn, $database) {
    $conn->exec("USE $database"); //databaza qe na duhet
    $stmt = $conn->query("SHOW TABLES"); // lista e tabelave
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

//me i marr tabelat e databazes qe e kem specifiku
function getTableData($conn, $database, $table, $dateRange = null) {
   // perdorimi i asaj databaze
    $conn->exec("USE $database");

    //filtrimi i kohes tek last time
    $dateField = 'created_at';  // Default f
    if ($table == 'contact_messages') {
        $dateField = 'time';  // 'time' per tabelen contact_messages 
    } elseif ($table == 'njoftimet') {
        $dateField = 'data_publikimit';  // 'data_publikimit' per tabelen njoftimet 
    }

    // date range
    $dateCondition = '';
    if ($dateRange) {
        // start date
        $dateInterval = '';
        switch ($dateRange) {
            case '24h':
                $dateInterval = '24 hours';
                break;
            case '3d':
                $dateInterval = '3 days';
                break;
            case '7d':
                $dateInterval = '7 days';
                break;
            case '30d':
                $dateInterval = '30 days';
                break;
            case '60d':
                $dateInterval = '60 days';
                break;
            default:
                $dateInterval = '0 days'; // Default
        }

        // me i marr te dhenat per kohet e kerkuara
        $dateCondition = " WHERE $dateField >= NOW() - INTERVAL $dateInterval";
    }

    $stmt = $conn->query("SELECT * FROM $table" . $dateCondition);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// download
function outputCSV($data, $database, $table, $output) {
    // Add database and table names to the CSV
    fputcsv($output, ["Database: $database"]);
    fputcsv($output, ["Table: $table"]);

    if (count($data) > 0) {
        // rreshti i par column headers
        fputcsv($output, array_keys($data[0]));

        //te dhenat e tabelave
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
    } else {
        // nese ska kurgjo
        fputcsv($output, ["No data available in this table."]);
    }
}

// export
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // downloadi
        $fileName = 'database_dump_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $output = fopen('php://output', 'w');

        // lista e databazave me emrat e tabelave
        $databases = [
            'lendet_db' => ['lendet', 'ligjeratat'],
            'contactform' => ['contact_messages'],
            'njoftimet_db' => ['kategorite', 'njoftimet'],
            'maindatabase' => ['content', 'users']
        ];

        // export databazat e selekturara ose te gjitha
        if ($database && isset($databases[$database])) {
            $tables = $databases[$database];
            foreach ($tables as $table) {
                $data = getTableData($conn, $database, $table, $startDate, $endDate);
                outputCSV($data, $database, $table, $output);
            }
        } elseif (!$database) {
            // eksportoj krejt nese nuk eshte selektuar asnjera
            foreach ($databases as $db => $tables) {
                foreach ($tables as $table) {
                    $data = getTableData($conn, $db, $table, $startDate, $endDate);
                    outputCSV($data, $db, $table, $output);
                }
            }
        }

        fclose($output);
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #343a40;
            color: white;
            text-align: center;
            display: flex; 
            align-items: center; 
            justify-content: space-between;  
            padding: 20px 20px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
            font-size: 24px; 
            text-align: center;
            flex-grow: 1; 
            color: white;
        }

        header .back-btn1{
            color: red;
            text-decoration: none;
            text-transform: uppercase;
        }

        header .back-btn1:hover{
            color: white;
            transform: scale(1.05);
        }

        .form-container {
            background: white;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }

        /* Button styling */
        button {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ff9900;
        }

        /* Export buttons section */
        .export-buttons {
            background: white;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .export-buttons h3 {
            margin-bottom: 10px;
            color: #333;
        }

        /* Table styling */
        table {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <header>
        <a href="dashboard.php" class="back-btn1">Back</a>
        <h1>Users Management System</h1>
    </header>

    <div class="form-container">
        <h2>Choose Data to Export</h2>
        <form action="exportdata.php" method="post">
            <label for="database">Choose Database:</label>
            <select name="database" id="database">
                <option value="">All Databases</option>
                <option value="lendet_db">Lendet DB</option>
                <option value="contactform">Contact Form</option>
                <option value="njoftimet_db">Njoftimet DB</option>
                <option value="maindatabase">Main Database</option>
            </select>

            <label for="date_range">Choose Date Range:</label>
            <select name="date_range" id="date_range">
                <option value="">All Time</option>
                <option value="24h">Last 24 Hours</option>
                <option value="3d">Last 3 Days</option>
                <option value="7d">Last 7 Days</option>
                <option value="30d">Last 30 Days</option>
                <option value="60d">Last 60 Days</option>
            </select>

            <button type="submit">Export Selected Data</button>
        </form>
    </div>

    <div class="export-buttons">
        <h3>Or Export Entire Databases</h3>
        <form action="exportdata.php" method="post">
            <input type="hidden" name="database" value="lendet_db">
            <button type="submit">Export Lendet DB</button>
        </form>
        <form action="exportdata.php" method="post">
            <input type="hidden" name="database" value="contactform">
            <button type="submit">Export Contact Form</button>
        </form>
        <form action="exportdata.php" method="post">
            <input type="hidden" name="database" value="njoftimet_db">
            <button type="submit">Export Njoftimet DB</button>
        </form>
        <form action="exportdata.php" method="post">
            <input type="hidden" name="database" value="maindatabase">
            <button type="submit">Export Main Database</button>
        </form>
        <form action="exportdata.php" method="post">
            <input type="hidden" name="database" value="">
            <button type="submit">Export All Databases</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 Your Website | All Rights Reserved</p>
    </footer>
</body>
</html>
