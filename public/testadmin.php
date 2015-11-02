<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $maps = new all_maps();
    $task=isset($_GET['task']) ? $_GET['task'] : "" ;
        if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
    switch ($task) {
        case 'editM':
            $maps->map_form(true, $_GET['id']);
            break;
        case 'updateM':
            $maps->update_announce($_POST);
            break;
        case 'deleteM':
            $maps->delete_announce($_GET['id']);
            break; 
        case 'addM':
            $maps->add_announce($_POST);
            break;       
        default:
            break;
    }
    if ($task != "editA"){
        $maps->map_form(false);
    }
     
        ?><div class = "row">
            <div class = "medium-12 columns"> 
              <table>
                <? $maps->print_maps(true); ?>
              </table>
            </div> 
        </div> <?
}

include("../includes/layouts/footer.php"); ?>