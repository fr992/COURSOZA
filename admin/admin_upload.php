<?php
include '../LidhjaDatabaza/databaza.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lenda_id = $_POST['lenda_id'];
    $titulli = $_POST['titulli'];
    $file = $_FILES['file'];

    if (empty($lenda_id) || empty($titulli) || empty($file['name'])) {
        die("Te gjitha fushat duhet te plotesohen.");
    }

    $upload_dir = "postlendet/"; 
    $file_path = $upload_dir . basename($file["name"]);

    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    
    if (move_uploaded_file($file["tmp_name"], $file_path)) {
        
        $stmt = $conn->prepare("INSERT INTO ligjeratat (lenda_id, titulli, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $lenda_id, $titulli, $file_path);

        if ($stmt->execute()) {
            $upload_message = "Ligjerata eshte shtuar me sukses!";
        } else {
            $upload_message = "Gabim: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $upload_message = "Ngarkimi i folderit deshtoi";
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
    <header>
        <a href="dashboard.php" class="back-btn1">Back</a>
        <h1>Users Management System</h1>
    </header>
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
<style>

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

form {
    background-color: white;
    margin: 40px auto;
    padding: 30px;
    width: 90%;
    max-width: 500px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

form label {
    font-size: 1.1em;
    color: #444;
    margin-bottom: 10px;
    display: block;
    font-weight: 600;
}

form input, 
form select, 
form textarea {
    font-size: 1em;
    padding: 12px 0;
    margin: 10px 0;
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

form input:focus, 
form select:focus, 
form textarea:focus {
    border-color: #ff9900;
    box-shadow: 0 0 5px rgba(255, 153, 0, 0.5);
    outline: none;
}

form button {
    font-size: 1.1em;
    padding: 12px 20px;
    border: none;
    background-color: #333;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 15px;
    width: 100%;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

form button:hover {
    background-color: #ff9900;
    transform: scale(1.05);
}

@media (max-width: 600px) {
    form {
        width: 95%;
        padding: 20px;
    }

    form input, 
    form select, 
    form textarea {
        font-size: 1em;
        padding: 10px;
    }

    form button {
        font-size: 1em;
        padding: 10px;
    }
}

</style>