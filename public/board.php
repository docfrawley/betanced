<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 



$the_board = new boardadmin();
?> 
<div class="row">
	<div class="small-12 columns">
		<?
		$the_board->print_board();
		?>
	</div>
</div>

<!-- modal windows -->
 <?  $the_board->generate_boxes(); 
include("../includes/layouts/footer.php"); ?>