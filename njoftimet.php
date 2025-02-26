<?php

include 'LidhjaDatabaza/dbe.php';

$sql = "SELECT titulli, short_text, full_text, koha_publikimit FROM njoftimet ORDER BY id DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Njoftimet</title>
    <link rel="stylesheet" href="stilizimi/njoftimet.css">
</head>
<body>
    <header>
        <div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
            </div>
        <nav>
            <ul class="nav-links">
            <li><a href="ligjeratat.php">Ligjeratat</a></li>
                <li><a href="ushtrimet.php">Ushtrimet</a></li>
                <li><a href="njoftimet.php" class="active">Njoftimet</a> </li>
                <li><a href="kuizindex.php">Kuizet</a></li>
                <li><a href="contactform.php">Contact Us</a></li>
            </ul>
        </nav>
        </div>
    </header>
    <section id="notifications">
        <h2>Njoftimet </h2>
        <input type="text" id="search-bar" placeholder="Kerko njoftimet...">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification">
                    <h3><?= htmlspecialchars($row["titulli"]) ?></h3>
                    <p class="short-text"><?= htmlspecialchars($row["short_text"]) ?></p>
                    <p class="full-text"><?= htmlspecialchars($row["full_text"]) ?></p>
                    <p class="time">Publikuar me: <?=htmlspecialchars($row["koha_publikimit"]) ?></p>
                </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Ende nuk ka njoftime.</p>
    <?php endif; ?>   
    </section>
    <footer>
    <p>Copyright <a href="#" style="color: white; text-decoration: none;">© WWW.COURSOZA |</a> All Rights Reserved</p>
    </footer>
    <script src="njoftime.js"></script>
</body>
</html>