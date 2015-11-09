<?php require_once("../includes/initialize.php"); 
session_start();

$lname=isset($_POST['lname']) ? $_POST['lname'] : "" ;
$ncednumber=isset($_POST['ncednumber']) ? $_POST['ncednumber'] : "" ;


if ($lname || $ncednumber) {
	$luser = new loginuser($lname, $ncednumber);
	$luser->first_check();
}

?>