<?php
require __DIR__.'/admin/lib/db.inc.php';
$cid = $_REQUEST["CID"];
$categories = ierg4210_cat_fetchAll();
$li_cat = '';
$current_cat = '';

foreach ($categories as $value_cat) {
    if ($value_cat["CID"] == $cid) {
        $li_cat .= '<li class="selected"><a href="category.php?cid='.$value_cat["CID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
        $current_cat = $value_cat["NAME"];
    } else {
        $li_cat .= '<li><a href="category.php?cid='.$value_cat["CID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';
    }
}

/*$products = ierg4210_prod_fetch_by_catid($cid);
$div_prod = '';
foreach ($products as $value_prod) {


     /*
                <li class="product_box">
                  <a href="shop/red-imposter.html">
                     <img src="shop/SUS.jpg" alt="" class="product-img">
                     <h2 class="product-title">imposter Skin</h2>
                     <span class="price">$5</span>
                  </a>
                  <!--box cart icon-->
                  <i class='bx bx-cart-add'></i>
                </li>

    $div_prod .= '<li class="product_box">
                    <a href="product.php?pid='.$value_prod["PID"].'">
                        <img src="'.$value_prod["THUMBNAIL"].'" alt="" class="product-img">
                          <h2 class="product-title">'.$value_prod["NAME"].'</h2>
                            <span class="price">$'.$value_prod["PRICE"].'</span>           
                       </a>
                  <!--box cart icon-->
                  <i class="bx bx-cart-add/"></i>
                </li>';

}*/
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0" charset="utf-8">
    <link rel="stylesheet" href="shop/style.css">
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

        <a href="home.html" class="nav-home">Home</a>

    </section>




    <h2 class="section-title">Shop Products</h2>
    <!--content-->

    <ul class="shop-content">
        <!--Box1-->
        <section class="Cat-menu">
            <h2 class="Category-title">Category</h2>

            <ul>
                <?php echo $li_cat; ?>

            </ul>
            <div class="selector">
                <a href="#first">&lt;</a>
                <a href="#last">&gt;</a>
            </div>
            <a href="shop/electronic.html" class="category">Electronic</a>

            <a href="shop/food.html" class="category">Food</a>
            <a href="shop/meme.html" class="category">MEME</a>

        </section>

        <?php
        //while($row = mysqli_fetch_assoc($all_prodcut)){
        ?>
        <li class="product_box">
            <a href="shop/airpod.html">
                <img src="shop/airpod.jpg" alt="" class="product-img">
                <h2 class="product-title">AirPod</h2>
                <span class="price">$25</span>
            </a>
            <!--box cart icon-->
            <i class='bx bx-cart-add'></i>
        </li>
        <?php
        // }
        ?>

        <!--Box2-->
        <li class="product_box">
            <a href="shop/red-imposter.html">
                <img src="shop/SUS.jpg" alt="" class="product-img">
                <h2 class="product-title">imposter Skin</h2>
                <span class="price">$5</span>
            </a>
            <!--box cart icon-->
            <i class='bx bx-cart-add'></i>
        </li>
        <!--Box3-->
        <li class="product_box">
            <a href="shop/burger.html">
                <img src="shop/burger.jpg" alt="" class="product-img">
                <h2 class="product-title">Burger</h2>
                <span class="price">$1.5</span>
            </a>
            <!--box cart icon-->
            <i class='bx bx-cart-add'></i>
        </li>
        <!--Box4-->

        <!--Box5-->

        <!--Box6-->

        <!--Box7-->

        <!--Box8-->

    </ul>

</section>

<footer>

</footer>

</body>

</html>
