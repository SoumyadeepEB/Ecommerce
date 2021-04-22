<?php 
    include "../config.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $request = json_decode($_POST['json']);
        $order_id = $_POST['order_id'];
        $request->orderid = $order_id;
        $sql = "SELECT user_id FROM orders WHERE id='$order_id'";
        $query = mysqli_query($link,$sql);
        $user_id = mysqli_fetch_assoc($query)['user_id'];
        $timestamp = time();
        $path = '../caches/notif_'.$user_id.'.json';
        if(file_exists($path)){
            $data = file_get_contents($path);
            $data = json_decode($data);
            $data->$timestamp = $request;
            file_put_contents($path,json_encode($data));
        }else{
            $response = json_encode([$timestamp => $request]);
            file_put_contents($path,$response);
        }
        echo 'success';
    }else{
        echo 'Bad request';
    }
?>