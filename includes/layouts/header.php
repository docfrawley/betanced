<?include_once("initialize.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width" />
<title>NCED Online</title>
    <link rel="stylesheet" href="css/app.css" />
    <link rel="stylesheet" href="css/foundation-datepicker.min.css">
    <script src="js/vendor/modernizr.js"></script>
</head>


<body >
  <? session_start();
  $fadmin = new files_object();?>

    <div class="off-canvas-wrap" data-offcanvas>
      <div class="inner-wrap">
        <nav class="tab-bar hide-for-medium-up">
            <section class="left-small">
              <a class="left-off-canvas-toggle menu-icon" href="#"><span>NCED</span></a>
            </section>
        </nav>
            <aside class="left-off-canvas-menu">
              <ul class="off-canvas-list">
                <li><a href="index.php">Home</a></li>

                <li><a href="#">ABOUT</a>
                 <ul>
                   <li><a href="<? echo $fadmin->get_path('BENEFITS OF NCED');?>"
                     target="_blank">BENEFITS OF NCED</a></li>
                   <li><a href="<? echo $fadmin->get_path('BY-LAWS'); ?>"
                     target="_blank">BY-LAWS</a></li>
                   <li><a href="<? echo $fadmin->get_path('STANDING RULES'); ?>"
                     target="_blank">STANDING RULES</a></li>
                   <li><a href="board.php">BOARD OF DIRECTORS</a></li>
                   <li><a href="<? echo $fadmin->get_path('HISTORY'); ?>"
                     target="_blank">HISTORY</a></li>
                   <li><a href="<? echo $fadmin->get_path('WHY NCED?'); ?>"
                     target="_blank">WHY NCED?</a></li>
                   <li><a href="<? echo $fadmin->get_path('CE REQUIREMENTS'); ?>"
                     target="_blank">CE REQUIREMENTS</a></li>
                  </ul>
                </li>

                <li><a href="#">EXAMINATIONS</a>
                 <ul>
                    <li><a href="tresults.php">RESULTS</a></li>
                    <li><a href="<? echo $fadmin->get_path('STUDY GUIDE'); ?>"
                      target="_blank">STUDY GUIDE</a></li>
                  </ul>
                </li>
                <li><a href="contact.php">CONTACT</a></li>
          <?
          if (isset($_SESSION['ncednumber'])) { ?>
          <li class="has-dropdown"><a href="#">MEMBER PAGES</a>
               <ul class="dropdown">
                 <li><a href="memberin.php">MEMBER HOME</a></li>
                 <li><a href="registry.php">REGISTRY</a></li>
                 <li><a href="receipts.php">PAYMENT HISTORY</a></li>
                 <li><a href="ceupage.php">CEU PAGE</a></li>
                 <li><a href="newsletters.php">NEWSLETTERS</a></li>
                 <li><a href="logout.php">LOGOUT</a></li>
               </ul>
          </li>
          <? } elseif (isset($_SESSION['ncedadmin'])) { ?>
          <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
               <ul class="dropdown">
                 <li><a href="ncedadmin.php">MEMBERSHIP</a></li>
                 <li><a href="ncedboard.php">NCED BOARD</a></li>
                 <li><a href="testadmin.php">TEST SITES</a></li>
                 <li><a href="tresultsadmin.php">TEST RESULTS</a></li>
                 <li><a href="announceadmin.php">ANNOUNCEMENTS</a></li>
                 <li><a href="reportsadmin.php">REPORTS</a></li>
                 <li><a href="filesadmin.php">UPLOAD FILES</a></li>
                 <li><a href="ncedpdfs.php">ADMIN PDFS</a></li>
                 <li><a href="logout.php">LOGOUT</a></li>
               </ul>
            </li>
          <? } elseif (isset($_SESSION['memberadmin'])) { ?>
          <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
               <ul class="dropdown">
                 <li><a href="ncedadmin.php">MEMBERSHIP</a></li>
                 <li><a href="reportsadmin.php">REPORTS</a></li>
                 <li><a href="logout.php">LOGOUT</a></li>
               </ul>
            </li>
          <? } elseif (isset($_SESSION['examadmin'])) { ?>
          <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
               <ul class="dropdown">
                 <li><a href="testadmin.php">TEST SITES</a></li>
                 <li><a href="tresultsadmin.php">TEST RESULTS</a></li>
                 <li><a href="logout.php">LOGOUT</a></li>
               </ul>
            </li>
          <? } else { ?>
          <li><a href="login.php">MEMBER LOGIN</a></li> <?
          } ?>
              </ul>
            </aside>
            <a class="exit-off-canvas"></a>

    <!-- top bar code -->
    <div class="sticky">
    <nav class="top-bar show-for-medium-up" data-topbar role="navigation" data-options="sticky_on: large">
      <ul class="title-area">
        <li class="name">
          <h1><a href="index.php">NCED Online</a></h1>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
      </ul>
      <section class="top-bar-section">
        <ul class="right">
            <li class="has-dropdown"><a href="#">ABOUT</a>
               <ul class="dropdown">
                 <li><a href="<? echo $fadmin->get_path('BENEFITS OF NCED');?>"
                   target="_blank">BENEFITS OF NCED</a></li>
                 <li><a href="<? echo $fadmin->get_path('BY-LAWS'); ?>"
                   target="_blank">BY-LAWS</a></li>
                 <li><a href="<? echo $fadmin->get_path('STANDING RULES'); ?>"
                   target="_blank">STANDING RULES</a></li>
                 <li><a href="board.php">BOARD OF DIRECTORS</a></li>
                 <li><a href="<? echo $fadmin->get_path('HISTORY'); ?>"
                   target="_blank">HISTORY</a></li>
                 <li><a href="<? echo $fadmin->get_path('WHY NCED?'); ?>"
                   target="_blank">WHY NCED?</a></li>
                 <li><a href="<? echo $fadmin->get_path('CE REQUIREMENTS'); ?>"
                   target="_blank">CE REQUIREMENTS</a></li>
               </ul>
            </li>

            <li class="has-dropdown"><a href="#">EXAMINATIONS</a>
              <ul class="dropdown">
                <li><a href="tresults.php">RESULTS</a></li>
             <li><a href="<? echo $fadmin->get_path('STUDY GUIDE'); ?>"
               target="_blank">STUDY GUIDE</a></li>
              </ul>
            </li>

            <li><a href="contact.php">CONTACT</a></li>
          <?
          if (isset($_SESSION['ncednumber'])) { ?>
          <li class="has-dropdown"><a href="#">MEMBER PAGES</a>
               <ul class="dropdown">
                 <li><a href="memberin.php">MEMBER HOME</a></li>
                 <li><a href="registry.php">REGISTRY</a></li>
                 <li><a href="receipts.php">PAYMENT HISTORY</a></li>
                 <li><a href="ceupage.php">CEU PAGE</a></li>
                 <li><a href="newsletters.php">NEWSLETTERS</a></li>
                 <li><a href="logout.php">LOGOUT</a></li>
               </ul>
          </li>
          <? } elseif (isset($_SESSION['ncedadmin'])) { ?>
          <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
               <ul class="dropdown">
                 <li><a href="ncedadmin.php">MEMBERSHIP</a></li>
                 <li><a href="ncedboard.php">NCED BOARD</a></li>
                 <li><a href="testadmin.php">TEST SITES</a></li>
                 <li><a href="tresultsadmin.php">TEST RESULTS</a></li>
                 <li><a href="emailadmin.php">EMAIL ADMIN</a></li>
                 <li><a href="announceadmin.php">ANNOUNCEMENTS</a></li>
                 <li><a href="reportsadmin.php">REPORTS</a></li>
                 <li><a href="filesadmin.php">UPLOAD FILES</a></li>
                 <li><a href="ncedpdfs.php">ADMIN PDFS</a></li>
                 <li><a href="logout.php">LOGOUT</a></li>
               </ul>
            </li>
            <? } elseif (isset($_SESSION['memberadmin'])) { ?>
            <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
                 <ul class="dropdown">
                   <li><a href="ncedadmin.php">MEMBERSHIP</a></li>
                   <li><a href="reportsadmin.php">REPORTS</a></li>
                   <li><a href="logout.php">LOGOUT</a></li>
                 </ul>
              </li>
            <? } elseif (isset($_SESSION['examadmin'])) { ?>
            <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
                 <ul class="dropdown">
                   <li><a href="testadmin.php">TEST SITES</a></li>
                   <li><a href="tresultsadmin.php">TEST RESULTS</a></li>
                   <li><a href="logout.php">LOGOUT</a></li>
                 </ul>
              </li>
            <? } else { ?>
            <li><a href="login.php">MEMBER LOGIN</a></li> <?
            } ?>
        </ul>
      </section>
    </nav>
    </div>
