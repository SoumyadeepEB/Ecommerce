<?php 
    include "../config.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $status = $_POST['status'];
        if($status == 0){
            $sql = "UPDATE category SET status=1 WHERE id='$id'";
        }else{
            $sql = "UPDATE category SET status=0 WHERE id='$id'";
        }
        $query = mysqli_query($link,$sql);
        if(!$query){
            echo 'alert("Error")';
        }
    }else{
        echo 'Bad request';
    }
?>