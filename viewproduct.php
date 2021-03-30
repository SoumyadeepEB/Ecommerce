<?php 
    session_start();
    include "config.php";
    $quantities = [1,2,3,4,5,6,7,8,9,10];
    $product_id = mysqli_real_escape_string($link,$_GET['pid']);
    $fsql = "SELECT product.id as product_id,category.name as category,subcategory.name as subcategory,product.name as product_name,price,image,stocks,description FROM product LEFT JOIN category ON product.cat_id = category.id LEFT JOIN subcategory ON product.subcat_id = subcategory.id WHERE product.id='$product_id'";
    $fquery = mysqli_query($link,$fsql);
    $product = mysqli_fetch_assoc($fquery);
?>
<head>
    <?php include 'head.php' ?>
    <title><?= isset($product['product_name']) ? $product['product_name'] : '' ?></title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <?php if($_SESSION['type'] != 1){ ?>
            <div class="jumbotron mt-5">

            <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php } ?>
            
                <h1 class="text-center"><?= $product['product_name'] ?></h1>
                <div class="row mt-5">
                    <div class="col-md-6 float-left">
                        <img src="assets/images/products/<?= $product['image'] ?>" height="500px" width="500px" style="border:2px solid">
                    </div>
                    <div class="col-md-6 float-right">
                    <form action="cart.php?action=add&pid=<?= $product['product_id'] ?>" method="POST">
                        <table cellpadding="20">
                            <tr>
                                <th><h4>Description:</h4></th>
                                <td><h4><?= $product['description'] ?></h4></td>
                            </tr>
                            <tr>
                                <th><h4>Category:</h4></th>
                                <td><h4 class="text-primary"><?= $product['category'] ?></h4></td>
                            </tr>
                            <tr>
                                <th><h4>Subcategory:</h4></th>
                                <td><h4 class="text-secondary"><?= $product['subcategory'] ?></h4></td>
                            </tr>
                            <tr>
                                <th><h2>Price:</h2></th>
                                <td><h2><strong>&#8377 <?= number_format($product['price']) ?></strong></h2></td>
                            </tr>
                            <tr>
                                <?php if($product['stocks'] > 0){ ?>
                                <td><h4>Qty:</h4></td>
                                <td><h4>
                                    <select name="quantity" title="Quantity">
                                        <?php foreach($quantities as $quantitiy){ ?>
                                            <option value="<?= $quantitiy ?>"><?= $quantitiy ?></option>
                                        <?php } ?>
                                    </select>
                                </h4></td>
                                <?php } ?>
                            </tr>
                            <tr class="text-center">
                                <td><a href="products.php" class="btn btn-default btn-lg">Back</a></td>
                                <?php if($product['stocks'] > 0){ ?>
                                <td><button type="submit" name="addtocart" class="btn btn-success btn-lg" title="Add to Cart"><i class='fas fa-shopping-cart'></i> Add to Cart</button></td>
                                <?php }else{ ?>
                                <td><p class="btn btn-default btn-lg text-danger"><strong>Out of Stock</strong></p></td>
                                <?php } ?>
                            </tr>
                        </table>
                    </form>
                    </div>
                </div>
            </div>
        <?php }else{ echo '<script>window.location.href="index.php"</script>'; } ?>
    </div>
    <?php include 'footer.php' ?>
</body>