var app = angular.module('app',['ngSanitize', 'ngRoute', 'ngAnimate', 'rodo.newsletter'], function($interpolateProvider) {
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


//app.factory('DateService', function($location) {
//    return {
//        days_more : {
//            first:1,
//            last:31
//        },
//        days_less : {
//            first:1,
//            last:30
//        },
//        days_leap_feb : {
//            first:1,
//            last:29
//        },
//        days_feb : {
//            first:1,
//            last:28
//        },
//        months:
//            [
//                {type:'more'},
//                {type:null},
//                {type:'more'},
//                {type:'less'},
//                {type:'more'},
//                {type:'less'},
//                {type:'more'},
//                {type:'more'},
//                {type:'less'},
//                {type:'more'},
//                {type:'less'},
//                {type:'more'}
//            ],
//        createYearsFromTo: function(start, end){
//
//            var years = [];
//
//            for(var i=start;i<=end;i++){
//                years.push(i);
//            }
//
//            return years;
//
//        },
//        checkIsLeapYear: function(rok){
//
//            if(((rok % 4 == 0) && (rok % 100 != 0)) || (rok % 400 == 0)){
//                return true;
//            }else{
//                return false;
//            }
//        }
//
//    };
//});
//
//
//app.directive('resize', function ($window,$timeout) {
//    return function (scope, element, attr) {
//
//        var w = angular.element($window);
//        scope.$watch(function () {
//            return {
//                'h': w.height(),
//                'w': w.width()
//            };
//        }, function (newValue, oldValue) {
//
//            scope.windowHeight = newValue.h;
//            scope.windowWidth = newValue.w;
//
//            $timeout(function(){
//
//
//            },500);
//
//
//
//        }, true);
//
//        w.bind('resize', function () {
//            scope.$apply();
//        });
//    }
//});
//
//
//app.filter('check_is_category_in_array', function() { return function(cat, cats) {
//
//    var is_in = false;
//
//    angular.forEach(cats, function(item) {
//
//        if(item===cat){
//            is_in = true;
//        }
//
//    });
//
//    return is_in;
//
//}});
//
//app.filter('decode_json_in_result', function() { return function(coll) {
//
//    var array = [];
//
//    angular.forEach(coll, function(item) {
//        item.params = JSON.parse(item.params);
//        array.push(item)
//
//    });
//
//    return array;
//
//}});
//
//
//app.filter('find_current_month_in_collection', function() { return function(coll,month) {
//
//
//    angular.forEach(coll, function(item,i) {
//
//        if(item.nr_month==month){
//            coll[i].now = true;
//        }
//
//    });
//
//    return coll;
//
//}});
//
//
//app.filter('cut_title_by_words', function() { return function(title,words) {
//
//    var str = '';
//
//    var lenfull = title.length;
//
//
//    if(lenfull>words) {
//
//        var remlem = lenfull - words;
//        var shorttitle = title.substr(0, words);
//
//        var fullex = title.split(' ');
//        var shortex = shorttitle.split(' ');
//        console.log(fullex.length);
//        console.log(shortex.length);
//
//        if(fullex[shortex.length-1].length==shortex[shortex.length-1].length){
//
//            angular.forEach(shortex, function(item, iter){
//
//                if(iter==0){
//                    str += item;
//                }else{
//                    str += ' '+item;
//                }
//
//            });
//
//            str+='...';
//
//        }else{
//
//            for(var i=0;i<(shortex.length-1);i++){
//
//                if(i==0){
//                    str += shortex[i];
//                }else{
//                    str += ' '+shortex[i];
//                }
//
//            }
//
//            str+='...';
//
//        }
//
//    }else{
//
//        str = title;
//
//    }
//
//
//
//    return str;
//
//}});
//
//
//app.filter('cut_title', function() { return function(title) {
//
//    var ntitle = '';
//    var arr = title.split(' ');
//
//    angular.forEach(arr, function(item,i) {
//
//        if(i<=5) {
//            if (i == 0) {
//                ntitle += item;
//            } else {
//                ntitle += ' '+item;
//                if(i==5){
//                    ntitle+='...';
//                }
//            }
//        }
//
//    });
//
//    return ntitle;
//
//}});
//
//app.filter('create_path', function() { return function(path) {
//
//    var npath = '';
//
//    if(path==':'){
//        npath += '/pictures';
//    }else{
//        npath += '/pictures';
//        var arr = path.split(':');
//        angular.forEach(arr, function(item,i){
//
//            if(i!=0){
//                npath += '/'+item
//            }
//
//        });
//    }
//
//    return npath;
//}
//
//});
//
//
//app.filter('cut_text', function() { return function(content) {
//
//    var ncontent = '';
//    var arr = content.split(' ');
//
//    angular.forEach(arr, function(item,i) {
//
//        if(i<=30) {
//            if (i == 0) {
//                ncontent += item;
//            } else {
//                ncontent += ' '+item;
//                if(i==30){
//                    ncontent+='...';
//                }
//            }
//        }
//
//    });
//
//    return ncontent;
//
//}});
//
//
//app.filter('cut_text_less', function() { return function(content) {
//
//    var ncontent = '';
//    var arr = content.split(' ');
//
//    angular.forEach(arr, function(item,i) {
//
//        if(i<=10) {
//            if (i == 0) {
//                ncontent += item;
//            } else {
//                ncontent += ' '+item;
//                if(i==10){
//                    ncontent+='...';
//                }
//            }
//        }
//
//    });
//
//    return ncontent;
//
//}});
//
//
//app.filter('get_week_day_name', function() { return function(day,trs,begin,end) {
//
//
//
//    var day_arr = day.split('-');
//
//    var year_number = day_arr[0];
//    var month_number = parseInt(day_arr[1])-1;
//    var day_number = parseInt(day_arr[2]);
//
//    var dt = new Date();
//    dt.setFullYear(year_number,month_number,day_number);
//
//    //console.log(trs);
//
//    if(begin==end){
//        return trs['weekday:'+dt.getDay()];
//    }else{
//        return '';
//    }
//
//
//
//}});
//
//
//app.filter('get_month_name', function() { return function(mth,trs) {
//
//
//    if(mth.toString().length==1){
//        return trs['month:regular:0'+mth];
//    }else{
//        return trs['month:regular:'+mth];
//    }
//
//
//}});
//
//
//app.filter('get_date_agenda', function() { return function(begin,end,trans) {
//
//    //console.log('begin',begin);
//    //console.log('end',end);
//
//    console.log(trans);
//
//    var string = '';
//
//    if(begin==end){
//
//        var bg_arr = begin.split('-');
//
//        string += bg_arr[2]+' '+trans['month:'+bg_arr[1]];
//
//    }else{
//
//        var bg_arr = begin.split('-');
//        var en_arr = end.split('-');
//
//        //console.log(bg_arr[0].substring(2, 4));
//
//        //string += bg_arr[2]+'.'+bg_arr[1]+'.'+bg_arr[0].substring(2, 4)+'-'+en_arr[2]+'.'+en_arr[1]+'.'+en_arr[0].substring(2, 4);
//        // string += bg_arr[2]+'.'+bg_arr[1]+'.'+bg_arr[0].substring(2, 4)+'-'+en_arr[2]+'.'+en_arr[1]+'.'+en_arr[0].substring(2, 4);
//        string += bg_arr[2]+' '+trans['month:'+bg_arr[1]]+' &ndash; '+en_arr[2]+' '+trans['month:'+en_arr[1]];
//
//        //if(bg_arr[2]+'.'+bg_arr[1]==en_arr[2]+'.'+en_arr[1]){
//        //
//        //}else{
//        //    string += bg_arr[2]+'.'+bg_arr[1]+'-'+en_arr[2]+'.'+en_arr[1];
//        //}
//
//    }
//
//    return string;
//
//}});
//
//
//app.filter('cut_time', function() { return function(begin_time, end_time) {
//
//    var string = '';
//
//    if((begin_time==null || begin_time==undefined) && (end_time==null || end_time==undefined)){
//
//
//
//    }else if((begin_time!=null && begin_time!=undefined) && (end_time==null || end_time==undefined)){
//
//        var bg_ex = begin_time.split(':');
//
//        string = bg_ex[0]+':'+bg_ex[1];
//
//    }else if((begin_time!=null && begin_time!=undefined) && (end_time!=null && end_time!=undefined)){
//
//        var bg_ex = begin_time.split(':');
//        var en_ex = end_time.split(':');
//
//        string = bg_ex[0]+':'+bg_ex[1]+'-'+en_ex[0]+':'+en_ex[1];
//    }
//
//    //console.log(begin_time+'-'+end_time);
//
//    return string;
//
//
//}});
//
//
//app.filter('filter_status_categories', function() { return function(filters,status) {
//
//    var fl = [];
//
//    angular.forEach(filters, function(item,iter){
//
//
//        if(item.status==1){
//            fl.push(item);
//        }
//
//    });
//
//    return fl;
//
//}});


app.filter('filter_status_categories', function() { return function(filters,status) {

    var fl = [];

    angular.forEach(filters, function(item,iter){


        if(item.status==1){
            fl.push(item);
        }

    });

    return fl;

}});

app.filter('json_decode_categories', function() { return function(data) {

    var arr = [];

    angular.forEach(data, function(item, iter){

        item.categories = JSON.parse(item.categories);
        arr.push(item);

    });

    return arr;

}});


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500);

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
        console.log($rootScope.lang);
    });

}]);

