<?php 
    session_start();
    include "config.php";
    if(empty($_SESSION['id'])){
        header('location:login.php');
    }

    function weekOfYear($date) {
        $weekOfYear = intval(date("W", $date));
        if (date('n', $date) == "1" && $weekOfYear > 51) {
            $weekOfYear = 0;    
        }
        return $weekOfYear;
    }

    function weekOfMonth($date){
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
    }
    
    $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
?>
<head>
    <?php include 'layouts/head.php' ?>
    <title>Summary Report</title>
</head>
<body>
    <?php include 'layouts/header.php' ?>
    <div class="container">
    <?php if($_SESSION['type'] == 1){ ?>
        <h1>Summary Report</h1>
        <div class="mt-4">
            <div class="border border-default p-3 mb-3">
                <div class="row">
                    <div class="col-md-6">
                    <?php 
                        $today = date('Y-m-d');
                        $today_income = 0;
                        $sql = "SELECT * FROM orders WHERE date='$today'";
                        $query = mysqli_query($link,$sql);
                        if(mysqli_num_rows($query) > 0){
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            foreach($results as $result){
                                $prices = json_decode($result['prices']);
                                $quantities = json_decode($result['quantities']);
                                foreach($prices as $key=>$price){
                                    $today_income += $price * $quantities[$key];
                                }
                            }
                        }
                    ?>
                        <h3>Today Income</h3>
                        <strong>(<?= date('d F Y, l') ?>)</strong>
                        <table class="table table-bordered table-striped table-hover text-uppercase">
                            <thead class="bg-dark text-center text-white">
                                <tr><th>Total</th></tr>
                            </thead>
                            <tbody class="text-center">
                                <tr><td id="today">&#8377 <?= number_format($today_income) ?><span class="ml-1"></span></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                    <?php 
                        $yesterday = date('Y-m-d',strtotime('-1 Days'));
                        $yesterday_income = 0;
                        $sql = "SELECT * FROM orders WHERE date='$yesterday'";
                        $query = mysqli_query($link,$sql);
                        if(mysqli_num_rows($query) > 0){
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            foreach($results as $result){
                                $prices = json_decode($result['prices']);
                                $quantities = json_decode($result['quantities']);
                                foreach($prices as $key=>$price){
                                    $yesterday_income += $price * $quantities[$key];
                                }
                            }
                        }
                    ?>
                        <h3>Yesterday Income</h3>
                        <strong>(<?= date('d F Y, l',strtotime('-1 Days')) ?>)</strong>
                        <table class="table table-bordered table-striped table-hover text-uppercase">
                            <thead class="bg-dark text-center text-white">
                                <tr><th>Total</th></tr>
                            </thead>
                            <tbody class="text-center">
                                <tr><td id="yesterday">&#8377 <?= number_format($yesterday_income) ?><span class="ml-1"></span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="chartContainer1" style="height:300px; width:100%;"></div>
            </div>

            <div class="border border-default p-3 mb-3">
                <div class="row">
                    <div class="col-md-12">
                    <?php 
                        $monday = date('Y-m-d',strtotime('monday this week'));
                        $sunday = date('Y-m-d',strtotime( 'sunday this week'));
                        $sql = "SELECT * FROM orders WHERE date BETWEEN '$monday' AND '$sunday'";
                        $query = mysqli_query($link,$sql);
                        $total_weekly_income = 0; $avg_weekly = 0;
                        $day1 = 0; $day2 = 0; $day3 = 0; $day4 = 0; $day5 = 0; $day6 = 0; $day7 = 0;
                        if(mysqli_num_rows($query) > 0){
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            foreach($results as $result){
                                $day = date('w',strtotime($result['date']));
                                $prices = json_decode($result['prices']);
                                $quantities = json_decode($result['quantities']);
                                foreach($prices as $key=>$price){
                                    switch($day){
                                        case 1:
                                            $day1 += $price * $quantities[$key];
                                            break;
                                        case 2:
                                            $day2 += $price * $quantities[$key];
                                            break;
                                        case 3:
                                            $day3 += $price * $quantities[$key];
                                            break;
                                        case 4:
                                            $day4 += $price * $quantities[$key];
                                            break;
                                        case 5:
                                            $day5 += $price * $quantities[$key];
                                            break;
                                        case 6:
                                            $day6 += $price * $quantities[$key];
                                            break;
                                        case 7:
                                            $day7 += $price * $quantities[$key];
                                            break;
                                    }
                                }
                            }
                            $total_weekly_income = $day1 + $day2 + $day3 + $day4 + $day5 + $day6 + $day7;
                            $avg_weekly = $total_weekly_income / 7;
                        }
                    ?>
                        <h3>Weekly Income</h3>
                        <strong>(<?= date('d F Y',strtotime($monday)).' - '.date('d F Y',strtotime($sunday)) ?>)</strong>
                        <table class="table table-bordered table-striped table-hover text-uppercase" id="weekly-report">
                            <thead class="bg-dark text-center text-white">
                                <tr>
                                    <?php 
                                        foreach($days as $day){
                                            echo '<th>'.$day.'</th>';
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr id="weekly-data">
                                    <td id="mon">&#8377 <?= number_format($day1) ?></td>
                                    <td id="tue">&#8377 <?= number_format($day2) ?></td>
                                    <td id="wed">&#8377 <?= number_format($day3) ?></td>
                                    <td id="thu">&#8377 <?= number_format($day4) ?></td>
                                    <td id="fri">&#8377 <?= number_format($day5) ?></td>
                                    <td id="sat">&#8377 <?= number_format($day6) ?></td>
                                    <td id="sun">&#8377 <?= number_format($day7) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right"><strong>Total: &#8377 <?= number_format($total_weekly_income) ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right"><strong>Average: &#8377 <?= number_format($avg_weekly,2) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="chartContainer2" style="height:300px; width:100%;"></div>
            </div>

            <div class="border border-default p-3 mb-3">
                <div class="row">
                    <div class="col-md-12">
                    <?php 
                        $firstday = date('Y-m-01');
                        $lastday = date('Y-m-t');
                        $weeks = weekOfMonth(strtotime($lastday));
                        $day = strtotime($firstday);
                        $week1 = 0; $week2 = 0; $week3 = 0; $week4 = 0; $week5 = 0;
                        while($day <= strtotime($lastday)){
                            $date = date('Y-m-d',$day);
                            $sql = "SELECT * FROM orders WHERE date='$date'";
                            $query = mysqli_query($link,$sql);
                            if(mysqli_num_rows($query) > 0){
                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                foreach($results as $result){
                                    $week_num = weekOfMonth(strtotime($date));
                                    $prices = json_decode($result['prices']);
                                    $quantities = json_decode($result['quantities']);
                                    foreach($prices as $key=>$price){
                                        switch($week_num){
                                            case 1:
                                                $week1 += $price * $quantities[$key];
                                                break;
                                            case 2:
                                                $week2 += $price * $quantities[$key];
                                                break;
                                            case 3:
                                                $week3 += $price * $quantities[$key];
                                                break;
                                            case 4:
                                                $week4 += $price * $quantities[$key];
                                                break;
                                            case 5:
                                                $week5 += $price * $quantities[$key];
                                                break;
                                        }
                                    }
                                }
                            }
                            $day = strtotime("+1 Days",$day);
                        }
                        if($weeks == 5){
                            $total_monthly_income = $week1 + $week2 + $week3 + $week4 + $week5;
                            $avg_monthly = $total_monthly_income / 5;
                        }else{
                            $total_monthly_income = $week1 + $week2 + $week3 + $week4;
                            $avg_monthly = $total_monthly_income / 4;
                        }
                    ?>
                        <h3>Monthly Income</h3>
                        <strong>(<?= date('d F Y',strtotime($firstday)).' - '.date('d F Y',strtotime($lastday)) ?>)</strong>
                        <table class="table table-bordered table-striped table-hover text-uppercase" id="monthly-report">
                            <thead class="bg-dark text-center text-white">
                                <tr>
                                    <?php 
                                        for($i=1; $i<=$weeks; $i++){
                                            echo '<th>Week'.$i.'</th>';
                                        } 
                                    ?>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr id="monthly-data">
                                    <td id="week1">&#8377 <?= number_format($week1) ?></td>
                                    <td id="week2">&#8377 <?= number_format($week2) ?></td>
                                    <td id="week3">&#8377 <?= number_format($week3) ?></td>
                                    <td id="week4">&#8377 <?= number_format($week4) ?></td>
                                    <?= $weeks == 5 ? '<td id="week5">&#8377 '.number_format($week5).'</td>' : '' ?>
                                </tr>
                                <tr>
                                    <td colspan="<?= $weeks ?>" class="text-right"><strong>Total: &#8377 <?= number_format($total_monthly_income) ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="<?= $weeks ?>" class="text-right"><strong>Average: &#8377 <?= number_format($avg_monthly,2) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="chartContainer3" style="height:300px; width:100%;"></div>
            </div>

            <div class="border border-default p-3">
                <div class="row">
                    <div class="col-md-12">
                    <?php 
                        $firstday_yr = date('Y-01-01');
                        $lastday_yr = date('Y-12-31');
                        $month1 = 0; $month2 = 0; $month3 = 0; $month4 = 0; $month5 = 0; $month6 = 0; $month7 = 0; $month8 = 0; $month9 = 0; $month10 = 0; $month11 = 0; $month12 = 0;
                        $total_yearly_income = 0;
                        $avg_yearly = 0;
                        $month = strtotime($firstday_yr);
                        while($month <= strtotime($lastday_yr)){
                            $firstday = date('Y-m-d',$month);
                            $lastday = date('Y-m-t',$month);
                            $sql = "SELECT * FROM orders WHERE date BETWEEN '$firstday' AND '$lastday'";
                            $query = mysqli_query($link,$sql);
                            if(mysqli_num_rows($query) > 0){
                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                foreach($results as $result){
                                    $month_code = date('M',$month);
                                    $prices = json_decode($result['prices']);
                                    $quantities = json_decode($result['quantities']);
                                    foreach($prices as $key=>$price){
                                        switch($month_code){
                                            case 'Jan':
                                                $month1 += $price * $quantities[$key];
                                                break;
                                            case 'Feb':
                                                $month2 += $price * $quantities[$key];
                                                break;
                                            case 'Mar':
                                                $month3 += $price * $quantities[$key];
                                                break;
                                            case 'Apr':
                                                $month4 += $price * $quantities[$key];
                                                break;
                                            case 'May':
                                                $month5 += $price * $quantities[$key];
                                                break;
                                            case 'Jun':
                                                $month6 += $price * $quantities[$key];
                                                break;
                                            case 'Jul':
                                                $month7 += $price * $quantities[$key];
                                                break;
                                            case 'Aug':
                                                $month8 += $price * $quantities[$key];
                                                break;
                                            case 'Sept':
                                                $month9 += $price * $quantities[$key];
                                                break;
                                            case 'Oct':
                                                $month10 += $price * $quantities[$key];
                                                break;
                                            case 'Nov':
                                                $month11 += $price * $quantities[$key];
                                                break;
                                            case 'Dec':
                                                $month12 += $price * $quantities[$key];
                                                break;
                                        }
                                    }
                                }
                            }
                            $month = strtotime("+1 Months",$month);
                        }
                        $total_yearly_income = $month1 + $month2 + $month3 + $month4 + $month5 + $month6 + $month7 + $month8 + $month9 + $month10 + $month11 + $month12;
                        $avg_yearly = $total_yearly_income / 12;
                    ?>
                        <h3>Yearly Income</h3>
                        <strong>(<?= date('d F Y',strtotime($firstday_yr)).' - '.date('d F Y',strtotime($lastday_yr)) ?>)</strong>
                        <table class="table table-bordered table-striped table-hover text-uppercase" id="yearly-report">
                            <thead class="bg-dark text-center text-white">
                                <tr>
                                <?php 
                                    foreach($months as $month){
                                        echo '<th>'.$month.'</th>';
                                    }
                                ?>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr class="yearly-data">
                                    <td id="jan">&#8377 <?= number_format($month1) ?></td>
                                    <td id="feb">&#8377 <?= number_format($month2) ?></td>
                                    <td id="mar">&#8377 <?= number_format($month3) ?></td>
                                    <td id="apr">&#8377 <?= number_format($month4) ?></td>
                                    <td id="may">&#8377 <?= number_format($month5) ?></td>
                                    <td id="jun">&#8377 <?= number_format($month6) ?></td>
                                    <td id="jul">&#8377 <?= number_format($month7) ?></td>
                                    <td id="aug">&#8377 <?= number_format($month8) ?></td>
                                    <td id="sept">&#8377 <?= number_format($month9) ?></td>
                                    <td id="oct">&#8377 <?= number_format($month10) ?></td>
                                    <td id="nov">&#8377 <?= number_format($month11) ?></td>
                                    <td id="dec">&#8377 <?= number_format($month12) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="12" class="text-right"><strong>Total: &#8377 <?= number_format($total_yearly_income) ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="12" class="text-right"><strong>Average: &#8377 <?= number_format($avg_yearly,2) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="chartContainer4" style="height:300px; width:100%;"></div>
            </div>
        </div>
    <?php }else{ echo '<script>window.location.href="index.php"</script>'; } ?>
    </div>
    <?php include 'layouts/footer.php' ?>
</body>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery-ui.1.11.2.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>