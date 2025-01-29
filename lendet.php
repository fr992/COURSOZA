<?php
include 'databaza.php'; 


$sql = "SELECT * FROM lendet ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LENDET</title>
    <link rel="stylesheet" href="course.css">
</head>
<body>
<a href="javascript:history.back()" class="back-button">←</a>
    <header>
        <div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
                <ul class="nav-links">
                  <li><a href="ligjeratat.php">Ligjeratat</a></li>
                </ul>
         </div>
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