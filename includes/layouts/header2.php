<?php  include_once("initialize.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>NCED Online</title>
    <link rel="stylesheet" href="css/app.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>


<body>
  <? session_start(); ?>
  <header class="show-for-medium-up">
  
        <div class="large-1 medium-1 columns"><a href="index.php"><img src="img/logo.png" alt="NCED Online. Click for Home."></a></div>
        <div class="large-4 medium-5 columns"><img src="img/NCED word.gif" alt=""></div>
        <div class="large-3 columns show-for-large-up"></div>
        <div class="large-4 medium-6 columns">
        <? $announcements = new all_announcements(); 
        if ($announcements->num_announce() > 0) {
          ?>
          <ul class="example-orbit-content" data-orbit data-options="animation_speed:500;
                                              animation:slide;
                                              animation_speed:500;
                                              pause_on_hover:false;
                                              animation_speed:500;
                                              navigation_arrows:false;
                                              slide_number: false;
                                              bullets: false">
            <? $announcements->print_announcements(); ?>
          </ul>
        
      <? } ?>
      </div>
  </header>
    <div class="off-canvas-wrap" data-offcanvas>
      <div class="inner-wrap">
        <nav class="tab-bar hide-for-medium-up">
            <section class="left-small">
              <a class="left-off-canvas-toggle menu-icon"><span>NCED</span></a>
            </section>
        </nav>
            <aside class="left-off-canvas-menu">
              <ul class="off-canvas-list">
                <li><a href="index.php">Home</a></li>
                
                <li><a href="#">About</a>
                 <ul>
                 		<li><a href="#">One</a></li>
                    <li><a href="#">Two</a></li>
                    <li><a href="#">Three</a></li>
                    <li><a href="#">Four</a></li>
                  </ul>
                </li>
            
                <li><a href="#">Be an NCED</a>
                 <ul>
                    <li><a href="#">ONE</a></li>
                 <li><a href="#">TWO</a></li>
                 <li><a href="#">THREE</a></li>
                 <li><a href="#">FOUR</a></li>
                  </ul>
                </li>
                <li><a href="https://www.princeton.edu/collegefacebook/">CONTACT</a></li>
                <? 
          if (isset($_SESSION['ncedadmin'])) { ?> 
          <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
               <ul class="dropdown">
                 <li><a href="memberin.php">NCED ADMIN HOME</a></li>
                 <li><a href="#">TWO</a></li>
                 <li><a href="#">THREE</a></li>
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
  	             <li><a href="#">ONE</a></li>
  	             <li><a href="#">TWO</a></li>
                 <li><a href="#">THREE</a></li>
                 <li><a href="#">FOUR</a></li>
  	           </ul>
	          </li>
          <li class="has-dropdown"><a href="#">BE AN NCED</a>
                 <ul class="dropdown">
                    <li><a href="#">ONE</a></li>
                 <li><a href="#">TWO</a></li>
                 <li><a href="#">THREE</a></li>
                 <li><a href="#">FOUR</a></li>
                  </ul>
              </li>
          <li><a href="https://www.princeton.edu/collegefacebook/">CONTACT</a></li>
          <? 
          if (isset($_SESSION['ncedadmin'])) { ?> 
          <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
               <ul class="dropdown">
                 <li><a href="memberin.php">NCED ADMIN HOME</a></li>
                 <li><a href="#">TWO</a></li>
                 <li><a href="#">THREE</a></li>
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
