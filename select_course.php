<?php
include 'databaza.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zgjidh Ligjeraten</title>
</head>
<body>
<a href="javascript:history.back()" class="back-button">←</a>
    <h1>Zgjidh Ligjeraten</h1>
    <form action="ligjeratat.php" method="GET"> 
        <label for="lenda_id">Lendet:</label>
        <select id="lenda_id" name="lenda_id">
            <?php
            
            $result = $conn->query("SELECT * FROM lendet");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['emri']) . "</option>";
            }
            ?>
        </select>
        <br><br>
        <button type="submit">Shiko Ligjeraten</button>
    </form>
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
    font-size: 1.5em;
    text-decoration: none;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: orange;
}
h1 {
    text-align: center;
    font-size: 2.5em;
    color: black;
    margin-top: 30px;
    padding-bottom: 10px;
    text-shadow: -1px 1px white, 1px 1px white, 1px -1px white, -1px -1px white;
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

form select {
    font-size: 1.1em;
    padding: 12px;
    margin: 15px 0;
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

form select:focus {
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
</body>
</html>