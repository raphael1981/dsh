var app = angular.module('app',['ngSanitize', 'ngRoute', 'rodo.newsletter'], function($interpolateProvider) {
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


app.run(function($rootScope, $document, $log, $window) {



    if($document.scrollTop()>280){
        document.getElementById('topMenu').className = 'dsh-container-menu fixed-show';
    }else{
        document.getElementById('topMenu').className = 'dsh-container-menu';
    }

    $document.on('scroll', function() {

        //console.log($document.scrollTop());
        //console.log(angular.element(document.getElementById('topMenu')));

        if($document.scrollTop()>280){
            document.getElementById('topMenu').className = 'dsh-container-menu fixed-show';
        }else{
            document.getElementById('topMenu').className = 'dsh-container-menu';
        }

    })

});


app.factory('DateService', function($location) {
    return {
        days_more : {
            first:1,
            last:31
        },
        days_less : {
            first:1,
            last:30
        },
        days_leap_feb : {
            first:1,
            last:29
        },
        days_feb : {
            first:1,
            last:28
        },
        months:
            [
                {type:'more'},
                {type:null},
                {type:'more'},
                {type:'less'},
                {type:'more'},
                {type:'less'},
                {type:'more'},
                {type:'more'},
                {type:'less'},
                {type:'more'},
                {type:'less'},
                {type:'more'}
            ],
        createYearsFromTo: function(start, end){

            var years = [];

            for(var i=start;i<=end;i++){
                years.push(i);
            }

            return years;

        },
        checkIsLeapYear: function(rok){

            if(((rok % 4 == 0) && (rok % 100 != 0)) || (rok % 400 == 0)){
                return true;
            }else{
                return false;
            }
        }

    };
});


app.directive('resize', function ($window,$timeout) {
    return function (scope, element, attr) {

        var w = angular.element($window);
        scope.$watch(function () {
            return {
                'h': w.height(),
                'w': w.width()
            };
        }, function (newValue, oldValue) {

            scope.windowHeight = newValue.h;
            scope.windowWidth = newValue.w;

            $timeout(function(){


            },500);



        }, true);

        w.bind('resize', function () {
            scope.$apply();
        });
    }
});


app.filter('check_is_category_in_array', function() { return function(cat, cats) {

    var is_in = false;

    angular.forEach(cats, function(item) {

        if(item===cat){
            is_in = true;
        }

    });

    return is_in;

}});

app.filter('decode_json_in_result', function() { return function(coll) {

    var array = [];

    angular.forEach(coll, function(item) {
        item.params = JSON.parse(item.params);
        array.push(item)

    });

    return array;

}});


app.filter('find_current_month_in_collection', function() { return function(coll,month) {


    angular.forEach(coll, function(item,i) {

        if(item.nr_month==month){
            coll[i].now = true;
        }

    });

    return coll;

}});


app.filter('cut_title', function() { return function(title) {

    var ntitle = '';
    var arr = title.split(' ');

    angular.forEach(arr, function(item,i) {

        if(i<=5) {
            if (i == 0) {
                ntitle += item;
            } else {
                ntitle += ' '+item;
                if(i==5){
                    ntitle+='...';
                }
            }
        }

    });

    return ntitle;

}});

app.filter('create_path', function() { return function(path) {


    var npath = '';

    if(path==':'){
        npath += '/pictures';
    }else{
        npath += '/pictures';
        var arr = path.split(':');
        angular.forEach(arr, function(item,i){

            if(i!=0){
                npath += '/'+item
            }

        });
    }

    return npath;
}

});


app.filter('get_week_day_name', function() { return function(day,trs) {
    //console.log(trs);
    //console.log(day);

    var day_arr = day.split('-');
    console.log(day_arr[0],parseInt(day_arr[1]),parseInt(day_arr[2]));
    var dt = new Date(day_arr[0],parseInt(day_arr[1]),parseInt(day_arr[2]));
    console.log(day_arr);

    return dt.getDay();

}});

app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500);

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });

}]);

app.controller('MenuController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope) {



}]);

app.controller('ManyOneListController', ['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', 'DateService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService,DateService) {

}]);


app.controller('ManyDoubleListController', ['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', 'DateService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService,DateService) {


    //$scope.initData = function(){
    //
    //    $scope.data = {
    //        elements:[],
    //        link:null
    //    };
    //
    //    $scope.getLinkData()
    //        .then(
    //            function(response){
    //                console.log(response);
    //                $scope.data.elements = response.list;
    //                $scope.data.link = response.link;
    //            }
    //        )
    //
    //}
    //
    //
    //$scope.getLinkData = function(){
    //
    //    var deferred = $q.defer();
    //
    //    $http.get(AppService.url+'/api/get/link/data/many/'+$scope.lid)
    //        .then(
    //            function successCallback(response){
    //                deferred.resolve(response.data);
    //            },
    //            function errorCallback(){
    //                deferred.reject(0);
    //            }
    //        )
    //
    //
    //    return deferred.promise;
    //
    //}
    //
    //
    //$scope.getLangsTranstations = function(){
    //
    //    var deferred = $q.defer();
    //
    //    $http.get(AppService.url+'/api/get/month/names/by/lang/'+$scope.lang_tag)
    //        .then(
    //            function successCallback(response){
    //                //console.log(response.data);
    //                deferred.resolve(response.data);
    //            },
    //            function errorCallback(){
    //                deferred.reject(0);
    //            }
    //        )
    //
    //    return deferred.promise;
    //}


}]);

app.controller('ManyTrioListController', ['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', 'DateService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService,DateService) {

}]);


app.controller('SubscribeNewsletterController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService) {

    $scope.initData = function(){
        $scope.memberemail = '';
        $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;
        $scope.raporttext = '';
        $scope.show_raport_cloud = false;
    }

    $scope.checkEmail = function(){

        var deferred = $q.defer();

        $http.post(AppService.url+'/api/check/is/member/email/newsletter', {email:$scope.memberemail})
            .then(
                function successCallback(response) {

                    deferred.resolve(response.data);

                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.addEmailToBase = function(action){

        var deferred = $q.defer();

        $http.put(AppService.url+'/api/member/newsletter/subscribe', {email:$scope.memberemail, action:action})
            .then(
                function successCallback(response) {
                    //console.log(response);
                    $scope.loadingclass = '';
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.subscribeAdd = function(){

        $scope.loadingclass = 'active-loading';

        $scope.checkEmail()
            .then(
                function(response){
                    console.log(response);
                    $scope.raporttext = response.message;
                    $scope.show_raport_cloud = true;

                    if(response.status=='not_exist'){
                        $scope.addEmailToBase(response.status);
                    }else if(response.status=='exist_not_subscribe'){
                        $scope.addEmailToBase(response.status);
                    }else if(response.status=='exist_not_confirmed_subscribe'){
                        $scope.addEmailToBase(response.status);
                    }else if(response.status=='exist_subscribe'){
                        $scope.loadingclass = '';
                    }


                },
                function(){
                    $scope.loadingclass = '';
                }
            )

    }


}]);