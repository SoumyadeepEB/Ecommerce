<?php 
    include "../config.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $status = $_POST['status'];

        switch($status){
            case 0:
                $sql = "UPDATE orders SET status=0 WHERE id='$id'";
                $response = ['id'=>0,'status'=>'Cancelled'];
                break;
            case 2:
                $sql = "UPDATE orders SET status=2 WHERE id='$id'";
                $response = ['id'=>2,'status'=>'Packed'];
                break;
            case 3:
                $sql = "UPDATE orders SET status=3 WHERE id='$id'";
                $response = ['id'=>3,'status'=>'Shipped'];
                break;
            case 4:
                $sql = "UPDATE orders SET status=4 WHERE id='$id'";
                $response = ['id'=>4,'status'=>'Delivered'];
                break;
        }
        $query = mysqli_query($link,$sql);
        if(!$query){
            echo 'alert("Error")';
        }
        print_r(json_encode($response));
    }else{
        echo 'Bad request';
    }
?>