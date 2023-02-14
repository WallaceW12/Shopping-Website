<?php

require __DIR__ . '/admin/lib/db.inc.php';

$cat = ierg4210_cat_fetchAll();
$li_cat = '';
$prod_box ='';

foreach ($cat as $ent_cat){

$li_cat .= '<li class="selected" ><a  class="category" href="category.php?cid='.$ent_cat["CID"].'"><span>'.$ent_cat["NAME"].'</span></a></li>';

}


$fetch_prod = ierg4210_prod_fetchAll();

foreach ($fetch_prod as $value_prod) {
    $prod_box .=
        '<li class="product_box">
                          <a href="product.php?pid=' . $value_prod["PID"] . '">
                              <img src="'. $value_prod["IMAGE"] .'" alt="" class="product-img">
                              <h2 class="product-title">' . $value_prod["NAME"] . '</h2>
                              <span class="price">$' . $value_prod["PRICE"] . '</span>
                          </a>
                          <!--box cart icon-->
                          <i class="bx bx-cart-add"></i>
                      </li>';


    // $li_cat .= '<li class="selected" ><a  class="category" href="category.php?cid='.$value_cat["CID"].'"><span>'.$value_cat["NAME"].'</span></a></li>';

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