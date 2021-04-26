<?php 
    session_start();
    include "config.php";
    require_once __DIR__ . '/vendor/autoload.php';
    use Mpdf\Mpdf;

    if(empty($_SESSION['id'])){
        header('location:login.php');
    }
    $user_id = $_SESSION['id'];

    if(isset($_GET['orderid']) && !empty($_GET['orderid']) && $user_id == $_SESSION['id']){
        $order_id = mysqli_escape_string($link,$_GET['orderid']);
        $sql = "SELECT orders.id as order_id,users.name as user_name,products,quantities,orders.prices as prices,orders.address as order_address,payment_method,date,time,orders.status as order_status FROM orders LEFT JOIN users ON orders.user_id=users.id WHERE orders.id='$order_id' AND user_id='$user_id'";
        $query = mysqli_query($link,$sql);
        $result = mysqli_fetch_assoc($query);
        $arr = json_decode($result['products']);
        $i = 1; $total = 0; 
        $quantities = json_decode($result['quantities']);
        $prices = json_decode($result['prices']); 
        foreach($prices as $key=>$price){ 
            $total += $price * $quantities[$key]; 
        }
        $key = 0;
    }else{
        header('location:index.php');
    }
    if($result['order_status'] != 0 && $result['order_status'] != 5){
        $mpdf = new Mpdf();
        $html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.'#ECOM'.strtotime($result['time'].' '.$result['date']).'-'.$result["order_id"].'</title>
        </head>
        <body>
        <div>
            <h1 style="text-align:center;"><strong>Ecommerce Order Invoice</strong></h1>
            <hr>
            <div style="background-color:lightgray;border:2px dotted black;">
                <p style="padding:5px 2px 10px 10px">
                    <span><strong>Customer Name: </strong>'.$result['user_name'].'</span><br>
                    <span><strong>Shipping Address: </strong>'.$result['order_address'].'</span><br>
                    <span><strong>Order Date: </strong>'.date('dS F Y',strtotime($result['date'])).'</span><br>
                    <span><strong>Payment Type: </strong>'.strtoupper($result['payment_method']).'</span><br>
                    <span><strong>Invoice No: </strong> <u style="color:blue">'.'#ECOM'.strtotime($result['time'].' '.$result['date']).'-'.$result["order_id"].'</u></span><br>
                </p>
            </div>
            <br>
            <table border="1" cellspacing="0" cellpadding="10" width="100%" style="text-align:center">
                <thead>
                    <tr style="background-color:black;font-weight:600">
                        <th style="color:white;padding:10px;font-size:15px">#</th>
                        <th style="color:white;padding:10px;font-size:15px">Product Name</th>
                        <th style="color:white;padding:10px;font-size:15px">Unit Price (INR)</th>
                        <th style="color:white;padding:10px;font-size:15px">Qty</th>
                        <th style="color:white;padding:10px;font-size:15px">Amount (INR)</th>
                    </tr>
                </thead>
                <tbody>';

                foreach($arr as $id){
                    $psql = "SELECT name FROM product WHERE id='$id'";
                    $pquery = mysqli_query($link,$psql);
                    $data = mysqli_fetch_assoc($pquery);
                    $html .= '
                    <tr>
                        <td>'.$i++.'</td>
                        <td>'.$data['name'].'</td>
                        <td>'.number_format($prices[$key]).'</td>
                        <td>'.$quantities[$key].'</td>
                        <td>'.($prices[$key] * $quantities[$key]).'</td>
                    </tr>
                    ';
                    $key++;
                }
                $html .= '
                <tr>
                    <td colspan="5" style="text-align:right;font-size:18px"><strong>Total: </strong>Rs. '.number_format($total).'</td>
                </tr>';    
                $html .= '</tbody>
            </table>
            <br><br>
            <p style="text-align:right">
                <img src="assets/images/signature.png" width="130" style="text-align:right"><br>
                <strong>Authorize Signature</strong>
            </p>
        ';
        $mpdf->WriteHTML($html);
        $mpdf->Output('order_invoice_'.'#ECOM'.strtotime($result['time'].' '.$result['date']).'-'.$result["order_id"].'.pdf','D');
    }else{
        $_SESSION['error'] = 'Invoice is not allow to be downloaded';
        header('location:orderlist.php');
    }
?>