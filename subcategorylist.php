<?php 
    session_start();
    include "config.php";
    $page = isset($_GET['page']) ? mysqli_real_escape_string($link,$_GET['page']) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $fsql = "SELECT subcategory.id as subcat_id,category.id as cat_id,category.name as cat_name,subcategory.name as subcat_name,subcategory.status as status FROM subcategory LEFT JOIN category ON subcategory.cat_id=category.id LIMIT $limit OFFSET $offset";
    $fquery = mysqli_query($link,$fsql);

    if(isset($_POST['subcategory_add'])){
        $category = $_POST['category'];
        $subcategory = $_POST['subcategory'];
        $check_sql = "SELECT * FROM subcategory WHERE name='$subcategory'";
        $check_query = mysqli_query($link,$check_sql);

        if(mysqli_num_rows($check_query) == 0){
            $sql = "INSERT INTO subcategory (cat_id,name,status) VALUES ('$category','$subcategory',0)";
            $query = mysqli_query($link,$sql);
            if($query){
                $_SESSION['success'] = 'One subcategory added successfully';
                header('location:subcategorylist.php');
            }else{
                $_SESSION['error'] = 'Subcategory not added';
                header('location:subcategorylist.php');
            }
        }else{
            $_SESSION['subcat_error'] = 'Subcategory name already exist';
        }
    }
?>
<head>
    <?php include 'head.php' ?>
    <title>Subcategory List</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <?php if($_SESSION['type'] == 1){ ?>
            <h1>Subcategory List</h1>
            <button type="button" class="btn btn-success mt-3 mb-3" data-toggle="modal" data-target="#myModal">Add Subcategory</button>

            <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php } ?>
            <?php if(isset($_SESSION['error'])){ ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php } ?>

            <div class="modal animate-top" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Subcategory</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                            <div class="row">
                                <div class="col-sm-12 mb-2">
                                    <strong>Category Name</strong>
                                    <select id="category" name="category" class="form-control" required>
                                        <option value="">--Select Category--</option>
                                        <?php 
                                            $csql = "SELECT * FROM category WHERE status=1";
                                            $cquery = mysqli_query($link,$csql);
                                            while($category = mysqli_fetch_assoc($cquery)){ 
                                        ?>
                                            <option value="<?= $category['id'] ?>" <?= (isset($_POST['category']) && $_POST['category'] == $category['id']) ? 'selected' : '' ?>><?= $category['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <strong>Subcategory Name</strong>
                                    <input type="text" name="subcategory" id="subcategory" class="form-control" placeholder="Subcategory name" value="<?= isset($_POST['subcategory']) ? $_POST['subcategory'] : '' ?>" required>
                                    <?php if(isset($_SESSION['subcat_error'])){ ?><span class="text-danger"><?= $_SESSION['subcat_error']; unset($_SESSION['subcat_error']); ?></span><?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="subcategory_add" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

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
                <?php if(mysqli_num_rows($fquery) == 0){ ?>
                    <tr><td colspan="5" class="text-center">No record found</td></tr>
                <?php }else{ ?>
                <?php 
                    $psql = "SELECT * FROM subcategory";
                    $pquery = mysqli_query($link,$psql);
                    $total_record = mysqli_num_rows($pquery);
                    $total_page = ceil($total_record / $limit);
                    while($subcategory = mysqli_fetch_assoc($fquery)){ 
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