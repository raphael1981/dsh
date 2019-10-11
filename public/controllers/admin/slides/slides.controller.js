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

app.factory('mySharedService', function($rootScope) {
    var sharedService = {};
    sharedService.data = {};

    sharedService.prepForBroadcast = function(data) {
        this.data = data;
        this.broadcastItem();
    };

    sharedService.broadcastItem = function() {
        $rootScope.$broadcast('handleBroadcast');
    };

    return sharedService;
});

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});

app.factory('ColorsDirectiveService', function($location,AppService,$http,$q) {
    return {
        getAllColors: function(){

            var deferred = $q.defer();

            $http.get(AppService.url+'/get/all/colors')
                .then(
                    function successCallback(response){
                        deferred.resolve(response.data);
                    }
                )

            return deferred.promise;

        },

        getSlideColorIfExist: function(id){

            var deferred = $q.defer();


            $http.get(AppService.url+'/get/color/if/exist/'+id)
                .then(
                    function successCallback(response){
                        deferred.resolve(response.data);
                    }
                )

            return deferred.promise;
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






app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');
    },100)

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });

}]);


app.directive('cloudCaruselPic', function($http,AppService,Upload, ColorsDirectiveService,$q,mySharedService) {
    return {
         //templateUrl: '/templates/admin/super/homepage/cloud-edit-content-custom-view.html',
        scope: true,
        templateUrl: '/templates/admin/super/homepage/cloud-carusel-view.html',

        link: function(scope, element, attributes, $rootScope) {


            //console.log('attt',attributes);

            scope.cropper = {};
            scope.cropper.sourceImage = null;
            scope.cropper.croppedImage = null;
            scope.bounds = {};
            scope.bounds.left = 0;
            scope.bounds.right = 0;
            scope.bounds.top = 0;
            scope.bounds.bottom = 0;
            if ($rootScope) {
                scope.image = $rootScope.image;
                scope.file = $rootScope.image;
            }

            scope.collection = {
                content: null,
                uploaded_file: null
            }


            ///////////////////////////////Upload////////////////////////////////////

            var local = new Date();
            scope.disk = 'pictures';
            scope.upload_dir = 'pictures/';
            scope.filename = 'cropped' + local.getDay() + '-' + local.getMonth() + '-' + local.getYear() + '-' + local.getHours() + '-' + local.getMinutes() + '-' + local.getSeconds();
            scope.upload_status = false;
            scope.collection.uploaded_file = null;

            scope.$watch('file', function () {
                if (scope.file) {
                    scope.filename += scope.file.name;
                    scope.is_can_add = true;
                }
            });

            scope.$on('handleBroadcast', function () {
                scope.data = mySharedService.data;
                scope.$watch('data.file', function () {
                    scope.file = scope.data.file;
                });
            });
            mySharedService.prepForBroadcast(scope);

            scope.uploadFiles = function (file) {
                if (file) {

                    //////////////////////////////////////////////////

                    Upload.upload({
                        url: AppService.url + '/upload/carusel/image',
                        fields: {
                            disk: scope.disk,
                            upload_dir: scope.upload_dir,
                            filename: scope.filename
                        },
                        file: file
                    }).progress(function (evt) {

                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        //scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                        //$log.info(progressPercentage);
                        scope.progress = progressPercentage;

                    }).success(function (data, status, headers, config) {
                        if(scope.data.currentSlide) {
                            scope.data.currentSlide.file = null;
                            scope.file = null;
                            restartData();
                        }
                        scope.data.initData();
                        //mySharedService.prepForBroadcast(scope);

                        //}
                        console.log(data);

                        if (data.success) {

                            //console.log(data.data)
                            scope.collection.uploaded_file = data.data;

                        }
                        scope.progress = 0;

                        //$timeout(function () {
                        //    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                        //});

                    }).error(function (data, status, headers, config) {
                        $log.info('ddd', data);
                    });

                }

            };

///////////////////////////////////////////////////////////////////////////////////
            scope.current_color = null;

            // console.log('serv',ColorsDirectiveService)

            // scope.link = null;

            scope.$on('color-change', function (event, args) {

                //console.log(args);
                scope.current_color = null;
                scope.link = null;

                ColorsDirectiveService.getAllColors()
                    .then(
                        function (response) {
                            //console.log(response);
                            scope.colors = response;
                        }
                    );


            });

            //scope.id = attributes.id;

            ColorsDirectiveService.getAllColors()
                .then(
                    function (response) {
                        console.log('colors', response);
                        scope.colors = response;
                    }
                );


            var deferred = $q.defer();

            scope.setColorCarusel = function (id, color) {
                var col = '{\"classname\":\"' + color.classname + '\",' +
                    '\"rgb\":\"' + color.rgb + '\"}';
                $http.put(AppService.url + '/change/slide/color', {id: id, color: col})
                    .then(
                        function successCallback(response) {
                            console.log('kol', response.data)
                            deferred.resolve(response.data);
                            scope.data.initData();
                        }
                    )

                return deferred.promise;

            }

            scope.setNewColor = function (c) {
                scope.current_color = c;
            }


            scope.addSlide = function () {
                var data = {
                    lang: scope.language,
                    title: scope.title,
                    description: scope.descript,
                    url: scope.address,
                    color: '{\"classname\":\"' + scope.current_color.classname + '\",' +
                    '\"rgb\":\"' + scope.current_color.rgb + '\"}',
                    image: scope.filename
                }
                //if(scope.collection.uploaded_file) {
                $http.post(AppService.url + '/add/slide', data)
                    .then(
                        function successCallback(response) {
                            console.log('resp', response)
                            scope.uploadFiles(scope.cropper.croppedImage);
                            scope.data.initData();
                            scope.data.closeWin();
                            //location.href=AppService.url + "/administrator/slides";
                        }, function errorCallback(err) {
                            console.log('error', err.data)
                        })
                //}

                console.log('add', data)

            }

            function restartData() {
                $http.get('/get/slide/' + scope.data.currentSlide.id).then(function successCallback(response) {
                        scope.data.currentSlide = response.data;
                        mySharedService.prepForBroadcast(scope);
                        console.log('edit', response.data);
                    },
                    function errorCallback(reason) {
                    })
            }


            scope.changePhotoSlide = function(){
                scope.uploadFiles(scope.cropper.croppedImage);
                var data = {slideId: scope.data.currentSlide.id,
                            oldImageName: scope.data.currentSlide.image,
                            cropImageName: scope.filename};
                $http.post(AppService.url + '/change/slide', data)
                           .then(function successCallback(response){
                                   console.log('a tu tu',response.data);
                               },
                           function errorCallback(reason){})
                console.log(scope.data)
            }

 /////////////////////////////////////////////////////////////////////////////////////

        }
    };
});

