<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $member_admin = new memadmin();

$the_board = new boardadmin(); ?>
<div class="row"> 
	<div class="medium-7 columns">
		<div class="row"> 
			<div class="medium-12 columns">
				<? $the_board->add_bmember_form(); ?>
			</div>
			<div class="medium-12 columns">
				<? $the_board->change_form(); ?>
			</div>
		</div>
	</div>
	<div class="medium-5 columns">
		<? $the_board->members_form(); ?>
	</div>
</div> <?
} 

include("../includes/layouts/footer.php"); ?>