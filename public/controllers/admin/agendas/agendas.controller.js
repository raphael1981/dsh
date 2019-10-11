var app = angular.module('app',['ngSanitize', 'ngRoute', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select','tree.directives','ckeditor','ngDialog','selectRotors','updateRotor'], function($interpolateProvider) {
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
        templateUrl: '/templates/admin/super/agendas/search.html'
    };
});


app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/agendas/status.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.elementid;

        }
    };
});


app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});


app.directive('fastAddPlace', function() {
    return {
        templateUrl: '/templates/admin/super/agendas/fast-add-place.html',
        link: function(scope, element, attributes){

            //console.log(attributes);

        }
    };
});


app.directive('galleryShow', function($http, AppService) {
    return {
        templateUrl: '/templates/admin/super/agendas/gallery-show.html',
        link: function(scope, element, attributes){

            //console.log(attributes);
            $http.get(AppService.url+'/get/gallery/by/id/'+attributes.galleryShow)
                .then(
                    function successCallback(response){
                        //console.log(response.data);
                        scope.gallery = response.data;
                    },
                    function errorCallback(reason){

                    }
                )

        }
    };
});


app.directive('attachmentLibrary', function($rootScope,Upload,$http,AppService) {
    return {
        templateUrl: '/templates/admin/super/agendas/attachment-library.html',
        link: function(scope, element, attributes){

            //console.log(attributes);
            scope.attachments=[];
            scope.disk = 'media';
            scope.open_attach_cloud = true;
            scope.upload_path='/';
            scope.flname = '';
            scope.errorfolder = '';
            scope.current_node = null;
            scope.hideattachcloud = 'hidden';

            scope.$on('showcloud-attach',function(event,args){
                scope.hideattachcloud = '';
            });


            scope.$watchCollection('attachments', function(){
                $rootScope.$broadcast('attachments-add',scope.attachments);
            });


            scope.TreeBulid = function() {

                $http.get(AppService.url + '/media/folder/tree/' + scope.disk)
                    .then(
                        function successCallback(response) {
                            //console.log(response.data);
                            scope.structure = response.data;

                        },
                        function errorCallback(reason) {
                            //console.log(reason);
                        }
                    )

            }


            scope.TreeBulid();


            scope.$watch('breadcrumbs', function(nVal, oVal){


                if(nVal!=undefined) {

                    if (nVal.length > 1) {
                        scope.upload_path = '';

                        angular.forEach(scope.breadcrumbs, function (item, iter) {

                            if (iter != 0) {
                                scope.upload_path += '/' + item;
                            }

                        });

                    } else {
                        scope.upload_path = '/';
                    }


                }

                //console.log(scope.upload_path);

            });


            scope.$watch('node', function(nVal, oVal){

                scope.current_node = nVal;

                if(nVal!=null) {
                    if (nVal.files) {
                        //console.log('folder', nVal);
                        scope.showFolderView(nVal);
                    } else {
                        //console.log('file', nVal);
                        scope.showFileView(nVal);
                    }
                }

            });


            scope.showFolderView = function(data){

                $http.put(AppService.url+'/media/get/folder/'+scope.disk, data)
                    .then(
                        function successCallback(response){
                            //console.log(response.data);
                            scope.folderfiles = response.data;
                        },
                        function errorCallback(reason){
                            //console.log(reason);

                        }
                    )

            }


            scope.addNewFolder = function(){

                if(scope.flname.length>0) {

                    scope.errorfolder = '';

                    var addpath = null;
                    //console.log(scope.structure);
                    //$rootScope.$broadcast('add-new-folder',scope.current_node);

                    if (scope.current_node != undefined) {

                        if (scope.current_node.path == '/') {
                            addpath = scope.disk + scope.current_node.path;
                        } else {
                            addpath = scope.disk + scope.current_node.path + '/';
                        }

                    } else {
                        addpath = scope.disk + '/';
                    }

                    //console.log(addpath);

                    $http.put(AppService.url + '/create/media/in/path', {addpath: addpath, folder: scope.flname})
                        .then(
                            function successCallback(response) {
                                console.log(response.data);
                                scope.TreeBulid();
                                scope.showFolderView(scope.current_node);
                            }
                        )

                }else{
                    scope.errorfolder = 'has-error';
                }
            }

            scope.attachFileToCollection = function(file,$index){

                var pushboolean = true;

                angular.forEach(scope.attachments, function(item){
                    if(item===file)
                        pushboolean=false;
                });

                if(pushboolean){
                    scope.attachments.push(file);
                }

            }


            scope.removeAttachFromCollection = function($index){

                scope.attachments.splice($index, 1)

            }


            scope.$watch('attachfiles', function(nVal,oVal){

                scope.uploadFiles(scope.attachfiles);

            });

            scope.refreshFilesList = function(breadc){

                var data = {};
                var pth = "";

                if(breadc.length>1){

                    for(var i=1;i<breadc.length;i++){
                        pth += '/'+breadc[i];
                    }

                }else{
                    pth = "/";
                }

                data.path = pth;
                scope.showFolderView(data);

                //console.log(breadc)
            }

            scope.uploadFiles = function (files) {

                //console.log(files);

                if(files) {

                    angular.forEach(files, function(file){

                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/media/to/folder',
                        fields: {upload_path: scope.upload_path, disk:scope.disk},
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {

                        //console.log(data);

                        //if(data.success){
                        //    //console.log(data.data)
                        //    scope.collection.uploaded_file = data.data;
                        //}

                        scope.progress = 0;
                        scope.refreshFilesList(scope.breadcrumbs);

                        //$timeout(function () {
                        //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                        //});

                    }).error(function (data, status, headers, config) {
                        //$log.info(data);
                    });

                    })

                }


            };


        }
    };
});




