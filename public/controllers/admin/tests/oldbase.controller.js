/**
 * Created by rradecki on 2017-04-28.
 */
var app = angular.module('app',['ngSanitize'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);


app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});

app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
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


app.filter('unique', function() {
    return function(collection, keyname) {
        var output = [],
            keys = [];
        angular.forEach(collection, function(item) {
            var key = item[keyname];
            if(keys.indexOf(key) === -1) {
                keys.push(key);
                output.push(item);
            }
        });
        return output;
    };
});

app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });

}]);


app.controller('OldBaseController',['$scope', '$http', function($scope, $http) {
$scope.oldExhibitions = function() {
    $http.get('/get/oldexhib')
        .then(
            function successCallback(response) {
                console.log(response.data);
                $scope.items = [];
                angular.forEach(response.data,function(item){
                    if(item.post_parent == 0) {
                        $scope.items.push(item);
                    }
                })
            },
            function errorCallback(reason) {
                //console.log(reason);
            }
        )
}


    function unique(collection, keyname) {
        var output = [],
            keys = [];
        angular.forEach(collection, function(item) {
            var key = item[keyname];
            if(keys.indexOf(key) === -1) {
                keys.push(key);
                output.push(item);
            }
        });
        return output;
    };

    $scope.oldItems = function(type) {
    console.log('type',type)
        $http.get('/get/olditems/'+type)
            .then(
                function successCallback(response) {
                    $scope.items = [];
                    items = [];

                   angular.forEach(response.data,function(item,idx){
                        if(idx > 0 && response.data[idx-1].ID == item.ID){
                            item.datastart = response.data[idx-1].dat
                            item.dataend = item.dat
                            items.push(item);
                        }

                    })

                    //console.log(response.data)
                    $scope.items = unique(items,'attach_parent');
                    //$scope.items = items;
                    console.log($scope.items);
                },
                function errorCallback(reason) {
                    //console.log(reason);
                }
            )
    }

    $scope.oldEditions = function(){
        $http.get('/get/oldeditions')
            .then(
                function successCallback(response) {
                    // $scope.items = response.data;
                    $scope.items = [];
                    angular.forEach(response.data,function(item,idx){

                        item.images = [];
                        if(item.attach_parent != null && item.attach_mime == 'image/jpeg'){
                            item.image = item.attach_guid;
                        }
                        if(idx > 0 && response.data[idx-1].post_id != item.post_id) {
                            $scope.items.push({ID: item.post_id,
                                               post_title:item.post_title,
                                               image: item.image})
                        }

                    })
                    console.log($scope.items);
                },
                function errorCallback(reason) {
                    //console.log(reason);
                }
            )
    }

}]);

