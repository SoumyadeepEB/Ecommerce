<?php 
    session_start();
    include "config.php";
?>
<head>
    <?php include 'head.php' ?>
    <title>404 | Page Not Found</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container">
        <div class="m-5 p-5">
            <img src="assets/images/404.png" width="100%">
        </div>
        <div class="text-center"><h4><a href="index.php" class="text-dark">Back to Home</a></h4></div>
    </div>
    <?php include 'footer.php' ?>
</body>