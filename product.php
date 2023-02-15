<?php

require __DIR__ . '/admin/lib/db.inc.php';

$cat = ierg4210_cat_fetchAll();
$cat_name ='';
$pid = $_REQUEST["pid"];
$li_cat = '';
$prod_box ='';


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
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
<!--header -->
<header>
    <!--Nav -->

    <div class="nav container">

        <a href="/home.php" class="logo">Shopping Sites</a>
        <!--Cart -->
        <div class="right-nav">
            <a href="/admin/admin.php" class="logo">Admin</a>

            <i class='bx bx-cart' class = "logo" id="cart-icon">

                <div class="cart">
                    <h2 class="cart-title">Your Cart</h2>
                    <!--cart content-->

                    <div class="cart-box">
                        <img src="shop/airpod.jpg" alt="" class="cart-img">
                        <div class="detail-box">
                            <div class="cart-product-title">Airpod</div>
                            <div class="cart-price">$25</div>
                            <div class="cart-quantity"></div>
                            <input type="number" min="0" value="1" class="cart-quantity">
                        </div>
                        <!--Remove-->
                        <i class='bx bx-trash remove-item'></i>
                    </div>

                    <div class="total">
                        <div class="total-title">Total $25</div>
                        <div class="totalprice"></div>
                    </div>
                    <!--Purchase-->
                    <button type="button" class="buy-button">Purchase</button>
                </div>

            </i>
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
                    <a href="#" class="cart-btn">Add to cart</a>

                </div>

            </div>
        </main>

    </section>

</section>

<footer>

</footer>

</body>

</html>