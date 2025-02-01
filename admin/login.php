<?php 
session_start();
include "LidhjaDatabaza/database.php";

// i rujm do raste si variabla qe me mujt me bo ekzekutim me JS alert nese ka ndonje error
$loginError = false;
$passChangeInvalid = false;
$userExistsError = false;
// $passResetError = false;

define('ROLE_ADMIN', 'admin');
define('ROLE_USER', 'user');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if(isset($_POST['login'])) { // nese e ka bo click login
        if(!empty($username) && !empty($password)){
            $stmt = $conn->prepare("SELECT id, password, email, role FROM users WHERE username = ?");
            // stmt -> statement
            $stmt->bind_param("s", $username); // bound username per ? qe me gjet ne databaz
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password, $email, $role);
                $stmt->fetch(); // me i marr (fetch data)
                if(password_verify($password, $hashed_password)){
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;

                // verifikimi i adminit
                    if($role === ROLE_ADMIN) {
                        header("Location: admin/dashboard.php");
                        exit; // si return ne java
                    } else {
                        header("Location: index.html");
                        exit;
                    }
                } else {
                    $loginError = true;
                    $_SESSION['incorrect_user'] = $username; // e run username per password reset link
                }
            } else {
                $userExistsError = true; // echo "User does not exist. Please register.";
            }
            $stmt->close();
        } else {
            echo"Enter username and password to login";
        }
    }

    if(isset($_POST['register'])){ // nese e ka bo click register
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $dob = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_SPECIAL_CHARS);

        // validimi ne back

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format. Please provide a valid email address.";
        } else if (!empty($username) && !empty($dob) && !empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, dob, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $dob, $hashed_password, 'ROLE_USER');
            if ($stmt->execute()) {
                echo "Registration successful.";
                header("Location: index.html");
                exit();
            } else {
                echo "Error: Registration failed.";
            }
        } else {
            echo "Please complete all fields to register.";
        }
    }
    if(isset($_POST['reset_password'])) {
        $reset_email = filter_input(INPUT_POST, "reset_email", FILTER_SANITIZE_EMAIL);
        $new_password = filter_input(INPUT_POST, "new_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $username_for_reset = $_SESSION['incorrect_user']; // e merr username qe e ka shkru prej session 

        if(!empty($reset_email) && !empty($new_password)) {
            $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
            $stmt->bind_param("s", $username_for_reset);
            $stmt->execute();
            $stmt->bind_result($email_from_db);
            $stmt->fetch();

            $stmt->close();

            if($reset_email === $email_from_db){
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                $stmt =  $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmt->bind_param("ss", $hashed_new_password, $username_for_reset);

                if($stmt->execute()){
                    echo "<script>alert('Password has been reset successfully!');</script>";

                    session_unset();
                    session_destroy();
                } else {
                    echo "Error resetting password.";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Error resetting password.');</script>";
                 // $passChangeInvalid = true;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Register</title>

    <script>
        function hapMbyll(){
            document.getElementById('loginForm').classList.toggle('hidden');
            document.getElementById('registerForm').classList.toggle('hidden');
        }

        function showForgotPasswordForm(){
            document.getElementById('forgotPasswordForm').classList.remove('hidden');
            document.getElementById('loginForm').classList.add('hidden');
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #fff4e6; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: linear-gradient(135deg, #ffffff, #fff7f0);
            border: 1px solid #ffdcb2; 
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        
        .container h2 {
            font-size: 2em;
            font-weight: bold;
            color: #ff6f00;
            margin-bottom: 15px;
        }

        .container h3 {
            font-size: 1.3em;
            color: #333333; 
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #4d4d4d;
            text-align: left;
            font-size: 0.9em;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ffdcb2; 
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 0.95em;
            background-color: #fffaf5; 
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #ff6f00;
            outline: none;
            background-color: #ffffff;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #ff6f00; 
            color: #ffffff; 
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            margin-top: 10px;
        }

        button[type="button"] {
            background-color: #333333; 
        }

        button:hover {
            background-color: #e65c00; 
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        button[type="button"]:hover {
            background-color: #1a1a1a; 
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .container h2 {
                font-size: 1.8em;
            }

            .container h3 {
                font-size: 1.1em;
            }
        }
    </style>
</head>
<body>

<!-- login form -->
    <div class="container" id="loginForm">
        <h2>Welcome to Coursoza</h2>
        <h3>Login</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <!-- htmlspecialchars na run prej sulmeve qe munen me ardh permes 
            inputeve apo te njohura si cross site scripting ndersa PHP_SELF tregon ku kan me u perpunu keto te dhena ne kete forme,
                pra php self do te thote se do te perpunohen ne te njejtin file-->
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <?php if($loginError): ?>
                <div class="error-message" style="color: red; font-size: 0.9rem; margin-top: 10px;">
                    <p>Password is incorrect. <a href="#" onclick="showForgotPasswordForm()">Forgot Password?</a></p>
                </div>
            <?php endif; ?>

            <?php if($userExistsError): ?>
                <div class="error-message" style="color: red; font-size: 0.9rem; margin-top: 10px;">
                    <p>User does not exist. Please register.</p>
                </div>
            <?php endif; ?>

            <button type="submit" name="login">Login</button>
            <button type="button" name="register" onclick="hapMbyll()">Register</button>
        </form>
    </div>
<!-- register form -->
    <div id="registerForm" class="hidden container">
        <h3>Register</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="register">Register</button>
            <button type="button" onclick="hapMbyll()">Back to Login</button>
        </form>
    </div>

    <!-- forgot password form -->
     <div id="forgotPasswordForm" class="hidden container">
        <h3>Reset Password</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="reset_email">Email:</label>
            <input type="email" name="reset_email" placeholder="Enter your email" required>
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" placeholder="Enter a strong password" required>

        <!-- <?php if ($passChangeInvalid): ?>
            <div class="error-message" style="color: red; font-size: 0.9em; margin-top: 10px;">
                <p>The email you provided doesn't match the username's email.</p>
            </div>
            <?php endif; ?> -->

            <button type="submit" name="reset_password">Reset Password</button>
            <button type="button" onclick="showForgotPasswordForm()">Cancel</button>
        </form>
    </div>
</body>
</html>