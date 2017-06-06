<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncednumber'])) {
  $member = new memobject(_SESSION['ncednumber']);
  if ($member->get_memstatus =='RENEWED'){
    $fadmin = new files_object();
  ?>
  <div class = "row custom-row-class">
      <div class = "medium-6 columns-centered">
        <h2 class="text-center title-color">NCED NEWSLETTERS</h2>
      </div>
    </div>
  <? $fadmin->show_newsletters(); ?>
  </div>

  <?
} else {
  ?><h1>Your renewal status does not allow you access to this page.</h1><?
}
}

include("../includes/layouts/footer.php"); ?>
