<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncedadmin'])) {
    $fadmin = new files_object();
    if(isset($_POST['submit'])) {
  		$fadmin->add_file_general($_POST, $_FILES);
  	}
    $task=isset($_GET['task']) ? $_GET['task'] : "" ;
    if ($task != "") {
      $fileid = ($_GET['fid']);
      $fadmin->delete_file($fileid);
    }

?>
<div class="row  custom-row-class">
  <div class="small-10 columns">
    <h3 class="title-color">General Admin Files</h3>
    <? $fadmin->general_file_form();?>
  </div>
</div><?

$fadmin->show_general_files();

}

include("../includes/layouts/footeradmin.php"); ?>
