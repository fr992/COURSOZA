<?php
include 'ushtrimedb.php';

if(isset($_GET['subject'])) {
    $subject = $_GET['subject'];

    // marrja e postimev per lenden perkatese
    $stmt = $pdo->prepare("SELECT * FROM content WHERE subject = ?"); 
    // pra aty dallohet qe po e perdorim tabelen content nga databaza lidhjatest(ja ndrrojm emrin pastaj)
    $stmt->execute([$subject]);
    $contents = $stmt->fetchAll();
} else {
    die("None of the subjects is selected");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files per <?= htmlspecialchars($subject) ?></title>
    <style>
                body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .content-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .content-item h3 {
            margin-top: 0;
        }
        .content-item a {
            color: blue;
            text-decoration: none;
            margin-right: 10px;
        }
        .content-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Files per <?= htmlspecialchars($subject) ?></h1>
        <?php if ($contents): ?>
            <?php foreach ($contents as $row): ?>
                <div class="content-item">
                <h3><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p><?= nl2br(htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8')) ?></p>
                <a href="<?= htmlspecialchars($row['file_path'], ENT_QUOTES, 'UTF-8') ?>">Download File</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>S'ka postime per kete lende.</p>
        <?php endif; ?>
    </div>
</body>
</html>