<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// lidhja me databaz
include 'LidhjaDatabaza/database.php';


// kerkimi behet ketu
$searchTerm = "";
if (isset($_POST['search'])) {
    $searchTerm = '%' . $_POST['search'] . '%';
    $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Error: ' . $conn->error);
    }
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM users");
}

// regjistrimi prej anes se adminit (dashboard)
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    $checkSql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $insertSql = "INSERT INTO users (username, email, password, dob) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt->bind_param("ssss", $username, $email, $hashedPassword, $dob);
        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0) {
            header("Location: userlista.php");
            exit();
        } else {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manageusers.css">
    <title>Manage Users</title>
    <style>
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
    </style>
</head>
<body>
<header>
    <a href="dashboard.php" class="back-btn1">Back</a>
    <h1>Users Management System</h1>
</header>
    <h2>Register New User</h2>
    <form method="POST">
        <input placeholder="Username" type="text" name="username" id="username" required><br><br>
        <input placeholder="Email" type="email" name="email" id="email" required><br><br>
        <input placeholder="Password" type="password" name="password" id="password" required><br><br>
        <input placeholder="Date of Birth" type="date" name="dob" id="dob" required><br><br>
        <button type="submit" name="register">Register</button>
    </form>

    <h2>Search Users</h2>
        <form method="GET" action="usersearch.php">
        <input type="text" name="search" placeholder="Search by Username or Email" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>
</body>
</html>

