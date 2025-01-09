<?php   
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "lidhjatest";

    $conn = "";

    $conn = mysqli_connect($db_server, 
                $db_user, $db_password, 
                $db_name);

    if($conn->connect_error){
        echo"You're not connected";
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Connected successfully!". "<br>";
    }
?>