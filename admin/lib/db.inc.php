<?php
function ierg4210_DB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/cart.db');

	// enable foreign key support
	$db->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db;
}

function ierg4210_cat_fetchall() {
    // DB manipulation
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM categories LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function ierg4210_prod_insert() {
    // input validation or sanitization

    // DB manipulation
    global $db;
    $db = ierg4210_DB();



    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $_POST['CID']))
        throw new Exception("invalid-cid");
    $_POST['CID'] = (int) $_POST['cid'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['NAME']))
        throw new Exception("invalid-name");
    //$_POST['NAME'] = (string) $_POST['NAME'];
    if (!preg_match('/^[\d]+$/', $_POST['PRICE']))
        throw new Exception("invalid-price");
    //$_POST['PRICE'] = (int) $_POST['price'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['DESCRIPTION']))
        throw new Exception("invalid-textt");
   // $_POST['description'] = (string) $_POST['description'];

    $cid = $_POST["CID"];
    $name = $_POST["NAME"];
    $price = $_POST["PRICE"];
    $inventory = $_POST["INVENTORY"];
    $desc = $_POST["DESCRIPTION"];
    $sql="INSERT INTO products (CID, NAME, PRICE, DESCRIPTION, INVENTORY, IMAGE, THUMBNAIL) VALUES (?, ?, ?, ?, ?, ?, ?);";

    $default_image = "./images/_default.jpg";
    $default_thumbnail= "./images/thumbnails/_default_thumbnail.jpg";

    $q = $db->prepare($sql);
    $q->bindParam(1, $cid);
    $q->bindParam(2, $name);
    $q->bindParam(3, $price);
    $q->bindParam(4, $desc);
    $q->bindParam(5, $inventory);
    $q->bindParam(6, $default_image);
    $q->bindParam(7, $default_thumbnail);
    $q->execute();
    
    $lastId = $db->lastInsertId();
    
    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["IMAGE"]["error"] == 0
    && ($_FILES["IMAGE"]["type"] == "image/jpeg" || $_FILES["IMAGE"]["type"] == "image/png" || $_FILES["IMAGE"]["type"] == "image/gif")
    && (mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/jpeg" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/png" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/gif")
    && $_FILES["IMAGE"]["size"] < 10000000){

        // create the thumbnail
        $extension = '';
        $original = '';
        if ($_FILES["IMAGE"]["type"] == "image/jpeg") {
            $extension = '.jpg';
            $original = imagecreatefromjpeg($_FILES["IMAGE"]["tmp_name"]);

            // scale the original image so as to create the thumbnail
            $temp_thumb = imagescale($original, 300, -1);

            //update the product thumbnail once successfully created as jpg and stored
            if (imagejpeg($temp_thumb, "/var/www/html/images/thumbnails/" . $lastId . "_thumbnail.jpg")) {
                $new_thumbnail = "./images/thumbnails/" . $lastId . "_thumbnail.jpg";

                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail);
                $q->bindParam(2, $lastId);
                $q->execute();

                // destroy the temporary thumbnail
                imagedestroy($temp_thumb);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo 'Thumbnail creation failed. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
                exit();
            }
        }

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $lastId . ".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: /var/www/html/admin/admin.php');
            $q->execute();
            exit();
        }
    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}

// TODO: add other functions here to make the whole application complete
function ierg4210_cat_insert() {
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command

    if (!preg_match('/^[\w\- ]+$/', $_POST["CATEGORY"]))
        throw new Exception("invalid-CATEGORY");

    $sql="INSERT INTO CATEGORIES (NAME) VALUES (?);";
    $q = $db->prepare($sql);
    $name = $_POST["CATEGORY"];
    $q->bindParam(1, $name);

    $q->execute();

    header('Location: admin.php#category-add-form');
    exit();
}
function ierg4210_cat_edit(){
    global $db;
    $db = ierg4210_DB();

    if (!preg_match('/^\d*$/', $_POST['CID']))
        throw new Exception("invalid-CID");
    $cid = (int) $_POST['CID'];

    if (!preg_match('/^[\w\- ]+$/', $_POST['CATEGORY']))
        throw new Exception("invalid-CATEGORY-name");
    $name = $_POST["CATEGORY"];


    $sql="UPDATE CATEGORIES SET NAME=? WHERE CID=? ;";

    $q = $db->prepare($sql);

    $q->bindParam(1, $name);
    $q->bindParam(2, $cid);

    $q->execute();
    header('Location: admin.php#category-add-form');
    exit();

}
function ierg4210_cat_delete(){
    global $db;
    $db = ierg4210_DB();

    if (!preg_match('/^\d*$/', $_POST['CID']))
        throw new Exception("invalid-CID");
    $_POST['CID'] = (int) $_POST['CID'];
    $cid = $_POST["CID"];

    try {
        ierg4210_prod_delete_by_cid($cid);
    } catch (Exception $e) {
        print_r("BRUH");
        exit();
    }

    $sql="DELETE FROM CATEGORIES WHERE CID=?;";
    $q = $db->prepare($sql);

    $q->bindParam(1, $cid);

    $q->execute();
    header('Location: admin.php#category-add-form');
    exit();
}

function ierg4210_prod_delete_by_cid($cid){
    if (!preg_match('/^\d*$/', $_POST['CID']))
        throw new Exception("invalid-CID");
    $cid = (int) $cid;

    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM PRODUCTS WHERE CID=?;";

    $q = $db->prepare($sql);

    $q->bindParam(1, $cid);
    $q->execute();

}
function ierg4210_prod_fetchAll(){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}
function ierg4210_prod_fetchOne(){}
function ierg4210_prod_edit(){}
function ierg4210_prod_delete(){
    if (!preg_match('/^\d*$/', $_POST['PID']))
        throw new Exception("invalid-PID");
    $pid = (int) $_POST['PID'];

    global $db;
    $db = ierg4210_DB();

    $sql = "DELETE FROM PRODUCTS WHERE PID=?;";

    $q = $db->prepare($sql);

    $q->bindParam(1, $pid);
    $q->execute();

    header('Location: admin.php#category-add-form');
    exit();

}
