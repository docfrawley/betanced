(function () {
'use strict';

angular.module('GoogleMap', [])
.controller('GoogleMapController', GoogleMapController)
.service('GoogleMapService', GoogleMapService);

GoogleMapController.$inject = ['GoogleMapService'];
function GoogleMapController(GoogleMapService) {
  var maps = this;

  // var mapOptions = {
  //       //  zoom: 3,
  //       //  center: new google.maps.LatLng(40.0000, -98.0000),
  //        mapTypeId: google.maps.MapTypeId.ROADMAP
  //    }

  maps.map = new google.maps.Map(document.getElementById('map'));

  maps.makers = [];
  maps.largeInfowindow = new google.maps.InfoWindow();
	maps.bounds = new google.maps.LatLngBounds();
  maps.promise = GoogleMapService.getMapItems();

  maps.promise.then(function(response) {
    maps.spots = response.data;
    console.log("list: ", maps.spots);

    for (var i = 0; i < maps.spots.length; i++) {
      // Get the position from the location array.
      maps.position = maps.spots[i].location;
      console.log("position: ", maps.position);
      var marker = new google.maps.Marker({
        map: maps.map,
        position: maps.position,
        title: maps.spots[i].name,
        content: maps.spots[i].content,
        whatshow: maps.spots[i].whatshow,
        address: maps.spots[i].address,
        animation: google.maps.Animation.DROP,
        id: i
      });
      // Push the marker to our array of markers.
      maps.makers.push(marker);
      // Create an onclick event to open an infowindow at each marker.
      marker.addListener('click', function() {
        populateInfoWindow(this, maps.largeInfowindow);
      });
      maps.bounds.extend(maps.spots[i].location);
    };
    // Extend the boundaries of the map for each marker

    maps.map.fitBounds(maps.bounds);

    function populateInfoWindow(marker, infowindow) {
      // Check to make sure the infowindow is not already opened on this marker.
      if (infowindow.marker != marker) {
        infowindow.marker = marker;
        infowindow.setContent(
          '<div>' +
            marker.title + '<br/>' +
            marker.address + '<br/>' +
            marker.whatshow + '<br/>' +
            marker.content + '<br/>' +
          '</div>');
        infowindow.open(map, marker);
        // Make sure the marker property is cleared if the infowindow is closed.
        infowindow.addListener('closeclick',function(){
          infowindow.setMarker = null;
        });
      }
    };

  })
  .catch(function (error) {
    console.log("Something went terribly wrong.");
  });
};



GoogleMapService.$inject = ['$http'];
function GoogleMapService($http) {
  var service = this;

  service.getMapItems = function() {
    var response = $http({
      method: "GET",
      url: ("http://localhost:8888/betanced/public/getmapfiles.php"),
      params: {
        task: 'markers'
      }
    });
    return response;
  };

}

})();
