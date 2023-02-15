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
        function resize_image($file, $w, $h, $crop=FALSE) {
            list($width, $height) = getimagesize($file);
            $r = $width / $height;
            if ($crop) {
                if ($width > $height) {
                    $width = ceil($width-($width*abs($r-$w/$h)));
                } else {
                    $height = ceil($height-($height*abs($r-$w/$h)));
                }
                $newwidth = $w;
                $newheight = $h;
            } else {
                if ($w/$h > $r) {
                    $newwidth = $h*$r;
                    $newheight = $h;
                } else {
                    $newheight = $w/$r;
                    $newwidth = $w;
                }
            }
            $src = imagecreatefromjpeg($file);
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

            return $dst;
        }
        $thumb = resize_image(,300,100);

        if(move_uploaded_file($thumb, "/var/www/html/images/thumbnails/" . $lastId . $img_type)){
            $new_image = "./images/thumbnails/" . $lastId . $img_type;
            $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
            $q = $db->prepare($sql);
            $q->bindParam(1, $thumb);
            $q->bindParam(2, $lastId);
            $q->execute();
            imagedestroy($thumb);
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
        }
        // Note: Take care of the permission of destination folder (hints: current user is apache)

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

    $cid = $_POST["CID"];
    $pid = $_POST["PID"];
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
        && $_FILES["IMAGE"]["size"] < 5000000) {



        $file = $_FILES['IMAGE']['tmp_name'];
        list($width,$height)=getimagesize($file);
        $nwidth=300;
        $nheight=200;

        if($_FILES["IMAGE"]["type"] == "image/jpeg"){

            $source=imagecreatefromjpeg($file);

            header('Content-Type: image/jpeg');
            $temp_thumb = imagescale($source, 300, -1);

            if(move_uploaded_file($file, "/var/www/html/images/" . $pid . ".jpg") ) {
                $new_image_path = "./images/" . $pid . ".jpg";
                $sql = "UPDATE PRODUCTS SET IMAGE=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_image_path);
                $q->bindParam(2, $pid);
                $q->execute();

            }
            if (imagejpeg($temp_thumb, "/var/www/html/images/thumbnails/" . $pid . "_thumbnail.jpg") ) {
                $new_thumbnail_path = "./images/thumbnails/" . $pid . "_thumbnail.jpg";
                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail_path);
                $q->bindParam(2, $pid);
                $q->execute();
                imagedestroy($temp_thumb);
            }

            header('Location: admin.php#category-add-form');
            exit();
        }else if($_FILES["IMAGE"]["type"] == "image/png"){

            $source=imagecreatefromjpeg($file);

            header('Content-Type: image/png');
            $temp_thumb = imagescale($source, $nwidth, $nheight);


            if (imagepng($temp_thumb, "/var/www/html/images/thumbnails/" . $pid . "_thumbnail.png") ) {
                $new_thumbnail_path = "./images/thumbnails/" . $pid . "_thumbnail.png";
                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail_path);
                $q->bindParam(2, $pid);
                $q->execute();
                imagedestroy($temp_thumb);
            }
            header('Location: admin.php#category-add-form');
            exit();
        }
        else if($_FILES["IMAGE"]["type"] == "image/gif"){

            $source=imagecreatefromjpeg($file);

            header('Content-Type: image/gif');
            $temp_thumb = imagescale($source, $nwidth, $nheight);


            if (imagegif($temp_thumb, "/var/www/html/images/thumbnails/" . $pid . "_thumbnail.gif") ) {
                $new_thumbnail_path = "./images/thumbnails/" . $pid . "_thumbnail.gif";
                $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
                $q = $db->prepare($sql);
                $q->bindParam(1, $new_thumbnail_path);
                $q->bindParam(2, $pid);
                $q->execute();
                imagedestroy($temp_thumb);
            }
            header('Location: admin.php#category-add-form');
            exit();
        }
        /*
        if (move_uploaded_file($file, "/var/www/html/images/thumbnails/" . $pid . "_thumbnail.jpg")) {
            $new_image_path = "./images/thumbnails/" . $pid . "_thumbnail.jpg";
            $sql = "UPDATE PRODUCTS SET THUMBNAIL=? WHERE PID=?;";
            $q = $db->prepare($sql);
            $q->bindParam(1, $new_image_path);
            $q->bindParam(2, $pid);
            $q->execute();
            header('Location: admin.php#category-add-form');
            exit();
        }*/
            /*
            if($_FILES["IMAGE"]["type"]=='image/jpeg'){
                console.log(1);
                $source=imagecreatefromjpeg($file);
                imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
                $file_name='.jpg';
                imagejpeg($newimage);// 'images/thumbnails/'.$file_name

            }elseif($_FILES["IMAGE"]["type"]=='image/png'){
                $source=imagecreatefrompng($file);
                imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
                $file_name=$pid.'.png';
                imagepng($newimage,'images/thumbnails/'.$file_name);
            }elseif($_FILES["IMAGE"]["type"]=='image/gif'){
                $source=imagecreatefromgif($file);
                imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
                $file_name=$pid.'.gif';
                imagegif($newimage,'images/thumbnails/'.$file_name);

            }else{
                echo "Please select only jpg, png and gif image";
            }

            // Note: Take care of the permission of destination folder (hints: current user is apache)

            header('Location: admin.php#category-add-form');
            exit();

        }
        // Only an invalid file will result in the execution below
        // To replace the content-type header which was json and output an error message

    */

    }
    header('Content-Type: text/html; charset=utf-8');
    header('Location: admin.php#category-add-form');
  //  echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
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
