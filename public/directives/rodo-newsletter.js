(function () {
    'use strict';

    angular.module('rodo.newsletter',[])

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


        .directive('rodoCloudNewsletter', function ($http) {
            return {
                scope:{
                    isshow:"=isshow"
                },
                templateUrl: '/templates/front/newsletter/rodo-cloud.html',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {


                    $scope.initData = function(boolean){

                        $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;
                        $scope.raporttext = '';
                        if(boolean) {
                            //$scope.show_raport_email_cloud = false;
                        }
                        $scope.not_checked_by_client = true;


                        $scope.data = {
                            name:'',
                            surname:'',
                            email:'',
                            reg:false
                        };

                        $scope.valid = {
                            name:false,
                            surname:false,
                            email:false
                        };

                        $scope.classes = {
                            name:'',
                            surname:'',
                            email:'',
                            reg:''
                        };

                    }


                    $scope.checkName = function(){

                        if($scope.data.name.length>1){
                            $scope.classes.name = 'form-success';
                            $scope.valid.name = true;
                        }else{
                            $scope.classes.name = 'form-error';
                            $scope.valid.name = false;
                        }


                    }

                    $scope.checkSurname = function(){

                        if($scope.data.name.length>1){
                            $scope.classes.surname = 'form-success';
                            $scope.valid.surname = true;
                        }else{
                            $scope.classes.surname = 'form-error';
                            $scope.valid.surname = false;
                        }

                    }

                    $scope.$watch('isshow', function(nVal,oVal){
                        if(nVal == false){
                            $scope.show_raport_email_cloud = false;
                            $scope.initData(false);
                        }
                    })


                    $scope.checkFullEmail = function(){

                        if($scope.emailregex.test($scope.data.email)){

                            $scope.checkEmail()
                                .then(
                                    function(response){
                                        console.log(response);
                                        //$scope.raporttext = response.message;
                                        //$scope.show_raport_email_cloud = true;

                                        if(response.status=='not_exist'){
                                            $scope.classes.email = 'form-success';
                                            $scope.valid.email = true;
                                        }else if(response.status=='exist_not_subscribe'){
                                            $scope.classes.email = 'form-success';
                                            $scope.valid.email = true;
                                        }else if(response.status=='exist_not_confirmed_subscribe'){
                                            $scope.classes.email = 'form-success';
                                            $scope.valid.email = true;
                                        }else if(response.status=='exist_subscribe'){
                                            $scope.classes.email = 'form-success';
                                            //$scope.valid.email = false;
                                        }


                                    },
                                    function(){
                                        $scope.loadingclass = '';
                                    }
                                )


                        }else{
                            $scope.classes.email = 'form-error';
                            $scope.valid.email = false;
                        }

                    }

                    $scope.$watch('data.reg', function(nVal,oVal){

                        if(!$scope.not_checked_by_client){

                            if($scope.classes) {
                                if (nVal) {
                                    $scope.classes.reg = '';
                                } else {
                                    $scope.classes.reg = 'form-error';
                                }
                            }

                        }else{
                            $scope.not_checked_by_client = false;
                        }


                    });
                    $scope.respdata = {};
                    $scope.checkEmail = function(){

                        var deferred = $q.defer();

                        $http.post(AppService.url+'/api/check/is/member/email/newsletter', {email:$scope.data.email})
                            .then(
                                function successCallback(response) {
                                    console.log(response.data);
                                    $scope.respdata = response.data
                                    deferred.resolve(response.data);
                                },
                                function errorCallback(reason){
                                    deferred.reject();
                                }
                            )

                        return deferred.promise;

                    }





                    $scope.addEmailToBase = function(action){

                        var deferred = $q.defer();

                        $http.put(AppService.url+'/api/member/newsletter/subscribe',
                            {email:$scope.data.email,
                             name:$scope.data.name,
                             surname:$scope.data.surname,
                             action:action})
                            .then(
                                function successCallback(response) {
                                    console.log('wprow', response);
                                    $scope.loadingclass = '';
                                    deferred.resolve(1);
                                    //$timeout(function(){$scope.initData(false)},5000);
                                },
                                function errorCallback(reason){
                                    //console.log(reason);
                                    deferred.reject();
                                }
                            )

                        return deferred.promise;

                    }


                    $scope.subscribeAdd = function(){

                        $scope.checkName();
                        $scope.checkSurname();
                        $scope.checkFullEmail();

                        if(!$scope.data.reg){
                            $scope.classes.reg = 'form-error';
                        }else{
                            $scope.classes.reg = '';
                        }

                        $scope.loadingclass = 'active-loading';

                        if($filter('checkfalse')($scope.valid) && $scope.data.reg) {

                            $scope.checkEmail()
                                .then(
                                    function (response) {
                                        //console.log(response);
                                        $scope.raporttext = response.message;
                                        $scope.show_raport_email_cloud = true;

                                        if (response.status == 'not_exist') {
                                            $scope.addEmailToBase(response.status);
                                        } else if (response.status == 'exist_not_subscribe') {
                                            $scope.addEmailToBase(response.status);
                                        } else if (response.status == 'exist_not_confirmed_subscribe') {
                                            $scope.addEmailToBase(response.status);
                                        } else if (response.status == 'exist_subscribe') {
                                            $scope.loadingclass = '';
                                        }



                                    },
                                    function () {
                                        $scope.loadingclass = '';
                                    }
                                )

                        }

                    }

                }]
            }})

        .directive('rodoNewsletter', function ($http) {
            return {
                templateUrl: '/templates/front/newsletter/rodo.html',
                controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

                    $scope.initData = function(){

                        $scope.isShow = false;

                    }


                }]
            }})
})();