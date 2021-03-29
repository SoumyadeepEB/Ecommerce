<?php 
    session_start();
    include "config.php";
    if(empty($_SESSION['id'])){
        header('location:login.php');
    }

    if(!empty($_GET['action'])){
        if($_GET['action'] == 'add' && !empty($_GET['pid'] && isset($_POST['addtocart']))){
            if(isset($_SESSION['id'])){
                $quantity = $_POST['quantity'];
                $product_id = $_GET['pid'];

                $sql = "SELECT * FROM product WHERE id='$product_id'";
                $query = mysqli_query($link,$sql);
                $item = mysqli_fetch_assoc($query);
                if(isset($_SESSION['cart_item']) || !empty($_SESSION['cart_item']) && array_key_exists($item['id'],$_SESSION['cart_item'])){
                    $_SESSION['cart_item'][$item['id']]['name'] = $item['name'];
                    $_SESSION['cart_item'][$item['id']]['price'] = $item['price'];
                    $_SESSION['cart_item'][$item['id']]['quantity'] += $quantity;
                }else{
                    $_SESSION['cart_item'][$item['id']]['name'] = $item['name'];
                    $_SESSION['cart_item'][$item['id']]['price'] = $item['price'];
                    $_SESSION['cart_item'][$item['id']]['quantity'] = $quantity;
                }
                $_SESSION['success'] = 'One product added into cart';
                header('location:viewproduct.php?pid='.$product_id);
            }else{
                header('location:login.php');
            }
        }
        
        if($_GET['action'] == 'remove' && !empty($_GET['pid'] && isset($_POST['removecart']))){
            $product_id = $_GET['pid'];
            unset($_SESSION['cart_item'][$product_id]);
            $_SESSION['success'] = 'One product removed from cart';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php' ?>
    <title>Cart | <?= isset($_SESSION['name']) ? $_SESSION['name'] : '' ?></title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <?php if($_SESSION['id'] != 1){ ?>
        <h1 class="mt-4 mb-4">Cart Items</h1>

        <?php if(isset($_SESSION['success'])){ ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php } ?>

        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if(!empty($_SESSION['cart_item'])){ $i = 1; $total = 0; foreach($_SESSION['cart_item'] as $key=>$item){ ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td>
                            <form action="cart.php?action=remove&pid=<?= $key ?>" method="POST">
                                <?= $_SESSION['cart_item'][$key]['name'] ?>&nbsp;&nbsp;&nbsp;
                                <button type="submit" class="btn btn-danger btn-sm" name="removecart">&#x2715</button>
                            </form>
                        </td>
                        <td><?= $_SESSION['cart_item'][$key]['quantity'] ?></td>
                        <?php 
                            $total += $_SESSION['cart_item'][$key]['price'] * $_SESSION['cart_item'][$key]['quantity'];
                        ?>
                        <td>&#8377 <?= number_format($_SESSION['cart_item'][$key]['price'] * $_SESSION['cart_item'][$key]['quantity']) ?></td>
                    </tr>
                <?php $i++; } ?>
                    <tr>
                        <th colspan="3">Total</th>
                        <td colspan="2">&#8377 <?= number_format($total) ?></td>
                    </tr>
                <?php $checkoutbtn = '<a href="order.php" class="btn btn-success"><i class="fas fa-arrow-circle-right"></i> Checkout</a>'; }else{ ?>
                    <tr><td colspan="5" class="text-center">Empty Cart</td></tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="text-right"><?= isset($checkoutbtn) ? $checkoutbtn : '' ?></div>
        <?php }else{ echo '<script>window.location.href="index.php"</script>'; } ?>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>