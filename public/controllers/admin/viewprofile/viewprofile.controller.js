var app = angular.module('app',['ngSanitize', 'ngRoute', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select', 'ui.sortable'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);

app.run(function(editableOptions, editableThemes) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'

});

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});


app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/viewprofiles/search.html'
    };
});


app.directive('addNewViewProfile', function($http, AppService, $rootScope) {
    return {
        templateUrl: '/templates/admin/super/viewprofiles/add-new.html',
        link: function (scope, element, attributes) {

            scope.is_add_cloud_open = false;

            scope.$on('add_new_profile', function (event, args) {

                scope.is_add_cloud_open = true;
                console.log(args.type.name);

            })


            scope.hideCloud = function(){

                scope.is_add_cloud_open = false;

            }

        }
    }

});



app.filter('checkfalse', function() { return function(obj) {

    if (!(obj instanceof Object)) return false;

    var bool = true;

    Object.keys(obj).forEach(function(key) {

        if(!obj[key]){
            bool = false;
        }

    });

    return bool;
}});


app.filter('add_slashes_to_icon', function() { return function(coll) {

    var ar = [];
    angular.forEach(coll, function(item){

        ar.push('/'+item);

    });


    return ar;

}});

app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });

}]);




app.controller('ViewProfilesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {


    $scope.initData = function(){

        $scope.viewprofiles = [];
        $scope.editprofile = [];

        $scope.entity_types = [
            {
                name:'Artykuły',
                id:'content'
            },
            {
                name:'Wydarzenia',
                id:'agenda'
            },
            {
                name:'Ogłoszenia',
                id:'publication'
            }
        ];

        $scope.current_entity_type = $scope.entity_types[0];



    }

    $scope.$watch('current_entity_type', function(nVal,oVal){
        $scope.getAllViewProfiles()
            .then(
                function(response){
                    $scope.prepareEditData()
                        .then(
                            function(response){

                                $scope.edit_data = response;
                                $scope.edit_data.icons = $filter('add_slashes_to_icon')($scope.edit_data.icons);


                                angular.forEach($scope.viewprofiles, function(item, iter){

                                    $scope.viewprofiles[iter].edit_data = $scope.edit_data;

                                });

                                console.log($scope.viewprofiles);

                            }
                        )
                }
            );
    });


    $scope.getAllViewProfiles = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/view/profiles/'+$rootScope.lang+'/'+$scope.current_entity_type.id)
            .then(
                function successCallback(response){
                    //console.log(response);

                    $scope.viewprofiles = [];

                    angular.forEach(response.data, function(item, iter){
                        item.params = JSON.parse(item.params);
                        $scope.viewprofiles.push(item);
                    });
                    //console.log($scope.viewprofiles);
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }

    $scope.prepareEditData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/color/and/icons')
            .then(
                function successCallback(response){
                    //console.log(response);
                    deferred.resolve(response.data);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.changeColorTemplate = function(index,color,id,suffix){

        var deferred = $q.defer();

        $http.put(AppService.url+'/change/vie/profile/color', {color:color,id:id,suffix:suffix,entity:$scope.current_entity_type})
            .then(
                function successCallback(response){
                    console.log(response);
                    deferred.resolve(response.data);
                    $scope.viewprofiles[index].params.color = color;
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }


    $scope.changeIconTemplate = function(index,icon,id,suffix){

        var deferred = $q.defer();

        $http.put(AppService.url+'/change/vie/profile/icon', {icon:icon,id:id,suffix:suffix,entity:$scope.current_entity_type})
            .then(
                function successCallback(response){
                    console.log(response);
                    deferred.resolve(response.data);
                    $scope.viewprofiles[index].params.icon = icon;
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }


    $scope.checkTemplateName = function(){
        //var d = $q.defer();
        //d.resolve('lsd');
        //d.resolve();
        //return d.promise;
    }


    $scope.changeTemplateName = function(data,id,suffix){
        console.log(data,id);

        var deferred = $q.defer();

        $http.put(AppService.url+'/change/vie/profile/name', {name:data,id:id,suffix:suffix,entity:$scope.current_entity_type})
            .then(
                function successCallback(response){
                    console.log(response);
                    deferred.resolve(response.data);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }



    $scope.checkTemplateSuffix = function(data,id,old_suffix){

        var d = $q.defer();

        $http.put(AppService.url+'/check/is/suffix/exits', {suffix:data,id:id,old_suffix:old_suffix})
            .then(
                function successCallback(response){
                    console.log(response);
                    if(response.data.success){
                        d.resolve();
                    }else{
                        d.resolve('suffix już istnije');
                    }

                },
                function errorCallback(reason){
                    //console.log(reason);
                    d.reject();
                }
            )

        return d.promise;

    }


    $scope.changeTemplateSuffix = function(data,id){

        //var d = $q.defer();

        $http.put(AppService.url+'/change/vie/profile/suffix', {suffix:data,id:id,entity:$scope.current_entity_type})
            .then(
                function successCallback(response){
                    console.log(response);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    //d.reject();
                }
            )

        //return d.promise;

    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.addNewProfileOpenCloud = function(){

        $rootScope.$broadcast('add_new_profile',{type:$scope.current_entity_type})

    }


}]);




app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/viewprofiles/master.html',
        controller: 'ViewProfilesController'
    });
    //when('/:id', {
    //    templateUrl: '/templates/stockroom.html',
    //    controller: 'StockRoomController'
    //}).
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});