'use strict';

/* App Module */

var editShowroom = angular.module('editShowroom', ['ngRoute', 'editShowroomServices']);

editShowroom.config(['$routeProvider', '$interpolateProvider', '$locationProvider', '$httpProvider',
    function($routeProvider, $interpolateProvider, $locationProvider, $httpProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
        $locationProvider.html5Mode(true);
}]);

editShowroom.controller('EditController', ['$scope', '$http', '$location', 'Showroom',
    function($scope, $http, $location, Showroom){
        var arrayUrl = $location.path().split("/");
        var num = arrayUrl.length;
        var id = arrayUrl[num - 1];

        //Get data from showroom
        Showroom.getShowroomById({id: id}, function(data) {
            $scope.evtId = data.evt_id;
            $scope.evtName = $scope.showroomName = data.name;
            $scope.evtDesc = data.description;
        });

        //Active edition
        $http({method: 'PATCH', url: '/api/showrooms/'+id+'/startedition'}).
            success(function() {}).
            error(function(data, status) {
                $scope.showResultEditing(status, "");
            });

        // Control for submit
        $scope.sendFormData = function() {
            if (!$scope.evtId){
                $scope.showResultEditing(status, "Error. There isn't ID showroom");
                return false;
            }
            if (!$scope.evtName){
                $scope.showResultEditing(status, "Please, fill the Name");
                return false;
            }
            if (!$scope.evtDesc){
                $scope.showResultEditing(status, "Please, fill the Description");
                return false;
            }
            $http({method: 'PATCH', url: '/api/showrooms/'+id+'/name', data:{name: $scope.evtName}}).
                success(function(data, status) {
                    $scope.showResultEditing(status, "");
                }).
                error(function(data, status) {
                    $scope.showResultEditing(status, "");
                });
            $http({method: 'PATCH', url: '/api/showrooms/'+id+'/description', data:{description: $scope.evtDesc}}).
                success(function(data, status) {
                    $scope.showResultEditing(status, "");
                }).
                error(function(data, status) {
                    $scope.showResultEditing(status, "");
                });
        }

        //Status list
        $scope.showResultEditing = function(status, alertMsg) {
            var text = "";
            var success = 0;
            switch (status) {
                case 403:
                    text = "You don't have permission to edit";
                    break;
                case 412:
                    text = "Showroom is reviewed. You cannot edit yet";
                    break;
                case 200:
                case 204:
                    success = 1;
                    text = "Your changes have been performed successfully!";
                    break;
                default:
                    text = alertMsg;
            };

            if (success == 0){
                $(".alert-success").css( "display", "none" );
                $(".alert-danger").css( "display", "block" );
                $scope.errorMsg = text;
            }else{
                $(".alert-danger").css( "display", "none" );
                $(".alert-success").css( "display", "block" );
                $scope.successMsg = text;
            }

        }
}]);
