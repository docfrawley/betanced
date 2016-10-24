<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");


$tresult = new tresult_object();
if (isset($_POST['enumber'])){
  ?>
    <div class="row custom-row-class">
    	<div class="small-12 columns">
    		<? $tresult->process_request($_POST); ?>
    	</div>
    </div>
  <?
}

?>
  <div class="row custom-row-class">
  	<div class="small-6 columns small-centered">
  		<?
      if ($tresult->have_result()){
        $tresult->show_result();
      } else {
        $tresult->show_form();
      }
  		?>
  	</div>
  </div>
<?

include("../includes/layouts/footer.php"); ?>
