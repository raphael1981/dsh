var app = angular.module('app',['ngSanitize', 'ngRoute','ngDialog', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select', 'ui.sortable'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);

app.run(function(editableOptions) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});


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




app.directive('datePdfFileSet', function($http,AppService,$q,DateService) {
    return {
        templateUrl: '/templates/admin/super/pdfprogram/dates.html',
        link: function(scope, element, attributes){

            //console.log(DateService);

            scope.getLangsTranstations = function(){

                var deferred = $q.defer();

                $http.get(AppService.url+'/api/get/month/names/by/lang/pl')
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


            var date = new Date();
            var year = date.getFullYear();
            var month = date.getMonth();

            scope.data = {
                current_month:month+1,
                current_year:year
            }

            scope.$broadcast('change-date-for-upload', {year:scope.data.current_year, month:scope.data.current_month});


            scope.click_year_inside = false;


            scope.makeMonthCollection = function(){

                scope.months = [];

                for(var i=1;i<=12;i++){

                    if(i>9) {

                        if(scope.data.current_month!=i){

                            scope.months.push({
                                name: scope.translations['month:regular:' + i],
                                nr_month: i,
                                active: false
                            });

                        }else{

                            scope.months.push({
                                name: scope.translations['month:regular:' + i],
                                nr_month: i,
                                active: true
                            });

                        }

                    }else{



                        if(scope.data.current_month!=i){

                            scope.months.push({
                                name: scope.translations['month:regular:0' + i],
                                nr_month: i,
                                active: false
                            });

                        }else{

                            scope.months.push({
                                name: scope.translations['month:regular:0' + i],
                                nr_month: i,
                                active: true
                            });

                        }

                    }


                }


            }


            scope.makeYearsCollection = function(){


                scope.years = [];
                var current_year = scope.data.current_year;

                for(var i=0;i<5;i++){

                    if(current_year==scope.data.current_year) {

                        scope.years.push({
                            y: current_year,
                            active: true
                        });

                    }else{

                        scope.years.push({
                            y: current_year,
                            active: false
                        });

                    }

                    current_year++;

                }

            }

            scope.getLangsTranstations()
                .then(
                    function(response){
                        //console.log(response);
                        scope.translations = response;
                        scope.makeMonthCollection();
                        scope.makeYearsCollection();

                    }
                );


            scope.changeMonthByClick = function(month){
                scope.data.current_month = month.nr_month;
                scope.makeMonthCollection();
            }





            scope.changeYearLine = function(action){


                    switch (action) {

                        case 'prev':

                            if(scope.data.current_year==scope.years[0].y) {

                                scope.data.current_year--;
                                scope.makeYearsCollection();

                            }else{

                                scope.data.current_year--;

                            }

                            break;

                        case 'next':

                            if(scope.data.current_year==scope.years[4].y){
                                scope.data.current_year++;
                                scope.makeYearsCollection();
                            }else{
                                scope.data.current_year++;
                            }



                            break;

                    }

                    scope.setActiveYear();



            }

            scope.changeYearByClick = function(y,$index){

                //console.log(y);

                if($index==(scope.years.length-1)){
                    scope.click_year_inside = true;
                }

                angular.forEach(scope.years, function(item,iter){

                    if(y.y==scope.years[iter].y){
                        scope.years[iter].active = true;
                    }else{
                        scope.years[iter].active = false;
                    }


                });

                scope.data.current_year = y.y;
            }

            scope.setActiveYear = function(){

                //console.log(scope.data.current_year);

                angular.forEach(scope.years, function(item,iter){

                    if(scope.data.current_year==scope.years[iter].y){
                        scope.years[iter].active = true;
                    }else{
                        scope.years[iter].active = false;
                    }

                });


            }


            scope.$watch('data.current_year', function(newVal,oldVal){
                scope.$broadcast('change-date-for-upload', {year:scope.data.current_year, month:scope.data.current_month});
            });

            scope.$watch('data.current_month', function(newVal,oldVal){
                scope.$broadcast('change-date-for-upload', {year:scope.data.current_year, month:scope.data.current_month});
            });




        }
    };
});



app.directive('showUploadPdf', function($http,AppService,$q,DateService,Upload,$filter, $rootScope) {
    return {
        templateUrl: '/templates/admin/super/pdfprogram/upload-show-pdf.html',
        link: function(scope, element, attributes){

            scope.checkIsFileProgramExist = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/check/is/pdf/program/exist',scope.upshdata)
                    .then(
                        function successCallback(response){

                            console.log(response.data);
                            deferred.resolve(response.data);
                        },
                        function errorCallback(){
                            deferred.reject(0);
                        }
                    )

                return deferred.promise;

            }


            scope.getLangsTranstations = function(){

                var deferred = $q.defer();

                $http.get(AppService.url+'/api/get/month/names/by/lang/pl')
                    .then(
                        function successCallback(response){

                            deferred.resolve(response.data);
                        },
                        function errorCallback(){
                            deferred.reject(0);
                        }
                    )

                return deferred.promise;
            }

            scope.getLangById = function(){

                var deferred = $q.defer();

                $http.get(AppService.url+'/get/lang/by/id/'+scope.lang_id)
                    .then(
                        function successCallback(response){

                            deferred.resolve(response.data);
                        },
                        function errorCallback(){
                            deferred.reject(0);
                        }
                    )

                return deferred.promise;

            }

            scope.$on('get-date-upload-and-show', function(event, args){


                //console.log(args);
                scope.view_complete = false;
                scope.upshdata = args;
                scope.lang_data = null;
                scope.lang_id = $rootScope.lang;
                scope.upshdata.lang = scope.lang_id;
                scope.pdf = null;
                scope.monthname = null;
                scope.disk = 'media';
                scope.show_link_program = false;
                scope.translation = null;

                scope.getLangById()
                    .then(function(response){

                        scope.lang_data = response;

                        scope.checkIsFileProgramExist()
                            .then(function(response){

                                scope.getLangsTranstations()
                                    .then(function(response){
                                        scope.translation = response;
                                        scope.view_complete = true;
                                    });

                                if(response.success){
                                    scope.show_link_program = true;
                                }

                            });

                    });




            });


            scope.$watch('pdf', function(nVal, oVal){
                scope.uploadPdf(scope.pdf);
            });


            scope.uploadPdf = function (file) {

                //console.log(file);

                if(file) {

                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/program/pdf',
                        fields: {disk: scope.disk, year:scope.upshdata.year, month:scope.upshdata.month, lang_id:scope.lang_id},
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {

                        //console.log(data);

                        scope.show_link_program = true;

                        scope.progress = 0;

                        //$timeout(function () {
                        //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                        //});

                    }).error(function (data, status, headers, config) {
                        //$log.info(data);
                    });

                }

            };




        }
    };
});

