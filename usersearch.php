<?php
// include i lidhjes
include 'database.php';

// me fshi user
if (isset($_POST['delete'])) {
    $userId = $_POST['user_id'];

    // kontrollo nese user ekziston
    $checkSql = "SELECT id FROM users WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $deleteSql = "DELETE FROM users WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $userId);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            header("Location: usersearch.php?search=" . $_GET['search']);
            exit();
        } else {
            echo "<script>alert('Error deleting user.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }
}

// user update
if (isset($_POST['update'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    // hash passwordin(qe mos me u rujt si string po si hashkey ne databaz" one way algo eshte)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $updateSql = "UPDATE users SET username = ?, email = ?, password = ?, dob = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssi", $username, $email, $hashedPassword, $dob, $id);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        header("Location: usersearch.php?search=" . $_GET['search']);
        exit();
    } else {
        echo "<script>alert('No changes made.');</script>";
    }
}

// kerkimi i userave
$searchTerm = "";
$result = null;
if (isset($_GET['search'])) {
    $searchTerm = '%' . $_GET['search'] . '%';
    $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manageusers.css">
    <title>Search Results</title>
</head>
<body>

<header>
    <a href="manageusers.php" class="back-btn1">Back</a>
    <h1>Users Management System</h1>
</header>

<h2>User Search Results</h2>
<table border="1">
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Date of Birth</th>
        <th>Admin Action</th>
    </tr>
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['dob']); ?></td>
                <td>
                    <a href="usersearch.php?search=<?php echo $_GET['search']; ?>&edit_id=<?php echo $row['id']; ?>">Edit</a>
                    <form method="POST" style="display:inline; padding: 0px; margin-left: 7px;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='4'>The user you searched doesn't exist.</td></tr>";
    }
    ?>
</table>

<?php
// Edit form
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
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
