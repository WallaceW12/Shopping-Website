<?php
if (session_id() == "")
    session_start();

function csrf_getNonce($action) {
    // generate a nonce by appending 2 random number
    $nonce = mt_rand() . mt_rand();

    // if there is no nonce in the session
    if (!isset($_SESSION['csrf_nonce']))
        // create one
        $_SESSION['csrf_nonce'] = array();

    // set the nonce of that form
    $_SESSION['csrf_nonce'][$action] = $nonce;

    return $nonce;
}

function csrf_verifyNonce($action, $receivedNonce) {
    // if there is a nonce AND it matches the one stored in the session
    if (isset($receivedNonce) && ($_SESSION['csrf_nonce'][$action] == $receivedNonce)) {

        // clear the nonce if auth is not present in the session
        if ($_SESSION['auth'] == null)
            unset($_SESSION['csrf_nonce'][$action]);
        return true;
    }

    throw new Exception('csrf-attack');
}
function Hell(){
    echo ("HI");
}
?>