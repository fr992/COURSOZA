<?php   
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "maindatabaze";

    $conn = ""; 
    // veq e inicializojme niher null tani e inicializojm posht me lidhje qe mos me pas naj error/konflikt

    $conn = mysqli_connect($db_server, 
                $db_user, $db_password, 
                $db_name);

    if($conn->connect_error){
        echo"Couldn't connect.";
        die("Connection failed: " . $conn->connect_error);
    } 
?>