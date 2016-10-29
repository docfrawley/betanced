(function () {
'use strict';

angular.module('GetPagesApp', [])
.controller('SetPagesController', SetPagesController)
.service('SetPagesService', SetPagesService);

SetPagesController.$inject = ['SetPagesService'];
function SetPagesController(SetPagesService) {
  var items = this;
    items.page = 1;
    items.last =0;

  items.pageInit = function (num_pages){
    items.last = num_pages;
  };

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

  items.firstPage =function () {
    items.page=1;
  };

  items.lastPage =function () {
    items.page=items.last;
  };

  items.increasePage= function () {
    if (items.page < items.last){
      items.page =parseInt(items.page) + 1;
    }
  };

  items.decreasePage= function () {
    if (items.page > 1){
      items.page =parseInt(items.page) - 1;
    }
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
