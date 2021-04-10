<?php 
    session_start();
    include "config.php";
    if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
        header('location:index.php');
    }
    
    if(isset($_POST['signup'])){
        $fname = $_POST['fname'];
        $email = $_POST['email'];
        $sex = $_POST['sex'];
        $address = $_POST['address'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirmpassword'];
        $type = 0;
        $created_at = date('Y-m-d h:m:s a');
        $updated_at = date('Y-m-d h:m:s a');

        if($password == $confirm_password){
            $sql1 = "SELECT * FROM users WHERE email='$email'";
            $query1 = mysqli_query($link,$sql1);
            $sql2 = "SELECT * FROM users WHERE username='$username'";
            $query2 = mysqli_query($link,$sql2);

            if(mysqli_num_rows($query1) == 0 && mysqli_num_rows($query2) == 0){
                $password_encrypt = md5($password);
                $sql = "INSERT INTO users (name,email,sex,address,username,password,type,created_at,updated_at) VALUES ('$fname','$email','$sex','$address','$username','$password_encrypt','$type','$created_at','$updated_at')";
                $query = mysqli_query($link,$sql);

                if($query){
                    $_SESSION['reg_success'] = 'User registration successfully. Now you can login';
                    header('location:login.php');
                }else{
                    $_SESSION['reg_error'] = 'User registration failed. Try again';
                }
            }else if(mysqli_num_rows($query1) > 0 && mysqli_num_rows($query2) == 0){
                $_SESSION['email_error'] = 'This email already used';
            }else if(mysqli_num_rows($query1) == 0 && mysqli_num_rows($query2) > 0){
                $_SESSION['username_error'] = 'This username already used';
            }else{
                $_SESSION['email_error'] = 'This email already used';
                $_SESSION['username_error'] = 'This username already used';
            }
        }else{
            $_SESSION['password_error'] = 'Password & Confirm password should be same';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layouts/head.php' ?>
    <title>Signup</title>
</head>
<body>
<?php include 'layouts/header.php' ?>
<div class="container">
  <div id="login">
    <div class="mb-4"><h1 class="text-center">Signup</h1></div>

    <?php if(isset($_SESSION['reg_error'])){ ?>
        <div class="alert alert-danger"><?= $_SESSION['reg_error']; unset($_SESSION['reg_error']); ?></div>
    <?php } ?>
    

    <form action="" method="POST">
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class='fas fa-user'></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Enter Fullname" id="fname" name="fname" value="<?= isset($_POST['fname']) ? $_POST['fname'] : '' ?>" requied>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class='fas fa-envelope'></i></span>
                </div>
                <input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" requied>
            </div>
            <?php if(isset($_SESSION['email_error'])){ ?><span class="text-danger ml-5"><?= $_SESSION['email_error']; unset($_SESSION['email_error']); ?></span><?php } ?>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class='fas fa-transgender'></i></span>
                </div>
                <?php if(!isset($_POST['sex'])){ ?>
                <select name="sex" class="form-control" required>
                    <option value="">--Select Sex--</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <?php }else if($_POST['sex'] == 'male'){ ?>
                <select name="sex" class="form-control" required>
                    <option value="">--Select Sex--</option>
                    <option value="male" selected>Male</option>
                    <option value="female">Female</option>
                </select>
                <?php }else{ ?>
                <select name="sex" class="form-control" required>
                    <option value="">--Select Sex--</option>
                    <option value="male">Male</option>
                    <option value="female" selected>Female</option>
                </select>
                <?php } ?>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class='fas fa-address-card'></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Enter Address" id="address" name="address" value="<?= isset($_POST['address']) ? $_POST['address'] : '' ?>" requied>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class='fas fa-user'></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Enter Username" id="username" name="username" value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>" requied>
            </div>
            <?php if(isset($_SESSION['username_error'])){ ?><span class="text-danger ml-5"><?= $_SESSION['username_error']; unset($_SESSION['username_error']); ?></span><?php } ?>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class='fas fa-key'></i></span>
                </div>
                <input type="password" class="form-control" placeholder="Enter Password" id="password" name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>" required>
            </div>
        </div>

        <div class="mb-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class='fas fa-key'></i></span>
                </div>
                <input type="password" class="form-control" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" required>
            </div>
            <?php if(isset($_SESSION['password_error'])){ ?><span class="text-danger ml-5"><?= $_SESSION['password_error']; unset($_SESSION['password_error']); ?></span><?php } ?>
        </div>

        <button type="submit" class="btn btn-primary btn-block" name="signup">Register</button>
        <p class="text-center mt-2">Already have account? <a href="login.php">Login Now</a></p>
    </form>
  </div>
</div>
<?php include 'layouts/footer.php' ?>
</body>
</html>