<?php
session_start();
include "database.php";

$username = 'admin';
$email = 'admin@example.com';
$dob = '2000-01-01';
$password = password_hash('admin', PASSWORD_DEFAULT);
$role = 'admin';


$stmt = $conn->prepare("INSERT INTO users (username, email, dob, password, role) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password), role = VALUES(role)");
$stmt->bind_param("sssss", $username, $email, $dob, $password, $role);

if ($stmt->execute()) {
    echo "Admin user created or updated successfully.";
} else {
    echo "Error creating or updating admin user.";
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Register</title>
    <script>
        function hapMbyll() {
            document.getElementById('loginForm').classList.toggle('hidden');
            document.getElementById('registerForm').classList.toggle('hidden');
        }
    </script>
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>
<h2>Welcome to COURSOZA</h2>

<div id="loginForm">
    <h3>Login</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
        <button type="button" onclick="hapMbyll()">Register</button>
    </form>
</div>

<div id="registerForm" class="hidden">
    <h3>Register</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br>
        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br>
        <label for="dob">Date of Birth:</label><br>
        <input type="date" name="dob" required><br>
        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit" name="register">Register</button>
        <button type="button" onclick="hapMbyll()">Back to Login</button>
    </form>
</div>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($_POST['register'])) {
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $dob = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_SPECIAL_CHARS);
        if (!empty($username) && !empty($email) && !empty($dob) && !empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                echo "Error: Username or Email already exists.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, email, dob, password, role) VALUES (?, ?, ?, ?, 'user')");
                $stmt->bind_param("ssss", $username, $email, $dob, $hashed_password);
                if ($stmt->execute()) {
                    echo "Registration successful.";
                    header("Location: index.html"); 
                    exit();
                } else {
                    echo "Error: Registration failed.";
                }
            }
            $stmt->close();
        } else {
            echo "Complete all fields to register.";
        }
    }

    if (isset($_POST['login'])) {
        if (!empty($username) && !empty($password)) {
            $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password, $role);
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;

                   
                    if ($username === 'admin' && $role === 'admin') {
                        header("Location: crud-dashboard.php");
                    } else {
                        header("Location: index.html");
                    }
                    exit();
                } else {
                    echo "Password is incorrect.";
                }
            } else {
                echo "User does not exist. Please register.";
            }
            $stmt->close();
        } else {
            echo "Enter username and password to login.";
        }
    }
}


$conn->close();
?>
