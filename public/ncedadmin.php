<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $member_admin = new memadmin();

    if (isset($_POST['ncednum'])) {
        $member_admin->add_member($_POST);
    }

    if (isset($_POST['ncednumber']) || isset($_POST['LastName']) || isset($_GET['ncednumberL'])){ 
        $ncednumber=isset($_GET['ncednumberL']) ? $_GET['ncednumberL'] : $member_admin->get_memberN($_POST); ;
        $member = new memobject($ncednumber);
        $meminfo = new infobject($ncednumber);
        $ceuinfo = new ceuinfo($ncednumber, $member->set_archivedate());?>
        <div class = "row">  
            <div class = "medium-9 columns">
                 <? $member->display_member(); ?>
            </div>
            <div class = "medium-3 columns"> 
                <? echo "<a href='ncedadmin.php' class='button small radius''>MEMBERSHIP ADMIN</a>"; ?>
            </div>  
        </div>  
        <div class = "row"> 
            <div class="small-7 columns">
                <? $ceuinfo->snapshot(false); ?>
            </div>
            <div class="small-5 columns">
                <? $member->admin_renew(); ?>
            </div>
        </div><?
    } else { ?>
        <div class = "row"> 
            <div class = "medium-12 columns"> 
                <div class = "row panel"> 
                    <?
                        if (isset($_SESSION['memmessage'])) { ?>
                            <div class = "medium-12 columns"> <h2><?
                                echo $_SESSION['memmessage']; 
                                $_SESSION['memmessage']=NULL; ?></h2>
                            </div> <?
                        } else {
                    ?>
                            <div class = "medium-4 columns"> <h3><?
                                echo "# Renewed: <strong>{$member_admin->get_numberOf('RENEWED')}</strong>"; ?></h3>
                            </div>
                            <div class = "medium-4 columns"> <h3><?
                                echo "# Non-Renewed: <strong>{$member_admin->get_numberOf('NOT RENEWED')}</strong>"; ?></h3>
                            </div>
                            <div class = "medium-4 columns"> <h3><?
                                echo "# Revoked: <strong>{$member_admin->get_numberOf('REVOKED')}</strong>"; ?></h3>
                            </div> <?
                        } ?>

            </div> 
        </div> 
        <div class = "row">
            <div class = "medium-6 columns"> <?
                $member_admin->search_member_form(); ?>
            </div>
            <div class = "medium-6 columns"> <?
                if (isset($_POST['renewal'])) { $member_admin->update_renew($_POST); }
                    $member_admin->set_renew(); ?>
            </div>
        </div> 
        <div class = "row">
            <div class = "medium-12 columns"> <?
                $member_admin->new_member_form(); ?>
            </div> 
        </div> <?
    }
}

include("../includes/layouts/footer.php"); ?>