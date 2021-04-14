<?php 
    date_default_timezone_set("Asia/Kolkata");
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'ecommerce';

    $link = mysqli_connect($server,$username,$password,$db);

    if(!$link){
        die('Database connection error '.mysqli_connect_error());
    }
?>