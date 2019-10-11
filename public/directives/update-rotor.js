(function () {
    'use strict';

    angular.module('updateRotor',['ui.sortable','ngFileUpload'])

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


        .directive('updateRotorData', function ($http) {
            return {
                scope:{

                },
                templateUrl: '/templates/admin/super/update-rotor.html',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout','Upload', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout,Upload) {

                    $scope.rotor = null;
                    $scope.index = null;
                    $scope.logo = null;
                    $scope.disk = 'logotypes';

                    $rootScope.$on('emit-to-update-rotor',function(event,attr){

                        //console.log(attr);
                        $scope.rotor = attr.r;
                        $scope.index = attr.index;

                        $scope.classes = {
                            name:'start',
                            rotor_title:'start'
                        };

                    })


                    ////////////////////////////////////////////////////////////////////////////////////////////////



                    $scope.removeLogo = function($index){

                        $scope.rotor.logotypes.splice($index,1);

                    }


                    $scope.checkName = function(){

                        if($scope.rotor.name.length>0){

                            $scope.classes.name = '';

                        }else{

                            $scope.classes.name = 'has-error';

                        }

                    }


                    $scope.checkRotorTitle = function(){

                        if($scope.rotor.rotor_title.length>0){

                            $scope.classes.rotor_title = '';

                        }else{

                            $scope.classes.rotor_title = 'has-error';

                        }

                    }


                    $scope.saveLogosRotor = function(){

                        $scope.checkName();
                        $scope.checkRotorTitle();

                        if($scope.classes.rotor_title=='' && $scope.classes.name=='') {

                            $http.post(AppService.url + '/update/logotypes/data', {
                                    id: $scope.rotor.id,
                                    logotypes: $scope.rotor.logotypes,
                                    data: $scope.rotor
                                })
                                .then(function (res) {
                                    console.log(res);
                                    $scope.rotor = null;
                                })

                        }

                    }


                    ////////////////////////////////////Upload File///////////////////////////////////////////////////////



                    $scope.$watch('logo', function () {


                        if($scope.logo){
                            $scope.uploadFileLogo($scope.logo);
                        }

                    });


                    $scope.uploadFileLogo = function (file) {

                        //console.log($scope.file.name.split('.').pop());
                        var local = new Date();
                        $scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();

                        if(file) {

                            Upload.upload({
                                url: AppService.url + '/resources/upload/logotype',
                                fields: { disk: $scope.disk, suffix:$scope.logo.name.split('.').pop()},
                                file: file
                            }).progress(function (evt) {

                                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                                $scope.log = 'progress: ' + progressPercentage + '% ' +
                                    evt.config.file.name + '\n' + $scope.log;

                                //$log.info(progressPercentage);
                                $scope.progress = progressPercentage;

                            }).success(function (data, status, headers, config) {

                                //$log.info($scope.uploaded);
                                $scope.progress = 0;

                                $scope.rotor.logotypes.unshift({link:'',logo_uri:data.real_path});

                                //$timeout(function() {
                                //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                                //});
                            }).error(function (data, status, headers, config) {
                                //$log.info(data);
                            });

                        }

                    };


                }]
            }})




})();