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

<html>
    <fieldset>
        <legend> ADD New Product</legend>
        <form id="add_prod" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
            <label for="prod_cid"> Category *</label>
            <div> <select id="prod_cid" name="CID"><?php echo $options_cat; ?></select></div>

            <label for="prod_name"> Name *</label>
            <div>   <input type="text" name="NAME" id="name-new-product" pattern="^[\w\- ]+$" required></div>

            <label for="prod_price"> Price *</label>
            <div> <input type="number" name="PRICE" step="any" id="price-new-product" pattern="^[\d\.]+$" required></div>

            <label for="prod_desc"> Description *</label></br>
            <textarea name="DESCRIPTION" id="description-add-product" cols="30" rows="10" required></textarea>
            </br>

            <label for="prod_inventory"> Inventory *</label>
            <div> <input id="prod_inventory" type="text" name="INVENTORY" required="required"> </div>

            <label for="prod_image"> Image * </label>
            <div class="image-upload-field">
                <div class="image-preview">
                    <img src="" alt="">
                    <div></div>
                </div>
                <div> <input type="file" name="IMAGE" required="true" accept="image/jpeg, image/png, image/gif, image/jpg"> </div>
            </div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
    <fieldset>
        <legend> ADD Categories  </legend>
        <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert"
        enctype="multipart/form-data">

            <label for="add_cat_name"> Name *</label>
            <div> <input id="cat_name" type="text" name="CATEGORY" required="required" pattern="^[\w\-]+$"/></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
    <fieldset>
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
    <fieldset>
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
            <div> <input type="number" name="PRICE" step="any" id="price-new-product" pattern="^[\d\.]+$" required></div>

            <label for="prod_desc"> Description *</label></br>
            <textarea name="DESCRIPTION" id="description-add-product" cols="30" rows="10" required></textarea>
            </br>

            <label for="prod_inventory"> Inventory *</label>
            <div> <input id="prod_inventory" type="text" name="INVENTORY" required="required"> </div>

            <label for="prod_image"> Image * </label>
            <div class="image-upload-field">
                <div class="image-preview">
                    <img src="" alt="">
                    <div></div>
                </div>
                <div> <input type="file" name="IMAGE" required="true" accept="image/jpeg, image/png, image/gif, image/jpg"> </div>
            </div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>


    <fieldset>
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
</html>
