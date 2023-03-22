<?php

if (session_id() == "")
    session_start();

function auth(){
    $db = new PDO('sqlite:/var/www/cart.db');
    $db->query('PRAGMA foreign_keys = ON;');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if(!empty($_SESSION['auth'])){
        $email = $_SESSION['auth']['em'];

        // fetch account details from database
        $sql = "SELECT * FROM USER WHERE EMAIL=?;";

        $q = $db->prepare($sql);
        $q->bindParam(1, $email);
        $q->execute();

        if ($q->execute()) {
            $result = $q->fetch();

            // return true if it is an admin account
            if ($result["FLAG"] == 1)
                return 1;
            else
                return 2;

        }
       // return $_SESSION['auth']['em'];
    }

    if(!empty($_COOKIE['auth'])){
        if($t = json_decode(stripslashes($_COOKIE['auth']),true )){
            if(time() > $t['exp'])
                return false;

            $q = $db->prepare("SELECT * FROM USER WHERE EMAIL=?;");
            $q->bindParam(1, $t['em']);

            $q->execute();
            if($r=$q->fetch()){
                $realk=hash_hmac('sha256',$t['exp'].$r['password'],$r['salt']);
                if($realk == $t['k']){
                    $_SESSION['auth'] = $t;
                    $email = $t['em'];

                    return $t['em'];

                }

                if($r['FLAG']==1)
                    return 1;
            }
        }



    }

return false;
}
?>