app.controller('SlidesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', '$interval', 'Upload','ngDialog','$sce','mySharedService', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,$interval,Upload,ngDialog,$sce,mySharedService) {
    $scope.newSlide = $rootScope.newSlide ? $rootScope.newSlide : false;
    $scope.currentSlide = null;
    //$scope.slides = [];

    $scope.id = 0;

    $scope.$watch('newSlide',function(newVal,oldVal){
        $rootScope.newSlide = newVal;
    })

    $scope.$watch('currentSlide',function(newVal,oldVal){
        $rootScope.currentSlide = newVal;
    })

    $scope.initData = function(){
       $http.get(AppService.url + '/get/slides',{headers:{lang:$scope.language}}).then(function successCallback(response){
                $scope.slides = response.data
                angular.forEach($scope.slides, function(item,idx){
                    $scope.slides[idx].color =  JSON.parse(item.color);
                })
        },function errorCallback(err){})

       console.log('jezdem!')
    }




    $scope.$watch('slides',function(newVal,oldVal){
        //if(newVal !== oldVal){
        $scope.slides = newVal
            console.log('nowy Waldek',newVal)
        //}
    });


        $scope.saveOrderData = function() {

            $http.put(AppService.url + '/set/new/order/carusel/data', {slides: $scope.slides})
                .then(
                    function successCallback(response) {
                        console.log('success', response.data);
                    },
                    function errorCallback(reason) {
                        console.log('masakra', reason.data);
                    }
                )

        }

    $scope.turnActive = function(slider){
            if(slider.status==1){
                slider.status=0;
            }else{
                slider.status=1;
            }
            $http.put('/get/slides/active',{slid: slider}).then(function successCallback(response){
                                   console.log('slide',response.data)
                               },
                               function errorCallbeck(reason){})
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

    $scope.confirmRemove = function(fn,slide,address){
        var add = address ? address : 'data/'+slide.id;
        $scope.forConfirmData = {
            fn: fn,
            item: slide,
            query: "Czy chcesz usunąć slide: <br />"+slide.title+"?",
        };
        $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }

    $scope.remove = function(slide){
        angular.forEach($scope.slides,function(item,index){
            console.log(slide);
            if($scope.slides[index].id == slide.item.id){
                $scope.slides.splice(index, 1);
            }
        })
        $http.delete(AppService.url+'/remove/slide',{params: {slide: {id: slide.item.id,
                                                                      image: slide.item.image}}})
            .then(function successCallback(response){
                  console.log('usunąłem',response.data);
                },
                  function errorCallback(reason){})

    }

    $scope.delegate = function(fn,data,address){
        fn(data);
    }

    $scope.toTrustedHTML = function( html ){
        return $sce.trustAsHtml( html );
    }

    $scope.openNew = function() {
        $scope.newSlide = true;
        $scope.currentSlide = null;
    }

    $scope.openEdit = function(id){
        $scope.currentSlide = null;
        $scope.newSlide = false;
        $scope.file = null;
        $scope.currColor = null;
        $scope.id = id;
        $http.get('/get/slide/'+id).then(function successCallback(response){
                                $scope.currentSlide = response.data;
                                $rootScope.currentSlide = response.data;
                                mySharedService.prepForBroadcast($scope);
                                //$scope.file = response.data.image;
                                $scope.currColor = JSON.parse($scope.currentSlide.color)

                                console.log('edit',response.data);
                                /*
                                    data = {filename: response.data.image}
                                    $http.post('/create/image',data)
                                         .then(function successCallback(response){
                                             $rootScope.image = response.data
                                             },
                                               function errorCallback(reason){})

                                    $rootScope.image = response.data.image
                                  */
                   },
                   function errorCallback(reason){})
    }

    $scope.changeTextField = function(id,fieldname,data){
        var data = {id:id,fieldname:fieldname,data:data}
        $http.put(AppService.url+'/change/slide/textfield',data)
            .then(function successCallback(response){
                        $scope.initData();
                        console.log('textfield',response.data);
                  },
                  function errorCallback(reason){
                        console.log(reason.data)
                  });
        console.log('asasasas',id);
    }


    $scope.closeWin = function(){
        $scope.newSlide = false;
        $scope.file = null;
        $scope.id = 0;
        mySharedService.prepForBroadcast($scope);
    }



}]);




app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/slides/master.html',
        controller: 'SlidesController'
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
