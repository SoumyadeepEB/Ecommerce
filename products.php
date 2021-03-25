<?php 
    session_start();
    include "config.php";
    $quantities = [1,2,3,4,5,6,7,8,9,10];
    $sql = "SELECT product.id as product_id,category.name as category,subcategory.name as subcategory,product.name as product_name,price,image,product.status as status FROM product LEFT JOIN category ON product.cat_id = category.id LEFT JOIN subcategory ON product.subcat_id = subcategory.id WHERE product.status=1";
    $query = mysqli_query($link,$sql);
?>
<head>
    <?php include 'head.php' ?>
    <title>All Products</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <h1 class="mt-4 mb-4">All Products</h1>

        <div class="row">
        <?php 
            $fsql = "SELECT * FROM category";
            $fquery = mysqli_query($link,$fsql);
        ?>
            <div class="col-md-12">
                <form action="" method="POST">
                    <div class="row mt-2 mb-4">
                        <div class="col-sm-3">
                            <strong>Category</strong>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">--Select Category--</option>
                                <?php while($category = mysqli_fetch_assoc($fquery)){ ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <strong>Subcategory</strong>
                            <select id="subcategory" name="subcategory" class="form-control" required>
                                <option value="">--Select Subcategory--</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <strong>Price Range Min</strong>
                            <input type="text" class="form-control" placeholder="Price" id="price" name="price" requied>
                        </div>
                        <div class="col-sm-3">
                            <strong>Price Range Max</strong>
                            <input type="text" class="form-control" placeholder="Price" id="price" name="price" requied>
                        </div>
                    </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-info"><i class='fas fa-search'></i> Search</button>
                            <a href="products.php" class="btn btn-danger">Reset</a>
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
                        </form>
                    </div>
                </div>
            </div>
            <?php }}else{echo '<h5>No Product Available</h5>';} ?>
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>