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


app.directive('resize', function ($window,$timeout,$rootScope) {
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

            $rootScope.$broadcast('resize-window', {window_width:scope.windowWidth});

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


app.filter('cut_title_by_words', function() { return function(title,words,filter_resize) {

    var str = '';

    console.log(filter_resize);

    if(filter_resize<788 && filter_resize>639) {
        words = 26;
    }else{
        words = 64;
    }

    var lenfull = title.length;


    if(lenfull>words) {

        var remlem = lenfull - words;
        var shorttitle = title.substr(0, words);

        var fullex = title.split(' ');
        var shortex = shorttitle.split(' ');
        //console.log(fullex.length);
        //console.log(shortex.length);

        if(fullex[shortex.length-1].length==shortex[shortex.length-1].length){

            angular.forEach(shortex, function(item, iter){

                if(iter==0){
                    str += item;
                }else{
                    str += ' '+item;
                }

            });

            str+='...';

        }else{

            for(var i=0;i<(shortex.length-1);i++){

                if(i==0){
                    str += shortex[i];
                }else{
                    str += ' '+shortex[i];
                }

            }

            str+='...';

        }

    }else{

        str = title;

    }



    return str;

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


app.filter('get_week_day_name', function() { return function(day,trs,begin,end) {



    var day_arr = day.split('-');

    var year_number = day_arr[0];
    var month_number = parseInt(day_arr[1])-1;
    var day_number = parseInt(day_arr[2]);

    var dt = new Date();
    dt.setFullYear(year_number,month_number,day_number);

    //console.log(trs);

    if(begin==end){
        return trs['weekday:'+dt.getDay()];
    }else{
        return '';
    }



}});


app.filter('get_month_name', function() { return function(mth,trs) {


    if(mth.toString().length==1){
        return trs['month:regular:0'+mth];
    }else{
        return trs['month:regular:'+mth];
    }


}});


app.filter('get_date_content', function() { return function(pub_date) {

    var string = '';

    var pub_d = pub_date.split(' ');
    var pub = pub_d[0].split('-');

    string += pub[2]+'.'+pub[1]+'.'+pub[0];

    return string;

}});


app.filter('cut_time', function() { return function(begin_time, end_time) {

    var string = '';

    if((begin_time==null || begin_time==undefined) && (end_time==null || end_time==undefined)){



    }else if((begin_time!=null && begin_time!=undefined) && (end_time==null || end_time==undefined)){

        var bg_ex = begin_time.split(':');

        string = bg_ex[0]+':'+bg_ex[1];

    }else if((begin_time!=null && begin_time!=undefined) && (end_time!=null && end_time!=undefined)){

        var bg_ex = begin_time.split(':');
        var en_ex = end_time.split(':');

        string = bg_ex[0]+':'+bg_ex[1]+'-'+en_ex[0]+':'+en_ex[1];
    }

    //console.log(begin_time+'-'+end_time);

    return string;


}});


app.filter('filter_status_categories', function() { return function(filters,status) {

    var fl = [];

    angular.forEach(filters, function(item,iter){


        if(item.status==1){
            fl.push(item);
        }

    });

    return fl;

}});


app.filter('get_month_name_by_nr', function() { return function(number,trans) {

    var name;

    //console.log(trans);

    if(trans!=null) {

        if (number < 10) {
            name = trans['month:regular:' + '0' + number];
        } else {
            name = trans['month:regular:' + number];
        }

    }

    return name;

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


app.controller('FilterCategoryContentYearArchiveController', ['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', 'DateService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService,DateService) {


    $scope.initData = function(){

        $scope.all_filters = [];
        $scope.is_active_filter = [];

        $scope.month = [];
        $scope.translations = null;

        $scope.filter_mobile_open = 'hidden';

        //DateService.days_more;
        //DateService.days_less;
        //DateService.days_leap_feb;
        //DateService.days_feb;


        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth();
        //console.log(date.getDate());


        //view types {name:'trio_columns',name:'line_elements"}


        $scope.view_type = 'line_elements';

        $scope.data = {
            init_now:true,
            all:true,
            filters:[],
            current_filters:[],
            current_year:year,
            selected_year:year,
            current_month:month+1,
            current_day:date.getDate(),
            filter_years:[
                //{value:year-1,active:false},
                //{value:year,active:true}
            ],
            filter_months:[

            ],
            result:[],
            classname:[]
        }



        $scope.filter_action = 'add_from_zero';



        $scope.getFilters()
            .then(
                function(response){

                    $scope.all_filters = response;
                    $scope.data.filters = $filter('filter_status_categories')(response);
                    $scope.data.current_filters = $filter('filter_status_categories')(response);

                    $scope.getLangsTranstations()
                        .then(
                            function(response){
                                //console.log(response);
                                $scope.translations = response;
                                //console.log($scope.translations);
                                //$scope.makeMonthCollection();


                                $scope.makeMonthCollection()
                                    .then(
                                        function(response){

                                            //console.log($scope.months);
                                            $scope.monthDoubleCreate();

                                            $scope.makeYearsArchLine()
                                                .then(
                                                    function(response){
                                                        //console.log($scope.data.filter_years);
                                                        $scope.getFiltersResult();
                                                    }
                                                );

                                        }
                                    )


                            }
                        );


                }
            )

    }


    $scope.getLangsTranstations = function(){

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




    $scope.getFilters = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/api/get/link/data/'+$scope.lid)
            .then(
                function successCallback(response){
                    deferred.resolve(response.data);
                },
                function errorCallback(){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.monthDoubleCreate = function(){

        //console.log($scope.data.current_month);

        $scope.data.filter_months.push($scope.months[$scope.data.current_month-1]);
        if($scope.data.current_month==12){
            $scope.data.filter_months.push($scope.months[0]);
        }else{
            $scope.data.filter_months.push($scope.months[$scope.data.current_month]);
        }

    }


    $scope.makeMonthCollection = function(){

        var deferred = $q.defer();

        $scope.months = [];

        for(var i=1;i<=12;i++){

            if(i>9) {

                if($scope.data.current_month!=i){

                    $scope.months.push({
                        name: $scope.translations['month:regular:' + i],
                        nr_month: i,
                        active: false
                    });

                }else{

                    $scope.months.push({
                        name: $scope.translations['month:regular:' + i],
                        nr_month: i,
                        active: true
                    });

                }

            }else{



                if($scope.data.current_month!=i){

                    $scope.months.push({
                        name: $scope.translations['month:regular:0' + i],
                        nr_month: i,
                        active: false
                    });

                }else{

                    $scope.months.push({
                        name: $scope.translations['month:regular:0' + i],
                        nr_month: i,
                        active: true
                    });

                }

            }


        }


        deferred.resolve(1);

        return deferred.promise;


    }


    $scope.makeYearsArchLine = function(){

        var deferred = $q.defer();

        for(var i=7;i>0;i--){

            var push_year = $scope.data.current_year-i;
            $scope.data.filter_years.push({value:push_year, active:false});

        }

        $scope.data.filter_years.push({value:$scope.data.current_year, active:true});

        deferred.resolve(1);

        return deferred.promise;


    }


    $scope.getFiltersResult = function(){

        $scope.overloadlayer='';
        //console.log()

        var cats;

        if($scope.select_all_categories){
            cats = $scope.data.filters;
        }else{
            cats = $scope.data.current_filters;
        }

        var deferred = $q.defer();

        //{filters:cats, year:$scope.data.current_year, month:$scope.data.current_month, is_all:$scope.data.all}

        var is_current_year = false;

        //if($scope.data.current_year==$scope.data.selected_year){
        //    is_current_year = true;
        //}else{
        //    is_current_year = false;
        //}
        is_current_year = false;

        //console.log($scope.data.selected_year);

        $http.post(AppService.url+'/api/get/filter/archive/content/result', {filters:cats,
                year:$scope.data.selected_year,
                current_month:$scope.data.current_month,
                is_current_year:is_current_year,
                current_day:$scope.data.current_day,
                is_all:$scope.data.all,
                full_filter_collection:$scope.all_filters,
                order:$scope.order})
            .then(
                function successCallback(response){

                    console.log(response.data);
                    //$scope.data.result.not_group = $filter('decode_json_in_result')(response.data.not_group);
                    $scope.data.result.not_group = response.data;
                    $scope.overloadlayer='hidden';
                    //console.log($scope.data.result);
                    //deferred.resolve(response.data);
                }
            )

        return deferred.promise;
    }


    $scope.changeCurrentFilter = function(filter, $index, action){

        console.log(filter);

        if(action=='add') {

            if ($scope.filter_action == 'add_from_zero') {
                $scope.data.current_filters = [];
                $scope.filter_action = 'add_push_to';
                //$scope.is_active_filter[$index] = true;
                $scope.data.all = false;
            }

            if (!$filter('check_is_category_in_array')(filter, $scope.data.current_filters)) {
                $scope.data.current_filters.push(filter);
                //console.log($scope.data.current_filters);
                $scope.data.all = false;
                $scope.getFiltersResult();
            }

        }

        if(action=='remove') {


            angular.forEach($scope.data.current_filters, function(item,iter){
                if(item.id==filter.id){
                    $scope.data.current_filters.splice(iter, 1);
                }
            });

            if($scope.data.current_filters.length==0){
                $scope.filter_action = 'add_from_zero';
                $scope.data.all = true;
            }
            $scope.getFiltersResult();

        }

    }

    $scope.changeAllFilters = function(){

        $scope.data.current_filters=$scope.data.filters;
        $scope.filter_action='add_from_zero';
        $scope.data.all = true;
        $scope.getFiltersResult();

    }


    $scope.checkIsFilterActive = function(filter){

        var bool = false;
        //console.log('filter',filter);
        angular.forEach($scope.data.current_filters, function(item){
            //console.log('item',item);
            if(item===filter){
                bool=true;
            }
        });

        return bool;

    }


    $scope.changeYearLine = function(action){

        //console.log($scope.data.filter_years);

        var active_index = null;

        angular.forEach($scope.data.filter_years, function(item, iter){

            if(item.active){
                active_index = iter;
            }

            $scope.data.filter_years[iter].active = false;

        });

        //console.log(active_index);

        switch (action){

            case 'prev':

                if(active_index==0){

                    angular.forEach($scope.data.filter_years, function(item, iter){
                        $scope.data.filter_years[iter].value--;
                    });

                    $scope.data.filter_years[0].active = true;
                    $scope.data.selected_year = $scope.data.filter_years[0].value;

                }else{

                    $scope.data.filter_years[active_index-1].active = true;
                    $scope.data.selected_year = $scope.data.filter_years[active_index-1].value;

                }


                break;

            case 'next':


                if(active_index==7){

                    angular.forEach($scope.data.filter_years, function(item, iter){
                        $scope.data.filter_years[iter].value++;
                    });

                    $scope.data.filter_years[7].active = true;
                    $scope.data.selected_year = $scope.data.filter_years[7].value;

                }else{

                    $scope.data.filter_years[active_index+1].active = true;
                    $scope.data.selected_year = $scope.data.filter_years[active_index+1].value;

                }


                break;

        }

        $scope.data.init_results = false;

    }

    $scope.changeYearByClick = function(year){

        $scope.data.selected_year = year.value;

        angular.forEach($scope.data.filter_years, function(item, iter){

            if(item.value==year.value){
                $scope.data.filter_years[iter].active = true;
            }else{
                $scope.data.filter_years[iter].active = false;
            }


        });

        $scope.data.init_results = false;

    }



    $scope.changeMonthLine = function(action){

        console.log($scope.data.current_month);

        switch (action){

            case 'prev':

                console.log($scope.data.current_month);
                console.log($scope.data.filter_months);

                if($scope.data.filter_months[0].nr_month==$scope.data.current_month){

                    if($scope.data.current_month==1){

                        $scope.data.filter_months[0] = $scope.months[10];
                        $scope.data.filter_months[1] = $scope.months[11];
                        $scope.changeYearLine('prev');

                    }else if($scope.data.current_month==2){

                        $scope.data.filter_months[0] = $scope.months[11];
                        $scope.data.filter_months[1] = $scope.months[0];

                    }else{

                        $scope.data.filter_months[0] = $scope.months[$scope.data.current_month-3];
                        $scope.data.filter_months[1] = $scope.months[$scope.data.current_month-2];
                    }

                    $scope.data.filter_months[0].active = false;
                    $scope.data.filter_months[1].active = true;
                    $scope.data.current_month = $scope.data.filter_months[1].nr_month;

                }else{

                    if($scope.data.current_month==1){
                        $scope.changeYearLine('prev');
                    }

                    $scope.data.filter_months[0].active = true;
                    $scope.data.filter_months[1].active = false;
                    $scope.data.current_month = $scope.data.filter_months[0].nr_month;

                }

                break;

            case 'next':

                if($scope.data.filter_months[1].nr_month==$scope.data.current_month){


                    if($scope.data.current_month==12) {

                        $scope.data.filter_months[0] = $scope.months[0];
                        $scope.data.filter_months[1] = $scope.months[1];
                        $scope.changeYearLine('next');

                    }else if($scope.data.current_month==11){

                        $scope.data.filter_months[0] = $scope.months[11];
                        $scope.data.filter_months[1] = $scope.months[0];

                    }else{

                        $scope.data.filter_months[0] = $scope.months[$scope.data.current_month];
                        $scope.data.filter_months[1] = $scope.months[$scope.data.current_month+1];

                    }


                    $scope.data.filter_months[0].active = true;
                    $scope.data.filter_months[1].active = false;
                    $scope.data.current_month = $scope.data.filter_months[0].nr_month;


                }else{

                    if($scope.data.current_month==12){

                        $scope.changeYearLine('next');

                    }

                    $scope.data.filter_months[0].active = false;
                    $scope.data.filter_months[1].active = true;
                    $scope.data.current_month = $scope.data.filter_months[1].nr_month;

                }


                break;

        }


        $scope.data.init_results = false;


    }

    $scope.changeMonthByClick = function(month,$index){

        console.log(month);
        console.log($scope.data.filter_months);
        $scope.data.filter_months[0].active = false;
        $scope.data.filter_months[1].active = false;
        $scope.data.filter_months[$index].active = true;
        $scope.data.current_month = month.nr_month;

        $scope.data.init_results = false;

    }


    $scope.$watch('data.selected_year', function(nVal, oVal){

        if(!$scope.data.init_results) {
            $scope.getFiltersResult();
        }

    });


    $scope.$watch('data.current_month', function(nVal, oVal){

        if(!$scope.data.init_results) {
            $scope.getFiltersResult();
        }

    });


    $scope.$on('resize-window', function(event, args){
        //console.log(args.window_width);
        $scope.filter_resize = args.window_width;
    });



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

