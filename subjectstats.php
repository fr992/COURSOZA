<?php 
include 'ushtrimedb.php'; // lidhja me databaz

$subjects = [
    "Matematik 1",
    "SHKI0",
    "BIEE",
    "Anglisht",
    "AOK",
    "SHKI1",
    "SO",
    "QDS",
    "HCI",
    "Mat2",
    "HSI",
    "SHKI2",
    "SD",
    "SBD",
    "Rrjeta",
    "Agoritme",
    "Web"
];

$subjectCounts = [];

foreach($subjects as $subject) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM content WHERE subject = ?");
    $stmt->execute([$subject]);
    $row = $stmt->fetch();
    $subjectCounts[$subject] = $row['count'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Post Statistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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
            padding: 30px 20px; 
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
        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        li {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
            color: #555;
        }
        li:last-child {
            border-bottom: none;
        }
        li span {
            font-weight: bold;
            color: blue;
        }
        li:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <header>
        <a href="dashboard.php" class="back-btn1">Back</a>
        <h1>Subject Post Statistics</h1>
    </header>
    <h2>Postimet per Lende</h2>
    <ul>
        <?php foreach ($subjectCounts as $subject => $count): ?>
            <li>
                <?= htmlspecialchars($subject) ?>: <span><?= $count ?> posts</span>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>