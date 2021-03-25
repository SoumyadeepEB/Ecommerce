<?php 
    session_start();
    include "config.php";
?>
<head>
    <?php include 'head.php' ?>
    <title><?= isset($_SESSION['name']) ? 'Ecommerce | '.$_SESSION['name'] : 'Ecommerce | Guest' ?></title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <?php if($_SESSION['type'] == 1){ ?>
            <div class="jumbotron mt-5">
                <h1 class="text-center">Admin Dashboard</h1><br>
                <div class="row">
                    <div class="col-md-4">
                        <?php 
                            $osql = "SELECT * FROM orders";
                            $oquery = mysqli_query($link,$osql);
                            $orders = mysqli_num_rows($oquery);
                        ?>
                        <div class="card">
                            <div class="card-body bg-info text-white" style="width:100%;">
                                <div class="float-left">
                                    <h2 class="card-title"><strong><?= isset($orders) ? $orders : 0 ?></strong></h2>
                                    <h4 class="card-text">Total Orders</h4>
                                </div>
                                <div class="float-right">
                                    <i class='fas fa-shopping-cart' style="font-size:80px;opacity:0.3"></i>
                                </div>
                            </div>
                            <div class="text-center" style="background-color:#258699"><a href="#" class="text-white">More info</a></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php 
                            $isql = "SELECT * FROM orders";
                            $iquery = mysqli_query($link,$isql);
                            $income = 0;
                            if(mysqli_num_rows($iquery) > 0){
                                while($row = mysqli_fetch_assoc($iquery)){
                                    $prices = explode(',',$row['prices']);
                                    $quantities = explode(',',$row['quantities']);
                                    foreach($prices as $key=>$price){
                                        $income += $price * $quantities[$key];
                                    }
                                }
                            }
                        ?>
                        <div class="card">
                            <div class="card-body bg-success text-white" style="width:100%;">
                                <div class="float-left">
                                    <h2 class="card-title"><strong>&#8377 <?= number_format($income) ?></strong></h2>
                                    <h4 class="card-text">Total Income</h4>
                                </div>
                                <div class="float-right">
                                    <i class='fas fa-money-bill-alt' style="font-size:80px;opacity:0.3"></i>
                                </div>
                            </div>
                            <div class="text-center" style="background-color:#248a3d"><a href="#" class="text-white">More info</a></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php 
                            $usql = "SELECT * FROM users WHERE type=0";
                            $uquery = mysqli_query($link,$usql);
                            $users = mysqli_num_rows($uquery);
                        ?>
                        <div class="card">
                            <div class="card-body bg-danger text-white" style="width:100%;">
                                <div class="float-left">
                                    <h2 class="card-title"><strong><?= isset($users) ? $users : 0 ?></strong></h2>
                                    <h4 class="card-text">Total Users</h4>
                                </div>
                                <div class="float-right">
                                    <i class='fas fa-user' style="font-size:80px;opacity:0.3"></i>
                                </div>
                            </div>
                            <div class="text-center" style="background-color:#b83737"><a href="#" class="text-white">More info</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <img src="assets/images/ecommerce.png" width="100%" height="300px" style="border:2px dotted blue">
        <?php }else{ 
                $usql = "SELECT product.id as product_id,category.name as category,subcategory.name as subcategory,product.name as product_name,price,image,product.status as status FROM product LEFT JOIN category ON product.cat_id = category.id LEFT JOIN subcategory ON product.subcat_id = subcategory.id WHERE product.status=1 ORDER BY product_id DESC LIMIT 3";
                $uquery = mysqli_query($link,$usql);
            ?>
            <div class="jumbotron mt-5">
            
                <?php if(isset($_SESSION['success'])){ ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php } ?>
                <?php if(isset($_SESSION['error'])){ ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php } ?>

                <h1 class="text-center">Welcome to Ecommerce</h1>
                <h3>Latest Products</h3>
                <div class="row">
                <?php if(mysqli_num_rows($uquery) > 0){ while($product = mysqli_fetch_assoc($uquery)){ ?>
                    <div class="col-md-4">
                        <div class="card">
                            <a href="viewproduct.php?pid=<?= $product['product_id'] ?>" class="product" title="<?= $product['product_name'] ?>">
                            <img class="card-img-top border-bottom" src="assets/images/products/<?= $product['image'] ?>" alt="<?= $product['image'] ?>" style="width:100%;height:200px">
                            </a>
                            <div class="card-body" style="width:100%;height:200px">
                                <h4 class="card-title"><?= $product['product_name'] ?></h4>
                                <h4 class="card-text">&#8377 <?= number_format($product['price']) ?></h4>
                                <p><span class="badge badge-primary" title="Category"><?= $product['category'] ?></span> <i class='fas fa-angle-double-right'></i> <span class="badge badge-secondary" title="Subcategory"><?= $product['subcategory'] ?></span></p>
                            </div>
                        </div>
                    </div>
                <?php }}else{echo '<h5>No Product Available</h5>';} ?>
                </div>
            </div>
            <div class="text-right"><h4><a href="products.php" class="text-dark">See All Products</a></h4></div>
        <?php } ?>
    </div>
    <?php include 'footer.php' ?>
</body>