<?php 
    if(!isset($_SESSION['type']) || $_SESSION['type'] != 1 && $_SESSION['type'] != 0){
        $_SESSION['type'] = 'guest';
    }
    $msql = "SELECT category.id as cat_id,category.name as cat_name,subcategory.id as subcat_id,subcategory.name as subcat_name FROM subcategory RIGHT JOIN category ON subcategory.cat_id=category.id WHERE category.status=1";
    $mquery = mysqli_query($link,$msql);
    $menus = [];
    while($result = mysqli_fetch_assoc($mquery)){
        $menus[$result['cat_id']][$result['cat_name']][$result['subcat_id']] = $result['subcat_name'];
    }
?>
<nav class="navbar navbar-expand-sm bg-dark">
    <ul class="navbar-nav">   
        <strong class="navbar-brand pr-3 border-right"><a class="text-white" href="index.php">ECOMMERCE</a></strong>
        <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 0 || $_SESSION['type'] == 'guest'){ foreach($menus as $key1=>$cats){ foreach($cats as $cat=>$subcats){ ?>
            <li class="nav-item dropdown bg-secondary">
                <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown"><?= $cat ?></a>
                <div class="dropdown-menu">
                    <?php foreach($subcats as $key2=>$subcat){ ?>
                        <a class="dropdown-item" href="product.php?category=<?= $cat ?>&subcategory=<?= $subcat ?>"><?= $subcat ?></a>
                    <?php }} ?>
                </div>
            </li>
        <?php }if(isset($_SESSION['id'])){ ?>
                <a class="nav-link text-white" href="cart.php"><i class='fab fa-opencart'></i> Cart<sup class="badge <?= (isset($_SESSION['cart_item']) && count($_SESSION['cart_item']) > 0) ? 'badge-primary' : 'badge-secondary' ?>"><?= isset($_SESSION['cart_item']) ? count($_SESSION['cart_item']) : 0; ?></sup></a>
        <?php }} else{ ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown">Settings</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="categorylist.php">Category</a>
                    <a class="dropdown-item" href="subcategorylist.php">Subcategory</a>
                    <a class="dropdown-item" href="productlist.php">Product</a>
                </div>
            </li>
        <?php } ?>
        <?php if($_SESSION['type'] != 'guest'){ ?>
            <a class="nav-link text-white" href="vieworder.php">Orders</a>
        <?php } ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown"><?= isset($_SESSION['name']) ? 'Hi '.strtoupper(explode(' ',$_SESSION['name'])[0]) : 'Hi Guest' ?></a> 
            <div class="dropdown-menu">
                <?php if(isset($_SESSION['id'])){ ?>
                <a class="dropdown-item" href="#">Edit profile</a>
                <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                <?php }else{ ?>
                    <a class="dropdown-item" href="signup.php">Register Account</a>
                    <a class="dropdown-item text-success" href="login.php">Login</a>
                <?php } ?>
            </div>
        </li>            
    </ul>
</nav>