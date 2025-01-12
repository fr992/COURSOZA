<?php 
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    die("You are not able to upload files.");
}

// file upload handler e ben postimin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // kontrollimi
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $fileTmpPath = $_FILES['fileUpload']['tmp_name'];
        $fileName = $_FILES['fileUpload']['name'];
        $fileSize = $_FILES['fileUpload']['size'];
        $fileType = $_FILES['fileUpload']['type'];

        $uploadDir = 'uploads/'; // qitu na ruhen nFolder masi ti postojm

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetFilePath = $uploadDir . basename($fileName);

        if(move_uploaded_file($fileTmpPath, $targetFilePath)) {
            echo "File uploaded successfully.";
        } else {
            echo "Failed to upload file.";
        }

        // description 
        if(!empty($_POST['textInput'])) {
            $content = $_POST['textInput'];
            $fileTextPath = $uploadDir . "decsription.txt";

            file_put_contents($fileTextPath, $content . PHP_EOL, FILE_APPEND);
            echo "Description u ruajt";
        }
    } else {
        echo "No file uploaded ose ka error/failed.";
    }
}
?>