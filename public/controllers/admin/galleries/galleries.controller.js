var app = angular.module('app',['ngSanitize', 'ngRoute', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select', 'ui.sortable','ngDialog'], function($interpolateProvider) {
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
        templateUrl: '/templates/admin/super/galleries/search.html'
    };
});


app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});





app.directive('mediaLibrary', function() {
    return {
        templateUrl: '/templates/admin/super/media-library-galleries.html',
        link: function(scope, element, attributes){

            //console.log(attributes);

        }
    };
});


app.directive('mediaLibraryBase', function($http,AppService) {
    return {
        templateUrl: '/templates/admin/super/media-library-galleries-base.html',
        link: function(scope, element, attributes){

            scope.data = {
                searchcolumns:{
                    translations:true
                },
                frase:''
            }


            scope.baseimages = null;


            scope.searchPhoto = function() {

                $http.post(AppService.url + '/get/all/pictures/by/criteria', scope.data)
                    .then(
                        function successCallback(response) {
                            //console.log(response.data);
                            scope.baseimages = response.data;
                        },
                        function errorCallback(reason) {
                            //console.log(reason);

                        }
                    )

            }

            scope.searchPhoto();

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





app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });

}]);


app.controller('GalleriesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope','ngDialog','$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,ngDialog,$sce) {


    $scope.initData = function(){



        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            title:true,
            alias:true
        };

        $scope.filter = {
            status: {name:'Wszystkie', value:null}
        }

        $scope.statuses = [
            {name:'Wszystkie', value:null},
            {name:'Opublikowany', value:1},
            {name:'Nieopublikowany', value:0}
        ];

        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);



    }





    //Get Data Logic

    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/galleries',
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

    $scope.confirmRemove = function(fn,gallery){
        $scope.forConfirmData = {
            fn: fn,
            item: gallery,
            query: "Czy chcesz usunąć wydarzenie: <br />"+gallery.title+"?",
        };
        $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }

    $scope.deleteThisElement = function(data){
        // console.log('co mom ',data.item.id)
        $http.delete(AppService.url+'/delete/gallery/element/'+data.item.id)
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


app.controller('NewGalleryController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {

    $scope.initData = function(){

        $scope.galdata = {
            title:'',
            pictures:''
        }

        $scope.classes = {
            title:''
        }

        $scope.valid = {
            title:false
        }


        $scope.disk = 'pictures';

        $scope.viewtype = 'folder';

        $scope.upload_dir = null;

        $scope.uploaded = [];

        $scope.gallery = [];

        $scope.descclasses = [];

        //$scope.cropper = {};
        //$scope.cropper.sourceImage = null;
        //$scope.cropper.croppedImage   = null;
        //$scope.bounds = {};
        //$scope.bounds.left = 0;
        //$scope.bounds.right = 0;
        //$scope.bounds.top = 0;
        //$scope.bounds.bottom = 0;

        $scope.langdesctem = null;

        $scope.categories = null;

        $scope.if_title_valid = true;

        $scope.if_gallery_valid = true;

        $scope.getAllLanguages();
        $scope.getFoldersRecursion();

        $scope.options = {
            filesProperty: null,
            onNodeSelect: function (node, breadcrumbs) {
                //console.log(node);
                //console.log(breadcrums);
                $scope.node = node;
                $scope.breadcrumbs = breadcrumbs;
            },
            selectFile: function (file, breadcrumbs) {
                $scope.file = file;
            }
        };


    }


    $scope.getAllLanguages = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/languages')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.langdesc = {};

                    angular.forEach(response.data, function(item, key){

                        $scope.langdesc[key] = '';

                    });

                    //console.log($scope.langdesc);

                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }


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
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }




    $scope.$watch('node', function(nVal, oVal){

        if(nVal!=null) {
            if (nVal.files) {
                //console.log('folder', nVal);
                $scope.viewtype = 'folder';
                $scope.showFolderView(nVal);
            } else {
                //console.log('file', nVal);
                $scope.viewtype = 'file';
                $scope.showFileView(nVal);
            }
        }

    });


    $scope.$watch('breadcrumbs', function(nVal, oVal){

        if(nVal==undefined){

            $scope.upload_dir = $scope.disk+'/';

        }else{

            //$log.info($scope.viewtype);

            switch ($scope.viewtype){
                case 'folder':

                    $scope.upload_dir = '';
                    angular.forEach(nVal, function(item, key){

                        $scope.upload_dir += item+'/'

                    });

                    break;

                case 'file':

                    console.log(nVal);
                    $scope.upload_dir = '';
                    angular.forEach(nVal, function(item, key){

                        if(key==(nVal.length-1))
                            return;

                        $scope.upload_dir += item+'/'

                    });

                    break;
            }

        }
    });

    $scope.showFolderView = function(data){

        //console.log(data);

        $http.put(AppService.url+'/pictures/get/folder/'+$scope.disk, data)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.folderfiles = response.data;
                    $scope.viewtype = 'folder';
                },
                function errorCallback(reason){
                    //console.log(reason);

                }
            )

    }

    $scope.showFileView = function(data){

        data.breadcrumbs = $scope.breadcrumbs;

        $http.put(AppService.url+'/pictures/get/file/'+$scope.disk, data)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.viewtype = 'file';
                    //$scope.cropper.croppedImage = response.data;
                },
                function errorCallback(reason){
                    //console.log(reason);

                }
            )

    }




    ///////////////////////////////////////////////////////////////////////////////////////////////////////



    $scope.addImageToGalleryFromLib = function(file){
        if(file.base){
            file.desc = JSON.parse(file.desc);
        }
        $scope.gallery.push(file);

    }




    $scope.removeImageFromGallery = function(item, $index){

        var narray = [];

        $scope.gallery.splice($index, 1);
        angular.forEach($scope.gallery, function(el, key){

            narray.push(el);

        });

        $scope.gallery = narray;

    }


    $scope.sortableOptions = {
        update: function(e, ui) {

        },
        axis: 'x'
    };

    ////////////////////////////////////Upload Files///////////////////////////////////////////////////////


    $scope.$watch('files', function () {
        $scope.uploadFiles($scope.files);
    });


    $scope.uploadFiles = function (files) {


        //console.log(files);


        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {

                var file = files[i];

                Upload.upload({
                    url: AppService.url + '/resources/upload/images',
                    fields: {upload_dir:$scope.upload_dir, disk:$scope.disk},
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    //$log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {

                    $log.info(data);
                    $scope.uploaded.push(data);
                    $scope.gallery.push(data);
                    $scope.progress = 0;


                    $timeout(function() {
                        $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    });
                }).error(function(data, status, headers, config) {
                    //$log.info(data);
                });
            }
        }
    };



    ////////////////////////////////////Upload Files///////////////////////////////////////////////////////

    $scope.checkIsTitle = function(){


        if($scope.galdata.title.length>0 && $scope.galdata.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }


    }


    $scope.saveNewGallery = function(){

        var boolean = [];
        boolean.push($scope.checkIsTitle());

        if($scope.gallery.length==0){
            boolean.push(false);
            $scope.if_gallery_valid = false;
        }else{
            $scope.if_gallery_valid = true;
        }

        //console.log($scope.gallery);
        //console.log($scope.galdata);

        if($filter('checkfalsearray')(boolean)) {

            $http.put(AppService.url + '/create/new/gallery', {title: $scope.galdata.title, gallery: $scope.gallery})
                .then(
                    function successCallback(response) {

                        //console.log(response.data);
                        $location.path('/edit/'+response.data.id).search('action=updated');

                    },
                    function errorCallback(reason) {

                    }
                )

        }

    }




}]);


