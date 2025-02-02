<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "maindatabase";

    try {
        $pdo = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_password);
        // ktu e kena perdor pdo se ka ma shum shanca me na ndodh naj error edhe eshte ma let e kuptushme kur kem error, eshte ma fleksibil 
        // edhe eshte me i lexushem per user se perdallim prej mysqli qe statements i ka me ? ktu munesh me i lon me emra, pra me pas diqka ma komplekse o ma e let kjo pdo
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>