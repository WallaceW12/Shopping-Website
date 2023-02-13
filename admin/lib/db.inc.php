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

    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $_POST["CID"]))
        throw new Exception("invalid-cid");
    $_POST['CID'] = (int) $_POST['CID'];
    if (!preg_match('/^[\w\- ]+$/', $_POST["NAME"]))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['PRICE']))
        throw new Exception("invalid-price");

    if (!preg_match('/^[\d]+$/', $_POST['INVENTORY']))
        throw new Exception("invalid-inventory");
    if (!preg_match('/^[\w\- ]+$/', $_POST['DESCRIPTION']))
        throw new Exception("invalid-textt");
   // $_POST['description'] = (string) $_POST['description'];

    $cid = $_POST["CID"];
    $name = $_POST["NAME"];
    $price = $_POST["PRICE"];
    $inventory = $_POST["INVENTORY"];
    $desc = $_POST["DESCRIPTION"];
    $default_image = "./images/_default.jpg";
    $default_thumbnail= "./images/thumbnails/_default_thumbnail.jpg";
    $sql="INSERT INTO PRODUCTS (CID, NAME, PRICE, INVENTORY, DESCRIPTION, IMAGE, THUMBNAIL) VALUES (?, ?, ?, ?, ?, ?, ?);";

    // DB manipulation
    global $db;
    $db = ierg4210_DB();

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

    $img_type = '.jpg';
    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["IMAGE"]["error"] == 0
    && ($_FILES["IMAGE"]["type"] == "image/jpeg" || $_FILES["IMAGE"]["type"] == "image/png" || $_FILES["IMAGE"]["type"] == "image/gif")
    && (mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/jpeg" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/png" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/gif" )
    && $_FILES["IMAGE"]["size"] < 5000000){
/*
        try{
            $imgSrc = $_FILES["IMAGE"];

            //getting the image dimensions
            list($width, $height) = getimagesize($imgSrc);

            //saving the image into memory (for manipulation with GD Library)
            $myImage = imagecreatefromjpeg($imgSrc);

            // calculating the part of the image to use for thumbnail
            if ($width > $height) {
                $y = 0;
                $x = ($width - $height) / 2;
                $smallestSide = $height;
            } else {
                $x = 0;
                $y = ($height - $width) / 2;
                $smallestSide = $width;
            }

            // copying the part into thumbnail
            $thumbSize = 100;
            $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
            imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);

            //final output
            //header('Content-type: image/jpeg');
            imagejpeg($thumb);

        }catch (Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
*/

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["IMAGE"]["tmp_name"], "/var/www/html/images/" . $lastId . $img_type)) {
            $new_image = "./images/" . $lastId . $img_type;
            $sql = "UPDATE PRODUCTS SET IMAGE=? WHERE PID=?;";
            $q = $db->prepare($sql);
            $q->bindParam(1, $new_image);
            $q->bindParam(2, $lastId);
            $q->execute();

            header('Location: admin.php#category-add-form');
            exit();
        }
        header('Location: admin.php#category-add-form');
        exit();

    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');

    header('Location: admin.php');
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
function ierg4210_prod_fetchCat($cid) {
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM PRODUCTS WHERE CID=?;");

    $q->bindParam(1, $cid);
    if ($q->execute())
        return $q->fetchAll();
}
function ierg4210_prod_fetchOne($pid){


    global $db;
    $db = ierg4210_DB();


    $q = $db->prepare("SELECT * FROM PRODUCTS WHERE PID = ?;");

    $q->bindParam(1, $pid);
    if ($q->execute())
        return $q->fetchAll();



}


function ierg4210_prod_edit(){
    global $db;
    $db = ierg4210_DB();


    if (!preg_match('/^\d*$/', $_POST['CID']))
        throw new Exception("invalid-CID");
    $_POST['CID'] = (int) $_POST['CID'];
    if (!preg_match('/^\d*$/', $_POST['PID']))
        throw new Exception("invalid-PID");
    $_POST['PID'] = (int) $_POST['PID'];
    if (!preg_match('/^[\w\- ]+$/', $_POST["NAME"]))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['PRICE']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\d]+$/', $_POST['INVENTORY']))
        throw new Exception("invalid-inventory");
    if (!preg_match('/^[\w\- ]+$/', $_POST['DESCRIPTION']))
        throw new Exception("invalid-textt");
    // $_POST['description'] = (string) $_POST['description'];

    $sql="UPDATE PRODUCTS SET CID=?, NAME=?, PRICE = ? , INVENTORY=?, DESCRIPTION=?, IMAGE=?, THUMBNAIL=?  WHERE PID=? ;";

    $cid = $_POST['CID'];
    $pid = $_POST['PID'];
    $name = $_POST["NAME"];
    $price = $_POST["PRICE"];
    $inventory = $_POST["INVENTORY"];
    $desc = $_POST["DESCRIPTION"];
    $default_image = "./images/_default.jpg";
    $default_thumbnail= "./images/thumbnails/_default_thumbnail.jpg";


    $q = $db->prepare($sql);


    $q->bindParam(1, $cid);
    $q->bindParam(2, $name);
    $q->bindParam(3, $price);
    $q->bindParam(4, $inventory);
    $q->bindParam(5, $desc);
    $q->bindParam(6, $default_image);
    $q->bindParam(7, $default_thumbnail);
    $q->bindParam(8, $pid);
    $q->execute();


    if ($_FILES["IMAGE"]["error"] == 0
        && ($_FILES["IMAGE"]["type"] == "image/jpeg" || $_FILES["IMAGE"]["type"] == "image/png" || $_FILES["IMAGE"]["type"] == "image/gif")
        && (mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/jpeg" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/png" || mime_content_type($_FILES["IMAGE"]["tmp_name"]) == "image/gif" )
        && $_FILES["IMAGE"]["size"] < 5000000){


       // $temp = imagecreatefromjpeg($_FILES["IMAGE"]["tmp_name"]);

        // scale the original image so as to create the thumbnail
       // $temp_thumb = imagescale($temp, 300, -1);


        $img_type = '.jpg';

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["IMAGE"]["tmp_name"], "/var/www/html/images/" . $pid . $img_type)) {
            $new_image = "./images/" . $pid . $img_type;

            $sql = "UPDATE PRODUCTS SET IMAGE=? WHERE PID=?;";

            $q = $db->prepare($sql);
            $q->bindParam(1, $new_image);
            $q->bindParam(2, $pid);
            $q->execute();

            header('Location: admin.php#category-add-form');
            exit();
        }
        header('Location: admin.php#category-add-form');
        exit();

    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message

    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();



}
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

    $lastId = $db->lastInsertId();
    header('Location: admin.php#category-add-form');
    exit();

}
