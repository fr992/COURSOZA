<?php
    session_start();
    include ('../LidhjaDatabaza/database.php');

    $conn = new mysqli("localhost", "root", "", "lidhjatest");
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $result = $conn->query("SELECT COUNT(*) AS count FROM users");

    if($result){
        $totalUsers = $result->fetch_assoc()['count']; // assoc per associative
    } else {
        $totalUsers = 0;
        error_log("Ska user ne databaz ose ka ndonje error.");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a{
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin: 5px 0;
            display: block;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px 20px 20px 40px;
            background-color: #f8f9fa;
        }

        .content header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

        .content header h1 {
            margin: 0;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .dashboard-content {
            margin-top: 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1 1 calc(33.333% - 20px);
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
            margin-left: 20px;
        }

        .card h2 {
            margin: 0;
            font-size: 2rem;
            color: blue;
        }

        .card p {
            margin: 10px 0 0;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 200px;
            }
            .card {
                flex: 1 1 calc(50% - 20px);
            }
        }
        @media (max-width: 576px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
            }
            .content {
                margin-left: 0;
            }
            .dashboard-content {
                flex-direction: column;
            }
            .card {
                flex: 1 1 100%;
            }
        }
    </style>

    <script>
        function boLogout(){
            if(confirm("Are you sure you want to logout?")){
                window.location.href = "login.php";
            } else {
                console.log("Logout canceled");
            }
        }

        function gotoUsersManage(){
            window.location.href = "manageusers.php";
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Admin View</h2>
        <a href="dashboard.php">Statistics</a>
        <a href="manageusers.php" onclick="gotoUsersManage()">Register Users</a>
        <a href="">Reports</a>
        <a href="admin_upload.php">Lendet</a>
        <a href="userlista.php">Users List</a>
        <a href="ushtrimeadmin.php">Materiali</a>
        <a href="">Settings</a>
        <a href="" onclick="boLogout()">Logout</a>
    </div>

    <div class="content">
        <header>
            <h1>Dashboard</h1>
            <button type="submit" class="logout-btn" onclick="boLogout()">Logout</button>
        </header>
        <div class="dashboard-content">
            <div class="card">
                <h2><?php echo $totalUsers; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="card">
                <h2>18</h2>
                <p><a href="subjectstats.php" style="text-decoration: none;">Subjects</a></p>
            </div>
            <div class="card">
                <h2>X</h2>
                <p>Reports</p>
            </div>

            <!-- shton sa dush cards me div h2 edhe p -->
        </div>
    </div>
</body>
</html>