var app = angular.module('app',['ngSanitize', 'ngRoute','ngDialog', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select', 'ui.sortable'], function($interpolateProvider) {
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

app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});


app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});

app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/logotypes/search.html'
    };
});


//app.directive('statusBtnInList', function() {
//    return {
//        templateUrl: '/templates/admin/super/logotypes/status.html',
//        link: function(scope, element, attributes){
//
//            //console.log(attributes);
//            scope.status = attributes.statusBtnInList;
//            scope.id = attributes.elementid;
//
//        }
//    };
//});




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


app.filter('propsFilter', function() {
    return function(items, props) {
        var out = [];

        if (angular.isArray(items)) {
            var keys = Object.keys(props);

            items.forEach(function(item) {
                var itemMatches = false;

                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();

                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }

                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
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


app.controller('LogotypesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter','ngDialog', '$timeout','$route','$routeParams', '$rootScope', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, ngDialog, $timeout,$route, $routeParams, $rootScope,$sce) {


    $scope.initData = function(){


        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            name:true,
            rotor_title:true
        };


        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);


    }


    //Get Data Logic

    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/logotypes',
            {
                start:$scope.start,
                limit:$scope.limit,
                frase:$scope.frase,
                searchcolumns:$scope.searchcolumns,
                lang:$scope.lang
            })
            .then(
                function successCallback(response) {

                    $log.info(response.data);

                    $scope.count = response.data.count;

                    if(iterstart==null){
                        $scope.iterstart = $scope.start;
                    }else{
                        $scope.iterstart = iterstart;
                    }

                    $scope.elements = response.data.elements;
                    $scope.allpags = response.data.count/$scope.limit;
                    $scope.even = $scope.allpags%1;
                    $scope.pages = Math.floor($scope.allpags);
                    $scope.half = Math.floor($scope.pages/2);


                    if($scope.pages>(2*$scope.limit)){

                        $scope.first = [];
                        $scope.last = [];

                        if( iterstart>($scope.pages-5) && !$scope.halfclick) {


                            var iterlimit = 3;
                            if (iterstart >= ($scope.half - 2)) {
                                iterlimit = 5;
                            }


                            var x = 0;

                            for (var i = 0; i < iterlimit; i++) {

                                if (iterstart == i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: 'active'};

                                } else {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: ''};
                                }

                                x++;
                            }

                        }else if($scope.halfclick){


                            var x = 0;

                            for (var i = $scope.start; i < ($scope.start+3); i++) {

                                if (iterstart == i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: 'active'};

                                } else {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: ''};
                                }

                                x++;
                            }


                        }else {

                            var x = 0;

                            for (var i = iterstart; i < (iterstart + 3); i++) {


                                if(iterstart==i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass:'active'};

                                }else{

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass:''};

                                }

                                x++;
                            }

                        }


                        ///////////////////////////Last Buttons/////////////////////////////////////////////////

                        var plusiter = 1;
                        if($scope.even==0){
                            plusiter = 0;
                        }

                        if( (iterstart<$scope.pages) && !$scope.halfclick) {

                            var y = 0;

                            var backfromend = 3;

                            //jeżeli z przodu jest jest już ottanie takie jakie z przodu
                            if(iterstart<$scope.pages && iterstart>($scope.pages-5)){
                                backfromend = 4;
                            }else{
                                backfromend = 3;
                            }
                            //jeżeli z przodu jest jest już ottanie takie jakie z przodu



                            for (var i = ($scope.pages + plusiter); i > ($scope.pages - backfromend); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }


                        }else if($scope.halfclick){


                            var y = 0;


                            for (var i = ($scope.pages + plusiter); i > ($scope.pages - 3); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }



                        }else {

                            //console.log($scope.pages);

                            var y = 0;

                            var minusright = $scope.pages-$scope.iterstart;

                            for (var i = (($scope.pages + plusiter)- minusright); i > ($scope.pages - 3 - minusright); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }


                        }

                        $scope.last.reverse();

                        $scope.minimalist = true;



                    }else{

                        var reduct = 0;

                        if($scope.even==0){
                            reduct = 1;
                        }


                        $scope.pag = [];

                        for(var i=0;i<=($scope.pages-reduct);i++){

                            if(iterstart==i) {

                                $scope.pag[i] = {start: i, nr: (i + 1), pclass:'active'};

                            }else{

                                $scope.pag[i] = {start: i, nr: (i + 1), pclass:''};

                            }
                        }

                        $scope.minimalist = false;

                    }


                },
                function errorCallback(response) {

                }
            );

    }


    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);


    }


    $scope.goToHalf = function(){

        $scope.halfclick = true;
        $scope.start = $scope.half;
        $scope.getElements($scope.start);

    }


    $scope.changePagePrev = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart-1;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);

    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        $scope.getElements(0);

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        $scope.getElements($scope.pages);

    }


    //Get Data Logic

    $scope.searchSubmit = function(){

        $scope.start = 0;
        $scope.getElements(0);

    }


    $scope.changeData = function(field ,value , id){

        $http.put(AppService.url+'/change/member/data', {field: field, value:value, id:id})
            .then(
                function successCallback(response){
                    $scope.getElements();
                },
                function errorCallback(reason){

                }
            )
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.openSubWindow = function(temp,klasa){
        ngDialog.open({
            scope: $scope,
            template: temp,
            className: klasa,
            cache: true,
            overlay: true,
            closeByDocument:false,
        });
        $scope.$on('ngDialog.closed', function (e, $dialog) {
            console.log('zamykam')
        });
    }

    $scope.confirmRemove = function(fn,item){
        $scope.forConfirmData = {
            fn: fn,
            item: item,
            query: "Czy chcesz usunąć logotypy: <br />"+item.name+"?",
        };
        $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }

    $scope.deleteThisElement = function(data){
        // console.log('co mom ',data.item.id)
        $http.delete(AppService.url+'/delete/logotypes/element/'+data.item.id)
            .then(
                function successCallback(response){
                    console.log(response);
                    $scope.getElements();
                }
            )

    }

    $scope.toTrustedHTML = function( html ){
        return $sce.trustAsHtml( html );
    }

    $scope.delegate = function(fn,data){
        fn(data);
    }



}]);



