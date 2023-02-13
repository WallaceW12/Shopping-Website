<?php

require __DIR__ . './admin/lib/db.inc.php';

//require_once '';
   //$sql = "SELECT * FROM product";
   //$all_prodcut = $conn->query($sql);
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
               
               <a href="/shop/home.php" class="logo">Shopping Sites</a>
               <!--Cart -->
               <div class="right-nav">
                  <a href="/admin/admin.php" class="logo">Admin</a>
                  
                  <i class='bx bx-cart' class = "logo" id="cart-icon">
                  
                     <div class="cart">
                        <h2 class="cart-title">Your Cart</h2>
                        <!--cart content-->
                     
                           <div class="cart-box">
                              <img src="airpod.jpg" alt="" class="cart-img">
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

                  <a href="electronic.html" class="category">Electronic</a>
                  <a href="food.html" class="category">Food</a>
                  <a href="meme.html" class="category">MEME</a>
                  
               </section>

               <?php
                  //while($row = mysqli_fetch_assoc($all_prodcut)){
               ?>
               <li class="product_box">
                  <a href="airpod.html">
                     <img src="airpod.jpg" alt="" class="product-img">
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
                  <a href="red-imposter.html">
                     <img src="SUS.jpg" alt="" class="product-img">
                     <h2 class="product-title">imposter Skin</h2>
                     <span class="price">$5</span>
                  </a>
                  <!--box cart icon-->
                  <i class='bx bx-cart-add'></i> 
                </li>
                <!--Box3-->
                <li class="product_box">
                  <a href="burger.html">
                     <img src="burger.jpg" alt="" class="product-img">
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