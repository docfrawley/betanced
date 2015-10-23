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
                if (isset($_POST['ncednumber']) || isset($_POST['LastName']) || isset($_GET['ncednumberL'])){ 
                        $ncednumber=isset($_GET['ncednumberL']) ? $_GET['ncednumberL'] : $member_admin->get_memberN($_POST, 'ncedboard'); 
                        $task = 'medit_form';
                    }    
        		switch ($task) {
        			case 'medit_form':
                        $ncedNumber = isset($ncednumber) ? $ncednumber : $_GET['member'];
        				$the_board->add_bmember_form($ncedNumber);
        				break;
        			case 'mupdate':
                        echo "mupdate";
        				$the_board->bmember_update($_POST);
        				break;
        			case 'mdelete':
        				$the_board->bmember_delete($_GET['member']);
        				break;
        			case 'madd':
                        $the_board->bmember_add($_POST);        					
        				break;	
                    case 'bchange':
                    case 'bchangeU':
                        if ($task == "bchangeU"){
                            $the_board->update_position($_POST); 
                        } else {
                            $the_board->edit_position_form($_GET['member'], $_GET['position']);  
                        }                      
                        break;      
        			default:
        				break;
        		}
                if ($task!='medit_form' && $task != 'bchange'){ 
                    $member_admin->search_member_form("ncedboard"); 
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