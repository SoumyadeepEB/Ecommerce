<?php 
    session_start();
    include "config.php";
?>
<head>
    <?php include 'layouts/head.php' ?>
    <title><?= isset($_SESSION['name']) ? 'Ecommerce | '.$_SESSION['name'] : 'Ecommerce | Guest' ?></title>
</head>
<body>
    <?php include 'layouts/header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <?php if($_SESSION['type'] == 1){ ?>
            <div class="jumbotron mt-5">
                <h1 class="text-center">Admin Dashboard</h1>
                <p class="text-center" id="datetime">
                    <strong id="date">Date: <?= date('d').'<sup>'.date('S').'</sup>'.' '.date('F Y') ?></strong><br>
                    <strong id="time">Time: <?= date('h:i:s a') ?></strong>
                </p>
                <div class="row">
                    <div class="col-md-4">
                        <?php 
                            $today = date('Y-m-d');
                            $sql = "SELECT * FROM orders WHERE date='$today'";
                            $query = mysqli_query($link,$sql);
                            if(mysqli_num_rows($query) > 0){
                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                $today_income = 0;
                                foreach($results as $result){
                                    $prices = json_decode($result['prices']);
                                    $quantities = json_decode($result['quantities']);
                                    foreach($prices as $key=>$price){
                                        $today_income += $price * $quantities[$key];
                                    }
                                }
                            }else{
                                $today_income = 0;
                            }
                        ?>
                        <div class="card">
                            <div class="card-body bg-info text-white" style="width:100%; height:150px;">
                                <div class="float-left">
                                    <h2 class="card-title"><strong>&#8377 <?= isset($today_income) ? number_format($today_income) : 0 ?></strong></h2>
                                    <h4 class="card-text">Today</h4>
                                </div>
                                <div class="float-right">
                                    <i class='fas fa-money-bill-alt' style="font-size:80px;opacity:0.3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php 
                            $firstday = date('Y-m-01');
                            $today = date('Y-m-d');
                            $sql = "SELECT * FROM orders WHERE date BETWEEN '$firstday' AND '$today'";
                            $query = mysqli_query($link,$sql);
                            if(mysqli_num_rows($query) > 0){
                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                $current_month_income = 0;
                                foreach($results as $result){
                                    $prices = json_decode($result['prices']);
                                    $quantities = json_decode($result['quantities']);
                                    foreach($prices as $key=>$price){
                                        $current_month_income += $price * $quantities[$key];
                                    }
                                }
                            }else{
                                $current_month_income = 0;
                            }
                        ?>
                        <div class="card">
                            <div class="card-body bg-success text-white" style="width:100%; height:150px;">
                                <div class="float-left">
                                    <h2 class="card-title"><strong>&#8377 <?= isset($current_month_income) ? number_format($current_month_income) : 0 ?></strong></h2>
                                    <h4 class="card-text">Current Month</h4>
                                </div>
                                <div class="float-right">
                                    <i class='fas fa-money-bill-alt' style="font-size:80px;opacity:0.3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php 
                            $month_ini = new DateTime("first day of last month"); $month_ini = $month_ini->format('Y-m-d');
                            $month_end = new DateTime("last day of last month"); $month_end = $month_end->format('Y-m-d');

                            $sql = "SELECT * FROM orders WHERE date BETWEEN '$month_ini' AND '$month_end'";
                            $query = mysqli_query($link,$sql);
                            if(mysqli_num_rows($query) > 0){
                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                $prev_month_income = 0;
                                foreach($results as $result){
                                    $prices = json_decode($result['prices']);
                                    $quantities = json_decode($result['quantities']);
                                    foreach($prices as $key=>$price){
                                        $prev_month_income += $price * $quantities[$key];
                                    }
                                }
                            }else{
                                $prev_month_income = 0;
                            }
                        ?>
                        <div class="card">
                            <div class="card-body bg-danger text-white" style="width:100%; height:150px;">
                                <div class="float-left">
                                    <h2 class="card-title"><strong><?= isset($prev_month_income) ? number_format($prev_month_income) : 0 ?></strong></h2>
                                    <h4 class="card-text">Last Month</h4>
                                </div>
                                <div class="float-right">
                                    <i class='fas fa-money-bill-alt' style="font-size:80px;opacity:0.3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <img src="assets/images/ecommerce.png" width="100%" height="300px" style="border:2px dotted blue">
        <?php }else{ 
                $usql = "SELECT product.id as product_id,category.name as category,subcategory.name as subcategory,product.name as product_name,price,image,product.status as status,stocks FROM product LEFT JOIN category ON product.cat_id = category.id LEFT JOIN subcategory ON product.subcat_id = subcategory.id WHERE product.status=1 AND stocks!=0 ORDER BY product_id DESC LIMIT 3";
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
            <div class="text-right"><h4><a href="allproduct.php" class="text-dark">See All Products</a></h4></div>
        <?php } ?>
    </div>
    <?php include 'layouts/footer.php' ?>
</body>