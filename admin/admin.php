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
    print_r($options_prod);
    // $options .= '<option value="'.$value["CID"].'"> '.$value["NAME"].' </option>';
}

?>

<html>
    <fieldset>
        <legend> New Product</legend>
        <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert"
        enctype="multipart/form-data">
            <label for="prod_cid"> Category *</label>
            <div> <select id="prod_cid" name="cid"><?php echo $options_cat; ?></select></div>
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="NAME" required="required" pattern="^[\w\-]+$"/></div>
            <label for="prod_price"> Price *</label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
            <label for="prod_desc"> Description *</label>
            <div> <input id="prod_desc" type="text" name="description"/> </div>
            <label for="prod_image"> Image * </label>
            <div> <input type="file" name="file" required="true" accept="image/jpeg"/> </div>
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
        <legend>Category Delete Form </legend>
        <form method="POST" action="admin-process.php?action=cat_delete" enctype="multipart/form-data" onsubmit="return check_option(this)">
            <label for="CID-delete">Choose a category to be Deleted &#42;</label>
            <select name="CID" id="CID-delete">
                <?php echo $options_cat; ?>
            </select>

            <div class="actions">
                <input type="reset" value="Reset">
                <input type="submit" value="Submit">
            </div>
        </form>
    </fieldset>
    <fieldset>
        <legend>Product Delete Form </legend>
        <form method="POST" action="admin-process.php?action=prod_delete" enctype="multipart/form-data" onsubmit="return check_option(this)">
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
