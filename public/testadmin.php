<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncedadmin']) || isset($_SESSION['examadmin']) ) {
    $maps = new all_maps();
    $task=isset($_GET['task']) ? $_GET['task'] : "" ;
        if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
    switch ($task) {
        case 'editM':
            $maps->map_form(true, $_GET['id']);

            break;
        case 'updateM':
            $maps->update_map($_POST);
            break;
        case 'deleteM':
            $maps->delete_map($_GET['id']);
            break;
        case 'addM':
            $maps->add_map($_POST);
            break;
        default:
            break;
    }
    if ($task != "editM"){
        $maps->map_form(false);
    }

        ?>
        <div class = "row">
            <div class = "medium-12 columns">
              <p>To edit a test site, simply click on the location below. Click the delete button to delete the test site.</p>
            </div>
        </div>
        <div class = "row">
            <div class = "medium-12 columns">
              <table>
                <? $maps->print_maps(true); ?>
              </table>
            </div>
        </div> <?
}

include("../includes/layouts/footer.php"); ?>
