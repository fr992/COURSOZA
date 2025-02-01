<?php
include 'LidhjaDatabaza/databaza.php'; 


$sql = "SELECT * FROM lendet ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LENDET</title>
</head>
<body>
    <header>
        <div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
         </div>
         <ul class="nav-links">
            <li><a href="index.html">Ballina</a></li>
      </div>  
    </header>  
    <form action="select_course.php" method="POST">
    <label for="course_select">Zgjidh Lenden:</label>
    <select id="course_select" name="course_id">
    
        
        <option value="1">Matematik 1</option>
        <option value="2">AOK</option>
        <option value="3">BIEE</option>
        <option value="4">Anglisht</option>
        <option value="5">Hyrje ne Shkenca Kompjuterike</option>
        <option value="6">Sisteme Operative</option>
        <option value="7">Qarqe Digjitale</option>
        <option value="8">Shkrim Akademik</option>
        <option value="9">Hyrje ne Sigurine e Informacionit</option>
        <option value="10">Matematika 2</option>
        <option value="11">Shkenca Kompjuterike 1</option>
        <option value="12">Nderveprim Kompjuter-Njeri</option>
        <option value="13">Struktura diskrete 1</option>
        <option value="14">Dizajni dhe zhvilli i Web</option>
        <option value="15">Shkenca Kompjuterike 2</option>
        <option value="16">Sistemet e bazes se te dhenave</option>
        <option value="17">Rrjeta kompjuterike dhe komunikimi</option>
        <option value="18">Hyrje ne Algortime</option>
    </select>
    <br><br>
    <button type="submit">Dergo</button>


                <?php
                
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['emri'] . " - " . $row['pershkrimi'] . "</option>";
                }
                ?>
            </select><br><br>
            
    
    </div>

    <div class="footer-bottom">
        <p>Copyright <a href="#">© WWW.COURSOZA.</a> All Rights Reserved</p>
    </div>
</body>
</html>

<?php
$conn->close();
?>
<style>
  body {
    line-height: 1.2;
    font-family: sans-serif;
    font-weight: 400;
    margin: 0;
    padding: 0;
}

header {
    background-color: rgb(34, 34, 34);
    padding: 16px;
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 100;
}

header .priv-navbar {
    display: flex;
    justify-content: center; 
    align-items: center;
    height: 75px;
    position: relative;
    border-bottom: 1px solid black;
    flex-wrap: wrap;
}

header .priv-navname {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
}

header .priv-navname h2 {
    color: white;
    font-size: 1.8em;
}

header .priv-navname h2 span {
    color: orange;
}

.nav-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
    align-items: center;
    justify-content: center;
}

.nav-links li a {
    color: white;
    text-decoration: none;
    font-size: 1.2em;
    padding: 8px 16px;
    position: relative;
    transition: color 0.3s ease;
}

.nav-links li a:hover {
    color: #f7b851;
}

.nav-links li a::before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 1.6px;
    background-color: white;
    transform: scaleX(0);
    transition: transform 0.3s ease-in-out;
    transform-origin: left;
}

.nav-links li a:hover::before {
    transform: scaleX(1);
}

a.back-button {
    color: white;
    font-size: 1.5rem;
    position: absolute;
    top: 20px;
    left: 20px;
    text-decoration: none;
    z-index: 10;
}

a.back-button:hover {
    color: #f7b851;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 100px;
}

form label {
    color: white;
    font-size: 1.2em;
    margin-bottom: 8px;
}

form select {
    padding: 8px;
    font-size: 1em;
    border: 2px solid #f7b851;
    border-radius: 8px;
    background-color: #333;
    color: white;
    width: 250px;
    margin-bottom: 16px;
    transition: border-color 0.3s;
}

form select:hover {
    border-color: #f7b851;
}

form button {
    padding: 10px 20px;
    background-color: #f7b851;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s;
}

form button:hover {
    background-color: #d98c11;
}

.footer-bottom {
    background-color: rgb(34, 34, 34);
    color: white;
    text-align: center;
    padding: 16px;
    position: fixed;
    width: 100%;
    bottom: 0;
}

.footer-bottom a {
    color: white;
    text-decoration: none;
}

.footer-bottom a:hover {
    color: #f7b851;
}

@media (max-width: 480px) {
    header .priv-navname h2 {
        font-size: 1.5em;
    }

    .nav-links {
        gap: 12px;
    }

    form label {
        font-size: 1em;
    }

    form select {
        width: 200px;
    }

    form button {
        font-size: 1em;
    }

    .footer-bottom {
        padding: 8px;
    }
}

@media (min-width: 1200px) {
    header .priv-navname h2 {
        font-size: 2em;
    }

    form label {
        font-size: 1.4em;
    }

    form select {
        width: 300px;
    }

    form button {
        font-size: 1.4em;
    }

    .footer-bottom {
        padding: 20px;
    }
}


</style>