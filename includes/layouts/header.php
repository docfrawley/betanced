<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>NCED Online</title>
    <link rel="stylesheet" href="css/app.css" />
    <script src="js/vendor/modernizr.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
      },
      bar: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
      }
    };

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
          var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> <br/>" + address;
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon
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

</head>


<body onload="load()">
  <? session_start(); ?>
  
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
          if (isset($_SESSION['ncednumber'])) { ?> 
          <li class="has-dropdown"><a href="#">MEMBER PAGES</a>
               <ul class="dropdown">
                 <li><a href="memberin.php">MEMBER HOME</a></li>
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
          if (isset($_SESSION['ncednumber'])) { ?> 
          <li class="has-dropdown"><a href="#">MEMBER PAGES</a>
               <ul class="dropdown">
                 <li><a href="memberin.php">MEMBER HOME</a></li>
                 <li><a href="#">TWO</a></li>
                 <li><a href="#">THREE</a></li>
                 <li><a href="logout.php">LOGOUT</a></li>
               </ul>
          </li>
          <? } elseif (isset($_SESSION['ncedadmin'])) { ?> 
          <li class="has-dropdown"><a href="#">ADMIN PAGES</a>
               <ul class="dropdown">
                 <li><a href="ncedadmin.php">MEMBERSHIP</a></li>
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
