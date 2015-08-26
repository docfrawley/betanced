<?php require_once("../includes/initialize.php"); 
session_start();

$lname=isset($_POST['lname']) ? $_POST['lname'] : "" ;
$ncednumber=isset($_POST['ncednumber']) ? $_POST['ncednumber'] : "" ;

$username=isset($_POST['username']) ? $_POST['username'] : "" ;
$password=isset($_POST['password']) ? $_POST['password'] : "" ;

if ($_SESSION["tryagainc"]=="check") {
	$luser = new loginuser($_SESSION['lname'], $_SESSION['ncednumber']);
	$luser->check_form($_POST);
	}

if ($lname || $ncednumber) {
	$luser = new loginuser($lname, $ncednumber);
	$luser->first_check();
}

if ($username || $password) {
	$luser = new loginuser($username, $password);
	$luser->do_check();
}

?>