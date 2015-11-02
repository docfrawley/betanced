<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); ?>



<div class="row">
	<div class="small-12 columns">
		<img src="img/header.png" alt="header" />
	</div>
</div>
<div class="row">
    <div class="medium-5 columns">
		<ul class="example-orbit-content" data-orbit data-options="animation_speed:500;
                                              animation:fade;
                                              animation_speed:500;
                                              pause_on_hover:false;
                                              animation_speed:500;
                                              navigation_arrows:false;
                                              slide_number: false;
                                              bullets: false">
            <li><img src="img/1stpic.gif" alt="slide 1" /></li>
            <li><img src="img/2ndpic.gif" alt="slide 2" /></li>
            <li><img src="img/3rdpic.gif" alt="slide 3" /></li>
            <li><img src="img/4thpic.gif" alt="slide 4" /></li>
            <li><img src="img/5thpic.gif" alt="slide 5" /></li>
         </ul>
	</div>
	<div class="medium-3 columns">
		<div class="panel">
			NATIONAL REGISTRY
		</div>
		<div class="panel">
			LEARN MORE
		</div>
		<div class="panel">
			SIGN UP NOW
			EXAM DATES & LOCATIONS
		</div>
	</div>
  <div class="medium-4 columns show-for-medium-up">
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
<div class="row">
	<div class="medium-8">
		<div id="map" style="height: 350px"></div>

    <script type="text/javascript">
    //<![CDATA[

    

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(45.6145, -106.3418),
        zoom: 3,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      downloadUrl("phpsqlajax_genxml.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var content = markers[i].getAttribute("content");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> <br/>" + address + "<br/>" + content;
          var marker = new google.maps.Marker({
            map: map,
            position: point,
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>

  </script>
	</div>
	<div class="medium-4">
	</div>
</div>




<? include("../includes/layouts/footer.php"); ?>
