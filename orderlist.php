<?php 
    session_start();
    include "config.php";
    if(empty($_SESSION['id'])){
        header('location:login.php');
    }
    $psql = "SELECT id,name FROM product";
    $pquery = mysqli_query($link,$psql);
    $product_list = [];
    while($result = mysqli_fetch_assoc($pquery)){
        $product_list[$result['id']]['name'] = $result['name'];
    }

    if($_SESSION['type'] == 1){
        $sql = "SELECT orders.id as order_id,users.name as user_name,products,quantities,orders.prices as prices,orders.address as order_address,payment_method,invoice,status,timestamp FROM orders LEFT JOIN users ON orders.user_id=users.id"; 
    }else{
        $user_id = $_SESSION['id'];
        $sql = "SELECT orders.id as order_id,users.name as user_name,products,quantities,orders.prices as prices,orders.address as order_address,payment_method,invoice,status,timestamp FROM orders LEFT JOIN users ON orders.user_id=users.id WHERE user_id='$user_id'"; 
    }
    $query = mysqli_query($link,$sql);
?>
<head>
    <?php include 'layouts/head.php' ?>
    <title>Orders</title>
</head>
<body>
    <?php include 'layouts/header.php' ?>
    <div class="container">
        <h1>Order List</h1>
        <?php if(mysqli_num_rows($query) > 0){ $orders = mysqli_fetch_all($query,MYSQLI_ASSOC); $slno = 1; ?>
        <table class="table table-bordered">
            <thead class="bg-dark text-center text-white">
                <tr>
                    <th class="align-middle">#</th>
                    <th class="align-middle">Order Id</th>
                    <?= $_SESSION['type'] == 1 ? '<th class="align-middle">Customer Name</th>' : '' ?>
                    <th class="align-middle">Products</th>
                    <th class="align-middle">Cost</th>
                    <?= $_SESSION['type'] == 1 ? '<th class="align-middle">Address</th>' : '' ?>
                    <th class="align-middle">Order Status</th>
                    <?= $_SESSION['type'] == 0 ? '<th class="align-middle">Order Invoice</th>' : '' ?>
                    <th class="align-middle">Order Date</th>
                </tr>
            </thead>
            <?php  ?>
            <tbody>
                <?php foreach($orders as $order){ ?>
                <tr class="oreder-item">
                    <td class="align-middle text-center"><?= $slno ?></td>
                    <td class="align-middle text-center"><strong><?= '#ECOM'.strtotime($order['timestamp']).'-'.$order['order_id'] ?></strong></td>
                    <?= $_SESSION['type'] == 1 ? '<td class="align-middle text-center">'.$order['user_name'].'</td>' : '' ?>
                    <?php 
                        $arr = json_decode($order['products']);
                        $product_array = [];
                        foreach($product_list as $id=>$item){
                            if(in_array($id,$arr)){
                                $product_array[$id] = $item;
                            }
                        }
                    ?>
                    <td>
                        <ul style="list-style-type:none">
                            <?php foreach($product_array as $id=>$product){ ?>
                                <li class="pb-2"><a href="#" data-toggle="popover" title="<?= $product['name'] ?>" data-pid="<?= $id ?>" data-num="<?= $slno ?>" id="<?= $slno.'-'.$id ?>" class="product"><?= $product['name'] ?></li>
                            <?php } ?>
                        </ul>
                    </td>
                    <td>
                        <ul style="list-style-type:none">
                            <?php $total = 0; foreach(json_decode($order['prices']) as $price){ $total += $price; ?>
                                <li class="pb-2">&#8377 <?= $price ?></li>
                            <?php } ?>
                            <li><strong>Total: &#8377 <?= $total ?></strong></li>
                        </ul>
                    </td>
                    <?= $_SESSION['type'] == 1 ? '<td class="align-middle text-center">'.$order['order_address'].'</td>' : '' ?>
                    <?php
                        switch($order['status']){
                            case 1:
                                $status = 'Received';
                                $class = 'badge badge-secondary';
                                break;
                            case 2:
                                $status = 'Packed';
                                $class = 'badge badge-primary';
                                break;
                            case 3:
                                $status = 'Shipped';
                                $class = 'badge badge-info';
                                break;
                            case 4:
                                $status = 'Delivered';
                                $class = 'badge badge-success';
                                break;
                            default:
                                $status = 'Cancelled';
                                $class = 'badge badge-danger';
                        }
                    ?>
                    <td class="align-middle text-center"><span class="<?= isset($class) ? $class : '' ?>"><?= isset($status) ? $status : '' ?></span></td>
                    <?= $_SESSION['type'] == 0 ? '<td class="align-middle text-center"><a href="assets/invoice/'.$order['invoice'].'" class="text-dark"><i class="fas fa-file-download"></i></a></td>' : '' ?>
                    <?php 
                        $date = date('d',strtotime($order['timestamp']));
                        $str = '';
                        if($date < 10){
                            if($date == 1)
                                $str = 'st';
                            else if($date == 2)
                                $str = 'nd';
                            else if($date == 3)
                                $str = 'rd';
                            else
                                $str = 'th';
                        }else{
                            if($date[1] == 1)
                                $str = 'st';
                            else if($date[1] == 2)
                                $str = 'nd';
                            else if($date[1] == 3)
                                $str = 'rd';
                            else
                                $str = 'th';
                        }
                    ?>
                    <td class="align-middle text-center"><?= date('d',strtotime($order['timestamp'])).'<sup>'.$str.'</sup>'.date(' F Y, h:m;s a',strtotime($order['timestamp'])) ?></td>
                </tr>
                <?php $slno++; } ?>
            </tbody>
        </table>
        <?php }else{ echo '<h4>No order found</h4>'; } ?>
    </div>
    <?php include 'layouts/footer.php' ?>
</body>