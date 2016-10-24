(function () {
'use strict';

angular.module('GetPagesApp', [])
.controller('SetPagesController', SetPagesController)
.service('SetPagesService', SetPagesService);

SetPagesController.$inject = ['SetPagesService'];
function SetPagesController(SetPagesService) {
  var items = this;
    items.page = 1;
    console.log(items.page);
  var promise = SetPagesService.getAjaxItems(items.page);

  promise.then(function(response) {
    items.list = response.data;
  })
  .catch(function (error) {
    console.log("Something went terribly wrong.");
  });

  items.getNewPage = function (newPage) {
    var promise = SetPagesService.getAjaxItems(newPage);

    promise.then(function (response) {
      items.list = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
  };

}

SetPagesService.$inject = ['$http'];
function SetPagesService($http) {
  var service = this;

  service.getAjaxItems = function(the_page) {
    var response = $http({
      method: "GET",
      url: ("http://localhost:8888/betanced/public/getajaxfiles.php"),
      params: {
        page: the_page
      }
    });
    return response;
  };

}

})();
