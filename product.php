<?php

require __DIR__ . '/admin/lib/db.inc.php';

include_once ('./admin/auth.php');

$cat = ierg4210_cat_fetchAll();
$cat_name ='';
$pid = $_REQUEST["pid"];
$li_cat = '';
$prod_box ='';
$count=0;
$login='';

$adminPanel='';
$welcome='';
$name='Guest';
if (session_id() == null){
    session_start();
}

if(auth()){
    $login .= '<form class="user-menu" method="POST" action="/admin/auth-process.php?action=logout" enctype="multipart/form-data">
                    <input  class="log-out-outside" type="submit" value="Logout">
                </form>';

    if(auth() == 1){
        $adminPanel ='  <a href="/admin/admin.php" class="logo">Manage</a>';
    }
    //welcome message, retrieve stored email
    if (!empty($_SESSION['auth'])) {
        $name = substr($_SESSION['auth']['em'], 0, strrpos($_SESSION['auth']['em'],"@"));
    }

}else{
    $login =  '<a href="/login.php" class="logo">Login</a>';

}
$welcome='<p> Welcome! , '.$name.' </p>';
$fetch_prod = ierg4210_prod_fetchOne($pid);
foreach ($fetch_prod as $value) {

}
foreach($cat as $cat_list){
    if($value["CID"] == $cat_list['CID']){
        $cat_name = $cat_list['NAME'];
    }
}

foreach ($cat as $ent_cat){
    if ($ent_cat["CID"] == $fetch_prod["CID"]) {
    $li_cat .= '<li class="selected" ><a  class="category" href="category.php?cid='.$ent_cat["CID"].'"><span>'.$ent_cat["NAME"].'</span></a></li>';

        $cat_name = $cat["NAME"];
    } else {
        $li_cat .= '<li><a class="category" href="category.php?cid='.$ent_cat["CID"].'"><span>'.$ent_cat["NAME"].'</span></a></li>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0" charset="utf-8">
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script type="text/javascript" src="/js/cart.js" defer> </script>
</head>
<body>
<!--header -->
<header>
    <!--Nav -->

    <div class="nav container">

        <a href="/home.php" class="logo">Shopping Sites</a>
        <?php

        echo $welcome;

        ?>
        <!--Cart -->
        <div class="right-nav">
            <?php
            echo $adminPanel;
            ?>


            <i class='bx bx-cart' class = "logo" id="cart-icon">

                <div class="cart">
                    <h2 class="cart-title">Your Cart</h2>
                    <!--cart content-->


                    <div class="cart-list">


                    </div>

                    <div class="total">
                        　
                        <div class="total-price"></div>
                    </div>
                    <!--Purchase-->
                    <?php require_once('payment.php');

                    ?>
                    <button type="button" class="buy-button">Purchase</button>
                </div>

            </i>
            <?php echo $login; ?>
        </div>
        <!--horizontal Nav-->
    </div>

</header>




<!--shopping cart detail-->


<!--shop-->
<section class="shop container">

    <!--Nav menu>category>-->
    <section class="nav-menu">

        <a href="home.php" class="nav-home">Home</a>
         >
        <?php echo '<a href="category.php?cid='.$value["CID"].'">'.$cat_name.'</a>' ?>
         >
        <?php echo '<a href="product.php?pid='.$value["PID"].'">'.$value["NAME"].'</a>' ?>

    </section>





    <!--content-->

    <section class="shop-content">


        <section class="Cat-menu">
            <h2 class="Category-title">Category</h2>
            <?php echo $li_cat; ?>
        </section>

        <main class="product-container">
           <!-- Left Column / Headphones Image -->
            <div class="left-column">
                <img src="<?php echo $value["IMAGE"]; ?>" alt="" class="product-descrip-img">

            </div>


            <!-- Right Column -->
            <div class="right-column">


                <div class="product-description">

                    <span><?php echo $value["CATEGORIES"]; ?></span>
                    <h1><?php echo $value["NAME"]; ?></h1>

                    <p>
                        <?php echo $value["DESCRIPTION"]; ?>
                    </p>
                </div>


                <div class="product-configuration">

                </div>

                <i class="inventory-left">
                    <?php
                    if($value["INVENTORY"] <= 3){
                        echo "ONLY ".$value["INVENTORY"]." LEFT!!!";

                    }else{
                        echo $value["INVENTORY"]." LEFT";

                    }

                     ?>
                </i>


                <div class="product-price">
                    <span> $ <?php echo $value["PRICE"]; ?></span>
                    <a href="#" class="cart-btn"  pid="<?php echo $value['PID']; ?>" onclick="addToCartClicked(event, this);" >Add to cart</a>

                </div>

            </div>
        </main>

    </section>

</section>

<footer>

</footer>

</body>

</html>