app.controller('EditGalleryController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {

    $scope.initData = function(){

        $scope.added = 'hidden';
        $scope.updated = 'hidden';

        if($routeParams.action){

            switch ($routeParams.action){

                case 'added':

                    $scope.added = '';
                    $scope.updated = 'hidden';

                    break;

                case 'updated':

                    $scope.added = 'hidden';
                    $scope.updated = '';

                    console.log($routeParams);

                    break;

            }

        }


        $scope.galdata = {
            id:$routeParams.id,
            title:'',
            pictures:''
        }

        $scope.classes = {
            title:''
        }

        $scope.valid = {
            title:false
        }


        $scope.disk = 'pictures';

        $scope.viewtype = 'folder';

        $scope.upload_dir = null;

        $scope.uploaded = [];

        $scope.gallery = [];

        $scope.descclasses = [];

        //$scope.cropper = {};
        //$scope.cropper.sourceImage = null;
        //$scope.cropper.croppedImage   = null;
        //$scope.bounds = {};
        //$scope.bounds.left = 0;
        //$scope.bounds.right = 0;
        //$scope.bounds.top = 0;
        //$scope.bounds.bottom = 0;

        $scope.langdesctem = null;

        $scope.categories = null;

        $scope.if_title_valid = true;

        $scope.if_gallery_valid = true;

        $scope.getAllLanguages();
        $scope.getFoldersRecursion();
        $scope.getFullGalleryData();

        $scope.options = {
            filesProperty: null,
            onNodeSelect: function (node, breadcrumbs) {
                //console.log(node);
                //console.log(breadcrums);
                $scope.node = node;
                $scope.breadcrumbs = breadcrumbs;
            },
            selectFile: function (file, breadcrumbs) {
                $scope.file = file;
            }
        };


    }


    $scope.getFullGalleryData = function(){

        $http.get(AppService.url+'/get/full/gallery/data/'+$scope.galdata.id, {})
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.gallery = response.data.pictures;
                    $scope.galdata.title = response.data.gallery.title;

                },
                function errorCallback(reason){

                }
            )

    }


    $scope.getAllLanguages = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/languages')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.langdesc = {};

                    angular.forEach(response.data, function(item, key){

                        $scope.langdesc[key] = '';

                    });

                    //console.log($scope.langdesc);

                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }


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
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }



    $scope.$watch('node', function(nVal, oVal){

        if(nVal!=null) {
            if (nVal.files) {
                //console.log('folder', nVal);
                $scope.viewtype = 'folder';
                $scope.showFolderView(nVal);
            } else {
                //console.log('file', nVal);
                $scope.viewtype = 'file';
                $scope.showFileView(nVal);
            }
        }

    });


    $scope.$watch('breadcrumbs', function(nVal, oVal){

        if(nVal==undefined){

            $scope.upload_dir = $scope.disk+'/';

        }else{

            //$log.info($scope.viewtype);

            switch ($scope.viewtype){
                case 'folder':

                    $scope.upload_dir = '';
                    angular.forEach(nVal, function(item, key){

                        $scope.upload_dir += item+'/'

                    });

                    break;

                case 'file':

                    //console.log(nVal);
                    $scope.upload_dir = '';
                    angular.forEach(nVal, function(item, key){

                        if(key==(nVal.length-1))
                            return;

                        $scope.upload_dir += item+'/'

                    });

                    break;
            }

        }
    });

    $scope.showFolderView = function(data){

        //console.log(data);

        $http.put(AppService.url+'/pictures/get/folder/'+$scope.disk, data)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.folderfiles = response.data;
                    $scope.viewtype = 'folder';
                },
                function errorCallback(reason){
                    //console.log(reason);

                }
            )

    }

    $scope.showFileView = function(data){

        data.breadcrumbs = $scope.breadcrumbs;

        $http.put(AppService.url+'/pictures/get/file/'+$scope.disk, data)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.viewtype = 'file';
                    //$scope.cropper.croppedImage = response.data;
                },
                function errorCallback(reason){
                    //console.log(reason);

                }
            )

    }




    ///////////////////////////////////////////////////////////////////////////////////////////////////////



    $scope.addImageToGalleryFromLib = function(file){
        if(file.base){
            file.desc = JSON.parse(file.desc);
        }
        $scope.gallery.push(file);

    }




    $scope.removeImageFromGallery = function(item, $index){

        var narray = [];

        $scope.gallery.splice($index, 1);
        angular.forEach($scope.gallery, function(el, key){

            narray.push(el);

        });

        $scope.gallery = narray;

    }


    $scope.sortableOptions = {
        update: function(e, ui) {

        },
        axis: 'x'
    };

    ////////////////////////////////////Upload Files///////////////////////////////////////////////////////


    $scope.$watch('files', function () {
        $scope.uploadFiles($scope.files);
    });


    $scope.uploadFiles = function (files) {


        //console.log(files);


        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {

                var file = files[i];

                Upload.upload({
                    url: AppService.url + '/resources/upload/images',
                    fields: {upload_dir:$scope.upload_dir, disk:$scope.disk},
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    //$log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {

                    //$log.info(data);
                    $scope.uploaded.push(data);
                    $scope.gallery.push(data);
                    $scope.progress = 0;


                    $timeout(function() {
                        $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    });
                }).error(function(data, status, headers, config) {
                    //$log.info(data);
                });
            }
        }
    };



    ////////////////////////////////////Upload Files///////////////////////////////////////////////////////

    $scope.checkIsTitle = function(){


        if($scope.galdata.title.length>0 && $scope.galdata.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }


    }


    $scope.updateGallery = function(){

        var boolean = [];
        boolean.push($scope.checkIsTitle());

        if($scope.gallery.length==0){
            boolean.push(false);
            $scope.if_gallery_valid = false;
        }else{
            $scope.if_gallery_valid = true;
        }

        //console.log($scope.gallery);
        //console.log($scope.galdata);

        if($filter('checkfalsearray')(boolean)) {

            $http.put(AppService.url + '/update/gallery/'+$scope.galdata.id, {title: $scope.galdata.title, gallery: $scope.gallery})
                .then(
                    function successCallback(response) {

                        console.log(response.data);
                        //$location.path('/edit/'+response.data.id).search('action=updated');

                    },
                    function errorCallback(reason) {

                    }
                )

        }

    }


}]);



app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/galleries/master.html',
        controller: 'GalleriesController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/galleries/new-gallery.html',
        controller: 'NewGalleryController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/galleries/edit-gallery.html',
        controller: 'EditGalleryController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});