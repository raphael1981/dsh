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

app.filter('cut_text', function() { return function(content) {

    var ncontent = '';
    var arr = content.split(' ');

    angular.forEach(arr, function(item,i) {

        if(i<=30) {
            if (i == 0) {
                ncontent += item;
            } else {
                ncontent += ' '+item;
                if(i==30){
                    ncontent+='...';
                }
            }
        }

    });

    return ncontent;

}});


app.filter('cut_text_less', function() { return function(content) {

    var ncontent = '';
    var arr = content.split(' ');

    angular.forEach(arr, function(item,i) {

        if(i<=10) {
            if (i == 0) {
                ncontent += item;
            } else {
                ncontent += ' '+item;
                if(i==10){
                    ncontent+='...';
                }
            }
        }

    });

    return ncontent;

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


    var day_arr = day.split('-');

    var year_number = day_arr[0];
    var month_number = parseInt(day_arr[1])-1;
    var day_number = parseInt(day_arr[2]);

    var dt = new Date();
    dt.setFullYear(year_number,month_number,day_number);

    return dt.getDay();

}});


app.filter('get_date_agenda', function() { return function(begin,end) {

    //console.log('begin',begin);
    //console.log('end',end);

    var string = '';

    if(begin==end){

        var bg_arr = begin.split('-');

        string += bg_arr[2]+'.'+bg_arr[1];

    }else{

        var bg_arr = begin.split('-');
        var en_arr = end.split('-');

        //console.log(bg_arr[0].substring(2, 4));

        //string += bg_arr[2]+'.'+bg_arr[1]+'.'+bg_arr[0].substring(2, 4)+'-'+en_arr[2]+'.'+en_arr[1]+'.'+en_arr[0].substring(2, 4);
        // string += bg_arr[2]+'.'+bg_arr[1]+'.'+bg_arr[0].substring(2, 4)+'-'+en_arr[2]+'.'+en_arr[1]+'.'+en_arr[0].substring(2, 4);
        string += bg_arr[2]+'.'+bg_arr[1]+'-'+en_arr[2]+'.'+en_arr[1];

        //if(bg_arr[2]+'.'+bg_arr[1]==en_arr[2]+'.'+en_arr[1]){
        //
        //}else{
        //    string += bg_arr[2]+'.'+bg_arr[1]+'-'+en_arr[2]+'.'+en_arr[1];
        //}

    }

    return string;

}});


