<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $member_admin = new memadmin();

$the_board = new boardadmin(); ?>
<div class="row"> 
	<div class="medium-6 columns">
		<? $the_board->change_form(); ?>
	</div>
	<div class="medium-6 columns">
		<? $the_board->members_form(); ?>
	</div>
</div> <?
} 

include("../includes/layouts/footer.php"); ?>