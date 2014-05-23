'use strict';

/* App Module */

var editShowroom = angular.module('editShowroom', ['ngRoute']);

editShowroom.config(['$routeProvider', '$interpolateProvider',
    function($routeProvider, $interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
/*
    $routeProvider.
        when('/employee/showrooms/edit/:showroomId', {
            templateUrl: Routing.generate('evt_intranet_showroom_edit'),
            controller: 'EditShowroomCtrl'
        }).
        otherwise({
            redirectTo: '/employee/showrooms'
        });*/
}]);


editShowroom.controller('EditController', function EditController($scope) {
    $scope.example = "HOLA";
    alert("EYYY");
});
