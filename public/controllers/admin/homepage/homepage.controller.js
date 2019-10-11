var app = angular.module('app',['ngSanitize', 'ngRoute', 'xeditable', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache','ngFileUpload','wysiwyg.module', 'AxelSoft', 'angular-img-cropper', 'ui.select', 'ui.sortable'], function($interpolateProvider) {
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


app.factory('ColorsService', function($location, $http, AppService, $q) {
    return {
        getColors: function(){

            var deferred = $q.defer();

            $http.get(AppService.url+'/get/all/colors')
                .then(
                    function successCallback(response){
                        deferred.resolve(response.data);
                    }
                )

            return deferred.promise;
        },
        getDefaultColor: function(){

            var deferred = $q.defer();

            $http.get(AppService.url+'/get/default/color')
                .then(
                    function successCallback(response){
                        deferred.resolve(response.data);
                    }
                )

            return deferred.promise;
        }
    };
});


app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});


////////////////////////////////////////////Edit Directives////////////////////////////////////////////////////////////


app.directive('nextEditYoutubeOnly', function($http,AppService, $rootScope, ColorsService, $timeout) {
    return {
        templateUrl: '/templates/admin/super/homepage/edit/only_youtube.html',
        link: function (scope, element, attributes) {

            scope.$on('edit_only_youtube', function(event, args){

                console.log(args);


                scope.findkeyinfo = args.block[0];
                scope.findkeyinfo.deeperkey = args.index;


                scope.show_edit_cloud_only_youtube = true;



                scope.collection = {
                    youtube_url:'https://www.youtube.com/watch?v='+args.data.data.youtube_id,
                    youtube_id:args.data.data.youtube_id,
                    youtube_title:args.data.data.youtube_title,
                    youtube_desc:args.data.data.youtube_desc,
                    youtube_color:args.data.data.border_color
                }

                $timeout(function(){
                    scope.searchParseSearchYoutube();
                },2000);


                ColorsService.getColors()
                    .then(
                        function(response){
                            scope.colors = response;
                        }
                    )


                scope.$watchCollection(
                    "collection",
                    function( newValue, oldValue ) {
                        //console.log(newValue);

                        if(newValue.youtube_url.length>0 && newValue.youtube_id!=null && newValue.youtube_title.length>0){
                            scope.is_can_add = true;
                        }else{
                            scope.is_can_add = false;
                        }

                    }
                );



            });

            scope.searchParseSearchYoutube = function(){

                document.getElementById('showYt').innerHTML = '';

                var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;

                if(regExp.test(scope.collection.youtube_url)) {

                    var ex1 = scope.collection.youtube_url.split('?');
                    var ex2 = ex1[1].split('&');
                    var ex3 = ex2[0].split('=');
                    //console.log(ex3[1]);
                    scope.collection.youtube_id = ex3[1];
                    var iframe = document.createElement('iframe');
                    iframe.setAttribute('frameborder', 0);
                    iframe.setAttribute('allowfullscreen', null);
                    iframe.style.width = '100%';
                    iframe.style.height = '400px';
                    iframe.setAttribute('src', 'https://www.youtube.com/embed/' + scope.collection.youtube_id);
                    var cont = document.getElementById('showYt');
                    cont.appendChild(iframe);

                    //$http.get("http://gdata.youtube.com/feeds/api/videos/" + scope.youtube_id + "")
                    //    .then(
                    //        function(response){
                    //            console.log(response.data);
                    //        }
                    //    )

                }


            }




            scope.hideEditCloudOnlyYoutube = function(){

                scope.show_edit_cloud_only_youtube = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});

            }


            scope.$on('hide_cloud_only_youtube', function(event, args){
                scope.show_edit_cloud_only_youtube = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});
            })


            scope.removeYoutube = function(){
                scope.collection.youtube_url = '';
                scope.collection.youtube_id = null;
                document.getElementById('showYt').innerHTML = '';
            }


        }

    }

});


app.directive('nextEditYoutubeBanner', function($http,AppService, $rootScope, ColorsService, $timeout) {
    return {
        templateUrl: '/templates/admin/super/homepage/edit/youtube_banner.html',
        link: function (scope, element, attributes) {

            scope.$on('youtube_banner', function(event, args){

                console.log(args);


                scope.findkeyinfo = args.block[0];
                scope.findkeyinfo.deeperkey = args.index;


                scope.show_edit_cloud_youtube_banner = true;



                scope.collection = {
                    youtube_url:'https://www.youtube.com/watch?v='+args.data.data.youtube_id,
                    youtube_id:args.data.data.youtube_id,
                    youtube_title:args.data.data.youtube_title,
                    youtube_desc:args.data.data.youtube_desc,
                    youtube_color:args.data.data.border_color
                }


                scope.youtube_url = 'https://www.youtube.com/watch?v='+args.data.data.youtube_id;

                $timeout(function(){
                    scope.searchParseSearchYoutubeThumb();
                },2000);

                ColorsService.getColors()
                    .then(
                        function(response){
                            scope.colors = response;
                        }
                    )


                scope.$watchCollection(
                    "collection",
                    function( newValue, oldValue ) {
                        //console.log(newValue);

                        if(newValue.youtube_url.length>0 && newValue.youtube_id!=null && newValue.youtube_title.length>0){
                            scope.is_can_add = true;
                        }else{
                            scope.is_can_add = false;
                        }

                    }
                );



            });


            scope.hideEditCloudYoutubeBanner = function(){

                scope.show_edit_cloud_youtube_banner = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});

            }


            scope.$on('hide_cloud_youtube_banner', function(event, args){
                scope.show_edit_cloud_youtube_banner = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});
            })


            scope.removeYoutubeThumb = function(){
                scope.collection.youtube_url = '';
                scope.collection.youtube_id = null;
                document.getElementById('showYt').innerHTML = '';
            }


            scope.searchParseSearchYoutubeThumb = function(){

                var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;

                if(regExp.test(scope.collection.youtube_url)) {

                    var ex1 = scope.collection.youtube_url.split('?');
                    var ex2 = ex1[1].split('&');
                    var ex3 = ex2[0].split('=');
                    //console.log(ex3[1]);
                    scope.collection.youtube_id = ex3[1];

                }

            }



        }

    }

});


app.directive('nextEditExternalBanner', function($http,AppService, $rootScope, ColorsService) {
    return {
        templateUrl: '/templates/admin/super/homepage/edit/external_baner.html',
        link: function (scope, element, attributes) {

            scope.show_edit_cloud_external_banner = false;

            scope.collection = {
                banner_url:null,
                banner_title:null,
                banner_desc:null,
                uploaded_file:null,
                banner_color:null,
                banner:null
            }


            scope.$on('external_baner', function(event, args){

                //console.log(args);
                //console.log(args.data.data);


                if(args.data.data.banner) {
                    var formatname = args.data.data.banner.format ? args.data.data.banner.format.name : 'normal'

                    scope.cropindex = formatname == 'panoram' ? 1 : 0;
                }

                //////////////////////////////////new crop model start/////////////////////////////
                scope.cropmodel = [{name:'normal',width:1200, height:800},
                    {name:'panoram',width:1200, height:512}]
                scope.cropcurrent = scope.cropmodel[scope.cropindex];

                scope.$watch('cropindex',function(nVal,oVal){
                    scope.cropcurrent = scope.cropmodel[nVal]
                })
                //////////////////////////////////new crop model end/////////////////////////////



                scope.findkeyinfo = args.block[0];
                scope.findkeyinfo.deeperkey = args.index;


                scope.show_edit_cloud_external_banner = true;

                var add_upload = {};
                add_upload.disk = args.data.data.banner.disk;
                add_upload.name = args.data.data.banner.name;
                add_upload.path = args.data.data.banner.path;
                add_upload.uncomplete_request = '/image/'+args.data.data.banner.name+'/'+args.data.data.banner.disk+'/'+args.data.data.banner.path+'/';

                scope.collection = {
                    banner_url:args.data.data.banner_url,
                    banner_title:args.data.data.banner_title,
                    banner_desc:args.data.data.banner_desc,
                    uploaded_file:add_upload,
                    banner_color:args.data.data.border_color,
                    banner:args.data.data.banner
                }

                scope.target_url = args.data.data.banner_url_target;


                ColorsService.getColors()
                    .then(
                        function(response){
                            //console.log(response);
                            scope.colors = response;
                        }
                    )


                scope.$watchCollection(
                    "collection",
                    function( newValue, oldValue ) {
                        console.log(newValue.banner_title.length);
                        console.log(newValue.uploaded_file);

                        if(scope.collection.banner_url.length>1 && scope.collection.uploaded_file!=null && scope.collection.banner_title.length>0){
                            scope.is_can_add = true;
                        }else{
                            scope.is_can_add = false;
                        }

                    }
                );

                scope.disk = 'pictures';
                scope.upload_dir = 'pictures/';
                var local = new Date();
                //scope.findkeyinfo = attributes.findKeyInfo ? JSON.parse(attributes.findKeyInfo) : '';
                scope.edititem = attributes.editLeadCloudExternalBanner ? JSON.parse(attributes.editLeadCloudExternalBanner) : '';
                //console.log(scope.edititem.current_type);
                scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();
                scope.upload_status = false;
                scope.uploaded_file = null;




                scope.is_can_add = false;



                scope.target_url = '_self';
                scope.banner_url = '';
                scope.url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');



                scope.$watch('file', function () {
                    if(scope.file) {
                        scope.filename += scope.file.name;
                        scope.uploadFiles(scope.file);
                    }
                });





                scope.cropper = {};
                scope.cropper.sourceImage = null;
                scope.cropper.croppedImage   = null;
                scope.bounds = {};
                scope.bounds.left = 0;
                scope.bounds.right = 0;
                scope.bounds.top = 0;
                scope.bounds.bottom = 0;


            });

            scope.hideEditCloudExternalBanner = function(){

                scope.show_edit_cloud_external_banner = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});


            }


            scope.$on('hide_cloud_external_banner', function(event, args){
                scope.show_edit_cloud_external_banner = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});
            })


            scope.$on('clear_browser_trim_file',function(event, args){

                scope.cropper = {};
                scope.cropper.sourceImage = null;
                scope.cropper.croppedImage   = null;
                scope.bounds = {};
                scope.bounds.left = 0;
                scope.bounds.right = 0;
                scope.bounds.top = 0;
                scope.bounds.bottom = 0;

            });




        }


    }

});


