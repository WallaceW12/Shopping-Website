<?php
if (session_id() == "")
    session_start();


include_once ('./admin/auth.php');
include_once('./csrf.php');

if(auth()){
    header('Location: ./admin/admin.php');
    exit();
}

$message='';
$login_form=' 
 
 <form id="login-form-action" method="POST" action="admin/auth-process.php?action='.($action="login").'" enctype="multipart/form-data">
            <label for="login-email">Email</label>
            <input type="email" name="EMAIL" id="login-email" pattern="^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$"  placeholder="Enter your email here" required="true">
            <label for="login-password">Password</label>
            <input type="password" name="PASSWORD" id="login-password" pattern="^.+$" placeholder="Enter your password here" required="true">
            <div class="actions">
                <input type="submit" value="Login">
            </div>
            <input type="hidden" name="nonce" value="'.csrf_getNonce($action).'">

        </form>';
$reset_form=

        '  
        <form id="reset-form-action" method="POST" action="admin/auth-process.php?action='.($action="resetPW").'" enctype="multipart/form-data">
            <label for="reset-password">Reset Password</label>
            <label for="reset-email">Email</label>
            <input type="email" name="RESETEMAIL" id="reset-email" pattern="^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$"  placeholder="Enter your email here" required="true">
            <label for="reset-password">Old Password</label>
            <input type="password" name="OLDPASSWORD" id="reset-old" pattern="^.+$" placeholder="Enter your old password here" required="true">
            <label for="reset-password">New Password</label>
            <input type="password" name="NEWPASSWORD" id="reset-new" pattern="^.+$" placeholder="Enter your new password here" required="true">
            <div class="actions">
                <input type="submit" value="Reset">
            </div>
            <input type="hidden" name="nonce" value="'.csrf_getNonce($action).'">
        </form>';

if (isset($_REQUEST["error"])) {
    $error = $_REQUEST["error"];
    if ($error == 1) {
        $message = '<p class="errorM">Account Does Not Exist </p>';
    }
    if ($error == 2) {
        $message = '<p class="errorM">Invalid Credential </p>';
    }
    if ($error == 3) {
        $message = '<p class="errorM">Unknown Error occur </p>';
    }
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
        <?php echo ($message);?>
        <legend>Login</legend>

        <?php echo($login_form); ?>


        <?php echo($reset_form); ?>

    </fieldset>

</div>

</div>
</body>
</html>

