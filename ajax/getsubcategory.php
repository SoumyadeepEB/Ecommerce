<?php 
    include "../config.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $category = $_POST['category'];
        $sql = "SELECT * FROM subcategory WHERE cat_id='$category' AND status=1";
        $query = mysqli_query($link,$sql);
        $response = '<option value="">--Select Subcategory--</option>';
        while($subcategory = mysqli_fetch_assoc($query)){
            $response .= '<option value="'.$subcategory['id'].'">'.$subcategory['name'].'</option>';
        }
        echo $response;
    }else{
        echo 'Bad request';
    }
?>