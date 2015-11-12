<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $member_admin = new memadmin();

    if (isset($_POST['ncednum'])) {
        $member_admin->add_member($_POST);
    }

    if (isset($_POST['ncednumber']) || isset($_POST['LastName']) || isset($_GET['ncednumberL'])){ 
        if ((isset($_POST['ryear'])) || (isset($_POST['editinfo']))){
            $ncednumber = $_POST['ncednumber'];
            $member = new memobject($ncednumber);
            if (isset($_POST['ryear'])){
                $member->update_renew($_POST);
            }
        } else {
            $ncednumber=isset($_GET['ncednumberL']) ? $_GET['ncednumberL'] : $member_admin->get_memberN($_POST, 'ncedadmin');
            $member = new memobject($ncednumber);
        }
        $meminfo = new infobject($ncednumber);
        if (isset($_POST['editinfo'])){
            $meminfo->info_update($_POST); 
        }
        $ceuinfo = new ceuinfo($ncednumber, $member->set_archivedate());
        ?>
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
                <div class = "row"> 
                    <div class="small-12 columns">
                        <? $ceuinfo->snapshot(false); ?>
                    </div>
                     <div class="small-12 columns">
                        <? $meminfo->info_form(true, 'ncedadmin.php'); ?>
                    </div>
                </div>
            </div>
            <div class="small-5 columns">
                <div class="row">
                    <div class"small-12 columns">
                        <? $member->admin_renew(); ?>
                    </div>
                    <div class"small-12 columns">
                        <? $member->payment_history(); ?>
                    </div>
                </div>
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
                            <div class = "medium-3 columns"> <h5><?
                            $renewyear = $member_admin->get_year();
                            $prevyear = $renewyear-1;
                                echo "# Renewed ".$prevyear.": <strong>{$member_admin->get_numberOf('RENEWEDP')}</strong>"; ?></h5>
                            </div>
                            <div class = "medium-3 columns"> <h5><?
                                echo "# Renewed ".$renewyear.": <strong>{$member_admin->get_numberOf('RENEWED')}</strong>"; ?></h5>
                            </div>
                            <div class = "medium-3 columns"> <h5><?
                                echo "# Non-Renewed: <strong>{$member_admin->get_numberOf('NOT RENEWED')}</strong>"; ?></h5>
                            </div>
                            <div class = "medium-3 columns"> <h5><?
                                echo "# Revoked: <strong>{$member_admin->get_numberOf('REVOKED')}</strong>"; ?></h5>
                            </div> <?
                        } ?>

            </div> 
        </div> 
        <? if (isset($_GET['task'])) { ?>
            <div class = "medium-12 columns"> <?
                    $member_admin->pending_list(); ?>
            </div> <?
			} else {  ?>
            <div class = "row">
                <div class = "medium-6 columns"> <?
                    $member_admin->search_member_form("ncedadmin"); ?>
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
}

include("../includes/layouts/footer.php"); ?>