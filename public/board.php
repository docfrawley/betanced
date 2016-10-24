<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");



$the_board = new boardadmin();
?>
<div class="row custom-row-class">
	<div class="small-12 small-centered columns">
		<h3 class="text-center title-color">Chairs and Heads of Committees</h3>
	</div>
</div>
<div class="row custom-row-class">
	<div class="small-8 small-centered columns">

		<?
		$the_board->print_board();
		?>
	</div>
</div>

<div class="row custom-row-class">
	<div class="small-12 small-centered columns">
		<h3 class="text-center title-color">Board Members</h3>

	</div>
</div>
<div class="row custom-row-class">
	<div class="small-8 small-centered columns">
		<? $the_board->list_commembers(); ?>
	</div>
</div>

<!-- modal windows -->
 <?  $the_board->generate_boxes();
include("../includes/layouts/footer.php"); ?>
