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
<a href="javascript:history.back()" class="back-button">←</a>
<header>
        <div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
            </div>
            <ul class="nav-links">
                <li><a href="lendet.php">Lendet</a></li>
            </ul>
        </div>
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
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #d98c11, #F7B851);
    background-size: 300% 300%;
}
.back-button {
    position: fixed;
    top: 0px;
    left: 0px;
    padding: 10px 30px;
    background-color: #d98c11;
    color: white;
    font-size: 1.2em;
    text-decoration: none;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: orange;
}
.priv-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position fixed
    left: 0;
    right 0;
    border-bottom: 1.6px solid black;
    top: 0;
    height: 75px;
    width: auto;
    padding: 8px 16px;
    background-color: rgb(34,34,34);
    z-index: 1000;
}

header .priv-navbar h2 {
    color: white;
    font-size: 1.6em;
}

.priv-navbar ul {
    display: flex;
    flex: 4;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 16px;
    flex-wrap: wrap;
    justify-content: center;
} 

.priv-navbar ul li a {
    padding: 8px 16px;
    text-decoration: none;
    color: white;
    font-size: 0.95em;
    position: relative;
    overflow: hidden;
    transition: color 0.3s ease-in-out;
}

.priv-navbar ul li a::before {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    height: 1.6px; 
    width: 100%;
    background-color: white;
    transform: scaleX(0);
    transition: transform 0.3s ease-in-out;
    transform-origin: left;
}

.priv-navbar ul li a:hover{
    color: #f7b851;
}

.priv-navbar ul li a:hover::before{
    transform: scale(1.05);
}
form {
    background-color: white;
    margin: 40px auto;
    padding: 30px;
    width: 60%;
    max-width: 500px; 
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); 
    text-align: center;
}

form label {
    font-size: 1.2em;
    color: #555;
    margin-bottom: 15px;
    display: block;
}

form input, form select {
    font-size: 1.1em;
    padding: 12px;
    margin: 15px 0;
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

form input:focus, form select:focus {
    border-color: #ff9900;
    outline: none;
}

form button {
    font-size: 1.1em;
    padding: 12px 20px;
    border: none;
    background-color: #333;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #ff9900; 
}


</style>