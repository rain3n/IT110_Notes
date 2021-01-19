<?php 
    $dsn = "mysql:host=localhost;dbname=notes_demo1";
    $user = "root";
    $password = "";
    $pdo = new PDO($dsn, $user, $password);

    date_default_timezone_set('Asia/Manila');

    if(!$pdo){
        echo "Failed to connect to MySQL Database";
        exit();
    }

?>