app.controller('EditLogotypesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope','ngDialog','$sce', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,ngDialog,$sce,Upload) {


    $scope.initData = function(){

        $scope.disk = 'logotypes';
        $scope.data = {};
        $scope.classes = {
            name:'',
            rotor_title:''
        };
        $scope.lcollection = [];
        $scope.lid = $routeParams.id;

        $scope.getLogoTypes()
            .then(function(res){
                console.log(res);
                $scope.data.name = res.name;
                $scope.data.rotor_title = res.rotor_title;
                $scope.lcollection = res.logotypes;
            })

    }


    $scope.getLogoTypes = function(){

        var defer = $q.defer();

        $http.get(AppService.url+'/get/logotypes/record/'+$scope.lid)
            .then(function(res){
                defer.resolve(res.data);
            });

        return defer.promise;

    }


    $scope.checkName = function(){

        if($scope.data.name.length>0){

            $scope.classes.name = '';

        }else{

            $scope.classes.name = 'has-error';

        }

    }


    $scope.checkRotorTitle = function(){

        if($scope.data.rotor_title.length>0){

            $scope.classes.rotor_title = '';

        }else{

            $scope.classes.rotor_title = 'has-error';

        }

    }


    $scope.removeLogo = function($index){

        $scope.lcollection.splice($index,1);

    }


    $scope.updateLogotypes = function(){


        $scope.checkName();
        $scope.checkRotorTitle();

        if($scope.classes.rotor_title=='' && $scope.classes.name=='') {

            $http.post(AppService.url + '/update/logotypes/data', {
                    id: $scope.lid,
                    logotypes: $scope.lcollection,
                    data: $scope.data
                })
                .then(function (res) {
                    console.log(res);

                })

        }

    }


    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('file', function () {

        if($scope.file){
            $scope.uploadFileLogo($scope.file);
        }

    });


    $scope.uploadFileLogo = function (file) {

        //console.log($scope.file.name.split('.').pop());
        var local = new Date();
        $scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();

        if(file) {

            Upload.upload({
                url: AppService.url + '/resources/upload/logotype',
                fields: { disk: $scope.disk, suffix:$scope.file.name.split('.').pop()},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' +
                    evt.config.file.name + '\n' + $scope.log;

                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                $log.info(data);
                //$log.info($scope.uploaded);
                $scope.progress = 0;

                $scope.lcollection.unshift({link:'',logo_uri:data.real_path});

                //$timeout(function() {
                //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                //});
            }).error(function (data, status, headers, config) {
                //$log.info(data);
            });

        }

    };





}]);



app.controller('NewLogotypesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope','ngDialog','$sce', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,ngDialog,$sce,Upload) {


    $scope.initData = function(){

        $scope.disk = 'logotypes';
        $scope.data = {
            name:'',
            rotor_title:''
        };
        $scope.classes = {
            name:'start',
            rotor_title:'start'
        };
        $scope.lcollection = [];

    }



    $scope.removeLogo = function($index){

        $scope.lcollection.splice($index,1);

    }


    $scope.checkName = function(){

        if($scope.data.name.length>0){

            $scope.classes.name = '';

        }else{

            $scope.classes.name = 'has-error';

        }

    }


    $scope.checkRotorTitle = function(){

        if($scope.data.rotor_title.length>0){

            $scope.classes.rotor_title = '';

        }else{

            $scope.classes.rotor_title = 'has-error';

        }

    }



    $scope.createLogotypes = function(){


        $scope.checkName();
        $scope.checkRotorTitle();

        if($scope.classes.rotor_title=='' && $scope.classes.name=='') {

            $http.post(AppService.url + '/create/logotypes', {
                    id: $scope.lid,
                    logotypes: $scope.lcollection,
                    data: $scope.data,
                    lang_id: $rootScope.lang
                })
                .then(function (res) {
                    console.log(res);
                    $location.path('/edit/'+res.data.id);
                })

        }

    }



    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('file', function () {

        if($scope.file){
            $scope.uploadFileLogo($scope.file);
        }

    });


    $scope.uploadFileLogo = function (file) {

        //console.log($scope.file.name.split('.').pop());
        var local = new Date();
        $scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();

        if(file) {

            Upload.upload({
                url: AppService.url + '/resources/upload/logotype',
                fields: { disk: $scope.disk, suffix:$scope.file.name.split('.').pop()},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' +
                    evt.config.file.name + '\n' + $scope.log;

                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                $log.info(data);
                //$log.info($scope.uploaded);
                $scope.progress = 0;

                $scope.lcollection.unshift({link:'',logo_uri:data.real_path});

                //$timeout(function() {
                //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                //});
            }).error(function (data, status, headers, config) {
                //$log.info(data);
            });

        }

    };

}]);





app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/logotypes/master.html',
        controller: 'LogotypesController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/logotypes/new-logotypes.html',
        controller: 'NewLogotypesController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/logotypes/edit-logotypes.html',
        controller: 'EditLogotypesController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});