<?php require_once("../includes/initialize.php");

if (isset($_SESSION['ncedadmin']) || isset($_SESSION['memberadmin'])) {
$page = $_GET['page'];
$member_admin = new memadmin();
$result = $member_admin->ajax_renewals($page, 25);
//print_r($result);
echo $json_response = json_encode($result);
}

if ($_GET['task']=='markers'){
  $map_markers = new all_maps();
  $result = $map_markers->get_markers();
  echo $json_response = json_encode($result);
}
?>
