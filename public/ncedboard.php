<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $member_admin = new memadmin();

$the_board = new boardadmin(); ?>
<div class="row"> 
	<div class="medium-7 columns">
		<div class="row"> 
			<div class="medium-12 columns">
				<? 
				$task=isset($_GET['task']) ? $_GET['task'] : "" ;
        			if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
        		if (!$task){ $the_board->add_bmember_form(); }	
        		else {
        			switch ($task) {
        				case 'medit_form':
        					$the_board->add_bmember_form($_GET['member']);
        					break;
        				case 'mupdate':
        					$the_board->bmember_update($_POST);
        					break;
        				case 'mdelete':
        					$the_board->bmember_delete($_POST);
        					break;
        				case 'madd':
                            $the_board->bmember_add($_POST);        					
        					break;	
        				default:
        					break;
        			}
        		}
				 ?>
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