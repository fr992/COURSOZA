<?php
include 'databaza.php'; 


if (isset($_GET['lenda_id'])) {
    $lenda_id = intval($_GET['lenda_id']); 

    
    if ($conn->connect_error) {
        die("Gabim lidhjeje me bazën e të dhënave: " . $conn->connect_error);
    }

   
    $stmt = $conn->prepare("SELECT * FROM ligjeratat WHERE lenda_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $lenda_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $ligjeratat = $result->fetch_all(MYSQLI_ASSOC); 
    } else {
        $ligjeratat = []; 
    }

    $stmt->close();
} else {
    die("Lënda nuk është e specifikuar.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligjeratat</title>
</head>
<body>
<a href="javascript:history.back()" class="back-button">←</a>
<div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
            </div>
            <ul class="nav-links">
                <li><a href="ligjeratat.php">Ligjeratat</a></li>
            </ul>
            </div>
    <h1>Ligjeratat</h1>
    <?php if (!empty($ligjeratat)): ?>
        <?php foreach ($ligjeratat as $ligjerata): ?>
            <div class="ligjerata">
                <h2><?php echo htmlspecialchars($ligjerata['titulli']); ?></h2>
                <a href="<?php echo htmlspecialchars($ligjerata['file_path']); ?>" download>Shkarko</a> | 
                <a href="<?php echo htmlspecialchars($ligjerata['file_path']); ?>" target="_blank">Shiko</a>
                <p><small>Ngarkuar me: <?php echo $ligjerata['created_at']; ?></small></p>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results">Nuk ka ende Ligjerata te shtuara per kete lende.</p>
    <?php endif; ?>
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
.priv-navbar {
    display: flex;
    justify-content: start;
    align-items: center;
    right: 0;
    border-bottom: 1.6px solid black;
    top: 0;
    height: 75px;
    width:100%;
    padding: 8px 16px;
    background-color: rgb(34,34,34);
    flex-wrap: wrap;
    position: fixed;
  }
  .back-button {
    position: fixed;
    top: 90px;
    left: 0px;
    padding: 10px 20px;
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
.priv-navbar ul {
    display: flex;
    list-style: none;
    flex: 4;
    padding: 0;
    margin: 0;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
  } 
  
  .priv-navbar ul li a {
    padding: 8px 16px;
    text-decoration: none;
    color: white;
    font-size: 1.5em;
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
h1 {
    text-align: center;
    font-size: 2.5em;
    color: orange;
    margin-top: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid white;
    text-shadow: -1px 1px white, 1px 1px white, 1px -1px white, -1px -1px white;
}

.ligjerata {
    background: linear-gradient(135deg, #d98c11)
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin: 20px auto;
    width: 90%;
    max-width: 800px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}


.ligjerata h2 {
    font-size: 1.8em;
    color:black;
    margin-bottom: 10px;
}

.ligjerata a {
    font-size: 1.2em;
    text-decoration: none;
    color: black;
    margin-right: 15px;
    padding: 8px 16px;
    border-radius: 5px;
}

.ligjerata a:hover {
    background: #d98c11;
}

.ligjerata p small {
    font-size: 0.9em;
}


.no-results {
    text-align: center;
    font-size: 1.2em;
    color: #e74c3c;
    margin-top: 50px;
}

.footer-bottom {
    width: 100%;
    text-align: center;
    background-color: rgb(34,34,34);
    padding: 10px;
    position: relative;
    bottom: 0;
}

.footer-bottom a {
    color: orange;
    text-decoration: none;
}


    </style>
</body>
</html>