<?php require_once("../includes/initialize.php");

if (isset($_SESSION['ncedadmin'])) {
$page = $_GET['page'];
$member_admin = new memadmin();
$result = $member_admin->ajax_renewals($page, 25);
//print_r($result);
echo $json_response = json_encode($result);
}
?>
