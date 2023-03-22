<?php
if (session_id() == "")
    session_start();


include_once ('./admin/auth.php');
include_once('./csrf.php');

if(auth()){
    header('Location: ./admin/admin.php');
    exit();
}


?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0" charset="utf-8">
    <link rel="stylesheet" href="/css/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
<header>
    <!--Nav -->

    <div class="nav container">

        <a href="/home.php" class="logo">Shopping Sites</a>
        <!--Cart -->
        <div class="right-nav">


        </div>
        <!--horizontal Nav-->
    </div>

</header>

<div class="form-container">
    <fieldset id="login-form">
        <legend>Login</legend>
        <form id="login-form-action" method="POST" action="admin/auth-process.php?action=<?php echo ($action='login');?>" enctype="multipart/form-data">
            <label for="login-email">Email</label>
            <input type="email" name="EMAIL" id="login-email" pattern="^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$"  placeholder="Enter your email here" required="true">
            <label for="login-password">Password</label>
            <input type="password" name="PASSWORD" id="login-password" pattern="^.+$" placeholder="Enter your password here" required="true">
            <div class="actions">
                <input type="reset" value="Reset">
                <input type="submit" value="Login">

            </div>
            <input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action);?>">
        </form>
    </fieldset>
</div>

</div>
</body>
</html>