app.filter('get_only_id_array', function() { return function(array) {

    var ids = [];

    angular.forEach(array, function(item, i){
        ids.push(item.id);
    });

    return ids;

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


app.controller('FilterLeafListController', ['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', 'DateService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService,DateService) {


    $scope.initData = function(){

        $scope.translations = null;

        //DateService.days_more;
        //DateService.days_less;
        //DateService.days_leap_feb;
        //DateService.days_feb;


        var date = new Date();
        var year = date.getFullYear();
        //console.log(date.getMonth());

        $scope.data = {
            init_now:true,
            all:true,
            filters:[],
            current_filters:[],
            current_year:year,
            filter_years:[
                {value:year-1,active:false},
                {value:year,active:true}
            ],
            result:[],
            classname:[]
        }


        $scope.filter_action = 'add_from_zero';



        $scope.getFilters()
            .then(
                function(response){

                    $scope.data.filters = response;
                    $scope.data.current_filters = $scope.data.filters;
                    $scope.getFiltersResult();

                    //$scope.getLangsTranstations()
                    //    .then(
                    //        function(response){
                    //            //console.log(response);
                    //            $scope.translations = response;
                    //            //console.log($scope.translations);
                    //            $scope.makeMonthCollection();
                    //            $scope.getFiltersResult();
                    //
                    //        }
                    //    );


                }
            )

    }


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


    //$scope.makeMonthCollection = function(){
    //
    //    $scope.month = [];
    //    $scope.data.filter_months = [];
    //
    //
    //    for(var i=1;i<=12;i++){
    //
    //        if(i.toString().length==1){
    //
    //            //console.log($scope.translations['month:0'+i]);
    //            var first_and_last = {};
    //
    //            if(DateService.months[i-1].type!=null){
    //
    //                if(DateService.months[i-1].type=='less'){
    //
    //                    first_and_last.first = '01-0'+i+'-'+$scope.data.current_year;
    //                    first_and_last.last = DateService.days_less.last+'-0'+i+'-'+$scope.data.current_year;
    //
    //                }else{
    //
    //                    first_and_last.first = '01-0'+i+'-'+$scope.data.current_year;
    //                    first_and_last.last = DateService.days_more.last+'-0'+i+'-'+$scope.data.current_year;
    //
    //                }
    //
    //            }else{
    //
    //                if(DateService.checkIsLeapYear($scope.data.current_year)){
    //                    first_and_last.first = '01-0'+i+'-'+$scope.data.current_year;
    //                    first_and_last.last = DateService.days_leap_feb.last+'-0'+i+'-'+$scope.data.current_year;
    //                }else{
    //                    first_and_last.first = '01-0'+i+'-'+$scope.data.current_year;
    //                    first_and_last.last = DateService.days_feb.last+'-0'+i+'-'+$scope.data.current_year;
    //                }
    //
    //            }
    //
    //
    //            $scope.month.push({
    //                name:$scope.translations['month:regular:0'+i],
    //                between:first_and_last,
    //                nr_month:i,
    //                active:false
    //            });
    //
    //            //console.log(DateService.months[i-1]);
    //
    //        }else{
    //
    //            var first_and_last = {};
    //
    //            if(DateService.months[i-1].type!=null){
    //
    //                if(DateService.months[i-1].type=='less'){
    //
    //                    first_and_last.first = '01-'+i+'-'+$scope.data.current_year;
    //                    first_and_last.last = DateService.days_less.last+'-'+i+'-'+$scope.data.current_year;
    //
    //                }else{
    //
    //                    first_and_last.first = '01-'+i+'-'+$scope.data.current_year;
    //                    first_and_last.last = DateService.days_more.last+'-'+i+'-'+$scope.data.current_year;
    //
    //                }
    //
    //            }
    //
    //            //console.log($scope.translations['month:'+i])
    //            $scope.month.push({
    //                name:$scope.translations['month:regular:'+i],
    //                between:first_and_last,
    //                nr_month:i,
    //                active:false
    //            });
    //
    //            //console.log(DateService.months[i-1]);
    //        }
    //
    //    }
    //
    //
    //    //console.log($filter('find_current_month_in_collection')($scope.month,$scope.data.current_month));
    //    //$scope.month[$scope.data.current_month-1].active = true;
    //    if(($scope.data.current_month-1)==0) {
    //        $scope.data.filter_months.push($scope.month[0]);
    //        $scope.data.filter_months.push($scope.month[1]);
    //        $scope.data.filter_months[0].active = true;
    //    }else{
    //        $scope.data.filter_months.push($scope.month[$scope.data.current_month-2]);
    //        $scope.data.filter_months.push($scope.month[$scope.data.current_month-1]);
    //        $scope.data.filter_months[1].active = true;
    //    }
    //
    //}



    $scope.getFilters = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/api/link/links/deeper/'+$scope.lid)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    deferred.resolve(response.data);
                },
                function errorCallback(){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.getFiltersResult = function(){

        var cats;

        if($scope.select_all_categories){
            cats = $scope.data.filters;
        }else{
            cats = $scope.data.current_filters;
        }

        //console.log(cats);
        var ids = $filter('get_only_id_array')(cats);

        //$scope.data.current_filters = ids;

        var deferred = $q.defer();

        $http.post(AppService.url+'/api/get/filter/leaf/result', {filters:ids, year:$scope.data.current_year})
            .then(
                function successCallback(response){

                    console.log(response.data);
                    $scope.data.result = response.data;
                    //$scope.data.result = $filter('decode_json_in_result')(response.data);
                    //console.log($scope.data.result);
                    //deferred.resolve(response.data);
                }
            )

        return deferred.promise;
    }


    $scope.checkIsFilterActive = function(filter){

        var bool = false;
        //console.log($scope.data.current_filters);
        angular.forEach($scope.data.current_filters, function(item){
            //console.log('item',item);
            //console.log('filter',filter);
            if(item===filter){
                bool=true;
            }
        });

        return bool;

    }


    $scope.changeCurrentFilter = function(filter){


        if($scope.data.current_filters===$scope.data.filters && $scope.filter_action == 'add_from_zero'){
            $scope.data.current_filters = [];
            $scope.filter_action = 'add_push_to';
        }

        if(!$filter('check_is_category_in_array')(filter, $scope.data.current_filters)){
            $scope.data.current_filters.push(filter);
            //console.log($scope.data.current_filters);
            $scope.getFiltersResult();
        }

    }

    $scope.changeAllFilters = function(){

        $scope.data.current_filters=$scope.data.filters;
        $scope.filter_action='add_from_zero';
        $scope.getFiltersResult();

    }


    $scope.changeYearLine = function(action){

        //console.log($scope.data.filter_years);

        switch (action){

            case 'prev':


                if($scope.data.filter_years[1].active){

                    $scope.data.filter_years[0].active = true;
                    $scope.data.filter_years[1].active = false;
                    $scope.data.current_year = $scope.data.filter_years[0].value;

                }else{
                    $scope.data.current_year = $scope.data.filter_years[0].value-1;
                    $scope.data.filter_years[0].value = $scope.data.filter_years[0].value-2;
                    $scope.data.filter_years[1].value = $scope.data.filter_years[1].value-2;
                    $scope.data.filter_years[1].active = true;
                    $scope.data.filter_years[0].active = false;

                }



                break;

            case 'next':

                if($scope.data.filter_years[1].active){

                    $scope.data.filter_years[0].value++;
                    $scope.data.filter_years[1].value++;
                    $scope.data.filter_years[0].active = true;
                    $scope.data.filter_years[1].active = false;
                    $scope.data.current_year = $scope.data.filter_years[0].value;

                }else{

                    $scope.data.filter_years[0].active = false;
                    $scope.data.filter_years[1].active = true;
                    $scope.data.current_year = $scope.data.filter_years[1].value;

                }



                break;

        }

        $scope.data.init_now = false;
        //$scope.getFiltersResult();

    }

    $scope.changeYearByClick = function(year){

        //console.log($scope.data.current_year);
        //console.log(year);
        //console.log($scope.data.filter_years);

        var prev = false;

        angular.forEach($scope.data.filter_years, function(item,i){
            //console.log(item);
            if(item===year){
                if(i==0){
                    prev = true;
                }
            }
        });

        if(prev){

            $scope.data.filter_years[0].active = true;
            $scope.data.filter_years[1].active = false;

        }else{

            $scope.data.filter_years[0].active = false;
            $scope.data.filter_years[1].active = true;

        }

        $scope.data.current_year = year.value;

        $scope.data.init_now = false;
        //$scope.getFiltersResult();

    }

    $scope.$watch('data.current_year', function(nVal, oVal){

        if(!$scope.data.init_now){
            $scope.getFiltersResult();
        }


    });

    //$scope.$watch('data.current_month', function(nVal, oVal){
    //
    //    //console.log('month',nVal);
    //    if(!$scope.data.init_now) {
    //        $scope.getFiltersResult();
    //    }
    //
    //});


    //$scope.changeMonthLine = function(action){
    //
    //    //console.log($scope.data.current_month);
    //
    //    switch (action){
    //
    //        case 'prev':
    //
    //            if(($scope.data.current_month-1)==1) {
    //
    //                $scope.data.filter_months = [];
    //                $scope.data.filter_months.push($scope.month[0]);
    //                $scope.data.current_month = 1;
    //                $scope.data.filter_months[0].active = true;
    //
    //            }else if($scope.data.current_month!=1){
    //
    //                if(!$scope.data.filter_months[0].active){
    //                    //$scope.data.filter_months = [];
    //                    //$scope.data.filter_months.push($scope.month[$scope.data.current_month-1]);
    //                    //$scope.data.filter_months.push($scope.month[$scope.data.current_month]);
    //                    $scope.data.current_month--;
    //                    $scope.data.filter_months[0].active = true;
    //                    $scope.data.filter_months[1].active = false;
    //                }else{
    //                    $scope.data.filter_months = [];
    //                    $scope.data.filter_months.push($scope.month[$scope.data.current_month-3]);
    //                    $scope.data.filter_months.push($scope.month[$scope.data.current_month-2]);
    //                    $scope.data.current_month--;
    //                    $scope.data.filter_months[0].active = false;
    //                    $scope.data.filter_months[1].active = true;
    //                }
    //
    //            }
    //
    //            break;
    //
    //        case 'next':
    //
    //
    //            if(($scope.data.current_month-1)==0) {
    //
    //                $scope.data.filter_months = [];
    //                $scope.data.filter_months.push($scope.month[1]);
    //                $scope.data.filter_months.push($scope.month[2]);
    //                $scope.data.current_month = 2;
    //                $scope.data.filter_months[0].active = true;
    //
    //            }else if($scope.data.current_month==11) {
    //
    //                $scope.data.filter_months = [];
    //                $scope.data.filter_months.push($scope.month[11]);
    //                $scope.data.current_month = 12;
    //                $scope.data.filter_months[0].active = true;
    //
    //            }else if($scope.data.current_month!=12){
    //
    //                if($scope.data.filter_months[0].active){
    //                    //$scope.data.filter_months = [];
    //                    //$scope.data.filter_months.push($scope.month[$scope.data.current_month-1]);
    //                    //$scope.data.filter_months.push($scope.month[$scope.data.current_month]);
    //                    $scope.data.current_month++;
    //                    $scope.data.filter_months[0].active = false;
    //                    $scope.data.filter_months[1].active = true;
    //                }else{
    //                    $scope.data.filter_months = [];
    //                    $scope.data.filter_months.push($scope.month[$scope.data.current_month]);
    //                    $scope.data.filter_months.push($scope.month[$scope.data.current_month+1]);
    //                    $scope.data.current_month++;
    //                    $scope.data.filter_months[0].active = true;
    //                    $scope.data.filter_months[1].active = false;
    //                }
    //
    //            }
    //
    //
    //            break;
    //
    //    }
    //
    //
    //    $scope.data.init_now = false;
    //
    //}


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