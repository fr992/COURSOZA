<?php
include 'LidhjaDatabaza/database.php';

// merri user nga databaza
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// user update
if (isset($_POST['update'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    //user update ne sql db
    $updateSql = "UPDATE users SET username = ?, email = ?, password = ?, dob = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    if (!$updateStmt) {
        die('Error (updateStmt): ' . $conn->error);
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $updateStmt->bind_param("ssssi", $username, $email, $hashedPassword, $dob, $id);
    $updateStmt->execute();

    // nese u bo update mir shko te e para nese jo te e dyta (ne url)
    if ($updateStmt->affected_rows > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "");
        echo"<alert>User updated.</alert>";
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "");
        echo"<alert>User wasn't updated.</alert>";
        exit();
    }
}

// nese klikohet ne delete
if (isset($_POST['delete'])) {
    $userId = $_POST['user_id'];

    // verifikimi nese user ekziston
    $checkSql = "SELECT id FROM users WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkStmt->store_result();

    // nese ekziston vazhdo...
    if ($checkStmt->num_rows > 0) {
        $deleteSql = "DELETE FROM users WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $userId);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?");
            echo"<alert>User has been deleted.</alert>";
            exit();
        } else {
            header("Location: " . $_SERVER['PHP_SELF'] . "");
            echo"<alert>Couldn't delete user.</alert>";
            exit();
        }
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "");
        echo"<alert>User isn't found.</alert>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | User Management</title>
    <link rel="stylesheet" href="manageusers.css">
    <script>
        function scrollToSection(section) {
            document.getElementById(section).scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</head>
<body>
    <header>
        <a href="manageusers.php" class="back-btn1">Back</a>
        <h1>Users Management System</h1>
    </header>

    <h2 id="userList">User List</h2>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Date of Birth</th>
            <th>Admin Action</th>
        </tr>
        <?php
        if ($result) {
            while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['dob']); ?></td>
                <td>
                    <a href="userlista.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <form method="POST" style="display:inline; padding: 0px; margin-left: 7px;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
            <?php }
        } else {
            echo "<tr><td colspan='4'>No users found.</td></tr>";
        }
        ?>
    </table>

    <?php 
    // Edit form
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if(!$stmt) {
            die("Error: " . $conn->error);
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
            echo "<p>User doesn't exist.</p>";
        }
    }
    ?>
</body>
</html>
