<?php 
    session_start();
    include "config.php";
    $page = isset($_GET['page']) ? mysqli_real_escape_string($link,$_GET['page']) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $sql = "SELECT subcategory.id as subcat_id,category.id as cat_id,category.name as cat_name,subcategory.name as subcat_name,subcategory.status as status FROM subcategory LEFT JOIN category ON subcategory.cat_id=category.id LIMIT $limit OFFSET $offset";
    $query = mysqli_query($link,$sql);
?>
<head>
    <?php include 'head.php' ?>
    <title>Category List</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <?php if($_SESSION['type'] == 1){ ?>
            <h1>Subcategory List</h1>
            <a href="addsubcategory.php" class="btn btn-success mt-3 mb-3">Add Subcategory</a>

            <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php } ?>
            <?php if(isset($_SESSION['error'])){ ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php } ?>

            <table class="table table-bordered table-striped table-hover">
            <thead class="bg-dark text-center text-white">
                <tr>
                    <th>Subcategory Id</th>
                    <th>Category</th>
                    <th>Subcategory Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if(mysqli_num_rows($query) == 0){ ?>
                    <tr><td colspan="5" class="text-center">No record found</td></tr>
                <?php }else{ ?>
                <?php 
                    $psql = "SELECT * FROM subcategory";
                    $pquery = mysqli_query($link,$psql);
                    $total_record = mysqli_num_rows($pquery);
                    $total_page = ceil($total_record / $limit);
                    while($subcategory = mysqli_fetch_assoc($query)){ 
                ?>
                <tr>
                    <td><?= $subcategory['subcat_id'] ?></td>
                    <td><?= $subcategory['cat_id'].' - '.$subcategory['cat_name'] ?></td>
                    <td><?= $subcategory['subcat_name'] ?></td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="status" class="subcategory_status" <?= $subcategory['status'] == 1 ? 'checked' : '' ?> value="<?= $subcategory['status'] ?>" data-sid=<?= $subcategory['subcat_id'] ?>>
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <a href="update.php?id=<?= $subcategory['subcat_id'] ?>" class="text-dark"><i class='fas fa-edit'></i></a>
                        <a href="delete.php?id=<?= $subcategory['subcat_id'] ?>" class="text-dark"><i class='fas fa-trash'></i></a>
                    </td>
                </tr>
                <?php }} ?>
            </tbody>
            </table>
            <br>
            <ul class="pagination">
                <li class="page-item <?= ($page > 1) ? '' : 'disabled' ?>"><a class="page-link" href="subcategorylist.php?page=<?= $page-1 ?>">Previous</a></li>
            <?php for($i=1;$i<=$total_page;$i++){ ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>"><a class="page-link" href="subcategorylist.php?page=<?= $i ?>"><?php echo $i ?></a></li>
            <?php } ?>
                <li class="page-item <?= ($total_page > $page) ? '' : 'disabled' ?>"><a class="page-link" href="subcategorylist.php?page=<?= $page+1 ?>">Next</a></li>
            </ul>
        <?php }else{ echo '<script>window.location.href="index.php"</script>'; } ?>
    </div>
    <?php include 'footer.php' ?>
</body>