app.filter('showMonthName', function() { return function(month, translations) {

    if(month>9){
        return translations['month:regular:' + month];
    }else{
        return translations['month:regular:0' + month];
    }

}});


app.filter('checkfalsearray', function() { return function(array) {

    if (!(array instanceof Array)) return false;

    var bool = true;

    for(var i=0;i<array.length;i++){
        if(!array[i]){
            bool  = false;
        }
    }

    return bool;
}});



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




app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });


}]);


app.controller('PfdProgramController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'Upload', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,Upload,$timeout) {


    $scope.initData = function(){

        $scope.disk = 'media';
        $scope.pdfdata = {
            year:null,
            month:null,
            pdf:null
        }



    }


    $scope.$watch('pdfdata.year', function(newVal,oldVal){

        //console.log(newVal);
        $timeout(function(){
            $rootScope.$broadcast('get-date-upload-and-show', $scope.pdfdata);
        },2000)


    });



    $scope.$on('change-date-for-upload', function(event, args){

        //console.log(args);
        $scope.pdfdata.year = args.year;
        $scope.pdfdata.month = args.month;

        $rootScope.$broadcast('get-date-upload-and-show',$scope.pdfdata);


    });





    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('file', function () {
        $scope.uploadPdfFile($scope.files);
    });


    $scope.uploadPdfFile = function (file) {


        //console.log(files);


        if (file) {

                Upload.upload({
                    url: AppService.url + '/resources/upload/pfd/file',
                    fields: {disk:$scope.disk},
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    //$log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {

                    $log.info(data);
                    $scope.progress = 0;

                    //$timeout(function() {
                    //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    //});
                }).error(function(data, status, headers, config) {
                    //$log.info(data);
                });

        }

    };



}]);




app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/pdfprogram/master.html',
        controller: 'PfdProgramController'
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