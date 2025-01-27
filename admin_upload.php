<?php
include 'databaza.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lenda_id = $_POST['lenda_id'];
    $titulli = $_POST['titulli'];
    $file = $_FILES['file'];

    if (empty($lenda_id) || empty($titulli) || empty($file['name'])) {
        die("Të gjitha fushat janë të detyrueshme.");
    }

    $upload_dir = "uploads/";
    $file_path = $upload_dir . basename($file["name"]);

    if (move_uploaded_file($file["tmp_name"], $file_path)) {
        $stmt = $conn->prepare("INSERT INTO ligjeratat (lenda_id, titulli, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $lenda_id, $titulli, $file_path);

        if ($stmt->execute()) {
            echo "Ligjërata u shtua me sukses!";
        } else {
            echo "Gabim: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Ngarkimi i skedarit dështoi.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngarko Ligjerata</title>
</head>
<body>
    <form action="admin_upload.php" method="POST" enctype="multipart/form-data">
        <label for="lenda_id">Zgjidh Lenden:</label>
        <select id="lenda_id" name="lenda_id">
            <?php
            $result = $conn->query("SELECT * FROM lendet");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['emri'] . "</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="titulli">Titulli i Ligjerates:</label>
        <input type="text" id="titulli" name="titulli">
        <br><br>
        <label for="file">Ngarko Filen:</label>
        <input type="file" id="file" name="file">
        <br><br>
        <button type="submit">Ngarko</button>
    </form>
</body>
</html>