<?php
include '../LidhjaDatabaza/dbcontact.php'; // lidhja

// marrja e mesazheve nga databaza
$query = "SELECT * FROM contact_messages ORDER BY time DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
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

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .message:last-child {
            border-bottom: none;
        }

        .message h3 {
            margin: 0;
            font-size: 1.4em;
            color: #333;
        }

        .message p {
            margin: 5px 0;
            font-size: 1.1em;
        }

        .message p strong {
            color: #555;
        }

        .time {
            font-size: 0.9em;
            color: #777;
        }

        .no-messages {
            text-align: center;
            font-size: 1.2em;
            color: #777;
        }
    </style>
</head>
<body>

    <header>
        <a href="dashboard.php" class="back-btn1">Back</a>
        <h1>Users Management System</h1>
    </header>
    <div class="container">
        <?php
        if (count($messages) > 0) {
            foreach ($messages as $row) {
                echo "<div class='message'>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars($row['message'])) . "</p>";
                echo "<p class='time'><strong>Time Sent:</strong> " . $row['time'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-messages'>No messages found.</p>";
        }
        ?>
    </div>

</body>
</html>
