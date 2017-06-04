<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncedadmin'])) {
    $fadmin = new files_object();
    if(isset($_POST['submit'])) {
      $fadmin->add_file($_POST, $_FILES);
  	}
    $task=isset($_GET['task']) ? $_GET['task'] : "" ;
    if ($task != "") {
      $fileid = ($_GET['fid']);
      $fadmin->delete_file($fileid);
    }

?>
<div class="row">
  <div class="small-6 columns">
    <div class="row">
      <div class="small-12 columns text-center">
        <br><h4 class='title-color'>GENERAL PDFS</h4>
      </div>
    </div>
    <? $fadmin->show_general_files(); ?>
  </div>

  <div class="small-6 columns right">
    <div class="row">
      <div class="small-12 columns text-center">
        <br><h4 class='title-color'>NEWSLETTERS</h4>
      </div>
    </div>
    <? $fadmin->show_newsletters_files(); ?>
    <div class="row">
      <div class="small-12 columns text-center">
        <? $fadmin->newsletter_file_form();?>
      </div>
    </div>
  </div>
</div><?

}

include("../includes/layouts/footeradmin.php"); ?>
