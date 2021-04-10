<?php 
    include "../config.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $sql = "SELECT image FROM product WHERE id='$id'";
        $query = mysqli_query($link,$sql);
        $result = mysqli_fetch_assoc($query)['image'];
        print_r($result);
    }else{
        echo 'Bad request';
    }
?>