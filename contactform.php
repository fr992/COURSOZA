<?php
include 'LidhjaDatabaza/dbcontact.php'; // lidhja

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $time = date('Y-m-d H:i:s'); // koah aktuale

    // insertimi ne databaze
    $query = "INSERT INTO contact_messages (name, email, message, time) VALUES (:name, :email, :message, :time)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':time', $time);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Message sent successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: Could not send message.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        .priv-navbar {
            display: flex;
            justify-content: start;
            align-items: center;
            border-bottom: 1.6px solid black;
            height: 72px;
            padding: 8px 16px;
            background-color: rgb(34, 34, 34);
            flex-wrap: wrap;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }

        .priv-navbar .priv-navname {
            margin-right: 50px;
        }

        header .priv-navbar h2 {
            color: white;
            font-size: 1.6em;
        }

        .priv-navbar ul {
            display: flex;
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
            font-size: 1.2em;
            position: relative;
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

        .priv-navbar ul li a:hover {
            color: #F7B851;
        }

        .priv-navbar ul li a:hover::before {
            transform: scaleX(1);
        }

        .priv-navbar .priv-signin {
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }

        .priv-signin i {
            color: white;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding-top: 60px; 
            padding-bottom: 40px; 
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: orange;
            color: white;
        }

        .contact-form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .contact-form-container label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .contact-form-container input,
        .contact-form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .contact-form-container textarea {
            resize: vertical;
        }

        .contact-form-container input[type="submit"] {
            background-color: blue;
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .contact-form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

    </style>
</head>
<body>

    <!-- Navbari -->
    <header>
        <div class="priv-navbar">
            <div class="priv-navname">
                <h2>COURS<span style="color: orange;">OZA</span></h2>
            </div>
            <ul class="nav-links">
                <li><a href="lendet.php">Lendet</a></li>
                <li><a href="ushtrimet.php">Ushtrimet</a></li>
                <li><a href="njoftimet.php">Njoftime</a></li>
                <li><a href="kuizindex.php">Kuizet</a></li>
            </ul>
            <div class="priv-signin">
                <a href="admin/login.php"><i class="login-logo fas fa-right-to-bracket" aria-label="Login"></i></a>
            </div>
        </div>
    </header>

    <!-- Contact Form -->
    <div class="contact-form-container">
        <h1>Contact Us</h1>
        <form action="contactform.php" method="POST">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="message">Message:</label><br>
            <textarea id="message" name="message" rows="4" required></textarea><br><br>
            
            <input type="submit" value="Send Message">
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 COURS<span style="color: orange;">OZA</span>. All rights reserved.</p>
    </footer>

</body>
</html>
