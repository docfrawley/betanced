<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncednumber'])) {
?>
<div class = "row custom-row-class">
    <div class = "medium-6 columns-centered">
      <h2 class="text-center title-color">NCED NEWSLETTERS</h2>
    </div>
  </div>
<div class = "row custom-row-class">
    <div class = "medium-3 columns left">
      <a class="button radius expand" href="pdfs/fall2016.pdf">August/September 2016</a>
    </div>
  </div>

<?
}

include("../includes/layouts/footer.php"); ?>
