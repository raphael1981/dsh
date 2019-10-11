var app = angular.module('app',['ngSanitize', 'ngRoute', 'xeditable','ngDialog'], function($interpolateProvider) {
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


app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});

app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/categories/search.html'
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


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });

}]);


app.controller('CategoriesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope','ngDialog','$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope, ngDialog, $sce) {


    $scope.initData = function(){



        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            name:true
        };


        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);


    }





    //Get Data Logic

    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/categories',
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

///////////////////////////////////////////////////////////////////////

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

    $scope.confirmRemove = function(fn,content){
        $scope.forConfirmData = {
            fn: fn,
            item: content,
            query: "Czy masz niezachwianą pewność, że chcesz usunąć kategorię: <br />"+content.name+"?",
        };
        $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }

    $scope.deleteThisElement = function(data){
        // console.log('co mom ',data.item.id)

        $http.delete(AppService.url+'/delete/category/element/'+data.item.id)
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

///////////////////////////////////////////////////////////////////////

}]);


app.controller('NewCategoryController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope) {

    $scope.initData = function(){

        $scope.data = {
            lang:$rootScope.lang,
            name:'',
            color:null,
            icon:null
        }

        $scope.classes = {
            name:''
        }

        $scope.valid = {
            name:false
        }

        $scope.params = {
            colors:null,
            svg:null
        }

        $scope.alerts ={
            is_in_base:false
        }

        //$scope.getParamsData();

    }

    $scope.getParamsData = function(){

        $http.get(AppService.url+'/categories/params/get')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.params.svg = [];
                    angular.forEach(response.data.svg, function(item){
                        $scope.params.svg.push(item.replace('//', '/'));
                    });
                    console.log($scope.params);

                },
                function errorCallback(reason) {

                }
            )

    }


    //////////////////////////////////////////////////////////////////////////////////////////////////

    //$scope.setColor = function($event, color){
    //
    //    angular.element(document.getElementsByClassName('color-select')).removeClass('selected');
    //    angular.element($event.target).addClass('selected');
    //    $scope.data.color = color;
    //
    //}

    $scope.setSvg = function($event, svg){
        angular.element(document.getElementsByClassName('svg-show')).removeClass('selected');
        angular.element($event.target).parent().addClass('selected');
        $scope.data.icon = svg;
    }


    ///////////////////////////////////////////////////////////////////////////////////////

    $scope.checkName = function(){


        $scope.alerts.is_in_base = false;

        if($scope.data.name.length>1){

            $scope.classes.name = 'has-success';
            $scope.valid.name = true;

        }else{

            $scope.classes.name = 'has-error';
            $scope.valid.name = false;

        }

    }


    $scope.checkIsCategory = function(){
        var deferred = $q.defer();

        $http.put(AppService.url+'/check/is/category', $scope.data)
            .then(
                function successCallback(response){

                    //console.log(response);
                    if(!response.data.success){
                        deferred.resolve(1)
                    }else{
                        deferred.resolve(0)
                    }

                },
                function errorCallback(reason){
                    deferred.reject(0)
                }
            )

        return deferred.promise;

    }


    $scope.saveNewCategory = function(){

        $scope.checkName();

        if($filter('checkfalse')($scope.valid)){

            $scope.checkIsCategory()
                .then(
                    function(response){
                        console.log(response);
                        if(response==1){
                            $scope.classes.name = '';
                            $scope.alerts.is_in_base = false;
                            $scope.addToBase()
                                .then(
                                    function(response){
                                        $location.path('/edit/'+response.data.data.id).search('action=added');
                                    },
                                    function(reason){

                                    }
                                );
                        }else{
                            $scope.classes.name = 'has-error';
                            $scope.alerts.is_in_base = true;
                        }
                    },
                    function(reason){
                        //console.log(reason);
                    }
                )

        }

    }


    $scope.addToBase = function(){

        var deferred = $q.defer();

        $http.put('/create/new/category', $scope.data)
            .then(

                function successCallback(response){
                    console.log(response);
                    deferred.resolve(response)
                },
                function errorCallback(reason){

                    deferred.reject(0);

                }

            )

        return deferred.promise
    }

}]);


