var app = angular.module('app',['ngSanitize', 'ngRoute','ngDialog', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select', 'ui.sortable','tree.directives','ckeditor','ngDialog','moment-picker','selectRotors','updateRotor'], function($interpolateProvider) {
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

app.filter('dateView', function() { return function(date) {

    var d = Date.parse(date);
    var dateObj = new Date(d)
    return dateObj.getDate()+"-"+(dateObj.getMonth()+1)+"-"+dateObj.getFullYear();

}});

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

            //console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.elementid;

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

app.directive('galleryShow', function($http, AppService) {
    return {
        templateUrl: '/templates/admin/super/agendas/gallery-show.html',
        link: function(scope, element, attributes){

            //console.log(attributes.galleryShow);
            var attrs = JSON.parse(attributes.galleryShow);
            $http.get(AppService.url+'/get/gallery/by/id/'+attrs.id)
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

                console.log(scope.disk);

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
                                scope.upload_path += '/'
                                    + item;
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


app.filter('cutName', function() { return function(name) {

    return name.substr(0, 8)+'...';

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



app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });


}]);


app.controller('ContentsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope','ngDialog','$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,ngDialog,$sce) {


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
            type: {name:'Wszystkie', value:null},
            archived: {name:'Wszystkie', value:null}
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

        $scope.archived = [
            {name:'Wszystkie', value:null},
            {name:'Niezarchiwizowane', value:0},
            {name:'zarchiwizowane', value:1}
        ]


        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);


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

    $scope.changeData = function(field ,value , id){

        $http.put(AppService.url+'/change/content/data', {field: field, value:value, id:id})
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


    $scope.changeData = function(field ,value , id){

        $http.put(AppService.url+'/change/content/data', {field: field, value:value, id:id})
            .then(
                function successCallback(response){
                    $scope.getElements();
                },
                function errorCallback(reason){

                }
            )
    }



///////////////////////////////Archive////////////////////////////////


    $scope.changeArchiveStatus = function(id,astatus,$index){

        $http.put(AppService.url+'/change/content/data', {field: 'archived', value:astatus, id:id})
            .then(
                function successCallback(response){
                    //$scope.getElements();
                    $scope.elements[$index].archived = astatus;
                },
                function errorCallback(reason){

                }
            )

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
            query: "Czy chcesz usunąć artykuł: <br />"+content.title+"?",
        };
        $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }

    $scope.deleteThisElement = function(data){
         // console.log('co mom ',data.item.id)

        $http.delete(AppService.url+'/delete/content/element/'+data.item.id)
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


app.controller('NewContentController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {



    $scope.initData = function(){

        $scope.loading = 'hidden';

        $scope.loading_disk = true;

        $scope.arttype = 'internal';
        //'site','external'

        $scope.uploaded_intro = null;

        $scope.newartdata = {
            title:'',
            introtext:'',
            fulltext:'',
            categories:null,
            intro_image:null,
            published_at:null,
            author:null,
            external_url:'',
            galleries:null,
            view_profile:null,
            attachments:[],
            logotypes:[]
        };

        $scope.imagetoadd = null;
        //$scope.media = null;
        //$scope.mediaconfig = [];
        //$scope.uploadmediaconfig = [];


        $scope.cropper = {};
        $scope.cropper.sourceImage = null;
        $scope.cropper.croppedImage   = null;

        $scope.cropmodel = [{name:'normal',width:1200, height:800},
            {name:'portret',width:800, height:1200},
            {name:'panoram',width:1200, height:512}]
        $scope.cropindex = 0;
        $scope.cropcurrent = $scope.cropmodel[$scope.cropindex];
        $scope.$watch('cropindex',function(nVal,oVal){
            $scope.cropcurrent = $scope.cropmodel[nVal]
        })

        $scope.bounds = {};
        $scope.bounds.left = 0;
        $scope.bounds.right = 0;
        $scope.bounds.top = 0;
        $scope.bounds.bottom = 0;



        $scope.show_crop_cloud = false;


        $scope.disk_intro = 'pictures';
        $scope.upload_dir_intro = 'pictures/';

        $scope.viewprofiles = null;

        $scope.attachments = [];


        $scope.optionsdate = {format: 'YYYY/MM/DD HH:mm', showClear: false, keepOpen:true};

        //$scope.getFoldersRecursion();
        $scope.getCategories();
        $scope.getGalleries();
        $scope.getTreeSelectLinks();
        $scope.getViewProfiles();


        ////////Valid//////////

        $scope.if_title_valid = true;
        $scope.if_exurl_valid = true;
        $scope.if_published_at_valid = true;
        $scope.if_viewprofile_valid = true;


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


    $scope.$watch('arttype', function(nVal, oVal){
        $scope.if_title_valid = true;
        $scope.if_exurl_valid = true;
    });

    $rootScope.$on('emit-to-crtl', function(event,attr){
        $scope.newartdata.logotypes = attr.logotypes;
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

        $http.get(AppService.url+'/tree/empty/get/for/content/'+$rootScope.lang)
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
        $http.get(AppService.url+'/get/view/profiles/'+$rootScope.lang+'/content')
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


    ////////////////////////////////////////////////////////////////////////////////////////////

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
    //
    //
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
    //                console.log(response.data);
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
    //
    //$scope.showFileView = function(data){
    //
    //    data.breadcrumbs = $scope.breadcrumbs;
    //
    //    $http.put(AppService.url+'/pictures/get/file/'+$scope.disk, data)
    //        .then(
    //            function successCallback(response){
    //                console.log(response.data);
    //                $scope.viewtype = 'file';
    //                //$scope.cropper.croppedImage = response.data;
    //            },
    //            function errorCallback(reason){
    //                //console.log(reason);
    //
    //            }
    //        )
    //
    //}

    $scope.clickImageInLibrary = function(file, $index){

        document.execCommand('insertHTML', false, '<img src="'+file.request_uncomplete+$scope.mediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.mediaconfig[$index].float+'">');

    }

    $scope.clickImageInLibraryUpload = function(file, $index){

        document.execCommand('insertHTML', false, '<img src="'+file.request_uncomplete+$scope.uploadmediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.uploadmediaconfig[$index].float+'">');

    }

    $scope.clickImageInLibraryIntroArticle = function($index,img){

        $scope.newartdata.intro_image = null;
        $timeout(function(){
            $scope.newartdata.intro_image = img;
        },1000);

    }


    $scope.openMediaForIntro = function(){

        angular.element(document.getElementById('modal-media-lib')).removeClass('hidden');

    }


    $scope.setAsIntroImage = function(file){
        //console.log(file);
        $scope.newartdata.intro_image = file;
    }


    ///////////////////////////////////////////////////////////////////////////////////////////




    $scope.$watch('imagetoadd.image',function(newValue,oldValue){
        //console.log(newValue);
        if(newValue!=null){
            $scope.imagetoadd.maxwidth=$scope.imagetoadd.rootsize[0];
            $scope.imagetoadd.float='none';
        }
    });



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




    /////////////////////////////////////////////////////////////////////////////Editor module

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
        ['font'],
        ['font-size'],
        ['font-color', 'hilite-color'],
        ['remove-format'],
        ['ordered-list', 'unordered-list', 'outdent', 'indent'],
        ['left-justify', 'center-justify', 'right-justify'],
        ['code', 'quote', 'paragraph'],
        ['link', 'image'],
        ['openMediaModal']
    ];




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




    $scope.changeMaxWidthUploadMouseDown = function(action,$index){


        switch (action){

            case 'plus':

                $scope.uploadmediaconfig[$index].interval = $interval(function(){
                    if($scope.uploadmediaconfig[$index].maxWidth!=$scope.uploadmediaconfig[$index].validmaxwidth){
                        $scope.uploadmediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.uploadmediaconfig[$index].interval = $interval(function() {
                    if ($scope.uploadmediaconfig[$index].maxWidth != 0) {
                        $scope.uploadmediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthUploadMouseUp = function($index){

        $interval.cancel($scope.uploadmediaconfig[$index].interval);

    }



    //////////////////////////////////////View Profile/////////////////////////////////////////////////////

    $scope.changeViewProfile = function(v){
        //console.log(v);

        $scope.newartdata.view_profile = {
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




    ////////////////////////////////////Save//////////////////////////////////////////////////////////////




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


    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.newartdata.title.length>0 && $scope.newartdata.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }



    }


    $scope.checkExternalLink = function(){

        //var url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');

        if($scope.newartdata.external_url.length>0){

            $scope.url_class_valid = 'has-success';
            $scope.if_exurl_valid = true;

            return true;

        }else{

            $scope.url_class_valid = 'has-error';
            $scope.if_exurl_valid = false;

            return false;

        }


    }


    $scope.checkIsDataPublishValid = function(){


        if($scope.newartdata.published_at!=null){

            if($scope.newartdata.published_at.isValid()){
                $scope.newartdata.published_at = $scope.newartdata.published_at.format('YYYY-MM-DD H:m:s');
                $scope.if_published_at_valid = true;
                return true;
            }else{
                $scope.newartdata.published_at = null;
                $scope.if_published_at_valid = false;
                return false;
            }

        }else{
            $scope.newartdata.published_at = null;
            $scope.if_published_at_valid = false;
            return false;
        }

    }


    $scope.checkIsViewProfileSet = function(){


        if($scope.newartdata.view_profile!=null){
            $scope.if_viewprofile_valid = true;
            return true;
        }else{
            $scope.if_viewprofile_valid = false;
            return false;
        }


    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////

    $scope.saveNewArticle = function(){

        $scope.newartdata.attachments = $scope.attachments;
        var boolean = [];

        boolean.push($scope.checkIsDataPublishValid());

        boolean.push($scope.checkIsViewProfileSet());

        boolean.push($scope.checkIsTitle());

        if($scope.arttype=='external') {

            boolean.push($scope.checkExternalLink());

        }

        //console.log($filter('checkfalsearray')(boolean));

        if($filter('checkfalsearray')(boolean)) {

            $scope.loading = '';

            switch ($scope.arttype) {

                case 'internal':
                    if($scope.newartdata.intro_image) {
                        $scope.newartdata.intro_image.format = $scope.cropmodel[$scope.cropindex];
                    }
                    $http.put(AppService.url + '/create/new/content', {type:$scope.arttype, art:$scope.newartdata, tree:$scope.tree, lang:$rootScope.lang})
                        .then(
                            function successCallback(response){

                                //console.log(response.data);
                                $location.path('/edit/'+response.data.id).search('action=added');

                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

                case 'external':
                    if($scope.newartdata.intro_image) {
                        $scope.newartdata.intro_image.format = $scope.cropmodel[$scope.cropindex];
                    }
                    $http.put(AppService.url + '/create/new/content', {type:$scope.arttype, art:$scope.newartdata, tree:$scope.tree, lang:$rootScope.lang})
                        .then(
                            function successCallback(response){

                                //console.log(response.data);
                                $location.path('/edit/'+response.data.id).search('action=added');

                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

            }

        }else{

        }

    }



    ////////////////////////////////////Save//////////////////////////////////////////////////////////////




    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('file', function () {
        //$scope.uploadFilesIntroImage($scope.file);
        if($scope.file)
            $scope.show_crop_cloud = true;

    });


    $scope.uploadFilesIntroImage = function (file) {


        //console.log(files);
        var local = new Date();
        $scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();

        if(file){

                Upload.upload({
                    url: AppService.url + '/resources/upload/images/cropped',
                    fields: {upload_dir:$scope.upload_dir_intro, disk:$scope.disk_intro,filename:$scope.filename},
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    //$log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {

                    $log.info(data);
                    $scope.uploaded = data;
                    $scope.progress = 0;
                    $scope.newartdata.intro_image = $scope.uploaded;
                    $scope.show_crop_cloud = false;


                    //$timeout(function() {
                    //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    //});
                }).error(function(data, status, headers, config) {
                    //$log.info(data);
                });

        }
    };



}]);



app.controller('EditContentController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {

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

        $scope.loading = 'hidden';

        $scope.loading_disk = true;


        $scope.cropper = {};
        $scope.cropper.sourceImage = null;
        $scope.cropper.croppedImage   = null;
        $scope.cropmodel = [{name:'normal',width:1200, height:800},
            {name:'portret',width:800, height:1200},
            {name:'panoram',width:1200, height:512}
        ]
        $scope.cropindex = 0;
        $scope.cropcurrent = $scope.cropmodel[$scope.cropindex];
        $scope.$watch('cropindex',function(nVal,oVal){
            $scope.cropcurrent = $scope.cropmodel[nVal]
        })
        $scope.bounds = {};
        $scope.bounds.left = 0;
        $scope.bounds.right = 0;
        $scope.bounds.top = 0;
        $scope.bounds.bottom = 0

        $scope.show_crop_cloud = false;


        //'site','external'

        $scope.uploaded = [];

        $scope.arttype = 'internal';
        $scope.newartdata = {

            title:'',
            introtext:'',
            fulltext:'',
            categories:null,
            logotypes: null,
            intro_image:null,
            external_url:'',
            galleries:null,
            attachments:[],
            view_profile:null
        };

        $scope.viewprofiles = null;


        $scope.getFullContentData()
            .then(
                function(response){

                    $scope.attachments = $filter('attachmentsCollectionRefactor')(response.attachments);
                    var d = Date.parse(response.published_at);
                    var mobject = moment(d)
                    $scope.newartdata = response;
                    $scope.newartdata.published_at = mobject;
                    $scope.arttype = response.type;
                    //console.log($scope.newartdata.logotypes);
                    $rootScope.$broadcast('emit-from-crtl',{logotypes: $scope.newartdata.logotypes});
                },
                function(reason){

                }
            )



        $scope.imagetoadd = null;
        $scope.media = null;
        $scope.mediaconfig = [];
        $scope.uploadmediaconfig = [];

        $scope.disk_intro = 'pictures';
        $scope.upload_dir_intro = 'pictures/';


        //$scope.getFoldersRecursion();
        $scope.getCategories();
        $scope.getTreeSelectLinks();
        $scope.getGalleries();
        $scope.getViewProfiles();


        ////////Valid//////////

        $scope.if_title_valid = true;
        $scope.if_exurl_valid = true;
        $scope.if_published_at_valid = true;
        $scope.if_viewprofile_valid = true;


        $scope.options = {
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



    $scope.$watch('arttype', function(nVal, oVal){
        $scope.if_title_valid = true;
        $scope.if_exurl_valid = true;
    });


    $rootScope.$on('emit-to-crtl', function(event,attr){
        $scope.newartdata.logotypes = attr.logotypes;
    })


    $scope.getFullContentData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/full/content/data/'+$scope.id)
            .then(
                function successCallback(response){

                if(JSON.parse(response.data.params).format_intro_image) {
                    var formatName = JSON.parse(response.data.params).format_intro_image.name;
                }else{
                    var formatName = 0;
                }
                    if(formatName == 'panoram'){
                        $scope.cropindex = 2;
                    }else if(formatName == 'portret'){
                        $scope.cropindex = 1;
                    }else{
                        $scope.cropindex = 0;
                    }


                    deferred.resolve(response.data);
                },
                function errorCallback(reason){
                    deferred.reject(0);
                }
            )


        return deferred.promise;

    }


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

    $scope.getTreeSelectLinks = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/tree/get/for/content/'+$rootScope.lang+'/'+$scope.id)
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
        $http.get(AppService.url+'/get/view/profiles/'+$rootScope.lang+'/content')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    //$scope.viewprofiles = response.data;

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


    ////////////////////////////////////////////////////////////////////////////////////////////

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
    //
    //
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
    //                console.log(response.data);
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
    //
    //$scope.showFileView = function(data){
    //
    //    data.breadcrumbs = $scope.breadcrumbs;
    //
    //    $http.put(AppService.url+'/pictures/get/file/'+$scope.disk, data)
    //        .then(
    //            function successCallback(response){
    //                console.log(response.data);
    //                $scope.viewtype = 'file';
    //                //$scope.cropper.croppedImage = response.data;
    //            },
    //            function errorCallback(reason){
    //                //console.log(reason);
    //
    //            }
    //        )
    //
    //}

    $scope.clickImageInLibrary = function(file, $index){

        document.execCommand('insertHTML', false, '<img src="'+file.request_uncomplete+$scope.mediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.mediaconfig[$index].float+'">');

    }

    $scope.clickImageInLibraryUpload = function(file, $index){

        document.execCommand('insertHTML', false, '<img src="'+file.request_uncomplete+$scope.uploadmediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.uploadmediaconfig[$index].float+'">');

    }

    $scope.clickImageInLibraryIntroArticle = function($index,img){

        $scope.newartdata.intro_image = null;
        $timeout(function(){
            $scope.newartdata.intro_image = img;
        },1000);

    }


    $scope.openMediaForIntro = function(){

        angular.element(document.getElementById('modal-media-lib')).removeClass('hidden');

    }


    $scope.setAsIntroImage = function(file){
        //console.log(file);
        $scope.newartdata.intro_image = file;
    }


    ///////////////////////////////////////////////////////////////////////////////////////////




    $scope.$watch('imagetoadd.image',function(newValue,oldValue){
        //console.log(newValue);
        if(newValue!=null){
            $scope.imagetoadd.maxwidth=$scope.imagetoadd.rootsize[0];
            $scope.imagetoadd.float='none';
        }
    });


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


    /////////////////////////////////////////////////////////////////////////////Editor module

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
        ['font'],
        ['font-size'],
        ['font-color', 'hilite-color'],
        ['remove-format'],
        ['ordered-list', 'unordered-list', 'outdent', 'indent'],
        ['left-justify', 'center-justify', 'right-justify'],
        ['code', 'quote', 'paragraph'],
        ['link', 'image'],
        ['openMediaModal']
    ];


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




    $scope.changeMaxWidthUploadMouseDown = function(action,$index){


        switch (action){

            case 'plus':

                $scope.uploadmediaconfig[$index].interval = $interval(function(){
                    if($scope.uploadmediaconfig[$index].maxWidth!=$scope.uploadmediaconfig[$index].validmaxwidth){
                        $scope.uploadmediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.uploadmediaconfig[$index].interval = $interval(function() {
                    if ($scope.uploadmediaconfig[$index].maxWidth != 0) {
                        $scope.uploadmediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthUploadMouseUp = function($index){

        $interval.cancel($scope.uploadmediaconfig[$index].interval);

    }


    //////////////////////////////////////View Profile/////////////////////////////////////////////////////

    $scope.changeViewProfile = function(v){
        //console.log(v);

        $scope.newartdata.view_profile = {
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


    ////////////////////////////////////Save//////////////////////////////////////////////////////////////

    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.newartdata.title.length>0 && $scope.newartdata.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }



    }


    $scope.checkExternalLink = function(){

        //var url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');

        if($scope.newartdata.external_url.length>0){

            $scope.url_class_valid = 'has-success';
            $scope.if_exurl_valid = true;

            return true;

        }else{

            $scope.url_class_valid = 'has-error';
            $scope.if_exurl_valid = false;

            return false;

        }


    }


    $scope.checkIsDataPublishValid = function(){


        if($scope.newartdata.published_at!=null){

            if($scope.newartdata.published_at.isValid()){
                $scope.newartdata.published_at = $scope.newartdata.published_at.format('YYYY-MM-DD H:m:s');
                $scope.if_published_at_valid = true;
                return true;
            }else{
                $scope.newartdata.published_at = null;
                $scope.if_published_at_valid = false;
                return false;
            }

        }else{
            $scope.newartdata.published_at = null;
            $scope.if_published_at_valid = false;
            return false;
        }

    }


    $scope.checkIsViewProfileSet = function(){


        if($scope.newartdata.view_profile!=null){
            $scope.if_viewprofile_valid = true;
            return true;
        }else{
            $scope.if_viewprofile_valid = false;
            return false;
        }


    }


    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////

    $scope.saveNewArticle = function(){

        $scope.newartdata.attachments = $scope.attachments;

        var boolean = [];

        boolean.push($scope.checkIsDataPublishValid());

        boolean.push($scope.checkIsViewProfileSet());

        boolean.push($scope.checkIsTitle());

        if($scope.arttype=='external') {

            boolean.push($scope.checkExternalLink());

        }


        if($filter('checkfalsearray')(boolean)) {

            $scope.loading = '';

            switch ($scope.arttype) {

                case 'internal':
                    if($scope.newartdata.intro_image) {
                        $scope.newartdata.intro_image.format = $scope.cropmodel[$scope.cropindex];
                    }
                    $http.put(AppService.url + '/update/content/data', {lang:$rootScope.lang, type:$scope.arttype, art:$scope.newartdata, tree:$scope.tree, cid:$scope.id})
                        .then(
                            function successCallback(response){

                                console.log(response.data);
                                $scope.loading = 'hidden';
                                //$location.path('/edit/'+response.data.id).search('action=updated');
                                $scope.initData();

                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

                case 'external':
                    if($scope.newartdata.intro_image) {
                        $scope.newartdata.intro_image.format = $scope.cropmodel[$scope.cropindex];
                    }
                    $http.put(AppService.url + '/update/content/data', {lang:$rootScope.lang, type:$scope.arttype, art:$scope.newartdata, tree:$scope.tree, cid:$scope.id})
                        .then(
                            function successCallback(response){

                                console.log(response.data);
                                $scope.loading = 'hidden';
                                //$location.path('/edit/'+response.data.id).search('action=updated');
                                $scope.initData();

                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

            }

        }else{

        }

    }


    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('file', function () {
        //$scope.uploadFilesIntroImage($scope.file);
        if($scope.file)
            $scope.show_crop_cloud = true;
    });


    $scope.uploadFilesIntroImage = function (file) {

        var local = new Date();
        $scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();

        if(file){

                Upload.upload({
                    url: AppService.url + '/resources/upload/images/cropped',
                    fields: {upload_dir:$scope.upload_dir_intro, disk:$scope.disk_intro, filename:$scope.filename},
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    //$log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {

                    $log.info(data);
                    $scope.uploaded = data;
                    $scope.progress = 0;
                    $scope.newartdata.intro_image = $scope.uploaded;
                    $scope.show_crop_cloud = false;


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
        templateUrl: '/templates/admin/super/contents/master.html',
        controller: 'ContentsController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/contents/new-content.html',
        controller: 'NewContentController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/contents/edit-content.html',
        controller: 'EditContentController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});
