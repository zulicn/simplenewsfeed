'use strict';

var myApp = angular.module('myApp', [
  'ngRoute',
  'myAppControllers',
  'blogServices',
  'restServices'
]);

myApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'partials/login.html',
        controller: 'loginCtrl'
      }).
      when('/feed', {
        templateUrl: 'partials/blog.html',
        controller: 'blogCtrl'
      }).
       when('/signup', {
        templateUrl: 'partials/signup.html',
        controller: 'signupCtrl'
      }).
      when('/reset', {
        templateUrl: 'partials/reset.html',
        controller: 'resetCtrl'
      }).
      when('/changemail', {
        templateUrl: 'partials/changemail.html',
        controller: 'changeMailCtrl'
      }).
      when('/changepass', {
        templateUrl: 'partials/changepass.html',
        controller: 'changePassCtrl'
      }).
      when('/editacc', {
        templateUrl: 'partials/editacc.html',
        controller: 'editAccCtrl'
      }).
      when('/portals', {
        templateUrl: 'partials/portals.html',
        controller: 'portalsCtrl'
      }).
      when('/settings', {
        templateUrl: 'partials/settings.html',
        controller: 'settingsCtrl'
      }).      
      otherwise({
        redirectTo: '/'
      });
  }]);