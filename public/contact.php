<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");


$contact = new contactObject(); ?>

<div class="custom-row-class">
  <div class="small-12 small-centered columns centered">
    <?
    if (isset($_POST['task'])){
      $contact->send_email($_POST);
      ?> <h3> Thank you for contacting us. We'll be in touch.</h3><?
    } else {
      $contact->mail_form();
    }
    ?>

  </div>
</div>









<? include("../includes/layouts/footer.php"); ?>
