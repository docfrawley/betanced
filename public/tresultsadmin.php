<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncedadmin']) || isset($_SESSION['memberadmin']) || isset($_SESSION['examadmin'])) {
    $member_admin = new memadmin();

    if (isset($_POST['testnum'])) {
        $member_admin->addTestResult($_POST);
        echo "Test Result Entered<br>";
    }

    // $today_array = $member_admin->TestResultsToday();
    // if (count($today_array)>0){
    //   echo date("F j, Y")."<br>";
    //   print_r($today_array);
    // }


    //list tresults entered today and add button to delete and edit.


            ?><div class = "row">
                <div class = "medium-12 columns">
                <?$member_admin->new_member_test(); ?>
                </div>
            </div> <?

}

include("../includes/layouts/footer.php"); ?>
