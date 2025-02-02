<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zgjedh Lenden</title>
    <link rel="stylesheet" href="stilizimi/kuizstyles.css">
</head>
<body>
    <header>
        <div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
            </div>
            <ul class="nav-links">
                <li><a href="ligjeratat.php">Lendet</a></li>
                <li><a href="ushtrimet.php">Ushtrimet</a></li>
                <li><a href="njoftimet.php">Njoftime</a></li>
                <li><a href="kuizindex.php">Kuizet</a></li>
            </ul>
        </div>
    </header>

    <div class="subject-selection">
        <h1>Zgjedh Lenden</h1>
        <form action="Kuiz.php" method="get">
            <select name="subject_id" required>
                <option value="" disabled selected>Zgjedh nje nga lendet</option>
                <option value="1">Databaza</option>
                <option value="2">Shkenca Kompjuterike 2</option>
                <option value="3">Dizajni dhe Zhvillimi i Web</option>
            </select>
            <button type="submit">Fillo Kuizin</button>
        </form>
    </div>
</body>
</html>
