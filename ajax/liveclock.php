<?php 
    include "../config.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo 'Time: '.date('h:i:s a');
    }else{
        echo 'Bad request';
    }
?>