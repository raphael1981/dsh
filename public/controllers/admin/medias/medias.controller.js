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


app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});

app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/medias/search.html'
    };
});


//app.directive('statusBtnInList', function() {
//    return {
//        templateUrl: '/templates/admin/super/contents/status.html',
//        link: function(scope, element, attributes, searchFactory){
//
//            //console.log(attributes);
//            scope.status = attributes.statusBtnInList;
//            scope.id = attributes.elementid;
//
//        }
//    };
//});


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

app.filter('getIcon', function() { return function(json) {

    var params = JSON.parse(json);

    return params.icon;
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


app.controller('MediasController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,Upload) {


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
            filename:true,
            suffix:true
        };


        $scope.filter = {
            mimetype: {name:'Wszystkie', value:null}
        }

        $scope.mimetypes = [
            {name:'Wszystkie', value:null},
            {name:'Opublikowany', value:1},
            {name:'Nieopublikowany', value:0}
        ];

        $scope.disk = 'media';

        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);


    }





    //Get Data Logic

    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/media',
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

        $http.put(AppService.url+'/change/content/data', {field: field, value:value, id:id})
            .then(
                function successCallback(response){
                    $scope.getElements();
                },
                function errorCallback(reason){

                }
            )
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('files', function () {
        $scope.uploadFiles($scope.files);
    });


    $scope.uploadFiles = function (files) {


        //console.log(files);


        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {

                var file = files[i];

                Upload.upload({
                    url: AppService.url + '/resources/upload/files',
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
                    $scope.getElements(0);

                    //$timeout(function() {
                    //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    //});
                }).error(function(data, status, headers, config) {
                    //$log.info(data);
                });
            }
        }
    };



}]);


app.controller('NewContentController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {



    $scope.initData = function(){

        $scope.loading = 'hidden';

        $scope.loading_disk = true;

        $scope.arttype = 'site';
        //'site','external'

        $scope.uploaded = [];

        $scope.newartdata = {
            title:'',
            introtext:'',
            fulltext:'',
            intro_image:null,
            external_url:'',
            galleries:null
        };

        $scope.imagetoadd = null;
        $scope.media = null;
        $scope.mediaconfig = [];
        $scope.uploadmediaconfig = [];

        $scope.disk = 'pictures';
        $scope.upload_dir = null;


        $scope.getFoldersRecursion();
        $scope.getGalleries();
        $scope.getGalleries();


        ////////Valid//////////

        $scope.if_title_valid = true;
        $scope.if_exurl_valid = true;


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


    ////////////////////////////////////////////////////////////////////////////////////////////

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

        data.breadcrumbs = $scope.breadcrumbs;

        $http.put(AppService.url+'/pictures/get/file/'+$scope.disk, data)
            .then(
                function successCallback(response){
                    console.log(response.data);
                    $scope.viewtype = 'file';
                    //$scope.cropper.croppedImage = response.data;
                },
                function errorCallback(reason){
                    //console.log(reason);

                }
            )

    }

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

        var url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');

        if(url_regex.test($scope.newartdata.external_url)){

            $scope.url_class_valid = 'has-success';
            $scope.if_exurl_valid = true;

            return true;

        }else{

            $scope.url_class_valid = 'has-error';
            $scope.if_exurl_valid = false;

            return false;

        }


    }


    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////

    $scope.saveNewArticle = function(){

        //console.log($scope.arttype);
        //console.log($scope.newartdata);
        //console.log($scope.checkIsTitle());
        var boolean = [];
        boolean.push($scope.checkIsTitle());

        if($scope.arttype=='external') {

            boolean.push($scope.checkExternalLink());
        }

        console.log(boolean);

        if($filter('checkfalsearray')(boolean)) {

            $scope.loading = '';

            switch ($scope.arttype) {

                case 'site':

                    //$http.put(AppService.url + '/administrator/create/new/article', {type:$scope.arttype, art:$scope.newartdata})
                    //    .then(
                    //        function successCallback(response){
                    //
                    //            console.log(response.data);
                    //            $scope.initData();
                    //
                    //
                    //        },
                    //        function errorCallback(reason){
                    //
                    //            console.log(reason);
                    //
                    //        }
                    //    )

                    break;

                case 'external':

                    //$http.put(AppService.url + '/administrator/create/new/article', {type:$scope.arttype, art:$scope.newartdata})
                    //    .then(
                    //        function successCallback(response){
                    //
                    //            console.log(response.data);
                    //            $scope.initData();
                    //
                    //        },
                    //        function errorCallback(reason){
                    //
                    //            console.log(reason);
                    //
                    //        }
                    //    )

                    break;

            }

        }else{

        }

    }



    ////////////////////////////////////Save//////////////////////////////////////////////////////////////








}]);