app.controller('EditCategoryController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope) {

    $scope.initData = function(){

        console.log($routeParams.id);

        $scope.data = {
            id:$routeParams.id,
            lang:$rootScope.lang,
            name:'',
            color:null,
            icon:null
        }

        $scope.classes = {
            name:''
        }

        $scope.valid = {
            name:false
        }

        $scope.params = {
            colors:null,
            svg:null
        }

        $scope.alerts ={
            is_in_base:false
        }


        $scope.getParamsData()
            .then(
                function(response){
                    if(response==1) {
                        $scope.getCategoryData();
                    }
                }
            )



    }


    $scope.getCategoryData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/category/full/data/'+$scope.data.id)
            .then(
                function successCallback(response){
                    $scope.data.name = response.data.name;
                    //var params = JSON.parse(response.data.params);
                    //$scope.data.svg = params.icon;
                    $scope.ctite = response.data.name;

                    //angular.forEach(angular.element(document.getElementsByClassName('svg-show')), function(item, iter){
                    //    if(angular.element(item).find('img').attr('src')==$scope.data.svg){
                    //        angular.element(item).addClass('selected');
                    //    }
                    //});

                    deferred.resolve(1)

                },
                function errorCallback(reason){
                    deferred.reject(0)
                }
            )

        return deferred.promise;

    }

    $scope.getParamsData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/categories/params/get')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.params.svg = [];
                    angular.forEach(response.data.svg, function(item){
                        $scope.params.svg.push(item.replace('//', '/'));
                    });
                    console.log($scope.params);

                    deferred.resolve(1)

                },
                function errorCallback(reason) {

                    deferred.reject(0)

                }
            )

        return deferred.promise;

    }


    //////////////////////////////////////////////////////////////////////////////////////////////////

    //$scope.setColor = function($event, color){
    //
    //    angular.element(document.getElementsByClassName('color-select')).removeClass('selected');
    //    angular.element($event.target).addClass('selected');
    //    $scope.data.color = color;
    //
    //}

    $scope.setSvg = function($event, svg){
        angular.element(document.getElementsByClassName('svg-show')).removeClass('selected');
        angular.element($event.target).parent().addClass('selected');
        $scope.data.icon = svg;
    }


    ///////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////

    $scope.checkName = function(){


        $scope.alerts.is_in_base = false;

        if($scope.data.name.length>1){

            $scope.classes.name = 'has-success';
            $scope.valid.name = true;

        }else{

            $scope.classes.name = 'has-error';
            $scope.valid.name = false;

        }

    }


    $scope.checkIsCategory = function(){
        var deferred = $q.defer();

        $scope.data.constcat = $scope.ctite;

        $http.put(AppService.url+'/check/is/category/except/current', $scope.data)
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    if(!response.data.success){
                        deferred.resolve(1)
                    }else{
                        deferred.resolve(0)
                    }

                },
                function errorCallback(reason){
                    deferred.reject(0)
                }
            )

        return deferred.promise;

    }


    $scope.updateCategory = function(){

        $scope.checkName();

        if($filter('checkfalse')($scope.valid)){

            $scope.checkIsCategory()
                .then(
                    function(response){
                        console.log(response);
                        if(response==1){
                            $scope.classes.name = '';
                            $scope.alerts.is_in_base = false;
                            $scope.editInBase()
                                .then(
                                    function(response){
                                        $location.path('/edit/'+response.data.data.id).search('action=updated');
                                    },
                                    function(reason){

                                    }
                                );
                        }else{
                            $scope.classes.name = 'has-error';
                            $scope.alerts.is_in_base = true;
                        }
                    },
                    function(reason){
                        //console.log(reason);
                    }
                )

        }

    }


    $scope.editInBase = function(){

        var deferred = $q.defer();

        $http.put('/update/category', $scope.data)
            .then(

                function successCallback(response){
                    console.log(response);
                    deferred.resolve(response)
                },
                function errorCallback(reason){

                    deferred.reject(0);

                }

            )

        return deferred.promise
    }



}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/categories/master.html',
        controller: 'CategoriesController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/categories/new-category.html',
        controller: 'NewCategoryController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/categories/edit-category.html',
        controller: 'EditCategoryController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});