'use strict';

//Kontroleri

var myAppControllers = angular.module('myAppControllers', []);

myAppControllers.controller('loginCtrl', ['$scope', '$routeParams', 'userFactory', '$location',
  function($scope, $routeParams, userFactory, $location) {
    $scope.loginUser = function() {
      userFactory.login($scope.login, function(data) {
        if(data.poruka=="greska") {
          $location.path('/login');
        }
        else {
          $location.path('/feed');
        }
      });
    }
  }]);

myAppControllers.controller('signupCtrl', ['$scope', '$routeParams', 'usersFactory', '$location',
  function($scope, $routeParams, usersFactory, $location) {
     $scope.createNewUser = function() {
      var base64=$('#newavatar').attr("src");
      $scope.user.photo = base64.replace(/^data:image\/(png|jpg);base64,/, "");
      usersFactory.create($scope.user);
      $location.path('/login');
    }
  }]);

myAppControllers.controller('resetCtrl', ['$scope', '$routeParams', '$location',
  function($scope, $routeParams, $location) {
     $scope.back = function() {
      $location.path('/login');
    }
    $scope.sendNewPass = function() {
    }
  }]);

myAppControllers.controller('changeMailCtrl', ['$scope', '$routeParams', '$location',
  function($scope, $routeParams, $location ) {
    $scope.back = function() {
      $location.path('/editacc');
    }
    $scope.changeMail = function() {
    }
    
}]);

myAppControllers.controller('changePassCtrl', ['$scope','$routeParams', '$location',
  function($scope, $routeParams, $location) {
    $scope.back = function() {
      $location.path('/editacc');
    }
    $scope.changePass = function() {
    }
  }]);

myAppControllers.controller('editAccCtrl', ['$scope', '$routeParams', 'usersFactory', '$location',
  function($scope, $routeParams, usersFactory, $location) {
      
      $scope.photobtn="Upload new photo";
      usersFactory.getLoggedUser(function(data) {
        $scope.user=data;
        console.log(data);
      });
      $scope.uploadPhoto = function () {
        $('#inputFile').click();
        $('#inputFile').change(function (e) { // Promjena vrijednosti dugmeta u naziv uploadovane slike
          var path = $(this).val();
          var path_array = path.split("\\");
          var name = path_array[path_array.length-1];
          $scope.$apply(function(){
            $scope.photobtn = name;
          });
          var input = this;
          var FR = new FileReader();
          FR.onload = function(e) {             
              $scope.$apply(function(){
                $scope.user.photo = e.target.result;
              });
          };       
          FR.readAsDataURL( input.files[0] );
        });

      }
      $scope.changeMail = function () {
        $location.path('/changemail');
      }
      $scope.changePass = function () {
        $location.path('/changepass');
      }
      $scope.apply = function () {
        $scope.user.photo = $scope.user.photo.replace(/^data:image\/(png|jpg);base64,/, "");
        usersFactory.updateUser($scope.user);
        $scope.photobtn="Upload new photo";
        $location.path('/editacc');
      }
      $scope.back = function () {
        $location.path('/settings');
      }
  
    
}]);

myAppControllers.controller('portalsCtrl', ['$scope', '$routeParams', 'portalFactory', '$location',
  function($scope, $routeParams, portalFactory, $location) {
   portalFactory.get(function(data) {
        $scope.portals=data; 
      });
    $scope.back = function() {
      $location.path('/settings');
    }
    $scope.delete = function(portalId) {
      portalFactory.deletePortal({ id: portalId });
      portalFactory.get(function(data) {
        $scope.portals=data;
      });
    }
    $scope.add = function() {
      portalFactory.create($scope.portal);
      portalFactory.get(function(data) {
        $scope.portals=data;
       
      });
    }
  }]);

myAppControllers.controller('settingsCtrl', ['$scope', '$routeParams', 'userFactory', '$location',
  function($scope,$routeParams, userFactory, $location) {
     $scope.logoutUser = function() {
      userFactory.logout();
      $location.path('/login');
    }
    $scope.back = function() {
      $location.path('/feed');
    }
    $scope.choosePortals = function() {
      $location.path('/portals');
    }
    $scope.editAcc = function() {
      $location.path('/editacc');
    }
    
  }]);

myAppControllers.controller('blogCtrl', ['$scope', '$routeParams', 'userFactory', 'BlogList',  '$location',
	function($scope, $routeParams, userFactory, BlogList, $location) {
		$scope.feeds = BlogList.get();
   
    $scope.logoutUser = function() {
      userFactory.logout();
      $location.path('/login');
    }
	}
]);


