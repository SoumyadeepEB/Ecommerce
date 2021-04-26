<?php 
    include "../config.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $order_id = $_POST['order_id'];
        $products = $_POST['products'];
        $quantities = $_POST['qtys'];
        $prices = $_POST['prices'];
        foreach($products as $key=>$pid){
            $qty = $quantities[$key];
            $csql = "SELECT stocks FROM product WHERE id='$pid'";
            $cquery = mysqli_query($link,$csql);
            if($cquery){
                $curr_stock = mysqli_fetch_assoc($cquery)['stocks'];
                $update_stock = $curr_stock + $qty;
                $sql1 = "UPDATE product SET stocks='$update_stock' WHERE id='$pid'";
                $query1 = mysqli_query($link,$sql1);
                $sql2 = "UPDATE orders SET status=5 WHERE id='$order_id'";
                $query2 = mysqli_query($link,$sql2);
            }
        }
    }else{
        echo 'Bad request';
    }
?>