app.directive('mediaLibrary', function() {
    return {
        templateUrl: '/templates/admin/super/media-library.html',
        link: function(scope, element, attributes){

            //console.log(attributes);

        }
    };
});

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

app.filter('cutName', function() { return function(name) {

    return name.substr(0, 8)+'...';

}});


app.filter('timeToObject', function() { return function(time) {

    var tarr = time.split(':');
    var date = new Date(null,null,null,tarr[0],tarr[1],tarr[2]);

    return date;

}});

app.filter('attachmentsCollectionRefactor', function() { return function(coll) {

    var ncoll = [];

    angular.forEach(coll, function(item){

        //console.log(item);
        var json = JSON.parse(item.params);
        //console.log(json.icon)

        ncoll.push({
            basename:item.title,
            disk:item.disk,
            file:item.media_relative_path,
            icon:json.icon,
            info:{
                mimetype:item.mimetype,
                name:item.filename,
                suffix:item.suffix
            },
            name:item.full_filename,
            path:''
        });

    });

    return ncoll;

}});


app.filter('createPath', function() { return function(array, media) {

    //console.log(media);

    if(array!=undefined) {

        var str = '';

        angular.forEach(array, function (item) {
            str += '/' + item;
        });

    }else{
        var str = '/'+media;
    }

    return str;

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


app.filter('getonlyids', function() { return function(obj) {

    var ar = [];

    Object.keys(obj).forEach(function(key) {

        ar.push(obj[key].id);

    });

    return ar;
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


app.filter('propsFilterGallery', function() {
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


app.controller('AgendasController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope','ngDialog','$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,ngDialog,$sce) {


    $scope.initData = function(){



        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            title:true,
            alias:true,
            intro:true,
            content:true
        };

        $scope.filter = {
            status: {name:'Wszystkie', value:null}
        }

        $scope.statuses = [
            {name:'Wszystkie', value:null},
            {name:'Opublikowany', value:1},
            {name:'Nieopublikowany', value:0},
            {name:'Archiwalny', value:-1}
        ];

        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);



    }





    //Get Data Logic

    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/agendas',
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


    $scope.changeData = function(field ,value , id){

        $http.put(AppService.url+'/change/agenda/data', {field: field, value:value, id:id})
            .then(
                function successCallback(response){
                    $scope.getElements();
                },
                function errorCallback(reason){

                }
            )
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

    $scope.confirmRemove = function(fn,agenda){
        $scope.forConfirmData = {
            fn: fn,
            item: agenda,
            query: "Czy chcesz usunąć wydarzenie: <br />"+agenda.title+"?",
        };
        $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }

    $scope.deleteThisElement = function(data){
        // console.log('co mom ',data.item.id)
        $http.delete(AppService.url+'/delete/agenda/element/'+data.item.id)
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


app.controller('NewAgendaController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {

    $scope.initData = function(){

        $scope.data = {
            lang:$rootScope.lang,
            place:null,
            categories:null,
            galleries:null,
            title:'',
            intro_image:null,
            intro:null,
            content:null,
            begin:null,
            end:null,
            begin_time:null,
            end_time:null,
            meta_description:'',
            meta_keywords:'',
            attachments:[],
            logotypes:[]
        }



        //$scope.getParamsData();

        $scope.disk_intro = 'pictures';

        $scope.viewtype = 'folder';

        $scope.upload_dir_intro = 'pictures/';

        $scope.mediaconfig = [];

        $scope.uploaded = null;


        $scope.imagetoadd = null;
        $scope.media = null;
        $scope.mediaconfig = [];



        $scope.categories = null;


        $scope.cropper = {};
        $scope.cropper.sourceImage = null;
        $scope.cropper.croppedImage   = null;
        $scope.bounds = {};
        $scope.bounds.left = 0;
        $scope.bounds.right = 0;
        $scope.bounds.top = 0;
        $scope.bounds.bottom = 0

        $scope.show_crop_cloud = false;


        //////////////////////////////////new crop model start/////////////////////////////
        $scope.cropmodel = [{name:'normal',width:1200, height:800},
            {name:'panoram',width:1200, height:512}]
        $scope.cropindex = 0;
        $scope.cropcurrent = $scope.cropmodel[$scope.cropindex];

        $scope.$watch('cropindex',function(nVal,oVal){
            $scope.cropcurrent = $scope.cropmodel[nVal]
        })
        //////////////////////////////////new crop model end/////////////////////////////


        ////////Valid//////////

        $scope.if_title_valid = true;
        $scope.if_viewprofile_valid = true;
        $scope.if_date_valid = true;


        //$scope.getFoldersRecursion();
        $scope.getCategories();
        $scope.getGalleries();
        $scope.getTreeSelectLinks();
        $scope.getViewProfiles();

        $scope.options = {
            onNodeSelect: function (node, breadcrumbs) {
                //console.log(node);
                //console.log(breadcrumbs);
                $scope.node = node;
                $scope.breadcrumbs = breadcrumbs;
            },
            selectFile: function (file, breadcrumbs) {
                $scope.file = file;
            },
            hidefiles:true
        };


    }


    $rootScope.$on('emit-to-crtl', function(event,attr){
        $scope.data.logotypes = attr.logotypes;
    })


    $scope.getCategories = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/categories')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.categories = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }

    $scope.getCategoriesByType = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/categories')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.categories = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }


    $scope.getGalleries = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/galleries')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.galleries = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }


    //$scope.getFoldersRecursion = function(){
    //
    //    var deferred = $q.defer();
    //
    //    $http.get(AppService.url+'/pictures/folder/tree/'+$scope.disk)
    //        .then(
    //            function successCallback(response){
    //                //console.log(response.data);
    //                $scope.structure = response.data;
    //                deferred.resolve(1);
    //            },
    //            function errorCallback(reason){
    //                //console.log(reason);
    //                deferred.reject(0);
    //            }
    //        )
    //
    //    return deferred.promise;
    //}


    $scope.getTreeSelectLinks = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/tree/empty/get/for/agenda/'+$rootScope.lang)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.tree = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }



    $scope.getViewProfiles = function(){
        $http.get(AppService.url+'/get/view/profiles/'+$rootScope.lang+'/agenda')
            .then(
                function successCallback(response){
                    var emcol = [];

                    angular.forEach(response.data, function(item, iter){
                        item.params = JSON.parse(item.params);
                        emcol.push(item);
                    });
                    //console.log(emcol);
                    $scope.viewprofiles = emcol;
                },
                function errorCallback(reason){
                    //console.log(reason);
                }
            )
    }



    $scope.clickImageInLibrary = function(file, $index){


        document.execCommand('insertHTML', false, '<img src="'+file.request_uncomplete+$scope.mediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.mediaconfig[$index].float+'">');
        //angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        //angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');



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


    $scope.changeMaxWidthMouseDown = function(action,$index){

        switch (action){

            case 'plus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if ($scope.mediaconfig[$index].maxWidth != $scope.mediaconfig[$index].validmaxwidth) {
                        $scope.mediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if($scope.mediaconfig[$index].maxWidth!=0){
                        $scope.mediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthMouseUp = function($index){

        $interval.cancel($scope.mediaconfig[$index].interval);

    }


    $scope.setAsIntroImage = function(file){
        $scope.data.intro_image = file;
    }


    $scope.openMediaForIntro = function(){

        angular.element(document.getElementById('modal-media-lib')).removeClass('hidden');

    }


    ///////////////////////////////////Search Places//////////////////////////////////////

    var self = this;

    self.isDisabled    = false;

    //self.selectedItem = JSON.parse('{"id":1,"name":"Ardellaland","alias":"ardellaland","image":"11_Pazdzior_Helena_019_male-788x501.jpg","image_path":":","disk":"pictures","description":"Voluptatem tenetur dolorem non est qui aliquid unde et possimus omnis dolor accusantium deserunt.","lat":-83.366012,"lng":-50.035353,"params":"{}","created_at":"2017-04-03 13:39:03","updated_at":"2017-04-03 13:39:03"}')

    self.querySearch   = querySearch;
    self.selectedItemChange = selectedItemChange;
    self.searchTextChange   = searchTextChange;

    function querySearch (query) {

        var deferred = $q.defer();

        $http.post(AppService.url+'/get/places/by/query', {query:query})
                .then(
                    function successCallback(response){
                        console.log(response.data);
                        deferred.resolve( response.data );
                    },
                    function errorCallback(reason){

                    }
                )

        return deferred.promise;

    }

    function searchTextChange(text) {
        //$log.info('Text changed to ' + text);
    }

    function selectedItemChange(item) {
        if(item==undefined){
            $scope.data.place = null;
        }else{
            $scope.data.place = item;
        }

        //$log.info('Item changed to ' + JSON.stringify(item));
    }

    $scope.fastplace = {
        name:'',
        description:''
    }

    $scope.fastplaceclasses = {
        name:''
    }

    $scope.fastplacevalid = {
        name:false
    }

    $scope.checkFastPlaceName = function(){
        if($scope.fastplace.name.length>1){
            $scope.fastplaceclasses.name = 'has-success';
            $scope.fastplacevalid.name=true;
        }else{
            $scope.fastplaceclasses.name = 'has-error';
            $scope.fastplacevalid.name=false;
        }
    }

    $scope.addPlaceFast = function(){

        $scope.checkFastPlaceName();

        if($filter('checkfalse')($scope.fastplacevalid)){

            $http.put(AppService.url+'/administrator/fast/place/create', $scope.fastplace)
                .then(
                    function successCallback(response) {
                        console.log(response);
                        self.selectedItem = response.data;
                        $scope.data.place = response.data;
                        $scope.fastaddplaceclass='hidden';

                    },
                    function errorCallback(reason){

                    }
                )

        }


    }

    ///////////////////////////////////Search Places//////////////////////////////////////



    $scope.model = {};


    $scope.model.customMenu = {
        'openMediaModal' : {
            tag : 'button',
            classes: 'btn btn-primary btn-md btn-add-image',
            attributes: [{name : 'ng-model',
                value:'openMediaCloud'},
                {name : 'type',
                    value : 'button'},
                {name : 'title',
                    value : 'Dodaj obrazek'},
                {name: 'ng-click',
                    value: 'openMediaModal()'},
            ],
            data: [{
                tag: 'i',
                text: ' Dodaj obrazek',
                classes: 'fa fa-picture-o'
            }]
        }
    };



    $scope.model.menu = [
        ['bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript'],
        ['format-block'],
        ['font-size'],
        ['font-color', 'hilite-color'],
        ['remove-format'],
        ['ordered-list', 'unordered-list', 'outdent', 'indent'],
        ['left-justify', 'center-justify', 'right-justify'],
        ['quote', 'paragraph'],
        ['link', 'image'],
        ['openMediaModal']
    ];


    $scope.modelintro = {};

    $scope.modelintro.menu = [
        ['bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript'],
        ['format-block'],
        ['font-size'],
        ['font-color', 'hilite-color'],
        ['remove-format'],
        ['ordered-list', 'unordered-list', 'outdent', 'indent'],
        ['left-justify', 'center-justify', 'right-justify'],
        ['quote', 'paragraph'],
        ['link', 'image'],
        ['openMediaModal']
    ];


    ////////////////////////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.$on('attachments-add', function(event, args) {

        //console.log(args);
        $scope.attachments = args;

    });

    $scope.openAttachCloud = function(){
        $rootScope.$broadcast('showcloud-attach',true);
    }


    $scope.removeAttach = function($index){

        $scope.attachments.splice($index,1);
        $rootScope.$broadcast('remove-attach',{attach:$scope.attachments[$index], index:$index});

    }


    //$scope.$on('add-new-folder', function(event, args) {
    //
    //    console.log(args);
    //    $scope.showFolderView();
    //
    //});

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////View Profile/////////////////////////////////////////////////////

    $scope.changeViewProfile = function(v){
        //console.log(v);

        $scope.data.view_profile = {
            suffix: v.suffix,
            name: v.profile_name,
            icon: v.params.icon,
            color:{
                rgb:v.params.color.rgb,
                classname:v.params.color.classname,
                bgrgb: v.params.color.bgrgb,
                footbg: v.params.color.footbg
            }
        }


        //console.log($scope.newartdata.view_profile);

    }

    //////////////////////////////////////View Profile/////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.data.title.length>0 && $scope.data.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }



    }





    $scope.checkIsViewProfileSet = function(){


        if($scope.data.view_profile!=null){
            $scope.if_viewprofile_valid = true;
            return true;
        }else{
            $scope.if_viewprofile_valid = false;
            return false;
        }


    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.saveNewAgenda = function(){

        $scope.data.attachments = $scope.attachments;

        var boolean = [];
        boolean.push($scope.checkIsTitle());
        boolean.push($scope.checkIsViewProfileSet());


        $scope.data.begin_convert = null;
        $scope.data.end_convert = null;


        /////////////////////////////////DATETIME///////////////////////////////////////////////////////

        if($scope.data.begin==null){
            $scope.if_date_valid = false;
            boolean.push(false);
        }else{
            $scope.if_date_valid = true;
            boolean.push(true);
            var bd = new Date($scope.data.begin);
            $scope.data.begin_convert = bd.getFullYear()+'-'+(bd.getMonth()+1)+'-'+bd.getDate();
        }

        if($scope.data.end==null){

        }else{
            var ed = new Date($scope.data.end);
            $scope.data.end_convert = ed.getFullYear()+'-'+(ed.getMonth()+1)+'-'+ed.getDate();
            //console.log($scope.data.end_convert);
        }

        /////////////////////////////////DATETIME///////////////////////////////////////////////////////

        /////////////////////////////////TIME///////////////////////////////////////////////////////

        if($scope.data.begin_time==null){

        }else{
            var bt = new Date($scope.data.begin_time);
            $scope.data.begin_time_convert = bt.getHours()+':'+bt.getMinutes()+':'+bt.getSeconds();
            //console.log($scope.data.begin_time_convert);
        }


        if($scope.data.end_time==null){

        }else{
            var et = new Date($scope.data.end_time);
            $scope.data.end_time_convert = et.getHours()+':'+et.getMinutes()+':'+et.getSeconds();
            //console.log(et);
        }

        /////////////////////////////////TIME///////////////////////////////////////////////////////



        if($filter('checkfalsearray')(boolean)) {

            if($scope.data.intro_image != null) {
                $scope.data.intro_image.format = $scope.cropmodel[$scope.cropindex];
            }
            $http.put(AppService.url+'/create/new/agenda', {data:$scope.data, lang:$rootScope.lang, tree:$scope.tree})
                .then(
                    function successCallback(response){

                        //console.log(response.data);
                        $location.path('/edit/'+response.data.id).search('action=added');

                    },
                    function errorCallback(reason){

                    }
                )

        }

    }


    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('file', function () {
        //console.log($scope.files);
        //$scope.uploadFilesIntroImage($scope.file);
        if($scope.file)
            $scope.show_crop_cloud = true;
    });


    $scope.uploadFilesIntroImage = function (file) {

        var local = new Date();
        $scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();

        if(file) {

            Upload.upload({
                url: AppService.url + '/resources/upload/images/cropped',
                fields: {upload_dir: $scope.upload_dir_intro, disk: $scope.disk_intro, filename:$scope.filename},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' +
                    evt.config.file.name + '\n' + $scope.log;

                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                //$log.info(data);
                $scope.uploaded = data;
                //$log.info($scope.uploaded);
                $scope.progress = 0;
                $scope.data.intro_image = $scope.uploaded;
                $scope.show_crop_cloud = false;


                //$timeout(function() {
                //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                //});
            }).error(function (data, status, headers, config) {
                //$log.info(data);
            });

        }

    };

}]);



app.controller('EditAgendaController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {

    $scope.initData = function(){


        $scope.id = $routeParams.id;


        if($routeParams.action){

            switch ($routeParams.action){

                case 'added':

                    $scope.added = '';
                    $scope.updated = 'hidden';

                    break;

                case 'updated':

                    $scope.added = 'hidden';
                    $scope.updated = '';

                    break;

            }

        }

        $scope.data = {
            lang:$rootScope.lang,
            place:null,
            categories:null,
            galleries:null,
            logotypes: null,
            title:'',
            intro_image:null,
            intro:null,
            content:null,
            begin:null,
            end:null,
            begin_time:null,
            end_time:null,
            view_profile:null,
            attachments:[],
            meta_description:'',
            meta_keywords:''
        }



        //$scope.getParamsData();

        $scope.disk_intro = 'pictures';

        $scope.viewtype = 'folder';

        $scope.upload_dir_intro = 'pictures/';

        $scope.mediaconfig = [];

        $scope.uploaded = null;


        $scope.imagetoadd = null;
        $scope.media = null;
        $scope.mediaconfig = [];
        $scope.uploadmediaconfig = [];


        //////////////////////////////////new crop model start/////////////////////////////
        $scope.cropmodel = [{name:'normal',width:1200, height:800},
            {name:'panoram',width:1200, height:512}]
        $scope.cropindex = 0;
        $scope.cropcurrent = $scope.cropmodel[$scope.cropindex];

        $scope.$watch('cropindex',function(nVal,oVal){
            $scope.cropcurrent = $scope.cropmodel[nVal]
        })
        //////////////////////////////////new crop model end/////////////////////////////


        $scope.getFullAgendaData()
            .then(
                function(response){
                    //console.log('resp',JSON.parse(response.params).format_intro_image.name);
                    //console.log('test',$filter('attachmentsCollectionRefactor')(response.attachments));
                  if(JSON.parse(response.params).format_intro_image) {
                      var formatName = JSON.parse(response.params).format_intro_image.name;
                      $scope.cropindex = formatName == 'panoram' ? 1 : 0;
                  }else{
                      var formatName = '';
                  }
                    $scope.attachments = $filter('attachmentsCollectionRefactor')(response.attachments);

                    $scope.data = {
                        lang:$rootScope.lang,
                        place:response.place,
                        categories:$filter('getonlyids')(response.categories),
                        galleries:$filter('getonlyids')(response.galleries),
                        logotypes: response.logotypes,
                        title:response.title,
                        intro_image:response.intro_image,
                        intro:response.intro,
                        content:response.content,
                        begin:(response.begin!=null)?new Date(Date.parse(response.begin)):null,
                        end:(response.end!=null)?new Date(Date.parse(response.end)):null,
                        begin_time:(response.begin_time!=null)?$filter('timeToObject')(response.begin_time):null,
                        end_time:(response.end_time!=null)?$filter('timeToObject')(response.end_time):null,
                        view_profile:response.view_profile,
                        meta_description:'',
                        meta_keywords:''
                    }

                    //self.selectedItem = response.place;

                    $rootScope.$broadcast('emit-from-crtl',{logotypes: $scope.data.logotypes});
                },
                function(reason){

                }
            )


        //$scope.cropper = {};
        //$scope.cropper.sourceImage = null;
        //$scope.cropper.croppedImage   = null;
        //$scope.bounds = {};
        //$scope.bounds.left = 0;
        //$scope.bounds.right = 0;
        //$scope.bounds.top = 0;
        //$scope.bounds.bottom = 0;

        $scope.categories = null;


        ////////Valid//////////

        $scope.if_title_valid = true;
        $scope.if_viewprofile_valid = true;
        $scope.if_date_valid = true;


        //$scope.getFoldersRecursion();
        $scope.getCategories();
        $scope.getGalleries();
        $scope.getTreeSelectLinks();
        $scope.getViewProfiles();

        $scope.options = {
            onNodeSelect: function (node, breadcrumbs) {
                //console.log(node);
                //console.log(breadcrums);
                $scope.node = node;
                $scope.breadcrumbs = breadcrumbs;
            },
            selectFile: function (file, breadcrumbs) {
                $scope.file = file;
            },
            hidefiles:true
        };


    }

    $scope.$watch('data.begin', function(nVal,oVal){

        //console.log(nVal);

    });


    $scope.getFullAgendaData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/full/agenda/data/'+$scope.id)
            .then(
                function successCallback(response){
                    deferred.resolve(response.data);
                },
                function errorCallback(reason){
                    deferred.reject(0);
                }
            )


        return deferred.promise;

    }


    $rootScope.$on('emit-to-crtl', function(event,attr){
        $scope.data.logotypes = attr.logotypes;
    })


    $scope.getCategories = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/categories')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.categories = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }

    $scope.getGalleries = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/galleries')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.galleries = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;
    }


    //$scope.getFoldersRecursion = function(){
    //
    //    var deferred = $q.defer();
    //
    //    $http.get(AppService.url+'/pictures/folder/tree/'+$scope.disk)
    //        .then(
    //            function successCallback(response){
    //                //console.log(response.data);
    //                $scope.structure = response.data;
    //                deferred.resolve(1);
    //            },
    //            function errorCallback(reason){
    //                //console.log(reason);
    //                deferred.reject(0);
    //            }
    //        )
    //
    //    return deferred.promise;
    //}


    $scope.getTreeSelectLinks = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/tree/get/for/agenda/'+$rootScope.lang+'/'+$scope.id)
            .then(
                function successCallback(response){
                    console.log(response.data);
                    $scope.tree = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }


    $scope.getViewProfiles = function(){
        $http.get(AppService.url+'/get/view/profiles/'+$rootScope.lang+'/agenda')
            .then(
                function successCallback(response){
                    var emcol = [];

                    angular.forEach(response.data, function(item, iter){
                        item.params = JSON.parse(item.params);
                        emcol.push(item);
                    });
                    //console.log(emcol);
                    $scope.viewprofiles = emcol;
                },
                function errorCallback(reason){
                    //console.log(reason);
                }
            )
    }



    $scope.clickImageInLibrary = function(file, $index){


        document.execCommand('insertHTML', false, '<img src="'+file.request_uncomplete+$scope.mediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.mediaconfig[$index].float+'">');
        //angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        //angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');



    }


    //$scope.$watch('node', function(nVal, oVal){
    //
    //    if(nVal!=null) {
    //        if (nVal.files) {
    //            //console.log('folder', nVal);
    //            $scope.showFolderView(nVal);
    //        } else {
    //            //console.log('file', nVal);
    //            $scope.showFileView(nVal);
    //        }
    //    }
    //
    //});



    //$scope.$watch('breadcrumbs', function(nVal, oVal){
    //
    //    if(nVal==undefined){
    //
    //        $scope.upload_dir = $scope.disk+'/';
    //
    //    }else{
    //
    //        //$log.info($scope.viewtype);
    //
    //        switch ($scope.viewtype){
    //            case 'folder':
    //
    //                $scope.upload_dir = '';
    //                angular.forEach(nVal, function(item, key){
    //
    //                    $scope.upload_dir += item+'/'
    //
    //                });
    //
    //                break;
    //
    //            case 'file':
    //
    //                console.log(nVal);
    //                $scope.upload_dir = '';
    //                angular.forEach(nVal, function(item, key){
    //
    //                    if(key==(nVal.length-1))
    //                        return;
    //
    //                    $scope.upload_dir += item+'/'
    //
    //                });
    //
    //                break;
    //        }
    //
    //    }
    //});



    //$scope.showFolderView = function(data){
    //
    //    $http.put(AppService.url+'/pictures/get/folder/'+$scope.disk, data)
    //        .then(
    //            function successCallback(response){
    //                //console.log(response.data);
    //                $scope.folderfiles = response.data;
    //                $scope.viewtype = 'folder';
    //            },
    //            function errorCallback(reason){
    //                //console.log(reason);
    //
    //            }
    //        )
    //
    //}

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


    $scope.changeMaxWidthMouseDown = function(action,$index){

        switch (action){

            case 'plus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if ($scope.mediaconfig[$index].maxWidth != $scope.mediaconfig[$index].validmaxwidth) {
                        $scope.mediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if($scope.mediaconfig[$index].maxWidth!=0){
                        $scope.mediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthMouseUp = function($index){

        $interval.cancel($scope.mediaconfig[$index].interval);

    }


    $scope.setAsIntroImage = function(file){
        $scope.data.intro_image = file;
    }


    $scope.openMediaForIntro = function(){

        angular.element(document.getElementById('modal-media-lib')).removeClass('hidden');

    }


    ///////////////////////////////////Search Places//////////////////////////////////////

    var self = this;

    self.isDisabled    = false;

    self.querySearch   = querySearch;
    self.selectedItemChange = selectedItemChange;
    self.searchTextChange   = searchTextChange;

    $scope.$watch('data.place', function(nVal,oVal){
        if($scope.data.place!=null) {
            self.selectedItem = $scope.data.place;
        }
    });

    function querySearch (query) {

        var deferred = $q.defer();


        $http.post(AppService.url+'/get/places/by/query', {query:query})
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    deferred.resolve( response.data );
                },
                function errorCallback(reason){

                }
            )

        return deferred.promise;

    }

    function searchTextChange(text) {
        //$log.info('Text changed to ' + text);
    }

    function selectedItemChange(item) {

        if(item==undefined){
            $scope.data.place = null;
        }else{
            $scope.data.place = item;
        }

        //$log.info('Item changed to ' + JSON.stringify(item));
    }

    $scope.fastplace = {
        name:'',
        description:''
    }

    $scope.fastplaceclasses = {
        name:''
    }

    $scope.fastplacevalid = {
        name:false
    }

    $scope.checkFastPlaceName = function(){
        if($scope.fastplace.name.length>1){
            $scope.fastplaceclasses.name = 'has-success';
            $scope.fastplacevalid.name=true;
        }else{
            $scope.fastplaceclasses.name = 'has-error';
            $scope.fastplacevalid.name=false;
        }
    }

    $scope.addPlaceFast = function(){

        $scope.checkFastPlaceName();

        if($filter('checkfalse')($scope.fastplacevalid)){

            $http.put(AppService.url+'/administrator/fast/place/create', $scope.fastplace)
                .then(
                    function successCallback(response) {
                        //console.log(response);
                        self.selectedItem = response.data;
                        $scope.data.place = response.data;
                        $scope.fastaddplaceclass='hidden';

                    },
                    function errorCallback(reason){

                    }
                )

        }


    }

    ///////////////////////////////////Search Places//////////////////////////////////////



    $scope.model = {};


    $scope.model.customMenu = {
        'openMediaModal' : {
            tag : 'button',
            classes: 'btn btn-primary btn-md btn-add-image',
            attributes: [{name : 'ng-model',
                value:'openMediaCloud'},
                {name : 'type',
                    value : 'button'},
                {name : 'title',
                    value : 'Dodaj obrazek'},
                {name: 'ng-click',
                    value: 'openMediaModal()'},
            ],
            data: [{
                tag: 'i',
                text: ' Dodaj obrazek',
                classes: 'fa fa-picture-o'
            }]
        }
    };



    $scope.model.menu = [
        ['bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript'],
        ['format-block'],
        ['font-size'],
        ['font-color', 'hilite-color'],
        ['remove-format'],
        ['ordered-list', 'unordered-list', 'outdent', 'indent'],
        ['left-justify', 'center-justify', 'right-justify'],
        ['quote', 'paragraph'],
        ['link', 'image'],
        ['openMediaModal']
    ];


    $scope.modelintro = {};

    $scope.modelintro.menu = [
        ['bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript'],
        ['format-block'],
        ['font-size'],
        ['font-color', 'hilite-color'],
        ['remove-format'],
        ['ordered-list', 'unordered-list', 'outdent', 'indent'],
        ['left-justify', 'center-justify', 'right-justify'],
        ['quote', 'paragraph'],
        ['link', 'image'],
        ['openMediaModal']
    ];


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.$on('attachments-add', function(event, args) {

        console.log(args);
        $scope.attachments = args;

    });

    $scope.openAttachCloud = function(){
        $rootScope.$broadcast('showcloud-attach',true);
    }


    $scope.removeAttach = function($index){

        $scope.attachments.splice($index,1);
        $rootScope.$broadcast('remove-attach',{attach:$scope.attachments[$index], index:$index});

    }


    //$scope.$on('add-new-folder', function(event, args) {
    //
    //    console.log(args);
    //    $scope.showFolderView();
    //
    //});

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////View Profile/////////////////////////////////////////////////////

    $scope.changeViewProfile = function(v){
        //console.log(v);

        $scope.data.view_profile = {
            suffix: v.suffix,
            name: v.profile_name,
            icon: v.params.icon,
            color:{
                rgb:v.params.color.rgb,
                classname:v.params.color.classname,
                bgrgb: v.params.color.bgrgb,
                footbg: v.params.color.footbg
            }
        }


        //console.log($scope.newartdata.view_profile);

    }

    //////////////////////////////////////View Profile/////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.data.title.length>0 && $scope.data.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }


    }


    $scope.checkIsViewProfileSet = function(){


        if($scope.data.view_profile!=null){
            $scope.if_viewprofile_valid = true;
            return true;
        }else{
            $scope.if_viewprofile_valid = false;
            return false;
        }


    }



    ///////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.saveNewAgenda = function(){

        $scope.data.attachments = $scope.attachments;

        var boolean = [];
        boolean.push($scope.checkIsTitle());
        boolean.push($scope.checkIsViewProfileSet());




        $scope.data.begin_convert = null;
        $scope.data.end_convert = null;

        if($scope.data.begin==null){
            $scope.if_date_valid = false;
            boolean.push(false);
        }else{
            $scope.if_date_valid = true;
            boolean.push(true);
            var bd = new Date($scope.data.begin);
            $scope.data.begin_convert = bd.getFullYear()+'-'+(bd.getMonth()+1)+'-'+bd.getDate();
        }

        if($scope.data.end==null){

        }else{
            var ed = new Date($scope.data.end);
            $scope.data.end_convert = ed.getFullYear()+'-'+(ed.getMonth()+1)+'-'+ed.getDate();
        }



        if($scope.data.begin_time==null){

        }else{
            var bt = new Date($scope.data.begin_time);
            $scope.data.begin_time_convert = bt.getHours()+':'+bt.getMinutes()+':'+bt.getSeconds();
            //console.log($scope.data.begin_time_convert);
        }


        if($scope.data.end_time==null){

        }else{
            var et = new Date($scope.data.end_time);
            $scope.data.end_time_convert = et.getHours()+':'+et.getMinutes()+':'+et.getSeconds();
            //console.log(et);
        }




        if($filter('checkfalsearray')(boolean)) {

            //console.log($scope.data);
            //console.log($scope.tree);
            //console.log($scope.id);
            //console.log($rootScope.lang);
            if($scope.data.intro_image != null) {
                $scope.data.intro_image.format = $scope.cropmodel[$scope.cropindex];
            }
            $http.put(AppService.url+'/update/agenda/data', {data:$scope.data, lang:$rootScope.lang, tree:$scope.tree, aid: $scope.id})
                .then(
                    function successCallback(response){

                        //console.log(response.data);
                        $location.path('/edit/'+response.data.id).search('action=updated');

                    },
                    function errorCallback(reason){

                    }
                )

        }

    }


    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('file', function () {
        //console.log($scope.file);
        //$scope.uploadFilesIntroImage($scope.file);
        if($scope.file)
            $scope.show_crop_cloud = true;
    });


    $scope.uploadFilesIntroImage = function (file) {

        var local = new Date();
        $scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();

        if(file) {

            Upload.upload({
                url: AppService.url + '/resources/upload/images/cropped',
                fields: {upload_dir: $scope.upload_dir_intro, disk: $scope.disk_intro, filename:$scope.filename},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' +
                    evt.config.file.name + '\n' + $scope.log;

                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                //$log.info(data);
                $scope.uploaded = data;
                //$log.info($scope.uploaded);
                $scope.progress = 0;
                $scope.data.intro_image = $scope.uploaded;
                $scope.show_crop_cloud = false;


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
        templateUrl: '/templates/admin/super/agendas/master.html',
        controller: 'AgendasController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/agendas/new-agenda.html',
        controller: 'NewAgendaController as ctrl'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/agendas/edit-agenda.html',
        controller: 'EditAgendaController as ctrl'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});