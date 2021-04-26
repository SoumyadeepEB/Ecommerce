<?php 
    session_start();
    include "config.php";
    $page = isset($_GET['page']) ? mysqli_real_escape_string($link,$_GET['page']) : 1;
    $limit = 3;
    $offset = ($page - 1) * $limit;
    $sql = "SELECT orders.id as order_id,products,quantities,orders.prices as prices,date,time FROM orders WHERE status=0 ORDER BY orders.id DESC LIMIT $limit OFFSET $offset";
    $query = mysqli_query($link,$sql);
    $total_page = isset($total_page) ? $total_page : 0;
?>
<head>
    <?php include 'layouts/head.php' ?>
    <title>Stocks Management</title>
</head>
<body>
    <?php include 'layouts/header.php' ?>
    <div class="container">
        <img src="assets/images/loader.gif" id="loader" width="100px" style="position:absolute;top:50%;left:50%;z-index:1;display:none">
        <?php if($_SESSION['type'] == 1){ ?>
            <h1>Stocks Management</h1>

            <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php } ?>
            <?php if(isset($_SESSION['error'])){ ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php } ?>

            <table class="table table-bordered table-striped table-hover">
            <thead class="bg-dark text-center text-white">
                <tr>
                    <th class="align-middle">#</th>
                    <th class="align-middle">Order Id</th>
                    <th class="align-middle">Products</th>
                    <th class="align-middle">Unit Price</th>
                    <th class="align-middle">Quantity</th>
                    <th class="align-middle">Order Date</th>
                    <th class="align-middle">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if(mysqli_num_rows($query) == 0){ ?>
                    <tr><td colspan="10" class="text-center">No record found</td></tr>
                <?php }else{ ?>
                    <?php 
                        $psql = "SELECT * FROM orders WHERE status=0";
                        $pquery = mysqli_query($link,$psql);
                        $total_record = mysqli_num_rows($pquery);
                        $total_page = ceil($total_record / $limit);
                        $orders = mysqli_fetch_all($query,MYSQLI_ASSOC);
                        //echo '<pre>';print_r($orders);die;
                        $i = 1;
                        foreach($orders as $order){
                            $arr = json_decode($order['products']);
                    ?>
                    <tr id="<?= $order['order_id'] ?>">
                        <td class="align-middle"><?= $i++ ?></td>
                        <td class="align-middle"><?= '#ECOM'.strtotime($order['time'].' '.$order['date']).'-'.$order['order_id'] ?></td>
                        <td>
                            <ul style="list-style-type:none">
                                <?php 
                                    foreach($arr as $id){
                                        $isql = "SELECT name FROM product WHERE id='$id'";
                                        $iquery = mysqli_query($link,$isql);
                                        $product = mysqli_fetch_assoc($iquery);
                                ?>
                                    <li class="pb-3"><?= $product['name'] ?></li>
                                <?php } ?>
                            </ul>
                        </td>
                        <td>
                            <ul style="list-style-type:none">
                                <?php foreach(json_decode($order['prices']) as $price){ ?>
                                    <li class="pb-3">&#8377 <?= $price ?></li>
                                <?php } ?>
                            </ul>
                        </td>
                        <td>
                            <ul style="list-style-type:none">
                                <?php foreach(json_decode($order['quantities']) as $qty){ ?>
                                    <li class="pb-3"><?= $qty ?> pc</li>
                                <?php } ?>
                            </ul>
                        </td>
                        <td class="align-middle"><?= date('d',strtotime($order['date'])).'<sup>'.date('S',strtotime($order['date'])).'</sup>'.date(' F Y',strtotime($order['date'])).' '.$order['time'] ?></td>
                        <td class="align-middle"><button type="button" class="btn btn-info adjust-stock" data-id="<?= $order['order_id'] ?>" data-product="<?= $order['products'] ?>" data-qty=<?= $order['quantities'] ?> data-price=<?= $order['prices'] ?>>Adjust Stock</button></td>
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