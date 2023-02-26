<?php
require __DIR__.'/lib/db.inc.php';


$catList = ierg4210_cat_fetchall();
$prodList = ierg4210_prod_fetchall();

//$categories = ierg4210_cat_fetchAll();
//$products = ierg4210_prod_fetchAll();

$options_cat = '';
$options_prod = '';
$divs_prod = '';


foreach ($catList as $value ){
    $options_cat .= '<option value="'.$value["CID"].'"> '.$value["NAME"].' </option>';
}

foreach ($prodList as $value ){
    $options_prod .= '<option value="'.$value["PID"].'"> '.$value["NAME"].' </option>';
    //print_r($options_prod);
    // $options .= '<option value="'.$value["CID"].'"> '.$value["NAME"].' </option>';
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0" charset="utf-8">
        <link rel="stylesheet" href="/css/admin.css?v=<?php echo time(); ?>">
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
                <a href="/admin/admin.php" class="logo">Admin</a>

            </div>
            <!--horizontal Nav-->
        </div>

    </header>

    <div class="form-container">
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <fieldset class="form-fill"　id="add-product-form">
            <legend> ADD New Product</legend>
            <form id="add_prod" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
                <label for="prod_cid"> Category *</label>
                <div> <select id="prod_cid" name="CID"><?php echo $options_cat; ?></select></div>

                <label for="prod_name"> Name *</label>
                <div>   <input type="text" name="NAME" id="name-new-product" pattern="^[\w\- ]+$" required></div>

                <label for="prod_price"> Price *</label>
                <div> <input type="number"  min="0" name="PRICE" step="any" id="price-new-product" pattern="^[\d\.]+$" required></div>

                <label for="prod_desc"> Description *</label></br>
                <textarea name="DESCRIPTION" id="description-add-product" cols="30" rows="10" required></textarea>
                </br>

                <label for="prod_inventory"> Inventory *</label>
                <div> <input id="prod_inventory" type="text" pattern="^[\d\.]+$" name="INVENTORY" required="required"> </div>

                <label for="prod_image"> Image * </label>




                        <div class="drop-zone">
                            <img class="image_uploaded_add" src="" alt="">
                            <p class="drag-and-drop_p">Drop file or click to upload</p>
                            <input class ="upload_button" type="file" name="IMAGE" hidden required="true" accept="image/jpeg, image/png, image/gif">

                        </div>




                <input type="submit" value="Submit"/>
            </form>
        </fieldset>
        <fieldset class="form-fill"　id="add-cat-form" >
            <legend> ADD Categories  </legend>
            <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert"
            enctype="multipart/form-data">

                <label for="add_cat_name"> Name *</label>
                <div> <input id="cat_name" type="text" name="CATEGORY" required="required" pattern="^[\w\-]+$"/></div>
                <input type="submit" value="Submit"/>
            </form>
        </fieldset>
        <fieldset class="form-fill"　id="edit-cat-form">
            <legend> Edit Categories</legend>
            <form id="cat_edit" method="POST" action="admin-process.php?action=cat_edit"
            enctype="multipart/form-data">
                <label for="edit_cat_name"> Choose the Category to be edited *</label></br>
                <select name="CID" id="CID-delete">
                    <?php echo $options_cat; ?>
                </select>

                <br><label for="edit_cat_name"> New Name * </label> </br>
                <div> <input id="cat_name" type="text" name="CATEGORY" required="required" pattern="^[\w\-]+$"/></div>
                <input type="submit" value="Submit"/>
            </form>
        </fieldset>
        <fieldset class="form-fill"　id="delete-cat-form">
            <legend> DELETE Categories</legend>
            <form id="cat_edit" method="POST" action="admin-process.php?action=cat_delete"
                  enctype="multipart/form-data">
                <label for="edit_cat_name"> Choose the Category to be deleted *</label></br>
                <select name="CID" id="CID-delete">
                    <?php echo $options_cat; ?>
                </select>

                <input type="submit" value="Submit"/>
            </form>
        </fieldset>

        <fieldset class="form-fill"　id="edit-product-form">
            <legend>Edit Product Form </legend>
            <form method="POST" action="admin-process.php?action=prod_edit" enctype="multipart/form-data" onsubmit="return check_option(this)">
                <label for="PID-edit">Choose a product to be Edited &#42;</label>
                <select name="CID" id="CID-delete">
                    <?php echo $options_cat; ?>
                </select></br>
                <select name="PID" id="PID-delete">
                    <?php echo $options_prod; ?>
                </select></br>
                <label for="prod_name"> Name *</label>
                <div>
                     <input type="text" name="NAME" id="name-new-product" pattern="^[\w\- ]+$" required  >  </div>

                <label for="prod_price"> Price *</label>
                <div> <input type="number" min="0" name="PRICE" step="any" id="price-new-product" pattern="^[\d\.]+$" required></div>

                <label for="prod_desc"> Description *</label></br>
                <textarea name="DESCRIPTION" id="description-add-product" cols="30" rows="10" required></textarea>
                </br>

                <label for="prod_inventory"> Inventory *</label>
                <div> <input id="prod_inventory" type="text" name="INVENTORY" pattern="^[\d\.]+$" required="required"> </div>

                <label for="prod_image"> Image * </label>

                <div class="drop-zone">
                    <img class="image_uploaded_add" src="" alt="">
                    <p class="drag-and-drop_p">Drop file or click to upload</p>
                    <input class ="upload_button" type="file" name="IMAGE" hidden required="true" accept="image/jpeg, image/png, image/gif">
                    <script type="text/javascript" src="/js/drag-and-drop.js"> </script>
                </div>

                <input type="submit" value="Submit"/>
            </form>
        </fieldset>


        <fieldset class="form-fill"　id="delete-prod-form">
            <legend>Product Delete Form </legend>
            <form method="POST" action="admin-process.php?action=prod_delete" enctype="multipart/form-data" >
                <label for="CID-delete">Choose a Product to be Deleted &#42;</label>
                <select name="PID" id="PID-delete">
                    <?php echo $options_prod; ?>
                </select>

                <div class="actions">
                    <input type="reset" value="Reset">
                    <input type="submit" value="Submit">
                </div>
            </form>
        </fieldset>
    </div>
    </body>
</html>
