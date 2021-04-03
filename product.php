<?php 
    session_start();
    include "config.php";
    $quantities = [1,2,3,4,5,6,7,8,9,10];
    $category = mysqli_real_escape_string($link,$_GET['category']);
    $subcategory = mysqli_real_escape_string($link,$_GET['subcategory']);
    
    if(isset($_POST['search']) || $_SERVER['REQUEST_METHOD'] == 'POST'){

    }else{
        $sql = "SELECT product.id as product_id,category.name as category,subcategory.name as subcategory,product.name as product_name,price,image,product.status as status,stocks FROM product LEFT JOIN category ON product.cat_id = category.id LEFT JOIN subcategory ON product.subcat_id = subcategory.id WHERE product.status=1 AND category.name='$category' AND subcategory.name='$subcategory'";
    }
    $query = mysqli_query($link,$sql);
?>
<head>
    <?php include 'head.php' ?>
    <title>Products</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <?php if($_SESSION['type'] != 1){ ?>
        <?php if($_SERVER['REQUEST_METHOD'] == 'POST'){ echo '<h1 class="mt-4 mb-4">Search For '; foreach($_POST as $search){ if(!empty($search)){ ?>
            <span><?= '#'.$search.' ' ?></span>
        <?php }} echo '</h1>'; }else{ ?>
            <h2 class="mt-4 mb-4">Product By Category: <span class="text-primary"><?= isset($category) ? $category : '' ?></span> & Subcategory: <span class="text-secondary"><?= isset($subcategory) ? $subcategory : '' ?></span></h2>
        <?php } ?>
        <div class="row">
        <?php 
            $fsql = "SELECT * FROM category";
            $fquery = mysqli_query($link,$fsql);
        ?>
            <div class="col-md-12">
                <form action="" method="POST">
                    <div class="row mt-2 mb-4">
                        <div class="col-sm-3">
                            <strong>Product</strong>
                            <input type="text" class="form-control" placeholder="Product name" id="product" name="product" value="<?= isset($_POST['product']) ? $_POST['product'] : '' ?>">
                        </div>
                        <div class="col-sm-3">
                            <strong>Price Range Min</strong>
                            <input type="number" class="form-control" placeholder="Minimum price" id="price_min" name="price_min" min=0>
                        </div>
                        <div class="col-sm-3">
                            <strong>Price Range Max</strong>
                            <input type="number" class="form-control" placeholder="Maximum price" id="price_max" name="price_max" min=0>
                        </div>
                        <div class="col-sm-3 mt-4">
                            <button type="submit" class="btn btn-info btn-sm" name="search"><i class='fas fa-search'></i> Search</button>
                            <a href="products.php" class="btn btn-danger btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
        <?php if(mysqli_num_rows($query) > 0){ while($product = mysqli_fetch_assoc($query)){ ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <a href="viewproduct.php?pid=<?= $product['product_id'] ?>" class="product" title="<?= $product['product_name'] ?>">
                    <img class="card-img-top border-bottom" src="assets/images/products/<?= $product['image'] ?>" alt="<?= $product['image'] ?>" style="width:100%;height:200px">
                    </a>
                    <div class="card-body" style="width:100%;height:200px">
                        <h4 class="card-title"><?= $product['product_name'] ?></h4>
                        <h4 class="card-text">&#8377 <?= number_format($product['price']) ?></h4>
                        <p><span class="badge badge-primary" title="Category"><?= $product['category'] ?></span> <i class='fas fa-angle-double-right'></i> <span class="badge badge-secondary" title="Subcategory"><?= $product['subcategory'] ?></span></p>
                        <form action="cart.php?action=add&pid=<?= $product['product_id'] ?>" method="POST">
                        <?php if($product['stocks'] > 0){ ?>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <select name="quantity" title="Quantity" class="form-control">
                                        <?php foreach($quantities as $quantitiy){ ?>
                                            <option value="<?= $quantitiy ?>"><?= $quantitiy ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-8"><button type="submit" name="addtocart" class="btn btn-success" title="Add to Cart"><i class='fas fa-shopping-cart'></i> Add to Cart</button></div>
                            </div>
                            <?php }else{ ?>
                                <div class="row text-center">
                                    <div class="col-md-12">
                                        <p class="bg-danger text-white border pt-2 pb-2 border-danger">
                                            <strong>Out of Stock</strong>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php }}else{ ?>
                <div class="col-md-12"><img src="assets/images/search-not-found.jpg" alt="search-not-found" width="100%"></div>
            <?php } ?>
        </div>
        <?php }else{ echo '<script>window.location.href="index.php"</script>'; } ?>
    </div>
    <?php include 'footer.php' ?>
</body>