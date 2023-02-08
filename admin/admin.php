<?php
require __DIR__.'/lib/db.inc.php';
$res = ierg4210_cat_fetchall();
$options = '';

foreach ($res as $value){
    $options .= '<option value="'.$value["cid"].'"> '.$value["name"].' </option>';
}
?>

<html>
    <fieldset>
        <legend> New Product</legend>
        <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert"
        enctype="multipart/form-data">
            <label for="prod_cid"> Category *</label>
            <div> <select id="prod_cid" name="cid"><?php echo $options; ?></select></div>
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
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
        <legend> New Categories</legend>
        <form id="prod_insert" method="POST" action="admin-process.php?action=cat_insert"
        enctype="multipart/form-data">

            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
</html>
