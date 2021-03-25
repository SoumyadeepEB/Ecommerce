<?php 
    session_start();
    include "config.php";
    if(empty($_SESSION['id'])){
        header('location:login.php');
    }
    $products = []; $quantities = []; $prices = []; $total = 0;
    if(isset($_SESSION['cart_item']) && !empty($_SESSION['cart_item'])){
        foreach($_SESSION['cart_item'] as $key=>$item){
            $total += $_SESSION['cart_item'][$key]['price'] * $_SESSION['cart_item'][$key]['quantity'];
            array_push($products,$key);
            array_push($quantities,$item['quantity']);
            array_push($prices,$item['price']);
        }
        $products = implode(',',$products);
        $quantities = implode(',',$quantities);
        $prices = implode(',',$prices);
    }

    if(isset($_POST['confirm_order'])){
        $user_id = $_SESSION['id'];
        $asql = "SELECT address FROM users WHERE id='$user_id'";
        $aquery = mysqli_query($link,$asql);
        $address = mysqli_fetch_assoc($aquery)['address'];
        $payment_method = $_POST['payment_method'];
        $invoice = 'dummy.pdf';
        $timestamp = date('Y-m-d H:m:s a');

        $sql = "INSERT INTO orders (user_id,products,quantities,prices,address,payment_method,invoice,timestamp) VALUES ('$user_id','$products','$quantities','$prices','$address','$payment_method','$invoice','$timestamp')";
        $query = mysqli_query($link,$sql);
        if($query){
            unset($_SESSION['cart_item']);
            $_SESSION['success'] = 'Order successfully placed';
            header('location:index.php');
        }else{
            $_SESSION['error'] = 'Order failed';
            header('location:index.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php' ?>
    <title>New Order | <?= isset($_SESSION['name']) ? $_SESSION['name'] : '' ?></title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
    <?php if(!empty($_SESSION['cart_item'])){
    ?>
        <h1 class="mt-4 mb-4">Order Total : &#8377 <?= number_format($total) ?></h1>
        <form action="" method="POST">
        <table class="table table-bordered">
            <thead class="bg-primary text-white">
                <tr><th colspan="2"><h4>Available Payment Method</h4></th></tr>
            </thead>
            <tbody>
                <tr>
                    <th>Cash On Delivery</th>
                    <td><input type="radio" class="form-control" id="cod" name="payment_method" value="cod" checked></td>
                </tr>
                <tr>
                    <th>Debit Card</th>
                    <td><input type="radio" class="form-control" id="cod" name="payment_method" value="debit card" disabled></td>
                </tr>
                <tr>
                    <th>Credit Card</th>
                    <td><input type="radio" class="form-control" id="cod" name="payment_method" value="credit card" disabled></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <a href="cart.php" class="btn btn-danger"><i class="fas fa-arrow-circle-left"></i> Back</a>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success" name="confirm_order"><i class="fas fa-check-circle"></i> Confirm</button>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
    <?php }else{ echo '<script>window.location.href="index.php"</script>'; } ?>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>