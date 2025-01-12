<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'database.php';

if(isset($_GET['status'])) {
    $statusMessage = htmlspecialchars($_GET['status']);
    echo "<script>
        setTimeout() => {
            alert('$statusMessage');
            window.history.replaceState({}, document.title, window.location.pathname);
            }, 200);
    </script>";
    // window.history.replaceState qe me parandalu alertin me dal sa her bojm refresh mas update/fshirjes/deshtimit qe kem bo
}
// kerkimi nga ana e adminit
$searchTerm = "";
if(isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);

    if(!$stmt){
        die('Error preparing statement: ' . $conn->error);
    }
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM users");
}
// regjistrimi prej anes se adminit
if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    $checkSql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if($checkResult->num_rows > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=Username or Email already exists");
        exit();
    } else {
        $insertSql = "INSERT INTO users (username, email, password, dob) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt->bind_param("ssss", $username, $email, $hashedPassword, $dob);
        $insertStmt->execute();

        if($insertStmt->affected_rows > 0) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?status=Registration successful");
            exit();
        } else {
            header("Location: " . $_SERVER['PHP_SELF'] . "?status=Registration failed");
            exit();
        }
    }
}

// perditsimi i userave
if(isset($_POST['update'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    $updateSql = "UPDATE users SET username = ?, email = ?, password = ?, dob = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    if(!$updateStmt){
        die('Error preparing update statement: ' . $conn->error);
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $updateStmt->bind_param("ssssi", $username, $email, $hashedPassword, $dob, $id);
    $updateStmt->execute();

    if($updateStmt->affected_rows > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=Update successful");
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=Update failed, no changes.");
        exit();
    }
}

// fshirja e userave
if(isset($_POST['delete'])) {
    $userId = $_POST['user_id'];

    $checkSql = "SELECT id FROM users WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if($checkStmt->num_rows > 0) {
        $deleteSql = "DELETE FROM users WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $userId);
        $deleteStmt->execute();

        if($deleteStmt->affected_rows > 0) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?status=User deleted");
            exit();
        } else {
            header("Location: " . $_SERVER['PHP_SELF'] . "?status=Delete failed");
            exit();
        }
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=User not found");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manageusers.css">
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

    <!-- regjistrimi prej adminit -->
     <h2>Register New User</h2>
     <form method="POST">
        <input type="text" placeholder="Username" name="Username" id="username" required><br><br>
        <input type="email" placeholder="Email" name="Email" id="email" required><br><br>
        <input type="password" placeholder="Password" name="Password" id="password" required><br><br>
        <input placeholder="Date of Birth" type="date" name="dob" id="dob" required><br><br>
        <button type="submit" name="register">Register</button>
     </form>

    <!-- search forma per kerkim -->
    <h2>Search Users</h2>
    <form method="POST">
        <input type="text" name="search" placeholder="Search by Username or Email" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- lista e userave -->
    <h2>User List</h2>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Date of Birth</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['dob']); ?></td>
            <td>
                <a href="manageusers.php?id=<?php echo $row['id']; ?>">Edit</a>
                <form method="POST" style="display:inline; padding: 0px; margin-left: 7px;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete" class="delete-button">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table> 

    <!-- edit user form -->
    <?php 
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if(!$stmt) {
                die("Error: preparing sql statement: " . $conn->error);
            }
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if($user) {
                ?> 
                <h2>Edit User</h2>
                <form method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required><br><br>
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required><br><br>
                <button class="update-button" type="submit" name="update">Update User</button>
            </form>
            <?php
            } else {
                echo"<p>User doesn't exist.</p>";
            }
        }
    ?>
</body>
</html>