app.directive('nextEditAgendaCustom', function($http,AppService, $rootScope, Upload) {
    return {
        templateUrl: '/templates/admin/super/homepage/edit/agenda_custom_image.html',
        link: function (scope, element, attributes) {

            scope.show_edit_cloud_agenda_custom = false;

            //////////////////////////////////new crop model start/////////////////////////////
            scope.cropmodel = [{name:'normal',width:1200, height:800},
                {name:'panoram',width:1200, height:512}]
            scope.cropindex = 0;
            scope.cropcurrent = scope.cropmodel[scope.cropindex];

            scope.$watch('cropindex',function(nVal,oVal){
                scope.cropcurrent = scope.cropmodel[nVal]
            })
            //////////////////////////////////new crop model end/////////////////////////////

            scope.collection = {
                agenda:null,
                uploaded_file:null,
                color:null
            }


            scope.image_view_type = [
                {
                    name:'Wgrany obrazek',
                    value:'show_uplaod_image'
                },
                {
                    name:'Ikona wydarzenia',
                    value:'show_icon_image'
                },
                {
                    name:'Obrazek wydarzenia',
                    value:'show_entity_image'
                }
            ];

            ///////////////////////////////////////////////////////////

            scope.$on('agenda_custom_image', function(event, args){
                console.log(args);

                if(args.data.data.custom_image) {
                    var formatname = args.data.data.custom_image.format ? args.data.data.custom_image.format.name : 'normal'

                    scope.cropindex = formatname == 'panoram' ? 1 : 0;
                }


                scope.show_edit_cloud_agenda_custom = true;

                scope.findkeyinfo = args.block[0];
                scope.findkeyinfo.deeperkey = args.index;
                //scope.edititem = null;
                //console.log(scope.edititem);


                angular.forEach(scope.image_view_type, function(item,i){

                    if(args.data.data.image_type==item.value){
                        scope.with_type_image = item;
                    }

                });


                scope.collection = {
                    agenda:args.data.data.entity_data,
                    uploaded_file:null,
                    color:args.data.data.border_color
                }

                scope.collection.uploaded_file = args.data.data.custom_image;



                scope.is_can_add = false;


                scope.$watch(
                    "with_type_image",
                    function( newValue, oldValue ) {

                        if(scope.collection.agenda!=null){

                            if(scope.with_type_image.value=='show_uplaod_image') {

                                if (scope.collection.uploaded_file != null) {
                                    scope.is_can_add = true;
                                } else {
                                    scope.is_can_add = false;
                                }

                            }else if(scope.with_type_image.value=='show_icon_image'){

                                scope.is_can_add = true;

                            }else if(scope.with_type_image.value=='show_entity_image') {

                                if(scope.collection.agenda.image!=null && scope.collection.agenda.disk!=null && scope.collection.agenda.image_path!=null ){

                                    scope.is_can_add = true;

                                }else{

                                    scope.is_can_add = false;

                                }

                            }


                        }else{
                            scope.is_can_add = false;
                        }

                    }
                );


                scope.$watch(

                    "collection.uploaded_file",
                    function( newValue, oldValue ) {

                        if(scope.collection.content!=null){

                            if(scope.with_type_image.value=='show_uplaod_image') {

                                if (scope.collection.uploaded_file != null) {
                                    scope.is_can_add = true;
                                } else {
                                    scope.is_can_add = false;
                                }

                            }else if(scope.with_type_image.value=='show_icon_image'){

                                scope.is_can_add = true;

                            }else if(scope.with_type_image.value=='show_entity_image') {

                                if(scope.collection.content.image!=null && scope.collection.content.disk!=null && scope.collection.content.image_path!=null ){

                                    scope.is_can_add = true;

                                }else{

                                    scope.is_can_add = false;

                                }

                            }


                        }else{
                            scope.is_can_add = false;
                        }

                    }


                );

                scope.$watchCollection(
                    "collection",
                    function( newValue, oldValue ) {
                        //console.log(newValue);
                        if(scope.collection.agenda!=null){

                            if(scope.with_type_image.value=='show_uplaod_image') {

                                if (scope.collection.uploaded_file != null) {
                                    scope.is_can_add = true;
                                } else {
                                    scope.is_can_add = false;
                                }

                            }else if(scope.with_type_image.value=='show_icon_image'){

                                scope.is_can_add = true;

                            }else if(scope.with_type_image.value=='show_entity_image') {

                                if(scope.collection.agenda.image!=null && scope.collection.agenda.disk!=null && scope.collection.agenda.image_path!=null ){

                                    scope.is_can_add = true;

                                }else{

                                    scope.is_can_add = false;

                                }

                            }


                        }else{
                            scope.is_can_add = false;
                        }

                    }
                );



            });


            scope.hideEditCloudAgendaCustom = function(){

                scope.show_edit_cloud_agenda_custom = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});

            }


            scope.$on('agenda_custom_image_hide', function(event, args){

                scope.show_edit_cloud_agenda_custom = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});

            });


            scope.$on('clear_browser_trim_file',function(event, args){

                scope.cropper = {};
                scope.cropper.sourceImage = null;
                scope.cropper.croppedImage   = null;
                scope.bounds = {};
                scope.bounds.left = 0;
                scope.bounds.right = 0;
                scope.bounds.top = 0;
                scope.bounds.bottom = 0;

            });


            /////////////////////////////////////////////////////////




            scope.frase = '';
            scope.agendas = [];

            var local = new Date();
            scope.disk = 'pictures';
            scope.upload_dir = 'pictures/';
            scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();
            scope.upload_status = false;
            scope.uploaded_file = null;


            scope.cropper = {};
            scope.cropper.sourceImage = null;
            scope.cropper.croppedImage   = null;
            scope.bounds = {};
            scope.bounds.left = 0;
            scope.bounds.right = 0;
            scope.bounds.top = 0;
            scope.bounds.bottom = 0;



            scope.searchAgendas = function(){


                if(scope.frase.length>2){
                    $http.post(AppService.url+'/fast/find/agendas/by/title', {frase:scope.frase})
                        .then(
                            function successCallback(response){
                                console.log(response.data);
                                //scope.agendas = response.data;

                                scope.agendas = [];

                                angular.forEach(response.data, function(item,iter){

                                    var ni = item;

                                    angular.forEach(ni.categories,function(im,it){

                                        ni.categories[it].params = JSON.parse(ni.categories[it].params);

                                    });

                                    scope.agendas.push(ni);

                                });
                            }
                        )
                }else{

                }
            }

            scope.addAgenda = function(ag){
                //console.log(ag);
                scope.collection.color = ag.color;
                scope.collection.agenda = ag;
            }

            scope.removeAgenda = function(){
                scope.collection.agenda = null;
            }


            scope.$watch('file', function () {
                //scope.uploadFiles(scope.file);

                //if(scope.file) {
                //    //console.log(scope.file.name);
                //    scope.filename += scope.file.name;
                //}
            });


            scope.uploadFiles = function (file) {

                //console.log(file);

                if(file) {


                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/custom/leadscene/image',
                        fields: {disk: scope.disk, upload_dir: scope.upload_dir, filename:scope.filename},
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {

                        //console.log(data);

                        if(data.success){

                            //console.log(data.data)
                            scope.collection.uploaded_file = data.data;

                        }

                        scope.progress = 0;

                        //$timeout(function () {
                        //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                        //});

                    }).error(function (data, status, headers, config) {
                        //$log.info(data);
                    });

                }

            };


            ///////////////////////////////////////////////////////////////////////


        }

    }

});


