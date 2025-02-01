<?php


include 'ushtrimedb.php'; // lidhja db
// postimi i file edhe validimi


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file'], $_POST['title'], $_POST['text'], $_POST['subject'])) {
        $title = $_POST['title'];
        $text = $_POST['text'];
        $subject = $_POST['subject']; // me marr landen prej formes

        // validimi i llojit edhe madhesis
        $allowedTypes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/msword', // .doc
            'text/plain', // .txt
            'application/vnd.ms-excel', // .xls
            'image/jpeg', // .jpg
            'image/png', // .png
        ];

        $fileType = mime_content_type($_FILES['file']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            die("Invalid file type. Allowed types: PDF, Word, Excel, Text, Images.");
        }

        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($_FILES['file']['size'] > $maxSize) {
            die("File is too large. Max size: 10MB.");
        }

        // gjenerimi i parashtesave te file qe mos me pas ndeshje
        $fileName = uniqid() . '-' . basename($_FILES["file"]["name"]);
        $target_dir = "assets/uploads/"; 

        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                die("Failed to create upload directory.");
            }
        }

        $target_file = $target_dir . $fileName;

        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            die("Failed to upload file.");
        }

        // Insert into the database
        $stmt = $pdo->prepare("INSERT INTO content (title, text, file_path, subject) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $text, $target_file, $subject]);

        // Redirect to the same page qe mos me u bo submit nrefresh ose dy her
        header("Location: ushtrimeadmin.php");
        exit();
    }
} elseif (isset($_GET['delete'])) {
    // me fshi postin
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT file_path FROM content WHERE id = ?");
    $stmt->execute([$id]);
    $content = $stmt->fetch();

    if ($content && file_exists($content['file_path'])) {
        unlink($content['file_path']); // fshirja prej serveri
    }

    $stmt = $pdo->prepare("DELETE FROM content WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ushtrimeadmin.php"); // dergesa pas fshirjes
    exit();
} elseif (isset($_GET['edit'])) {
    // marrja e te dhenave per edit
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM content WHERE id = ?");
    $stmt->execute([$id]);
    $content = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Content Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], textarea, input[type="file"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #343a40;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #343a40;
        }
        h2 {
            color: #333;
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

        header .back-btn1{
            color: red;
            text-decoration: none;
            text-transform: uppercase;
        }

        header .back-btn1:hover{
            color: white;
            transform: scale(1.05);
        }
        header .back-btn:active{
            transform: scale(0.95);
        }
    </style>
</head>
<body>

<header>
    <a href="dashboard.php" class="back-btn1">Back</a>
    <h1>Admin Content Management</h1>
</header>
<div class="container">
    <!-- Admin Content Form --->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" name="title" value="<?= htmlspecialchars($content['title'] ?? '') ?>" placeholder="Title" required><br>
        </div>
        <div class="form-group">
            <textarea name="text" placeholder="Text" required><?= htmlspecialchars($content['text'] ?? '') ?></textarea><br>
        </div>
        <div class="form-group">
            <input type="file" name="file" accept=".pdf,.docx,.doc,.xlsx,.xls,.txt,.jpeg,.jpg,.png" required><br>
        </div>
        <div class="form-group">
            <select name="subject" required>
                <option value="">Select Subject</option>
                <option value="Matematik 1">Matematik 1</option>
                <option value="SHKI0">Hyrje ne Programim</option>
                <option value="BIEE">BIEE</option>
                <option value="Anglisht">Anglisht</option>
                <option value="AOK">AOK</option>
                <option value="ShkrimAkademik">Shkrim Akademik dhe Seminar</option>

                <option value="SHKI1">Shkenca Kompjuterike dhe Inxhinieri 1</option>
                <option value="SO">Sisteme Operative</option>
                <option value="QDS">Qarqet Digjitale dhe Sinjale</option>
                <option value="HCI">Nderveprimi Kompjuter Njeri</option>
                <option value="MAT2">Matematike 2</option>
                <option value="HSI">Hyrje ne Sigurin e Informacionit</option>

                <option value="SHKI2">Shkenca Kompjuterike dhe Inxhinieri 2</option>
                <option value="SD">Struktura Diskrete</option>
                <option value="SBD">Sistemet e Bazave se te Dhenave</option>
                <option value="Rrjeta">Rrjeta Kompjuterike dhe Komunikimi</option>
                <option value="Algoritme">Hyrje ne Algoritme</option>
                <option value="Web">Dizajni dhe Zhvillimi i Web</option>
                
                <!-- lendet tjera...-->
            </select>
        </div>
        <button type="submit">Submit</button>
    </form>

    <h2>Existing Content</h2>

    <?php
    $stmt = $pdo->query("SELECT * FROM content");
    while ($row = $stmt->fetch()) {
        echo "<div class='content-item'>";
        echo "<h3>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8')) . "</p>";
        $filePath = $row['file_path'];
        if (file_exists($filePath)) {
            echo "<a href='" . htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8') . "'>Download File</a><br>";
        } else {
            echo "<p>File not found.</p>";
        }
        echo "<a href='?edit=" . $row['id'] . "'>Edit</a> | ";
        echo "<a href='?delete=" . $row['id'] . "'>Delete</a>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