app.controller('MenuController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope) {



}]);


app.controller('SearchController', ['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', '$routeParams', '$route', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService,DateService,$routeParams,$route) {


    $scope.initData = function(){

        $scope.results = [];
        $scope.count = 0;
        $scope.now_iter = 0;
        $scope.show_result = false;
        $scope.view_results = [];
        $scope.translations = null;

        $scope.getLangsTranslations()
            .then(
                function(response){

                    $scope.translations = response;

                    if($location.path()) {
                        $scope.search = $scope.getSearchWord();
                        if($scope.search.length>2) {
                            $scope.searchExecute();
                        }
                    }


                }
            );


    }


    $scope.$watch('search', function(nVal, oldVal){
        if(nVal==''){
            $scope.show_result = false;
            $scope.results = [];
            $scope.view_results=[];
        }
    });


    $scope.searchIndexResult = function(){

        if($scope.search.length>2) {
            $location.path('search=' + $scope.search);
            $scope.searchExecute();
        }

    }


    $scope.searchExecute = function(){
        $http.post(AppService.url+'/api/get/search/in/index', {word:$scope.search, lang_id:$rootScope.lang})
            .then(
                function successCallback(response){
                    //console.log(response);
                    if(response.data.count>0){
                        $scope.count = response.data.count;
                        $scope.show_result = true;
                        $scope.results = response.data.results;
                        $scope.view_results = $scope.view_results.concat($filter('json_decode_categories')($scope.results[$scope.now_iter]));
                        //console.log($scope.view_results);
                    }else{
                        $scope.show_result = false;
                    }
                }
            )
    }


    $scope.getSearchWord = function(){

        var get_split = $location.path().split('=');
        return get_split[1];

    }


    $scope.addMoreResults = function(){
        $scope.now_iter++;
        //$scope.view_results = $scope.view_results.concat($scope.results[$scope.now_iter]);
        angular.forEach($filter('json_decode_categories')($scope.results[$scope.now_iter]), function(item, iter){

            $scope.view_results.push(item);

        });
    }


    $scope.getLangsTranslations = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/api/get/month/names/by/lang/'+$scope.lang_tag)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    deferred.resolve(response.data);
                },
                function errorCallback(){
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }


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


app.config(function($routeProvider, $locationProvider) {

    //$routeProvider.
    //when('/', {
    //    templateUrl: '/templates/front/search/content.html',
    //    controller: 'SearchController'
    //});
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});