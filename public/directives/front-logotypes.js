(function () {
    'use strict';

    angular.module('frontLogotypes',['ngAnimate'])

        .factory('AppService', function($location) {
            return {
                url : $location.protocol()+'://'+$location.host()
            };
        })


        .animation('.repeated-item', function() {
            return {
                enter: function(element, done) {
                    //console.log(element);
                    //console.log(done);
                    // Initialize the element's opacity
                    element.css('opacity', 0);

                    // Animate the element's opacity
                    // (`element.animate()` is provided by jQuery)
                    element.animate({opacity: 1}, done);

                    // Optional `onDone`/`onCancel` callback function
                    // to handle any post-animation cleanup operations
                    return function(isCancelled) {
                        if (isCancelled) {
                            // Abort the animation if cancelled
                            // (`element.stop()` is provided by jQuery)
                            element.stop();
                        }
                    };
                },
                leave: function(element, done) {
                    // Initialize the element's opacity
                    element.css('opacity', 1);

                    // Animate the element's opacity
                    // (`element.animate()` is provided by jQuery)
                    element.animate({opacity: 0}, done);

                    // Optional `onDone`/`onCancel` callback function
                    // to handle any post-animation cleanup operations
                    return function(isCancelled) {
                        if (isCancelled) {
                            // Abort the animation if cancelled
                            // (`element.stop()` is provided by jQuery)
                            element.stop();
                        }
                    };
                },

                // We can also capture the following animation events:
                move: function(element, done) {},
                addClass: function(element, className, done) {},
                removeClass: function(element, className, done) {}
            }
        })


        .directive('showFrontLogotypes', function ($http) {
            return {
                scope:{
                    id:"=id",
                    entitytype:"@entitytype"
                },
                templateUrl: '/templates/front/logotypes/front-logotypes.html',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

                    //console.log($scope.id);
                    //console.log($scope.entitytype);
                    $scope.rotors = null;

                    $scope.getRLogotypes = function(){

                        var defer = $q.defer();

                        $http.post(AppService.url+'/api/get/element/logotypes',{id:$scope.id,type:$scope.entitytype})
                            .then(
                                function(res){
                                    defer.resolve(res.data);
                                },
                                function(){
                                    defer.reject()
                                }
                            )

                        return defer.promise;

                    }


                    $scope.getRLogotypes()
                        .then(function(res){
                            //console.log(res)
                            $scope.rotors = res;
                        })

                }]
            }})


        .directive('rotorLogos', function ($http) {
            return {
                scope:{
                    logotypes:"=logotypes"
                },
                templateUrl: '/templates/front/logotypes/rotor.html',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {


                    $scope.initRotor = function(){

                        console.log($scope.logotypes);
                        $scope.title = $scope.logotypes.rotor_title;
                        $scope.limit = 3;
                        $scope.start = 0;
                        $scope.end = 2;
                        $scope.items = [];

                        $scope.startInterval();


                    }


                    $scope.startInterval = function(){

                        var iter_c = 0;
                        if($scope.logotypes.logotypes.length<=3){
                            iter_c = $scope.logotypes.logotypes.length;
                        }else{
                            iter_c = $scope.limit;
                        }

                        for(var i=$scope.start;i<iter_c;i++){
                            $scope.items.push($scope.logotypes.logotypes[i]);
                        }

                        $interval(function(){


                            if($scope.logotypes.logotypes.length<=3)
                                return;

                            $scope.end++;

                            if($scope.logotypes.logotypes[$scope.end]) {
                                var item = $scope.logotypes.logotypes[$scope.end];
                            }else{
                                $scope.end = 0;
                                var item = $scope.logotypes.logotypes[$scope.start];
                            }

                            $scope.items.splice(($scope.limit-1),1);
                            $scope.items.unshift(item);

                        },4000)
                    }


                }]
            }})


})();