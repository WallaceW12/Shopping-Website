<?php
include_once('admin/lib/db.inc.php');

if (!preg_match('/^\d*$/', $_GET['pid']))
    throw new Exception("invalid-pid");


$_GET['pid'] = (int) $_GET['pid'];

$pid = $_GET['pid'];

global $db;
$db = ierg4210_DB();

$sql = "SELECT * FROM PRODUCTS WHERE PID=?;";

$q = $db->prepare($sql);
$q->bindParam(1, $pid);
$q->execute();

if ($q->execute())
    echo json_encode($q->fetch());

?>

