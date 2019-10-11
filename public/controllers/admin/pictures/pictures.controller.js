var app = angular.module('app',['ngSanitize', 'ngRoute','AxelSoft'], function($interpolateProvider) {
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


app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});

app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/contents/search.html'
    };
});


app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/contents/status.html',
        link: function(scope, element, attributes, searchFactory){

            console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.elementid;

        }
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


app.controller('PicturesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope) {


    $scope.initData = function(){

        /*
         $table->integer('language_id')->unsigned();
         $table->foreign('language_id')->references('id')->on('languages');
         $table->string('title', 500);
         $table->string('alias', 500);
         $table->string('image',1000)->nullable();
         $table->string('disk',25)->nullable();
         $table->text('intro');
         $table->text('content');
         $table->enum('type', ['internal', 'external'])->default('internal');
         $table->string('url')->nullable();
         $table->text('params');
         $table->text('meta_description');
         $table->text('meta_keywords');
         $table->tinyInteger('status')->default(0);
         $table->timestamps();
         */

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            title:true,
            alias:true,
            intro:true,
            content:true,
            url:true
        };

        $scope.filter = {
            status: {name:'Wszystkie', value:null},
            type: {name:'Wszystkie', value:null}
        }

        $scope.statuses = [
            {name:'Wszystkie', value:null},
            {name:'Opublikowany', value:1},
            {name:'Nieopublikowany', value:0}
        ];

        $scope.types = [
            {name:'Wszystkie', value:null},
            {name:'Wewnętrzny', value:'internal'},
            {name:'Zewnętrzny', value:'external'}
        ];


        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        //$scope.getElements(0);




    }





    //Get Data Logic

    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/contents',
            {
                start:$scope.start,
                limit:$scope.limit,
                frase:$scope.frase,
                searchcolumns:$scope.searchcolumns,
                filter:$scope.filter,
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



}]);


app.controller('PicturesFoldersController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope) {

    $scope.initData = function(){

        $scope.node = null;
        $scope.disk = 'pictures';
        $scope.viewtype = null;
        $scope.folderfiles = null;

        $scope.getFoldersRecursion();

        $scope.options = {
            onNodeSelect: function (node, breadcrums) {
                //console.log(node);
                //console.log(breadcrums);
                $scope.node = node;
                $scope.breadcrums = breadcrums;
            },
            selectFile: function (file, breadcrumbs) {
                //console.log(file);
                //console.log(breadcrumbs);
                $scope.file = file;
            }
        };



    }


    $scope.$watch('node', function(nVal, oVal){

        if(nVal!=null) {
            if (nVal.files) {
                //console.log('folder', nVal);
                $scope.showFolderView(nVal);
            } else {
                //console.log('file', nVal);
                $scope.showFileView(nVal);
            }
        }

    });




    $scope.getFoldersRecursion = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/pictures/folder/tree/'+$scope.disk)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.structure = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }


    $scope.showFolderView = function(data){

        $http.put(AppService.url+'/pictures/get/folder/'+$scope.disk, data)
            .then(
                function successCallback(response){
                    console.log(response.data);
                    $scope.folderfiles = response.data;
                    $scope.viewtype = 'folder';
                },
                function errorCallback(reason){
                    //console.log(reason);

                }
            )

    }

    $scope.showFileView = function(data){

        $http.put(AppService.url+'/pictures/get/file/'+$scope.disk, data)
            .then(
                function successCallback(response){
                    console.log(response.data);
                    $scope.viewtype = 'file';
                },
                function errorCallback(reason){
                    //console.log(reason);

                }
            )

    }


}]);




app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/pictures/master.html',
        controller: 'PicturesController'
    }).
    when('/:pictures', {
        templateUrl: '/templates/admin/super/pictures/pictures-folder.html',
        controller: 'PicturesFoldersController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });


});