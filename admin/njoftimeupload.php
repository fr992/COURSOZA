<?php

include '../LidhjaDatabaza/dbe.php';

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $titulli = $_POST["titulli"];
    $short_text = $_POST["short_text"];
    $full_text = $_POST["full_text"];

    if(empty($titulli) || empty($short_text) || empty($full_text)){
        echo "Ju lutem plotesoni te gjitha fushat.!";
        exit;
    }
    $sql = "INSERT INTO njoftimet(titulli,short_text,full_text,koha_publikimit) VALUES (?,?,?,CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss",$titulli,$short_text,$full_text);

    if($stmt->execute()) {
        echo "Njoftimi u publikua me sukses.!";
    }
    else {
        echo "Gabim gjat publikimit te njoftimeve:" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faqja Adminit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #333;
            flex-direction: column;
            min-height: 100vh;
            

        }
        .container {
<<<<<<< HEAD:njoftimeupload.php
            max-width: 1000px;
=======
<<<<<<< HEAD:njoftimeupload.php
            max-width: 1000px;
=======
            max-width: 800;
>>>>>>> 81f76f7daf03d344727ebe45874d594012b18e10:admin/njoftimeupload.php
>>>>>>> b33d903e7a08d21bfdbbfa52b4814f196224b1d2:admin/njoftimeupload.php
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding-bottom: 130px;
           
        }
        h1 {
            text-align: center;
            color: orange;
            font-size: 2em;
<<<<<<< HEAD:njoftimeupload.php
            margin-bottom: 30px;
=======
<<<<<<< HEAD:njoftimeupload.php
            margin-bottom: 30px;
=======
            margin-bottom: 30px
>>>>>>> 81f76f7daf03d344727ebe45874d594012b18e10:admin/njoftimeupload.php
>>>>>>> b33d903e7a08d21bfdbbfa52b4814f196224b1d2:admin/njoftimeupload.php
        }
        form {
            display: flex;
            flex-direction: column;    
        }
        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input, textarea {
            padding: 12px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border 0.3s ease;
            resize: vertical;
            max-width:100%;
        }
        input:focus, textarea:focus {
            border-color: orange;
            outline: none;
        }
        button {
            background-color: orange;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        button:hover {
            background-color: green;
        }
        button:active {
            background-color: darkorange;
        }
        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            bottom: 0;
            left: 0;
            position: fixed;
        }


    </style>
</head>
<body>
    <div class="container">
        <h1>Posto Njoftimin</h1>
        <form method="POST" action="">
        <label for="titulli">Titulli</label>
        <input type="text"  id="titulli" name="titulli" required placeholder="Shkruani titullin e njoftimit ">

        <label for="short_text">Permbledhja e shkurter</label>
        <textarea name="short_text" id="short_text"  required placeholder="Shkruani permbledhjen e shkurter"></textarea>

        <label for="full_text">Teksti i plote</label>
        <textarea name="full_text" id="full_text" required placeholder="Shkruani tekstin e plot te njoftimit"></textarea>

        <button type="submit">Publiko Njoftimin </button>
    </form>
</div>
<footer>
    <div class="footer">
    <p>Copyright <a href="#">© WWW.COURSOZA.</a> All Rights Reserved</p>
</footer>
    
</body>
</html>