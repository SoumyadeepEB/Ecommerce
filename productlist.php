<?php 
    session_start();
    include "config.php";
    $page = isset($_GET['page']) ? mysqli_real_escape_string($link,$_GET['page']) : 1;
    $limit = 3;
    $offset = ($page - 1) * $limit;
    $sql = "SELECT product.id as product_id,category.name as category,subcategory.name as subcategory,product.name as product_name,price,image,stocks,description,product.status as status FROM product INNER JOIN category ON product.cat_id = category.id INNER JOIN subcategory ON product.subcat_id = subcategory.id LIMIT $limit OFFSET $offset";
    $query = mysqli_query($link,$sql);
?>
<head>
    <?php include 'layouts/head.php' ?>
    <title>Product List</title>
</head>
<body>
    <?php include 'layouts/header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <?php if($_SESSION['type'] == 1){ ?>
            <h1>Product List</h1>
            <a href="addproduct.php" class="btn btn-success mt-3 mb-3">Add Product</a>

            <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php } ?>
            <?php if(isset($_SESSION['error'])){ ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php } ?>

            <table class="table table-bordered table-striped table-hover">
            <thead class="bg-dark text-center text-white">
                <tr>
                    <th class="align-middle">Product Id</th>
                    <th class="align-middle">Category</th>
                    <th class="align-middle">Subcategory</th>
                    <th class="align-middle">Product Name</th>
                    <th class="align-middle">Price (&#8377)</th>
                    <th class="align-middle">Image</th>
                    <th class="align-middle">Stocks (pc)</th>
                    <th class="align-middle">Description</th>
                    <th class="align-middle">Status</th>
                    <th class="align-middle">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if(mysqli_num_rows($query) == 0){ ?>
                    <tr><td colspan="10" class="text-center">No record found</td></tr>
                <?php }else{ ?>
                    <?php 
                        $psql = "SELECT * FROM product";
                        $pquery = mysqli_query($link,$psql);
                        $total_record = mysqli_num_rows($pquery);
                        $total_page = ceil($total_record / $limit);
                        while($product = mysqli_fetch_assoc($query)){ 
                    ?>
                    <tr>
                        <td><?= $product['product_id'] ?></td>
                        <td><?= $product['category'] ?></td>
                        <td><?= $product['subcategory'] ?></td>
                        <td><?= $product['product_name'] ?></td>
                        <td><?= number_format($product['price']) ?></td>
                        <td>
                            <img src="assets/images/products/<?= $product['image'] ?>" width="125" height="125">
                        </td>
                        <td><?= $product['stocks'] ?>
                        <?php 
                            $count = str_word_count($product['description']);
                            $desc = array_slice(explode(' ',$product['description']),0,10);
                            $desc = implode(' ',$desc);
                        ?>
                        <td><?= $desc ?> <?= $count >= 10 ? '<a href="#">...see more</a>' : '' ?></td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="status" class="product_status" <?= $product['status'] == 1 ? 'checked' : '' ?> value="<?= $product['status'] ?>" data-pid=<?= $product['product_id'] ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <a href="update.php?id=<?= $product['product_id'] ?>" class="text-dark"><i class='fas fa-edit'></i></a>
                            <a href="delete.php?id=<?= $product['product_id'] ?>" class="text-dark"><i class='fas fa-trash'></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
            </table>
            <br>
            <ul class="pagination">
                <li class="page-item <?= ($page > 1) ? '' : 'disabled' ?>"><a class="page-link" href="productlist.php?page=<?= $page-1 ?>">Previous</a></li>
            <?php for($i=1;$i<=$total_page;$i++){ ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>"><a class="page-link" href="productlist.php?page=<?= $i ?>"><?php echo $i ?></a></li>
            <?php } ?>
                <li class="page-item <?= ($total_page > $page) ? '' : 'disabled' ?>"><a class="page-link" href="productlist.php?page=<?= $page+1 ?>">Next</a></li>
            </ul>
        <?php }else{ echo '<script>window.location.href="index.php"</script>'; } ?>
    </div>
    <?php include 'layouts/footer.php' ?>
</body>