app.directive('nextEditContentCustom', function($http,AppService, $rootScope, Upload) {
    return {
        templateUrl: '/templates/admin/super/homepage/edit/content_custom_image.html',
        link: function (scope, element, attributes) {

            scope.show_edit_cloud_content_custom = false;

            //////////////////////////////////new crop model start/////////////////////////////
            scope.cropmodel = [{name:'normal',width:1200, height:800},
                {name:'panoram',width:1200, height:512}]
            scope.cropindex = 0;
            scope.cropcurrent = scope.cropmodel[scope.cropindex];

            scope.$watch('cropindex',function(nVal,oVal){
                scope.cropcurrent = scope.cropmodel[nVal]
            })
            //////////////////////////////////new crop model end/////////////////////////////

            scope.collection = {
                content:null,
                uploaded_file:null,
                color:null
            }


            scope.image_view_type = [
                {
                    name:'Wgrany obrazek',
                    value:'show_uplaod_image'
                },
                {
                    name:'Ikona wydarzenia',
                    value:'show_icon_image'
                },
                {
                    name:'Obrazek wydarzenia',
                    value:'show_entity_image'
                }
            ];

            ///////////////////////////////////////////////////////////

            scope.$on('content_custom_image', function(event, args){
                console.log(args);

                if(args.data.data.custom_image) {
                    var formatname = args.data.data.custom_image.format ? args.data.data.custom_image.format.name : 'normal'

                    scope.cropindex = formatname == 'panoram' ? 1 : 0;
                }


                scope.show_edit_cloud_content_custom = true;

                scope.findkeyinfo = args.block[0];
                scope.findkeyinfo.deeperkey = args.index;
                //scope.edititem = null;
                //console.log(scope.edititem);


                angular.forEach(scope.image_view_type, function(item,i){

                    if(args.data.data.image_type==item.value){
                        scope.with_type_image = item;
                    }

                });


                scope.collection = {
                    content:args.data.data.entity_data,
                    uploaded_file:null,
                    color:args.data.data.border_color
                }

                scope.collection.uploaded_file = args.data.data.custom_image;



                scope.is_can_add = false;


                scope.$watch(
                    "with_type_image",
                    function( newValue, oldValue ) {

                        if(scope.collection.content!=null){

                            if(scope.with_type_image.value=='show_uplaod_image') {

                                if (scope.collection.uploaded_file != null) {
                                    scope.is_can_add = true;
                                } else {
                                    scope.is_can_add = false;
                                }

                            }else if(scope.with_type_image.value=='show_icon_image'){

                                scope.is_can_add = true;

                            }else if(scope.with_type_image.value=='show_entity_image') {

                                if(scope.collection.content.image!=null && scope.collection.content.disk!=null && scope.collection.content.image_path!=null ){

                                    scope.is_can_add = true;

                                }else{

                                    scope.is_can_add = false;

                                }

                            }


                        }else{
                            scope.is_can_add = false;
                        }

                    }
                );


                scope.$watch(

                    "collection.uploaded_file",
                    function( newValue, oldValue ) {

                        if(scope.collection.content!=null){

                            if(scope.with_type_image.value=='show_uplaod_image') {

                                if (scope.collection.uploaded_file != null) {
                                    scope.is_can_add = true;
                                } else {
                                    scope.is_can_add = false;
                                }

                            }else if(scope.with_type_image.value=='show_icon_image'){

                                scope.is_can_add = true;

                            }else if(scope.with_type_image.value=='show_entity_image') {

                                if(scope.collection.content.image!=null && scope.collection.content.disk!=null && scope.collection.content.image_path!=null ){

                                    scope.is_can_add = true;

                                }else{

                                    scope.is_can_add = false;

                                }

                            }


                        }else{
                            scope.is_can_add = false;
                        }

                    }


                );

                scope.$watchCollection(
                    "collection",
                    function( newValue, oldValue ) {
                        //console.log(newValue);
                        if(scope.collection.content!=null){

                            if(scope.with_type_image.value=='show_uplaod_image') {

                                if (scope.collection.uploaded_file != null) {
                                    scope.is_can_add = true;
                                } else {
                                    scope.is_can_add = false;
                                }

                            }else if(scope.with_type_image.value=='show_icon_image'){

                                scope.is_can_add = true;

                            }else if(scope.with_type_image.value=='show_entity_image') {

                                if( scope.collection.agenda !=null &&
                                    scope.collection.agenda.image!=null &&
                                    scope.collection.agenda.disk!=null &&
                                    scope.collection.agenda.image_path!=null ){

                                    scope.is_can_add = true;

                                }else{

                                    scope.is_can_add = false;

                                }

                            }


                        }else{
                            scope.is_can_add = false;
                        }

                    }
                );



            });


            scope.hideEditCloudContentCustom = function(){

                scope.show_edit_cloud_content_custom = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});

            }


            scope.$on('content_custom_image_hide', function(event, args){

                scope.show_edit_cloud_content_custom = false;
                scope.$broadcast('hide-edit-cloud-shadow',{});

            });


            scope.$on('clear_browser_trim_file',function(event, args){

                scope.cropper = {};
                scope.cropper.sourceImage = null;
                scope.cropper.croppedImage   = null;
                scope.bounds = {};
                scope.bounds.left = 0;
                scope.bounds.right = 0;
                scope.bounds.top = 0;
                scope.bounds.bottom = 0;

            });


            /////////////////////////////////////////////////////////




            scope.frase = '';
            scope.agendas = [];

            var local = new Date();
            scope.disk = 'pictures';
            scope.upload_dir = 'pictures/';
            scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();
            scope.upload_status = false;
            scope.uploaded_file = null;


            scope.cropper = {};
            scope.cropper.sourceImage = null;
            scope.cropper.croppedImage   = null;
            scope.bounds = {};
            scope.bounds.left = 0;
            scope.bounds.right = 0;
            scope.bounds.top = 0;
            scope.bounds.bottom = 0;



            scope.searchContents = function(){


                if(scope.frase.length>2){
                    $http.post(AppService.url+'/fast/find/contents/by/title', {frase:scope.frase})
                        .then(
                            function successCallback(response){
                                console.log(response.data);
                                //scope.agendas = response.data;

                                scope.contents = [];

                                angular.forEach(response.data, function(item,iter){

                                    var ni = item;

                                    angular.forEach(ni.categories,function(im,it){

                                        ni.categories[it].params = JSON.parse(ni.categories[it].params);

                                    });

                                    scope.contents.push(ni);

                                });
                            }
                        )
                }else{

                }
            }

            scope.addContent = function(ct){
                //console.log(ag);
                scope.collection.color = ct.color;
                scope.collection.content = ct;
            }

            scope.removeContent = function(){
                scope.collection.content = null;
            }


            scope.$watch('file', function () {
                //scope.uploadFiles(scope.file);

                //if(scope.file) {
                //    //console.log(scope.file.name);
                //    scope.filename += scope.file.name;
                //}
            });


            scope.uploadFiles = function (file) {

                //console.log(file);

                if(file) {


                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/custom/leadscene/image',
                        fields: {disk: scope.disk, upload_dir: scope.upload_dir, filename:scope.filename},
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {

                        //console.log(data);

                        if(data.success){

                            //console.log(data.data)
                            scope.collection.uploaded_file = data.data;

                        }

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

    }

});



////////////////////////////////////////////Edit Directives////////////////////////////////////////////////////////////





app.directive('editLeadCloudOnlyYoutube', function($http, AppService, ColorsService) {
    return {
        templateUrl: '/templates/admin/super/homepage/cloud-edit-only-youtube-view.html',
        link: function(scope, element, attributes){

            //console.log(attributes);

            scope.collection = {
                youtube_url:'',
                youtube_id:null,
                youtube_title:'',
                youtube_desc:'',
                youtube_color:null
            }

            ColorsService.getDefaultColor()
                .then(
                    function(response){
                        scope.collection.youtube_color = response;
                    }
                )

            ColorsService.getColors()
                .then(
                    function(response){
                        scope.colors = response;
                    }
                )



            scope.is_can_add = false;


            scope.findkeyinfo = JSON.parse(attributes.findKeyInfo);
            scope.edititem = JSON.parse(attributes.editLeadCloudOnlyYoutube);
            //scope.youtube_url = '';
            //scope.youtube_id = null;
            //scope.youtube_title = '';
            //scope.youtube_desc = '';
            //console.log(scope.edititem.current_type);

            scope.$watchCollection(
                "collection",
                function( newValue, oldValue ) {
                    //console.log(newValue);

                    if(newValue.youtube_url.length>0 && newValue.youtube_id!=null && newValue.youtube_title.length>0){
                        scope.is_can_add = true;
                    }else{
                        scope.is_can_add = false;
                    }

                }
            );

            scope.searchParseSearchYoutube = function(){

                var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;

                if(regExp.test(scope.collection.youtube_url)) {

                    var ex1 = scope.collection.youtube_url.split('?');
                    var ex2 = ex1[1].split('&');
                    var ex3 = ex2[0].split('=');
                    //console.log(ex3[1]);
                    scope.collection.youtube_id = ex3[1];
                    var iframe = document.createElement('iframe');
                    iframe.setAttribute('frameborder', 0);
                    iframe.setAttribute('allowfullscreen', null);
                    iframe.style.width = '100%';
                    iframe.style.height = '400px';
                    iframe.setAttribute('src', 'https://www.youtube.com/embed/' + scope.collection.youtube_id);
                    var cont = document.getElementById('showYt');
                    cont.appendChild(iframe);

                    //$http.get("http://gdata.youtube.com/feeds/api/videos/" + scope.youtube_id + "")
                    //    .then(
                    //        function(response){
                    //            console.log(response.data);
                    //        }
                    //    )

                }


            }


            scope.removeYoutube = function(){
                scope.collection.youtube_url = '';
                scope.collection.youtube_id = null;
                document.getElementById('showYt').innerHTML = '';
            }



        }
    };
});


app.directive('editLeadCloudExternalBanner', function($http,AppService,Upload,ColorsService) {
    return {
        templateUrl: '/templates/admin/super/homepage/cloud-edit-external-banner-view.html',
        link: function(scope, element, attributes){

            //console.log(attributes);

            scope.disk = 'pictures';
            scope.upload_dir = 'pictures/';
            var local = new Date();
            scope.findkeyinfo = JSON.parse(attributes.findKeyInfo);
            scope.edititem = JSON.parse(attributes.editLeadCloudExternalBanner);
            //console.log(scope.edititem.current_type);
            scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();
            scope.upload_status = false;
            scope.uploaded_file = null;


            scope.collection = {
                banner_url:'',
                banner_title:'',
                banner_desc:'',
                uploaded_file:null,
                banner_color:null
            }

            scope.is_can_add = false;

            ColorsService.getDefaultColor()
                .then(
                    function(response){
                        scope.collection.banner_color = response;
                    }
                )

            ColorsService.getColors()
                .then(
                    function(response){
                        scope.colors = response;
                    }
                )

            scope.target_url = '_self';
            scope.banner_url = '';
            scope.url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');



            scope.$watch('file', function () {
                if(scope.file) {
                    scope.filename += scope.file.name;
                    scope.uploadFiles(scope.file);
                }
            });


            scope.$watchCollection(
                "collection",
                function( newValue, oldValue ) {
                    console.log(newValue.banner_title.length);
                    console.log(scope.url_regex.test(newValue.banner_url));
                    console.log(newValue.uploaded_file);

                    if(newValue.banner_url.length>1 && newValue.uploaded_file!=null && newValue.banner_title.length>0){
                        scope.is_can_add = true;
                    }else{
                        scope.is_can_add = false;
                    }

                }
            );


            scope.cropper = {};
            scope.cropper.sourceImage = null;
            scope.cropper.croppedImage   = null;

            //////////////////////////////////new crop model start/////////////////////////////
            scope.cropmodel = [{name:'normal',width:1200, height:800},
                {name:'panoram',width:1200, height:512}]
            scope.cropindex = 0;
            scope.cropcurrent = scope.cropmodel[scope.cropindex];

            scope.$watch('cropindex',function(nVal,oVal){
                scope.cropcurrent = scope.cropmodel[nVal]
            })
            //////////////////////////////////new crop model end/////////////////////////////


            scope.bounds = {};
            scope.bounds.left = 0;
            scope.bounds.right = 0;
            scope.bounds.top = 0;
            scope.bounds.bottom = 0;


            scope.uploadFiles = function (file) {

                if(file) {

                    //console.log(file);
                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/banner/leadscene/image',
                        fields: {disk: scope.disk, upload_dir: scope.upload_dir, filename:scope.filename},
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {

                        //console.log(data);

                        if(data.success){

                            //console.log(data.data)
                            scope.collection.uploaded_file = data.data;

                        }

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


app.directive('editLeadCloudAgendatPic', function($http,AppService,Upload) {
    return {
        templateUrl: '/templates/admin/super/homepage/cloud-edit-agenda-custom-view.html',
        link: function(scope, element, attributes){

            //console.log(attributes);
            scope.findkeyinfo = JSON.parse(attributes.findKeyInfo);
            scope.edititem = JSON.parse(attributes.editLeadCloudAgendatPic);
            //console.log(scope.edititem);
            scope.frase = '';
            scope.agendas = [];
            scope.uploaded_file = null;
            //if(scope.edititem.current_type=='agenda'){
            //    scope.agenda = scope.edititem.data.entity_data;
            //}else{
            //    scope.agenda = null;
            //}

            scope.cropper = {};
            scope.cropper.sourceImage = null;
            scope.cropper.croppedImage   = null;

            //////////////////////////////////new crop model start/////////////////////////////
            scope.cropmodel = [{name:'normal',width:1200, height:800},
                {name:'panoram',width:1200, height:512}]
            scope.cropindex = 0;
            scope.cropcurrent = scope.cropmodel[scope.cropindex];

            scope.$watch('cropindex',function(nVal,oVal){
                scope.cropcurrent = scope.cropmodel[nVal]
            })
            //////////////////////////////////new crop model end/////////////////////////////


            scope.bounds = {};
            scope.bounds.left = 0;
            scope.bounds.right = 0;
            scope.bounds.top = 0;
            scope.bounds.bottom = 0;


            scope.image_view_type = [
                {
                    name:'Wgrany obrazek',
                    value:'show_uplaod_image'
                },
                {
                    name:'Ikona wydarzenia',
                    value:'show_icon_image'
                },
                {
                    name:'Obrazek wydarzenia',
                    value:'show_entity_image'
                }
            ];

            scope.with_type_image = scope.image_view_type[0];


            scope.collection = {
                agenda:null,
                uploaded_file:null,
                color:null
            }


            scope.is_can_add = false;


            scope.$watch(
                "with_type_image",
                function( newValue, oldValue ) {

                    if(scope.collection.agenda!=null){

                        if(scope.with_type_image.value=='show_uplaod_image') {

                            if (scope.collection.uploaded_file != null) {
                                scope.is_can_add = true;
                            } else {
                                scope.is_can_add = false;
                            }

                        }else if(scope.with_type_image.value=='show_icon_image'){

                            scope.is_can_add = true;

                        }else if(scope.with_type_image.value=='show_entity_image') {

                            if(scope.collection.agenda.image!=null && scope.collection.agenda.disk!=null && scope.collection.agenda.image_path!=null ){

                                scope.is_can_add = true;

                            }else{

                                scope.is_can_add = false;

                            }

                        }


                    }else{
                        scope.is_can_add = false;
                    }

                }
            );


            scope.$watch(

                "collection.uploaded_file",
                function( newValue, oldValue ) {

                    if(scope.collection.content!=null){

                        if(scope.with_type_image.value=='show_uplaod_image') {

                            if (scope.collection.uploaded_file != null) {
                                scope.is_can_add = true;
                            } else {
                                scope.is_can_add = false;
                            }

                        }else if(scope.with_type_image.value=='show_icon_image'){

                            scope.is_can_add = true;

                        }else if(scope.with_type_image.value=='show_entity_image') {

                            if(scope.collection.content.image!=null && scope.collection.content.disk!=null && scope.collection.content.image_path!=null ){

                                scope.is_can_add = true;

                            }else{

                                scope.is_can_add = false;

                            }

                        }


                    }else{
                        scope.is_can_add = false;
                    }

                }


            );

            scope.$watchCollection(
                "collection",
                function( newValue, oldValue ) {
                    //console.log(newValue);
                    if(scope.collection.agenda!=null){

                        if(scope.with_type_image.value=='show_uplaod_image') {

                            if (scope.collection.uploaded_file != null) {
                                scope.is_can_add = true;
                            } else {
                                scope.is_can_add = false;
                            }

                        }else if(scope.with_type_image.value=='show_icon_image'){

                            scope.is_can_add = true;

                        }else if(scope.with_type_image.value=='show_entity_image') {

                            if(scope.collection.agenda.image!=null && scope.collection.agenda.disk!=null && scope.collection.agenda.image_path!=null ){

                                scope.is_can_add = true;

                            }else{

                                scope.is_can_add = false;

                            }

                        }


                    }else{
                        scope.is_can_add = false;
                    }

                }
            );

            scope.searchAgendas = function(){

                if(scope.frase.length>2){
                    $http.post(AppService.url+'/fast/find/agendas/by/title', {frase:scope.frase})
                        .then(
                            function successCallback(response){
                                console.log(response.data);
                                //scope.agendas = response.data;

                                scope.agendas = [];

                                angular.forEach(response.data, function(item,iter){

                                    var ni = item;

                                    angular.forEach(ni.categories,function(im,it){

                                        ni.categories[it].params = JSON.parse(ni.categories[it].params);

                                    });

                                    scope.agendas.push(ni);

                                });
                            }
                        )
                }else{

                }
            }

            scope.addAgenda = function(ag){
                console.log(ag);
                scope.collection.color = ag.color;
                scope.collection.agenda = ag;
            }

            scope.removeAgenda = function(){
                scope.collection.agenda = null;
            }


            ///////////////////////////////Upload////////////////////////////////////

            var local = new Date();

            scope.disk = 'pictures';
            scope.upload_dir = 'pictures/';
            scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();
            scope.upload_status = false;
            scope.uploaded_file = null;


            scope.$watch('file', function () {
                //scope.uploadFiles(scope.file);
                if(scope.file) {
                    //console.log(scope.file.name);
                    scope.filename += scope.file.name;
                }
            });


            scope.uploadFiles = function (file) {

                if(file) {

                    //console.log(file);
                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/custom/leadscene/image',
                        fields: {disk: scope.disk, upload_dir: scope.upload_dir, filename:scope.filename},
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {

                        console.log(data);

                        if(data.success){

                            //console.log(data.data)
                            scope.collection.uploaded_file = data.data;

                        }

                        scope.progress = 0;

                        //$timeout(function () {
                        //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                        //});

                    }).error(function (data, status, headers, config) {
                        $log.info(data);
                    });

                }

            };


            //$scope.removeUplodedFile = function(imagedata){
            //
            //    $http.put(AppService.url+'/remove/image/from/'+scope.inter.disk, {fname:imagedata.fname})
            //        .then(
            //            function successCallback(response){
            //                console.log(response.data);
            //
            //                if(response.data.success){
            //                    scope.upload_status = false;
            //                    //$scope.cropper = {};
            //                    //$scope.cropper.sourceImage = null;
            //                    scope.cropper.croppedImage   = null;
            //                    scope.file = null;
            //                    //$scope.bounds = {};
            //                    //$scope.bounds.left = 0;
            //                    //$scope.bounds.right = 0;
            //                    //$scope.bounds.top = 0;
            //                    //$scope.bounds.bottom = 0;
            //                    scope.inter.portrait = null;
            //                }
            //
            //            },
            //            function errorCallback(reason){
            //
            //            }
            //        )
            //
            //}


        }
    };
});


app.directive('editLeadCloudContentPic', function($http,AppService,Upload) {
    return {
        templateUrl: '/templates/admin/super/homepage/cloud-edit-content-custom-view.html',
        link: function(scope, element, attributes){

            //console.log(attributes);
            scope.findkeyinfo = JSON.parse(attributes.findKeyInfo);
            scope.edititem = JSON.parse(attributes.editLeadCloudContentPic);
            //console.log(scope.edititem.current_type);

            scope.frase = '';
            scope.contents = [];
            //if(scope.edititem.current_type=='content'){
            //    scope.content = scope.edititem.data.entity_data;
            //}else{
            //    scope.content = null;
            //}

            scope.cropper = {};
            scope.cropper.sourceImage = null;
            scope.cropper.croppedImage   = null;

            //////////////////////////////////new crop model start/////////////////////////////
            scope.cropmodel = [{name:'normal',width:1200, height:800},
                {name:'panoram',width:1200, height:512}]
            scope.cropindex = 0;
            scope.cropcurrent = scope.cropmodel[scope.cropindex];

            scope.$watch('cropindex',function(nVal,oVal){
                scope.cropcurrent = scope.cropmodel[nVal]
            })
            //////////////////////////////////new crop model end/////////////////////////////

            scope.bounds = {};
            scope.bounds.left = 0;
            scope.bounds.right = 0;
            scope.bounds.top = 0;
            scope.bounds.bottom = 0;


            scope.image_view_type = [
                {
                    name:'Wgrany obrazek',
                    value:'show_uplaod_image'
                },
                {
                    name:'Ikona artykułu',
                    value:'show_icon_image'
                },
                {
                    name:'Obrazek artykułu',
                    value:'show_entity_image'
                }
            ];

            scope.with_type_image = scope.image_view_type[0];


            scope.collection = {
                content:null,
                uploaded_file:null,
                color:null
            }


            scope.is_can_add = false;


            scope.$watch(
                "with_type_image",
                function( newValue, oldValue ) {

                    if(scope.collection.content!=null){

                        if(scope.with_type_image.value=='show_uplaod_image') {

                            if (scope.collection.uploaded_file != null) {
                                scope.is_can_add = true;
                            } else {
                                scope.is_can_add = false;
                            }

                        }else if(scope.with_type_image.value=='show_icon_image'){

                            scope.is_can_add = true;

                        }else if(scope.with_type_image.value=='show_entity_image') {

                            if(scope.collection.content.image!=null && scope.collection.content.disk!=null && scope.collection.content.image_path!=null ){

                                scope.is_can_add = true;

                            }else{

                                scope.is_can_add = false;

                            }

                        }


                    }else{
                        scope.is_can_add = false;
                    }

                }
            );


            scope.$watch(

                "collection.uploaded_file",
                function( newValue, oldValue ) {

                    if(scope.collection.content!=null){

                        if(scope.with_type_image.value=='show_uplaod_image') {

                            if (scope.collection.uploaded_file != null) {
                                scope.is_can_add = true;
                            } else {
                                scope.is_can_add = false;
                            }

                        }else if(scope.with_type_image.value=='show_icon_image'){

                            scope.is_can_add = true;

                        }else if(scope.with_type_image.value=='show_entity_image') {

                            if(scope.collection.content.image!=null && scope.collection.content.disk!=null && scope.collection.content.image_path!=null ){

                                scope.is_can_add = true;

                            }else{

                                scope.is_can_add = false;

                            }

                        }


                    }else{
                        scope.is_can_add = false;
                    }

                }


            );

            scope.$watchCollection(
                "collection",
                function( newValue, oldValue ) {
                    //console.log(newValue);
                    if(newValue.content!=null){

                        if(scope.with_type_image.value=='show_uplaod_image') {

                            if (scope.collection.uploaded_file != null) {
                                scope.is_can_add = true;
                            } else {
                                scope.is_can_add = false;
                            }

                        }else if(scope.with_type_image.value=='show_icon_image'){

                            scope.is_can_add = true;

                        }else if(scope.with_type_image.value=='show_entity_image') {

                            if(newValue.content.image!=null && newValue.content.disk!=null && newValue.content.image_path!=null ){

                                scope.is_can_add = true;

                            }else{

                                scope.is_can_add = false;

                            }

                        }


                    }else{
                        scope.is_can_add = false;
                    }

                }
            );


            scope.searchContents = function(){

                if(scope.frase.length>2){
                    $http.post(AppService.url+'/fast/find/contents/by/title', {frase:scope.frase})
                        .then(
                            function successCallback(response){
                                console.log(response.data);
                                scope.contents = response.data;

                            }
                        )
                }else{

                }
            }

            scope.addContent = function(ag){
                scope.collection.color = ag.color;
                scope.collection.content = ag;
            }

            scope.removeContent = function(){
                scope.collection.content = null;
            }


            ///////////////////////////////Upload////////////////////////////////////

            var local = new Date();

            scope.disk = 'pictures';
            scope.upload_dir = 'pictures/';
            scope.filename = 'cropped'+local.getDay()+'-'+local.getMonth()+'-'+local.getYear()+'-'+local.getHours()+'-'+local.getMinutes()+'-'+local.getSeconds();
            scope.upload_status = false;
            scope.collection.uploaded_file = null;


            scope.$watch('file', function () {
                //scope.uploadFiles(scope.file);
                if(scope.file) {
                    //console.log(scope.file.name);
                    scope.filename += scope.file.name;
                }
            });


            scope.uploadFiles = function (file) {

                if(file) {

                    //console.log(file);
                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/custom/leadscene/image',
                        fields: {disk: scope.disk, upload_dir: scope.upload_dir, filename:scope.filename},
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {

                        //console.log(data);

                        if(data.success){

                            //console.log(data.data)
                            scope.collection.uploaded_file = data.data;

                        }

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


app.directive('editLeadCloudLinkYoutube', function($http,AppService,ColorsService) {
    return {
        templateUrl: '/templates/admin/super/homepage/cloud-youtube-link-banner.html',
        link: function(scope, element, attributes){

            //console.log(attributes);
            scope.findkeyinfo = JSON.parse(attributes.findKeyInfo);
            scope.edititem = JSON.parse(attributes.editLeadCloudLinkYoutube);
            scope.youtube_url = '';
            //scope.youtube_id = null;
            //scope.youtube_title = '';
            //scope.youtube_desc = '';


            scope.collection = {
                youtube_id:null,
                youtube_title:'',
                youtube_desc:'',
                youtube_color:null
            }


            scope.is_can_add = false;


            ColorsService.getDefaultColor()
                .then(
                    function(response){
                        scope.collection.youtube_color = response;
                    }
                )

            ColorsService.getColors()
                .then(
                    function(response){
                        scope.colors = response;
                    }
                )



            scope.$watchCollection(
                "collection",
                function( newValue, oldValue ) {
                    //console.log(newValue);

                    if(newValue.youtube_title.length>0 && newValue.youtube_id!=null){
                        scope.is_can_add = true;
                    }else{
                        scope.is_can_add = false;
                    }

                }
            );


            scope.searchParseSearchYoutube = function(){

                var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;

                if(regExp.test(scope.youtube_url)) {

                    var ex1 = scope.youtube_url.split('?');
                    var ex2 = ex1[1].split('&');
                    var ex3 = ex2[0].split('=');
                    console.log(ex3[1]);
                    scope.collection.youtube_id = ex3[1];

                }

            }


            scope.removeYoutube = function(){
                scope.youtube_url = '';
                scope.collection.youtube_id = null;
            }

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


app.filter('findbykey', function() { return function(obj,fkey) {

    var el = null;
    console.log(fkey);

    angular.forEach(obj, function(item,iter){

        if(item[0].findkey==fkey){
            el = item;
        }

    });


    return el;


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


app.controller('HomePageController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {



}]);


app.controller('NewLeadSceneController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {


    $scope.initData = function(){

        $scope.findkey = {nr:0, prefix:"A"};
        $scope.editkeyinfo = null;

        $scope.structure_name = '';

        $scope.lead_data = [];
        $scope.lead_select = [
            {name:'Układ trójkolumnowy', value:null, colclass:'col-md-4'},
            {name:'Układ jednokolumnowy', value:null, colclass:'col-md-12'}
        ];
        $scope.add_data_structure = $scope.lead_select[0];

        $scope.to_edit = null;
        $scope.cloud_show = false;

        $scope.types = [];
        $scope.col = [];

        $scope.croppFormat = [{name : 'normal', width:1280, height:800, align:'center'},
            {name : 'panoram', width:1280, height:512, align:'center'}];
        $scope.currFormatIndex = 0;

    }




    $scope.addStructure = function(){

        $scope.findkey.nr++;
        var new_structure;

        //
        //console.log(TemplateStructure.lead1);
        //console.log($scope.add_data_structure.name);

        switch ($scope.add_data_structure.name){

            case 'Układ trójkolumnowy':

                new_structure =  {name:'Układ trójkolumnowy', value:null, colclass:'col-md-4', findkey:$scope.findkey.prefix+$scope.findkey.nr};

                new_structure.value = [
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    },
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    },
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    }
                ];

                break;

            case 'Układ jednokolumnowy':

                new_structure =  {name:'Układ jednokolumnowy', value:null, colclass:'col-md-12', findkey:$scope.findkey.prefix+$scope.findkey.nr};

                new_structure.value = [
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    }
                ];

                break;

        }

        //console.log(new_structure);


        $scope.lead_data.push([
            new_structure
        ]);






    }


    $scope.removeElemnetFromStructure = function($index){
        $scope.lead_data.splice($index,1);
    }



    $scope.openConfigCloud = function(this_index, type, element, find_key){

        //console.log(this_index);
        //console.log(type);
        //console.log(element);
        //console.log(find_key);
        //console.log($filter('findbykey')($scope.lead_data, find_key));


        $scope.edited_iter=null;

        //console.log('columna', this_index);
        //console.log('key', find_key);

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==find_key){
                $scope.lead_data[iter][0].value[this_index].current_type=type.key;
                $scope.edited_iter = iter;
            }

        });



        if($scope.lead_data[$scope.edited_iter][0].value[this_index].changed){
            $scope.editkeyinfo = {findkey:$scope.lead_data[$scope.edited_iter][0].findkey, deeperkey:this_index};
            $scope.to_edit = $scope.lead_data[$scope.edited_iter][0].value[this_index];
            $scope.cloud_show = true;
        }else{
            $scope.editkeyinfo = {findkey:$scope.lead_data[$scope.edited_iter][0].findkey, deeperkey:this_index};
            //console.log($scope.lead_data[$scope.edited_iter][0].value[this_index]);
            $scope.to_edit = $scope.lead_data[$scope.edited_iter][0].value[this_index];
            $scope.cloud_show = true;
        }

        //console.log($scope.lead_data[$scope.edited_iter][0]);


    }





    ////////////////////////////////////////////Agenda Custom///////////////////////////////////////////////////////////

    $scope.saveLeadElementAgendaCustom = function(agenda,uploaded_file,keyinfo, image_type,currIndex){

        var defualt_icon = '/categories_icons/default-icon.svg';
        var default_color = 'ff5b3b';

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });


        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type = image_type.value;


        switch ($scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type){

            case "show_uplaod_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.format = $scope.croppFormat[currIndex];
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;


                break;


            case "show_icon_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;

                break;


            case "show_entity_image":

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;

                break;

        }


    }

    ////////////////////////////////////////////Content Custom///////////////////////////////////////////////////////////

    $scope.saveLeadElementContentCustom = function(content,uploaded_file,keyinfo,image_type, currIndex){


        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });




        //console.log(agenda);
        //console.log(uploaded_file);
        //console.log($scope.lead_data[first_iter][0].value[keyinfo.deeperkey]);

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type = image_type.value;


        switch ($scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type){

            case "show_uplaod_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.format = $scope.croppFormat[currIndex];
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;


                break;


            case "show_icon_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;

                break;


            case "show_entity_image":

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;

                break;

        }




    }

    ////////////////////////////////////////////Agenda Custom///////////////////////////////////////////////////////////


    //////////////////////////////////////////// Youtube/////////////////////////////////////////////////////////////////

    $scope.saveLeadElementYoutubeOnly = function(youtube_id, youtube_title, youtube_desc, color, keyinfo, image_type){

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });

        //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey] = TemplateStructure.lead1[0];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'only_youtube';

        //console.log(youtube_title);
        //console.log(youtube_desc);

        if(youtube_id!=null && youtube_title!=''){
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = youtube_id;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_title = youtube_title;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_desc = youtube_desc;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = color;
            $scope.cloud_show = false;

            var showC = document.getElementById('showYoutube'+keyinfo.findkey+keyinfo.deeperkey);
            showC.innerHTML = '';
            //console.log(showC);
            //console.log('showYoutube'+keyinfo.findkey+keyinfo.deeperkey);

            //var iframe = document.createElement('iframe');
            //iframe.setAttribute('frameborder', 0);
            //iframe.setAttribute('allowfullscreen', null);
            //iframe.style.width = '100%';
            //iframe.style.height = '400px';
            //iframe.setAttribute('src', 'https://www.youtube.com/embed/' + youtube_id);
            //
            //$timeout(function(){
            //    showC.appendChild(iframe);
            //}, 2000)

        }else{
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = null;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = false;
            $scope.cloud_show = false;
        }

    }



    //////////////////////////////////////////// Youtube/////////////////////////////////////////////////////////////////


    //////////////////////////////////////////// Youtube Banner//////////////////////////////////////////////////////////

    $scope.saveLeadElementYoutubeBanner = function(youtube_id, youtube_title, youtube_desc, color, keyinfo){

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });

        //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey] = TemplateStructure.lead1[0];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'youtube_banner';

        if(youtube_id!=null && youtube_title!=''){
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = youtube_id;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_title = youtube_title;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_desc = youtube_desc;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = false;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = color;
            $scope.cloud_show = false;

        }else{
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = null;
            $scope.cloud_show = false;
        }

    }



    //////////////////////////////////////////// Youtube Banner//////////////////////////////////////////////////////////


    ////////////////////////////////////////////Banner///////////////////////////////////////////////////////////////////


    $scope.saveLeadElementBanner = function(uploaded_file,banner_url,target_url,keyinfo,collection, currIndex) {

        console.log(uploaded_file,banner_url,target_url,keyinfo);

        var first_iter = null;

        angular.forEach($scope.lead_data, function (item, iter) {

            if (item[0].findkey == keyinfo.findkey) {
                first_iter = iter;
            }

        });


        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'external_baner';


        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url_target = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.disk = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.path = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.format = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_title = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_desc = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = null;

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url = banner_url;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url_target = target_url;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.disk = uploaded_file.disk;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = uploaded_file.name;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.path = uploaded_file.path;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = uploaded_file.name;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.format = $scope.croppFormat[currIndex];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_title = collection.banner_title;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_desc = collection.banner_desc;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = collection.banner_color;
        $scope.cloud_show = false;




    }



    ////////////////////////////////////////////Banner///////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.totalSaveStrukture = function(){

        //console.log($scope.structure_name);
        //console.log($scope.lead_data);
        //console.log($rootScope.lang);

        $http.put(AppService.url+'/add/new/homepage/structure', {name:$scope.structure_name, structure:$scope.lead_data, lang:$rootScope.lang})
            .then(
                function successCallback(response){

                    console.log(response);

                },
                function errorCallback(reason){

                }
            )

    }

}]);


app.controller('CurrentLeadSceneController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {


    $scope.initData = function(){

        $scope.findkey = {nr:0, prefix:"A"};
        $scope.editkeyinfo = null;

        $scope.structure_name = '';

        $scope.lead_id = null;
        $scope.lead_data = [];
        $scope.lead_select = [
            {name:'Układ trójkolumnowy', value:null, colclass:'col-md-4'},
            {name:'Układ jednokolumnowy', value:null, colclass:'col-md-12'}
        ];
        $scope.add_data_structure = $scope.lead_select[0];

        $scope.to_edit = null;
        $scope.cloud_show = false;
        $scope.cloud_show_edit = false;

        $scope.types = [];
        $scope.col = [];

        $scope.getCurrentLeadScene();


    }



    $scope.getCurrentLeadScene = function(){

        //var deferred = $q.defer();
        //deferred.resolve(1);
        //deferred.reject(0);
        //return deferred.promise;

        $http.get(AppService.url+'/get/current/leadscene/data')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.lead_id = response.data.id;
                    $scope.structure_name = response.data.name;

                    var deserilaize = JSON.parse(response.data.fast_serialize);

                    //console.log(deserilaize);

                    angular.forEach(deserilaize, function(item,iter){
                        angular.forEach(deserilaize[iter], function(ditem,diter){
                            $scope.findkey.nr++;
                            deserilaize[iter][diter].findkey = $scope.findkey.prefix+$scope.findkey.nr;
                        });
                    });


                    $scope.lead_data = deserilaize;
                }
            )

    }




    $scope.addStructure = function(){

        $scope.findkey.nr++;
        var new_structure;

        //
        //console.log(TemplateStructure.lead1);
        //console.log($scope.add_data_structure.name);

        switch ($scope.add_data_structure.name){

            case 'Układ trójkolumnowy':

                new_structure =  {name:'Układ trójkolumnowy', value:null, colclass:'col-md-4', findkey:$scope.findkey.prefix+$scope.findkey.nr};

                new_structure.value = [
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    },
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    },
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    }
                ];

                break;

            case 'Układ jednokolumnowy':

                new_structure =  {name:'Układ jednokolumnowy', value:null, colclass:'col-md-12', findkey:$scope.findkey.prefix+$scope.findkey.nr};

                new_structure.value = [
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    }
                ];

                break;

        }

        //console.log(new_structure);


        $scope.lead_data.push([
            new_structure
        ]);






    }


    $scope.removeElemnetFromStructure = function($index){
        $scope.lead_data.splice($index,1);
    }



    $scope.openConfigCloud = function(this_index, type, element, find_key){

        //console.log(this_index);
        //console.log(type);
        //console.log(element);
        //console.log(find_key);
        //console.log($filter('findbykey')($scope.lead_data, find_key));

        $scope.edited_iter=null;

        //console.log('columna', this_index);
        //console.log('key', find_key);

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==find_key){
                $scope.lead_data[iter][0].value[this_index].current_type=type.key;
                $scope.edited_iter = iter;
            }

        });



        if($scope.lead_data[$scope.edited_iter][0].value[this_index].changed){
            $scope.editkeyinfo = {findkey:$scope.lead_data[$scope.edited_iter][0].findkey, deeperkey:this_index};
            $scope.to_edit = $scope.lead_data[$scope.edited_iter][0].value[this_index];
            $scope.cloud_show = true;
        }else{
            $scope.editkeyinfo = {findkey:$scope.lead_data[$scope.edited_iter][0].findkey, deeperkey:this_index};
            //console.log($scope.lead_data[$scope.edited_iter][0].value[this_index]);
            $scope.to_edit = $scope.lead_data[$scope.edited_iter][0].value[this_index];
            $scope.cloud_show = true;
        }

        //console.log($scope.lead_data[$scope.edited_iter][0]);


    }





    ////////////////////////////////////////////Agenda Custom///////////////////////////////////////////////////////////

    $scope.saveLeadElementAgendaCustom = function(agenda,uploaded_file,keyinfo, image_type){

        var defualt_icon = '/categories_icons/default-icon.svg';
        var default_color = 'ff5b3b';

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });


        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type = image_type.value;


        switch ($scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type){

            case "show_uplaod_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;


                break;


            case "show_icon_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';

                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;

                break;


            case "show_entity_image":

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';

                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;

                break;

        }


    }

    ////////////////////////////////////////////Content Custom///////////////////////////////////////////////////////////

    $scope.saveLeadElementContentCustom = function(content,uploaded_file,keyinfo,image_type){


        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });



        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type = image_type.value;


        switch ($scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type){

            case "show_uplaod_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;


                break;


            case "show_icon_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';

                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;

                break;


            case "show_entity_image":

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';

                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;

                break;

        }




    }

    ////////////////////////////////////////////Agenda Custom///////////////////////////////////////////////////////////


    //////////////////////////////////////////// Youtube/////////////////////////////////////////////////////////////////

    $scope.saveLeadElementYoutubeOnly = function(youtube_id, youtube_title, youtube_desc, color, keyinfo, image_type){

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });

        //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey] = TemplateStructure.lead1[0];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'only_youtube';

        //console.log(youtube_title);
        //console.log(youtube_desc);

        if(youtube_id!=null && youtube_title!=''){
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = youtube_id;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_title = youtube_title;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_desc = youtube_desc;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = color;
            $scope.cloud_show = false;

            var showC = document.getElementById('showYoutube'+keyinfo.findkey+keyinfo.deeperkey);
            showC.innerHTML = '';
            //console.log(showC);
            //console.log('showYoutube'+keyinfo.findkey+keyinfo.deeperkey);

            //var iframe = document.createElement('iframe');
            //iframe.setAttribute('frameborder', 0);
            //iframe.setAttribute('allowfullscreen', null);
            //iframe.style.width = '100%';
            //iframe.style.height = '400px';
            //iframe.setAttribute('src', 'https://www.youtube.com/embed/' + youtube_id);
            //
            //$timeout(function(){
            //    showC.appendChild(iframe);
            //}, 2000)

        }else{
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = null;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = false;
            $scope.cloud_show = false;
        }

    }



    //////////////////////////////////////////// Youtube/////////////////////////////////////////////////////////////////


    //////////////////////////////////////////// Youtube Banner//////////////////////////////////////////////////////////

    $scope.saveLeadElementYoutubeBanner = function(youtube_id, youtube_title, youtube_desc, color, keyinfo){

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });

        //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey] = TemplateStructure.lead1[0];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'youtube_banner';

        if(youtube_id!=null && youtube_title!=''){
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = youtube_id;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_title = youtube_title;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_desc = youtube_desc;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = false;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = color;
            $scope.cloud_show = false;

        }else{
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = null;
            $scope.cloud_show = false;
        }

    }



    //////////////////////////////////////////// Youtube Banner//////////////////////////////////////////////////////////


    ////////////////////////////////////////////Banner///////////////////////////////////////////////////////////////////


    $scope.saveLeadElementBanner = function(uploaded_file,banner_url,target_url,keyinfo,collection) {

        console.log(uploaded_file,banner_url,target_url,keyinfo);

        var first_iter = null;

        angular.forEach($scope.lead_data, function (item, iter) {

            if (item[0].findkey == keyinfo.findkey) {
                first_iter = iter;
            }

        });


        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'external_baner';

        //var url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');




        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url_target = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.disk = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.path = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_title = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_desc = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = null;

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url = banner_url;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url_target = target_url;

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.disk = uploaded_file.disk;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = uploaded_file.name;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.path = uploaded_file.path;

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = uploaded_file.name;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_title = collection.banner_title;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_desc = collection.banner_desc;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = collection.banner_color;
        $scope.cloud_show = false;





    }



    ////////////////////////////////////////////Banner///////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.totalUpdateStructure = function(){

        //console.log($scope.structure_name);
        //console.log($scope.lead_data);
        //console.log($rootScope.lang);

        $http.put(AppService.url+'/update/current/homepage/structure', {name:$scope.structure_name, structure:$scope.lead_data, lang:$rootScope.lang, lead_id:$scope.lead_id})
            .then(
                function successCallback(response){

                    console.log(response);

                },
                function errorCallback(reason){
                    console.log(reason);
                }
            )

    }

}]);



app.controller('UpdateLeadSceneLeadScenesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {


    $scope.initData = function(){

        console.log($routeParams);

        $scope.edit_scene_string = null;

        $scope.findkey = {nr:0, prefix:"A"};
        $scope.editkeyinfo = null;

        $scope.structure_name = '';

        $scope.lead_id = $routeParams.id;
        $scope.lead_data = [];
        $scope.lead_select = [
            {name:'Układ trójkolumnowy', value:null, colclass:'col-md-4'},
            {name:'Układ jednokolumnowy', value:null, colclass:'col-md-12'}
        ];
        $scope.add_data_structure = $scope.lead_select[0];

        $scope.to_edit = null;
        $scope.cloud_show = false;
        $scope.cloud_show_edit = false;

        $scope.types = [];
        $scope.col = [];
        $scope.croppFormat = [{name : 'normal', width:1280, height:800, align:'center'},
            {name : 'panoram', width:1280, height:512, align:'center'}];
        $scope.currFormatIndex = 0;
        $scope.getLeadSceneById();


    }



    $scope.openEditWindow = function(pitem, $index, el){

        //console.log(pitem);
        //console.log($index);
        console.log(el.current_type);

        $scope.edit_scene_string = el.current_type;

        switch (el.current_type){

            case 'only_youtube':

                $rootScope.$broadcast('edit_only_youtube',{block:pitem, index:$index, data:el});
                $scope.cloud_show_edit = true;

                break;

            case 'youtube_banner':

                $rootScope.$broadcast('youtube_banner',{block:pitem, index:$index, data:el});
                $scope.cloud_show_edit = true;

                break;

            case 'external_baner':

                $rootScope.$broadcast('external_baner',{block:pitem, index:$index, data:el});
                $scope.cloud_show_edit = true;

                break;

            case 'agenda_custom_image':

                $rootScope.$broadcast('agenda_custom_image',{block:pitem, index:$index, data:el});
                $scope.cloud_show_edit = true;

                break;

            case 'content_custom_image':

                $rootScope.$broadcast('content_custom_image',{block:pitem, index:$index, data:el});
                $scope.cloud_show_edit = true;

                break;

        }

    }


    $scope.$on('hide-edit-cloud-shadow', function(){
        $scope.cloud_show_edit = false;
        $rootScope.$broadcast('clear_browser_trim_file');

    });



    $scope.getLeadSceneById = function(){

        //var deferred = $q.defer();
        //deferred.resolve(1);
        //deferred.reject(0);
        //return deferred.promise;

        $http.get(AppService.url+'/get/leadscene/by/'+$scope.lead_id)
            .then(
                function successCallback(response){

                    $scope.structure_name = response.data.name;

                    var deserilaize = JSON.parse(response.data.fast_serialize);

                    //console.log(deserilaize);

                    angular.forEach(deserilaize, function(item,iter){
                        angular.forEach(deserilaize[iter], function(ditem,diter){
                            $scope.findkey.nr++;
                            deserilaize[iter][diter].findkey = $scope.findkey.prefix+$scope.findkey.nr;
                        });
                    });


                    $scope.lead_data = deserilaize;
                    //$scope.lead_data = JSON.parse(response.data.fast_serialize);
                }
            )

    }




    $scope.addStructure = function(){

        $scope.findkey.nr++;
        var new_structure;

        //
        //console.log(TemplateStructure.lead1);
        //console.log($scope.add_data_structure.name);

        switch ($scope.add_data_structure.name){

            case 'Układ trójkolumnowy':

                new_structure =  {name:'Układ trójkolumnowy', value:null, colclass:'col-md-4', findkey:$scope.findkey.prefix+$scope.findkey.nr};

                new_structure.value = [
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    },
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    },
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    }
                ];

                break;

            case 'Układ jednokolumnowy':

                new_structure =  {name:'Układ jednokolumnowy', value:null, colclass:'col-md-12', findkey:$scope.findkey.prefix+$scope.findkey.nr};

                new_structure.value = [
                    {
                        id:null,
                        entity:null,
                        current_type:null,
                        showtype:[
                            //{key:"agenda", name:"Wydarzenie"},
                            //{key:"content", name:"Artykuł"},
                            {key:"only_youtube", name:"Film z Youtube"},
                            {key:"youtube_banner", name:"Link do filmu w kanale YT"},
                            {key:"external_baner", name:"Baner z linkiem"},
                            {key:"agenda_custom_image", name:"Wydarzenie z dedykowanym obrazkiem"},
                            {key:"content_custom_image", name:"Artykuł z dedykowanym obrazkiem"}
                        ],
                        data:{
                            entity_data:null,
                            youtube_id:null,
                            youtube_title:"",
                            youtube_desc:"",
                            is_youtube_player:false,
                            banner_url:"",
                            banner_url_target:"",
                            banner_title:"",
                            banner_desc:"",
                            banner:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            custom_image:{
                                disk:null,
                                name:null,
                                path:null
                            },
                            image_type:'show_icon_image',
                            border_color:null
                        },
                        changed:false
                    }
                ];

                break;

        }

        //console.log(new_structure);


        $scope.lead_data.push([
            new_structure
        ]);






    }


    $scope.removeElemnetFromStructure = function($index){
        $scope.lead_data.splice($index,1);
    }



    $scope.openConfigCloud = function(this_index, type, element, find_key){

        console.log(this_index);
        console.log(type);
        console.log(element);
        console.log(find_key);
        //console.log($filter('findbykey')($scope.lead_data, find_key));

        $scope.edited_iter=null;

        //console.log('columna', this_index);
        //console.log('key', find_key);

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==find_key){
                $scope.lead_data[iter][0].value[this_index].current_type=type.key;
                $scope.edited_iter = iter;
            }

        });



        if($scope.lead_data[$scope.edited_iter][0].value[this_index].changed){
            $scope.editkeyinfo = {findkey:$scope.lead_data[$scope.edited_iter][0].findkey, deeperkey:this_index};
            $scope.to_edit = $scope.lead_data[$scope.edited_iter][0].value[this_index];
            $scope.cloud_show = true;
        }else{
            $scope.editkeyinfo = {findkey:$scope.lead_data[$scope.edited_iter][0].findkey, deeperkey:this_index};
            //console.log($scope.lead_data[$scope.edited_iter][0].value[this_index]);
            $scope.to_edit = $scope.lead_data[$scope.edited_iter][0].value[this_index];
            $scope.cloud_show = true;
        }

        //console.log($scope.lead_data[$scope.edited_iter][0]);


    }


    ////////////////////////////////////////////Agenda/////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////Content////////////////////////////////////////////////////////////////



    ////////////////////////////////////////////Agenda Custom///////////////////////////////////////////////////////////

    $scope.saveLeadElementAgendaCustom = function(agenda,uploaded_file,keyinfo, image_type,currIndex){

        var defualt_icon = '/categories_icons/default-icon.svg';
        var default_color = 'ff5b3b';

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });


        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type = image_type.value;


        switch ($scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type){

            case "show_uplaod_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.format = $scope.croppFormat[currIndex];
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;


                break;


            case "show_icon_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;

                break;


            case "show_entity_image":

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'agenda_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = agenda.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = agenda;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Agenda';


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = agenda.params.color;

                $scope.cloud_show = false;

                break;

        }



        $rootScope.$broadcast('agenda_custom_image_hide',{hide:true});

    }

    ////////////////////////////////////////////Content Custom///////////////////////////////////////////////////////////

    $scope.saveLeadElementContentCustom = function(content,uploaded_file,keyinfo,image_type, currIndex){


        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });




        //console.log(agenda);
        //console.log(uploaded_file);
        //console.log($scope.lead_data[first_iter][0].value[keyinfo.deeperkey]);

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type = image_type.value;


        switch ($scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.image_type){

            case "show_uplaod_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.format = $scope.croppFormat[currIndex];
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;


                break;


            case "show_icon_image":


                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';

                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;

                break;


            case "show_entity_image":

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed = true;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'content_custom_image';

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].id = content.id;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.entity_data = content;
                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].entity = 'App\\Entites\\Content';

                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.disk = uploaded_file.disk;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.path = uploaded_file.path;
                //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.custom_image.name = uploaded_file.name;

                $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = content.params.color;

                $scope.cloud_show = false;

                break;

        }


        $rootScope.$broadcast('content_custom_image_hide',{hide:true});


    }

    ////////////////////////////////////////////Agenda Custom///////////////////////////////////////////////////////////


    //////////////////////////////////////////// Youtube/////////////////////////////////////////////////////////////////

    $scope.saveLeadElementYoutubeOnly = function(youtube_id, youtube_title, youtube_desc, color, keyinfo, image_type){

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });

        //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey] = TemplateStructure.lead1[0];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'only_youtube';

        //console.log(youtube_title);
        //console.log(youtube_desc);

        if(youtube_id!=null && youtube_title!=''){
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = youtube_id;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_title = youtube_title;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_desc = youtube_desc;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = color;
            $scope.cloud_show = false;

            var showC = document.getElementById('showYoutube'+keyinfo.findkey+keyinfo.deeperkey);
            showC.innerHTML = '';
            //console.log(showC);
            //console.log('showYoutube'+keyinfo.findkey+keyinfo.deeperkey);

            //var iframe = document.createElement('iframe');
            //iframe.setAttribute('frameborder', 0);
            //iframe.setAttribute('allowfullscreen', null);
            //iframe.style.width = '100%';
            //iframe.style.height = '400px';
            //iframe.setAttribute('src', 'https://www.youtube.com/embed/' + youtube_id);
            //
            //$timeout(function(){
            //    showC.appendChild(iframe);
            //}, 2000)

        }else{
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = null;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = false;
            $scope.cloud_show = false;
        }


        $rootScope.$broadcast('hide_cloud_only_youtube',{});

    }



    //////////////////////////////////////////// Youtube/////////////////////////////////////////////////////////////////


    //////////////////////////////////////////// Youtube Banner//////////////////////////////////////////////////////////

    $scope.saveLeadElementYoutubeBanner = function(youtube_id, youtube_title, youtube_desc, color, keyinfo){

        var first_iter = null;

        angular.forEach($scope.lead_data, function(item,iter){

            if(item[0].findkey==keyinfo.findkey){
                first_iter = iter;
            }

        });

        //$scope.lead_data[first_iter][0].value[keyinfo.deeperkey] = TemplateStructure.lead1[0];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'youtube_banner';

        if(youtube_id!=null && youtube_title!=''){
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = youtube_id;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_title = youtube_title;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_desc = youtube_desc;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.is_youtube_player = false;
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = color;
            $scope.cloud_show = false;

        }else{
            $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.youtube_id = null;
            $scope.cloud_show = false;
        }


        $rootScope.$broadcast('hide_cloud_youtube_banner',{hide:true});


    }



    //////////////////////////////////////////// Youtube Banner//////////////////////////////////////////////////////////


    ////////////////////////////////////////////Banner///////////////////////////////////////////////////////////////////


    $scope.saveLeadElementBanner = function(uploaded_file,banner_url,target_url,keyinfo,collection,currIndex) {

        console.log(uploaded_file,banner_url,target_url,keyinfo);

        var first_iter = null;

        angular.forEach($scope.lead_data, function (item, iter) {

            if (item[0].findkey == keyinfo.findkey) {
                first_iter = iter;
            }

        });

        console.log(target_url);
        console.log(collection);

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].current_type = 'external_baner';

        //var url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');

        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url_target = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.disk = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.path = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.format = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_title = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_desc = null;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = null;


        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].changed=true;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url = banner_url;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_url_target = target_url;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.disk = uploaded_file.disk;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = uploaded_file.name;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.path = uploaded_file.path;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.name = uploaded_file.name;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner.format = $scope.croppFormat[currIndex];
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_title = collection.banner_title;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.banner_desc = collection.banner_desc;
        $scope.lead_data[first_iter][0].value[keyinfo.deeperkey].data.border_color = collection.banner_color;
        $scope.cloud_show = false;

        //console.log($scope.lead_data[first_iter][0].value[keyinfo.deeperkey]);

        $rootScope.$broadcast('hide_cloud_external_banner',{});

    }



    ////////////////////////////////////////////Banner///////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.totalUpdateStructure = function(){

        //console.log($scope.structure_name);
        //console.log($scope.lead_data);
        //console.log($rootScope.lang);

        console.log('structure name ',$scope.structure_name);
        console.log('lead_id ',$scope.lead_id);
        console.log('lang ',$rootScope.lang);
        console.log('lead data ',$scope.lead_data);

        $http.put(AppService.url+'/update/current/homepage/structure', {name:$scope.structure_name, structure:$scope.lead_data, lang:$rootScope.lang, lead_id:$scope.lead_id})
            .then(
                function successCallback(response){

                    console.log('fest',response);

                },
                function errorCallback(reason){

                    console.log('error',reason);

                }
            )

    }

}]);