app.directive('mediaTreeView', function ($http) {
    return {
        scope:{
            tree:"=tree"
        },
        template: '<media-tree-element-view element="tree.folders[0]"></media-tree-element-view>',
        controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

            //console.log($scope.tree);
            $rootScope.$broadcast('emit-files',{el:$scope.tree.folders[0]});


        }]
    }})


app.directive('mediaTreeElementView', function ($http) {
        return {
            scope:{
                element:"=element"
            },
            template: '<div style="' +
                        'margin-left:15px;' +
                        'border-bottom: 1px solid #CCC;' +
                        'border-left:1px solid #CCC;'+
                        'padding: 6px;' +
                        'margin: 3px;'+
                        'font-size: 14px;'+
                        '" ng-repeat="el in element.folders">' +
                            '<i class="fa fa-folder-open-o" aria-hidden="true"></i>' +
                            '&nbsp;&nbsp;<a href="#" ng-click="$event.preventDefault();emitFilesList(el)">[[ el.name ]]</a>' +
                            '<media-tree-element-view element="el"></media-tree-element-view>' +
            '</div>',
            controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

                //console.log($scope.element);
                $scope.emitFilesList = function(el){
                    $rootScope.$broadcast('emit-files',{el:el});
                }

            }]
        }})


app.directive('mediaFilesList', function ($http) {
    return {
        scope:{
        },
        templateUrl: '/templates/admin/file-list.html',
        controller: ['$scope', '$http', '$q', '$rootScope','AppService','$filter', '$interval', '$timeout', function($scope, $http, $q, $rootScope, AppService, $filter, $interval, $timeout) {

            $scope.el = null;

            $rootScope.$on('emit-files', function(event,attr){
                //console.log(attr.el);
                $scope.el = attr.el;
            })


            $scope.trashFile = function(f,$index){

                var bool = confirm("Czy checesz przenieść plik do kosza?");

                if(bool) {
                    var slice_path = f.object_path.slice(1, f.object_path.length);
                    var split_path = slice_path.split(':');
                    //$scope.el.files.splice($index,1)
                    $http.post(AppService.url + '/administrator/trash/file', {file: f.name})
                        .then(function (res) {
                            //console.log(res);
                            $scope.el.files.splice($index, 1)
                        })
                }
            }


        }]
    }})


app.controller('MediasRecursiveController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,Upload) {


    $scope.initData = function(){

        $scope.disk = 'media';
        $scope.tree = null;

        $scope.getTreeMedia().then(
            function(res){
                $scope.tree = res;
                //console.log($scope.tree);
            }
        )

    }


    $scope.getTreeMedia = function(){

        var defer = $q.defer()

        $http.get(AppService.url+"/media/folder/tree/level/"+$scope.disk)
            .then(function(res){
                defer.resolve(res.data);
            })

        return defer.promise;
    }


}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    //when('/', {
    //    templateUrl: '/templates/admin/super/medias/master.html',
    //    controller: 'MediasController'
    //});
    when('/', {
        templateUrl: '/templates/admin/super/medias/medias-recursive.html',
        controller: 'MediasRecursiveController'
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