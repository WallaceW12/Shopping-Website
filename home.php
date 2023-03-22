<?php


require __DIR__ . '/admin/lib/db.inc.php';


include_once ('./admin/auth.php');

$cat = ierg4210_cat_fetchAll();
$li_cat = '';
$prod_box ='';
$login ='';

$welcome='';
$adminPanel='';

if (session_id() == null){
    session_start();
}

if(auth() == 1 || auth() == 2){
    $login .= '<form class="user-menu" method="POST" action="/admin/auth-process.php?action=logout" enctype="multipart/form-data">
                    <input  class="log-out-outside" type="submit" value="Logout">
                </form>';

    if(auth() == 1){
        $adminPanel ='  <a href="/admin/admin.php" class="logo">Manage</a>';
    }
    $welcome='<p> Welcome! , '.$_SESSION['auth']['em'].' </p>';
}else{
    $login =  '<a href="/login.php" class="logo">Login</a>';
    $welcome='<p> Welcome!, Guess </p>';
}


    foreach ($cat as $ent_cat){

$li_cat .= '<li class="selected" ><a  class="category" href="category.php?cid='.$ent_cat["CID"].'"><span>'.$ent_cat["NAME"].'</span></a></li>';

}


$fetch_prod = ierg4210_prod_fetchAll();
$count = 0;
foreach ($fetch_prod as $value_prod) {
    $prod_box .=
        '<li class="product_box" >
                          <a href="product.php?pid=' . $value_prod["PID"] . '">
                              <img src="'. $value_prod["THUMBNAIL"] .'" alt="" class="product-img">
                              <h2 class="product-title">' . $value_prod["NAME"] . '</h2>
                              <span class="price">$' . $value_prod["PRICE"] . '</span>
                          </a>
                          <!--box cart icon-->
                         <i class="bx bx-cart-add" counter='.$count.' pid='.$value_prod["PID"].' onclick="addToCartClicked(event, this);"></i>
                      </li>';

    $count++;
    // $li_cat .= '<li class="selected" ><a  class="category" href="category.php?cid='.$value_cat["CID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';


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
                  <i class='bx bx-cart' class = "logo" id="cart-icon" onclick="">
                  
                     <div class="cart">
                        <h2 class="cart-title">Your Cart</h2>
                        <!--cart content-->


                         <div class="cart-list">


                         </div>

                           <div class="total">
                         　
                              <div class="total-price"></div>
                           </div>
                           <!--Purchase-->
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

               <a href="/home.php" class="nav-home">Home</a>
               
            </section>



            

            <!--content-->

            <h2 class="section-title">Shop Products</h2>
            <ul class="shop-content">
                <!--Box1-->
                <section class="Cat-menu">
                    <h2 class="Category-title">Category</h2>
                    <?php echo $li_cat; ?>
                </section>

                    <?php

                    echo $prod_box;
                    ?>
            </ul>
        
        </section>

        <footer>

        </footer>

     </body>
 
</html>