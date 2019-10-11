var app = angular.module('app',['ngSanitize', 'ngRoute', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select','tree.directives','ckeditor','ngDialog'], function($interpolateProvider) {
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


app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});

app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/publications/search.html'
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
                        console.log('folder', nVal);
                        scope.showFolderView(nVal);
                    } else {
                        console.log('file', nVal);
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

                            scope.refreshFilesList(scope.breadcrumbs);
                            scope.progress = 0;

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



app.controller('PublicationsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope','ngDialog','$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,ngDialog,$sce) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            title:true,
            intro:true,
            content:true
        };

        $scope.filter = {
            status: {name:'Wszystkie', value:null}
        }

        $scope.statuses = [
            {name:'Wszystkie', value:null},
            {name:'Opublikowane', value:1},
            {name:'Nieopublikowane', value:0}
        ];



        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);


    }





    //Get Data Logic

    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/publications',
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


    $scope.changeData = function(field ,value , id){

        $http.put(AppService.url+'/change/publication/data', {field: field, value:value, id:id})
            .then(
                function successCallback(response){
                    $scope.getElements();
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
            query: "Czy chcesz usunąć ogłosznie: <br />"+content.title+"?",
        };
        $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }

    $scope.deleteThisElement = function(data){
        // console.log('co mom ',data.item.id)

        $http.delete(AppService.url+'/delete/publication/element/'+data.item.id)
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


app.controller('NewPublicationController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {

    $scope.initData = function(){


        $scope.data = {

            title:'',
            intro:'',
            content:'',
            view_profile:null,
            attachments:[]

        }


        $scope.attachments = [];


        $scope.if_title_valid = true;


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



    $scope.getViewProfiles = function(){
        $http.get(AppService.url+'/get/view/profiles/'+$rootScope.lang+'/publication')
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


    $scope.$watch('node', function(nVal, oVal){

        if(nVal!=null) {
            if (nVal.files) {
                console.log('folder', nVal);
                $scope.showFolderView(nVal);
            } else {
                console.log('file', nVal);
                $scope.showFileView(nVal);
            }
        }

    });



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



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.data.title.length>0 && $scope.data.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }



    }


    $scope.saveNewPublication = function(){

        $scope.data.attachments = $scope.attachments;

        var boolean = [];
        boolean.push($scope.checkIsTitle());

        if($filter('checkfalsearray')(boolean)) {

            $http.put(AppService.url + '/create/new/publication', {lang: $rootScope.lang, data: $scope.data})
                .then(
                    function successCallback(response) {

                        //console.log(response.data);
                        $scope.loading = 'hidden';
                        $location.path('/edit/'+response.data.id).search('action=updated');
                        //$scope.initData();

                    },
                    function errorCallback(reason) {

                        console.log(reason);

                    }
                )

        }

    }

}]);




app.controller('EditPublicationController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {

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

            title:'',
            intro:'',
            content:'',
            view_profile:null,
            attachments:[]

        }


        $scope.attachments = [];


        $scope.if_title_valid = true;


        $scope.getViewProfiles();
        $scope.getFullPublicationData()
            .then(
                function(response){
                    console.log(response);
                    $scope.attachments = $filter('attachmentsCollectionRefactor')(response.attachments);
                    $scope.data = response;
                },
                function(){

                }
            );


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


    $scope.getFullPublicationData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/full/publication/data/'+$scope.id)
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



    $scope.getViewProfiles = function(){
        $http.get(AppService.url+'/get/view/profiles/'+$rootScope.lang+'/publication')
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


    $scope.$watch('node', function(nVal, oVal){

        if(nVal!=null) {
            if (nVal.files) {
                console.log('folder', nVal);
                $scope.showFolderView(nVal);
            } else {
                console.log('file', nVal);
                $scope.showFileView(nVal);
            }
        }

    });



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



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.data.title.length>0 && $scope.data.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }



    }


    $scope.saveNewPublication = function(){

        $scope.data.attachments = $scope.attachments;

        var boolean = [];
        boolean.push($scope.checkIsTitle());

        if($filter('checkfalsearray')(boolean)) {

            $http.put(AppService.url + '/update/publication/data', {lang: $rootScope.lang, data: $scope.data})
                .then(
                    function successCallback(response) {

                        //console.log(response.data);
                        $scope.loading = 'hidden';
                        //$location.path('/edit/'+response.data.id).search('action=updated');
                        $scope.initData();

                    },
                    function errorCallback(reason) {

                        console.log(reason);

                    }
                )

        }

    }

}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/publications/master.html',
        controller: 'PublicationsController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/publications/new-publication.html',
        controller: 'NewPublicationController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/publications/edit-publication.html',
        controller: 'EditPublicationController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});