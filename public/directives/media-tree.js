(function () {
    'use strict';

    angular.module('mediaTree',[])

        .factory('AppService', function($location) {
            return {
                url : $location.protocol()+'://'+$location.host()
            };
        })


        .filter('checkfalse', function() { return function(obj) {

            if (!(obj instanceof Object)) return false;

            var bool = true;

            Object.keys(obj).forEach(function(key) {

                if(!obj[key]){
                    bool = false;
                }

            });

            return bool;
        }})





        .directive('mediaTreeView', function ($http) {
            return {
                scope:{
                    three:"=three"
                },
                templateUrl: '<media-tree-element-view></media-tree-element-view>',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

                    $scope.initData = function(){




                    }


                }]
            }})


        .directive('mediaTreeElementView', function ($http) {
            return {
                scope:{
                    element:"=element"
                },
                templateUrl: '<',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

                    $scope.initData = function(){




                    }


                }]
            }})


})();