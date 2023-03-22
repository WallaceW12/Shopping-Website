<?php
//check session is still valid, if no create one


include_once('../csrf.php');

if(session_id() == null){
    session_start();
}

header('Content-Type: application/json');

try {
    csrf_verifyNonce($_REQUEST['action'], $_POST['nonce']);
} catch (Exception $e) {
    new Exception($e);
};

// input validation
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
    echo json_encode(array('failed'=>'undefined'));
    exit();
}



// The following calls the appropriate function based to the request parameter $_REQUEST['action'],
//   (e.g. When $_REQUEST['action'] is 'cat_insert', the function ierg4210_cat_insert() is called)
// the return values of the functions are then encoded in JSON format and used as output
try {
    $db_user = ierg4210_DB_user();


    if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
        if ($db_user && $db_user->errorCode())
            error_log(print_r($db_user->errorInfo(), true));
        echo json_encode(array('failed'=>'1'));
    }else{
        echo 'while(1);' . json_encode(array('success' => $returnVal));
    }

} catch(PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(array('failed'=>'error-db_user'));
} catch(Exception $e) {
    echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
}


function ierg4210_DB_user() {
    // setup the database
    $db_user = new PDO('sqlite:/var/www/cart.db');

    // enable foreign key support
    $db_user->query('PRAGMA foreign_keys = ON;');

    // FETCH_ASSOC:
    // Specifies that the fetch method shall return each row as an
    // array indexed by column name as returned in the corresponding
    // result set. If the result set contains multiple columns with
    // the same name, PDO::FETCH_ASSOC returns only a single value
    // per column name.
    $db_user->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $db_user;
}
function ierg4210_login() {

    $email = strtolower($_POST["EMAIL"]);

    $password = $_POST["PASSWORD"];
    $pwSanitized = filter_var($email, FILTER_SANITIZE_STRING);
    $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!preg_match('/^[\w._%+-]+[a-zA-Z\d]+\@[\w.-]+\.[a-z]{2,8}$/', $emailSanitized) || !filter_var($emailSanitized, FILTER_VALIDATE_EMAIL))
        throw new Exception("invalid-email");
    if (!preg_match('/^.+$/', $password))
        throw new Exception("invalid-password");

    $db = ierg4210_DB_user();

    // try to fetch the account from database using user-entered email
    $sql = "SELECT * FROM USER WHERE EMAIL=?;";

    $q = $db->prepare($sql);
    $q->bindParam(1, $emailSanitized);

    if ($q->execute()) {
        $dbAcc = $q->fetch();

        // account does not exist
        if ($dbAcc['UID'] == null) {
            print_r($dbAcc['UID']) ;

            // redirect to login page
            header('Location: /login.php?');
            exit();
        }

        // get the salt and password from database
        $salt = $dbAcc["SALT"];
        $dbPassword = $dbAcc["PASSWORD"];


        // hash the user-entered password with salt from the database record
        $passwordHashed = hash_hmac('sha256', $password, $salt);

        echo $passwordHashed;
        // hash matched with DB
        if ( $passwordHashed == $dbPassword) {

            session_regenerate_id();

            $exp = time() + 3600 * 24 * 3;

            // t array (email, expire time, key)
            $t = array('em'=>$email, 'exp'=>$exp, 'k'=>hash_hmac('sha256', $exp.$passwordHashed, $salt));

            // set the cookie with the above t
            // http only on the second last true
            setcookie('auth', json_encode($t), $exp, '', '', true, true);

            // put it in the session
            $_SESSION['auth'] = $t;

            // redirect user to different page according to account type
            if ($dbAcc["FLAG"] == 1) {
                header('Location:admin.php',302);
                exit();
            }
            else {
                header('Location: /home.php',302);
                exit();
           }
        }
        throw new Exception('Wrong Credential');
        // account credentials did not match
        header('Location: login.php?error=3');
        exit();
    }

    // something went wrong
    else {
        throw new Exception('ERROR');
        header('Location: login.php?error=4');
        exit();
    }

}

function ierg4210_logout(){
    setcookie('auth', '', time() - 3600);

    unset($_SESSION['auth']);


    header('Location: /home.php', 302);

    exit();
}


