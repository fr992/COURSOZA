<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "njoftimet_db";

$conn= new mysqli($servername,$username,$password,$dbname);

if($conn->connect_error){
    die("Lidhja me bazen e te dhenave deshtoi: " . $conn->connect_error);

}
?>
