<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $announcements = new all_announcements();
    $task=isset($_GET['task']) ? $_GET['task'] : "" ;
        if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
    switch ($task) {
        case 'editA':
            $announcements->announce_form(true, $_GET['id']);
            break;
        case 'updateA':
            $announcements->update_announce($_POST);
            break;
        case 'deleteA':
            $announcements->delete_announce($_GET['id']);
            break; 
        case 'addA':
            $announcements->add_announce($_POST);
            break;       
        default:
            break;
    }
    if ($task != "editA"){
        $announcements->announce_form(false);
    }
     
        ?><div class = "row">
            <div class = "medium-12 columns"> 
              <table>
                <? $announcements->print_announcements(true); ?>
              </table>
            </div> 
        </div> <?
}

include("../includes/layouts/footer.php"); ?>