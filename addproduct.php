<?php 
    session_start();
    include "config.php";
    if(empty($_SESSION['id'])){
        header('location:login.php');
    }
    if($_SESSION['type'] == 0 || $_SESSION['type'] == 'guest'){
        header('location:index.php');
    }
    $fsql = "SELECT * FROM category WHERE status=1";
    $fquery = mysqli_query($link,$fsql);

    if(isset($_POST['product_add'])){
        $category = $_POST['category'];
        $subcategory = $_POST['subcategory'];
        $product = $_POST['product'];
        $price = $_POST['price'];
        $stocks = $_POST['stocks'];
        $image = $_FILES['image'];
        $description = $_POST['description'];

        if($image['type'] == 'image/jpg' || $image['type'] == 'image/jpeg' || $image['type'] == 'image/png'){
            $newimg = 'product_img_'.time().'.jpg';
            if(move_uploaded_file($image['tmp_name'],'assets/images/products/'.$newimg)){
                $sql = "INSERT INTO product (cat_id,subcat_id,name,price,image,stocks,description) VALUES ('$category','$subcategory','$product','$price','$newimg','$stocks','$description')";
                $query = mysqli_query($link,$sql);
                $_SESSION['success'] = 'Product added successfully';
                header('location:productlist.php');
            }else{
                $_SESSION['error'] = 'Error, Product not added';
            }
        }else{
            $_SESSION['error'] = 'Image error';
        }
    }
?>
<head>
    <?php include 'head.php' ?>
    <title>Add New Product</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <div class="mt-4 mb-4"><h1 class="text-center">Add New Product</h1></div>

        <?php if(isset($_SESSION['error'])){ ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php } ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6">
                    <strong>Category</strong>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">--Select Category--</option>
                        <?php while($category = mysqli_fetch_assoc($fquery)){ ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <strong>Subcategory</strong>
                    <select id="subcategory" name="subcategory" class="form-control" required>
                        <option value="">--Select Subcategory--</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mt-3">
                    <strong>Product Name</strong>
                    <input type="text" class="form-control" placeholder="Product name" id="product" name="product" requied>
                </div>
                <div class="col-sm-6 mt-3">
                    <strong>Price</strong>
                    <input type="text" class="form-control" placeholder="Price" id="price" name="price" requied>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mt-3">
                    <strong>Stocks</strong>
                    <input type="text" class="form-control" placeholder="Stocks" id="stocks" name="stocks" requied>
                </div>
                <div class="col-sm-6 mt-3">
                    <strong>Image</strong>
                    <input type="file" class="form-control" id="image" name="image" requied>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 mt-3">
                    <strong>Description</strong>
                    <textarea class="form-control" id="description" name="description" placeholder="Write product description here" style="height: 286px;" requied></textarea>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-info" name="product_add">Submit</button>
                <a href="productlist.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
    <?php include 'footer.php' ?>
</body>