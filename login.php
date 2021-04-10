<?php 
    session_start();
    include "config.php";
    if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
        header('location:index.php');
    }
    
    if(isset($_POST['login'])){
        $userid = $_POST['userid'];
        $password = md5($_POST['password']);
        $check = preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $userid) ? 'email' : 'username';
    
        if($check == 'email'){
            $sql = "SELECT * FROM users WHERE email='$userid' AND password='$password'";
            $query = mysqli_query($link,$sql);
            if(mysqli_num_rows($query) == 1){
                if(isset($_POST['remember_me'])){
                    setcookie('userid', $userid, time()+24*60*60);
                    setcookie('password', $_POST['password'], time()+24*60*60);
                }else{
                    setcookie('userid','');
                    setcookie('password','');
                }
                $data = mysqli_fetch_assoc($query);
                $_SESSION['id'] = $data['id'];
                $_SESSION['name'] = $data['name'];
                $_SESSION['type'] = $data['type'];
                header('location:index.php');
            }else{
                $_SESSION['error'] = 'Email or Password maybe wrong';
            }
        }else if($check == 'username'){
            $sql = "SELECT * FROM users WHERE username='$userid' AND password='$password'";
            $query = mysqli_query($link,$sql);
            if(mysqli_num_rows($query) == 1){
                if(isset($_POST['remember_me'])){
                    setcookie('userid', $userid, time()+24*60*60);
                    setcookie('password', $_POST['password'], time()+24*60*60);
                }else{
                    setcookie('userid','');
                    setcookie('password','');
                }
                $data = mysqli_fetch_assoc($query);
                $_SESSION['id'] = $data['id'];
                $_SESSION['name'] = $data['name'];
                $_SESSION['type'] = $data['type'];
                header('location:index.php');
            }else{
                $_SESSION['error'] = 'Username or Password maybe wrong';
            }
        }else{
            $_SESSION['error'] = 'Username/Email or Password maybe wrong';
        }
    }
?>
<head>
    <?php include 'layouts/head.php' ?>
    <title>Login</title>
</head>
<body>
<?php include 'layouts/header.php' ?>
<div class="container">
  <div id="login">
    <div class="mb-4"><h1 class="text-center">Login</h1></div>

    <?php if(isset($_SESSION['reg_success'])){ ?>
        <div class="alert alert-success"><?= $_SESSION['reg_success']; unset($_SESSION['reg_success']); ?></div>
    <?php } ?>
    <?php if(isset($_SESSION['error'])){ ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <form action="" method="POST">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class='fas fa-user'></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Enter Username/Email" id="userid" name="userid" value="<?php echo isset($_COOKIE['userid']) ? $_COOKIE['userid'] : '' ?>" requied>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class='fas fa-key'></i></span>
            </div>
            <input type="password" class="form-control" placeholder="Enter Password" id="password" name="password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : '' ?>" required>
        </div>

        <div class="text-right mb-4">
            <input type="checkbox" id="remember_me" name="remember_me" <?php echo isset($_COOKIE['userid']) && isset($_COOKIE['password']) ? 'checked' : '' ?>> Remember me
        </div>

        <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
        <p class="text-center mt-2">Have not account? <a href="signup.php">Signup Now</a></p>
    </form>
  </div>
</div>
<?php include 'layouts/footer.php' ?>
</body>