app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/homepage/search.html'
    };
});

app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});


app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/homepage/status.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.elementid;

        }
    };
});


app.controller('ListLeadScenesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload) {


    $scope.initData = function(){



        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            name:true
        };

        $scope.filter = {
            active: {name:'Wszystkie', value:null}
        }

        $scope.statuses = [
            {name:'Wszystkie', value:null},
            {name:'Aktywny', value:1},
            {name:'Pozostałe', value:0}
        ];

        $scope.$watch('lang', function(newValue, OldValue){
            $scope.lang=newValue;
        });

        $scope.getElements(0);



    }


    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/leadscenes',
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



    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.checkIsNameNotEmpty = function(data){

        var d = $q.defer();

        if(data.length>0){
            d.resolve();
        }else{
            d.resolve('Za krótka nazwa szablonu');
        }

        return d.promise;


    }


    $scope.updateLeadSceneName = function(data,id){

        //console.log(data);
        //console.log(id);

        $http.put(AppService.url+'/update/leadscene/name', {name:data, id:id})
            .then(
                function successCallback(response){

                    console.log(response);

                },
                function errorCallback(reason){

                }
            )

    }


    $scope.changeData = function(field ,value , id, other_value){

        $http.put(AppService.url+'/change/content/data/update/other', {field: field, value:value, id:id, other_value:other_value,lang:$rootScope.lang})
            .then(
                function successCallback(response){
                    console.log('changeData',response)
                    $scope.getElements();
                },
                function errorCallback(reason){

                }
            )
    }


    $scope.deleteScene = function(id){

        $http.delete(AppService.url+'/administrator/delete/lead/scene/'+id)
            .then(
                function successCallback(response){
                    $scope.getElements();
                },
                function errorCallback(reason){

                }
            )

    }


}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/homepage/master.html',
        controller: 'HomePageController'
    }).
    when('/new/leadscene', {
        templateUrl: '/templates/admin/super/homepage/create-new-leadscene.html',
        controller: 'NewLeadSceneController'
    }).
    when('/current/leadscene', {
        templateUrl: '/templates/admin/super/homepage/current-leadscene.html',
        controller: 'CurrentLeadSceneController'
    }).
    when('/list/leadscenes', {
        templateUrl: '/templates/admin/super/homepage/list-leadscenes.html',
        controller: 'ListLeadScenesController'
    }).
    when('/leadscene/edit/:id', {
        templateUrl: '/templates/admin/super/homepage/update-leadscene-by-id.html',
        controller: 'UpdateLeadSceneLeadScenesController'
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