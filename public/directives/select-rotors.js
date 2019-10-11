(function () {
    'use strict';

    angular.module('selectRotors',[])

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


        .directive('adminRotorsSelect', function ($http) {
            return {
                scope:{
                    isshow:"=isshow"
                },
                templateUrl: '/templates/admin/super/select-rotors.html',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

                    $scope.initData = function(){

                        $scope.phrase = '';

                        $scope.searchRotors = [];

                        $scope.selectRotors = [];

                    }


                    $scope.sRotors = function(){

                        $http.post(AppService.url+'/get/search/rotors',{phrase:$scope.phrase})
                            .then(function(res){
                                //console.log(res);
                                $scope.searchRotors = res.data;
                            })

                    }

                    $scope.pushToSelected = function(r){

                        var canAdd = true;

                         angular.forEach($scope.selectRotors,function(item){
                            if(item.id==r.id){
                                canAdd = false;
                            }
                         })

                        if(canAdd){
                            r.logotypes = JSON.parse(r.logotypes)
                            $scope.selectRotors.push(r);
                        }

                        $scope.emitToAgenda();
                    }


                    $scope.removeRotor = function($index){
                        $scope.selectRotors.splice($index,1);
                        $scope.emitToAgenda()
                    }


                    $scope.emitToAgenda = function(){
                        $rootScope.$broadcast('emit-to-crtl',{logotypes:$scope.selectRotors});
                    }

                    $scope.editRotor = function(r,$index){
                        $rootScope.$broadcast('emit-to-update-rotor',{r:r,index:$index});
                    }


                }]
            }})


        .directive('adminRotorsSelectExits', function ($http) {
            return {
                scope:{
                    logotypes:"=logotypes"
                },
                templateUrl: '/templates/admin/super/select-rotors.html',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

                    $scope.initData = function(){


                        $scope.phrase = '';

                        $scope.searchRotors = [];

                        $scope.selectRotors = [];

                    }


                    $rootScope.$on('emit-from-crtl', function(event,attr){
                        $scope.selectRotors = attr.logotypes;
                    })


                    $scope.sRotors = function(){

                        $http.post(AppService.url+'/get/search/rotors',{phrase:$scope.phrase})
                            .then(function(res){
                                //console.log(res);
                                $scope.searchRotors = res.data;
                            })

                    }

                    $scope.pushToSelected = function(r){

                        var canAdd = true;

                        angular.forEach($scope.selectRotors,function(item){
                            if(item.id==r.id){
                                canAdd = false;
                            }
                        })

                        if(canAdd){
                            r.logotypes = JSON.parse(r.logotypes)
                            $scope.selectRotors.push(r);
                        }

                        $scope.emitToAgenda();
                    }


                    $scope.removeRotor = function($index){
                        $scope.selectRotors.splice($index,1);
                        $scope.emitToAgenda()
                    }


                    $scope.emitToAgenda = function(){
                        $rootScope.$broadcast('emit-to-crtl',{logotypes:$scope.selectRotors});
                    }

                    $scope.editRotor = function(r,$index){
                        $rootScope.$broadcast('emit-to-update-rotor',{r:r,index:$index});
                    }


                }]
            }})


})();