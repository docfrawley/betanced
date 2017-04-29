<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); ?>



<div class="row custom-row-class">
	<div class="medium-6 columns">
		<img src="img/header.png" alt="header" />
    <div class="welcome-panel"><h1 class="text-center">Welcome to NCED</h1>The premier national credential for special education assessment professionals who hold high standards of practice</div>
	</div>
  <div class="medium-5 columns left">
    <h2 class="text-center title-color">NCED NEWS</h2>
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
</div>
<div class="row custom-row-class">
	<div class="medium-6 columns" ng-app="GoogleMap" ng-controller="GoogleMapController as maps">
		<p id="map" style="height: 350px"></p></div>




	<div class="medium-5 columns left">
    <h2 class="text-center title-color">TEST SITES</h2>
        <?
          $testSites = new all_maps();
          $testSites->list_sites();
        ?>
        <div class="row">
          <div class="small-6 columns">
            <button class="button radius expand test-button" data-reveal data-reveal-id="exams">REGISTER FOR EXAM</button>
          </div>
          <div class="small-6 columns">
           <button class="button expand radius certification-button" data-reveal data-reveal-id="requirements">CERTIFICATION REQUIREMENTS</button>
          </div>
        </div>
	</div>


	    <script async defer
	        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDue6uOBVjZ_POf3o152KQSO5yt0Ht1FY&v=3&callback=initMap">
	    </script>

  </div>

    <!-- modal windows -->
    <div id="exams" class="reveal-modal" data-reveal>
        <p>To register for an exam please download the application below that applies to you. Thank you.</p>

        <a href="pdfs/general11.pdf"><i class="fi-download"></i>&nbspGeneral Application</a><br/>
        <a href="pdfs/fasttrack11.pdf"><i class="fi-download"></i>&nbspFasttrack Application</a><br/>
        <a href="pdfs/retake11.pdf"><i class="fi-download"></i>&nbspRetake Application</a><br/>
        <br/><br/>
        <a class="close-reveal-modal">&#215;</a>
    </div>

  <div id="requirements" class="reveal-modal" data-reveal>
    <i class="fi-checkbox"></i>  Advanced degree (master’s or doctorate) in special education or related field.<br/>
    <i class="fi-checkbox"></i>  State-licensed in  an  education (non-psychology)  field.<br/>
    <i class="fi-checkbox"></i>  Minimum  of  two years teaching  experience  in  a private or  public  school.<br/>
    <i class="fi-checkbox"></i>  Minimum  of  two years assessment  experience  in  a private or  public  setting.<br/>
    <i class="fi-checkbox"></i>  Two  letters of  reference affirming competency  in  special education assessment.<br/>
    <i class="fi-checkbox"></i>  Membership in  CEC’s Council for Educational Diagnostic  Services  (CEDS).<br/>
    <i class="fi-checkbox"></i>  Passing  score on  the NCED  examination*.<br/><br/>
    <a href="pdfs/ncedreq.pdf"><strong>DOWNLOAD CHECKLIST</strong></a><br/><br/>
    <i class="fi-alert"></i>  NOTE: It  is  recommended that  ALL requirements  be  completed
  at  least four weeks prior  to  the exam  date.<br/>

    <a class="close-reveal-modal">&#215;</a>
  </div>


<? include("../includes/layouts/footer.php"); ?>
