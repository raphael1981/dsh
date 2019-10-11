var app = angular.module('app',['ngSanitize', 'ngRoute', 'rodo.newsletter'], function($interpolateProvider) {
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


app.run(function($rootScope, $document, $log, $window) {



    if($document.scrollTop()>280){
        document.getElementById('topMenu').className = 'dsh-container-menu fixed-show';
        //document.getElementById('topMenuFixed').className = 'dsh-container-menu fixed-show';
    }else{
        document.getElementById('topMenu').className = 'dsh-container-menu';
        //document.getElementById('topMenuFixed').className = 'dsh-container-menu';
    }

    $document.on('scroll', function() {

        //console.log($document.scrollTop());
        //console.log(angular.element(document.getElementById('topMenu')));

        if($document.scrollTop()>280){
            document.getElementById('topMenu').className = 'dsh-container-menu fixed-show';
            //document.getElementById('topMenuFixed').className = 'dsh-container-menu fixed-show';
        }else{
            document.getElementById('topMenu').className = 'dsh-container-menu';
            //document.getElementById('topMenuFixed').className = 'dsh-container-menu';
        }

    })

});


app.directive('resize', function ($window,$timeout,$rootScope,$filter) {
    return function (scope, element, attr) {

        var w = angular.element($window);
        scope.$watch(function () {
            return {
                'h': w.height(),
                'w': w.width()
            };
        }, function (newValue, oldValue) {

            scope.windowHeight = newValue.h;
            scope.windowWidth = newValue.w;

            $timeout(function(){


            },500);


            console.log(angular.element(document.getElementsByClassName('cut_title')));


            angular.forEach(angular.element(document.getElementsByClassName('cut_title')), function(item,iter){

                if(scope.windowWidth<1300 && scope.windowWidth>639){

                    angular.element(document.getElementsByClassName('cut_title'))[iter].innerHTML = $filter('cut_title_by_words')(item.innerHTML,36);

                }

                if(scope.windowWidth<=639){

                    angular.element(document.getElementsByClassName('cut_title'))[iter].innerHTML = $filter('cut_title_by_words')(item.innerHTML,36);

                }


            });





            //$rootScope.$broadcast('resize-window', {window_width:scope.windowWidth});


        }, true);

        w.bind('resize', function () {
            scope.$apply();
        });
    }
});


app.filter('cut_title_by_words', function() { return function(title,words) {

    var str = '';

    //if(filter_resize<788 && filter_resize>639) {
    //    words = 26;
    //}else{
    //    words = 64;
    //}

    var lenfull = title.length;


    if(lenfull>words) {

        var remlem = lenfull - words;
        var shorttitle = title.substr(0, words);

        var fullex = title.split(' ');
        var shortex = shorttitle.split(' ');
        //console.log(fullex.length);
        //console.log(shortex.length);

        if(fullex[shortex.length-1].length==shortex[shortex.length-1].length){

            angular.forEach(shortex, function(item, iter){

                if(iter==0){
                    str += item;
                }else{
                    str += ' '+item;
                }

            });

            str+='...';

        }else{

            for(var i=0;i<(shortex.length-1);i++){

                if(i==0){
                    str += shortex[i];
                }else{
                    str += ' '+shortex[i];
                }

            }

            str+='...';

        }

    }else{

        str = title;

    }



    return str;

}});

app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500);

    $scope.$watch('language', function(newValue, OldValue){
        $rootScope.lang = newValue;
    });

}]);

app.controller('MenuController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope) {



}]);


app.controller('HomePageController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope) {



}]);


app.controller('SlidesController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', '$interval', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,$interval) {


    $scope.initRotor = function() {

        $scope.lastkey = $scope.how_many-1;

        $interval(function () {

            $scope.stop--;

            if($scope.stop>0)
                return;



            //angular.element(document.getElementsByClassName('slide-'+$scope.now)).removeClass('active');
            angular.element(document.getElementsByClassName('slide')).removeClass('active');
            angular.element(document.getElementsByClassName('slide-'+$scope.next)).addClass('active');

            //angular.element(document.getElementsByClassName('tags-'+$scope.now)).removeClass('active');
            angular.element(document.getElementsByClassName('tags')).removeClass('active');
            angular.element(document.getElementsByClassName('tags-'+$scope.next)).addClass('active');

            //angular.element(document.getElementsByClassName('rotor-title-'+$scope.now)).removeClass('active');
            angular.element(document.getElementsByClassName('rotor-title')).removeClass('active');
            angular.element(document.getElementsByClassName('title-'+$scope.next)).addClass('active');

            //angular.element(document.getElementsByClassName('date-'+$scope.now)).removeClass('active');
            angular.element(document.getElementsByClassName('date-view')).removeClass('active');
            angular.element(document.getElementsByClassName('date-'+$scope.next)).addClass('active');

            //angular.element(document.getElementsByClassName('color-beam-'+$scope.now)).removeClass('active');
            angular.element(document.getElementsByClassName('color-beam')).removeClass('active');
            angular.element(document.getElementsByClassName('color-beam-'+$scope.next)).addClass('active');

            //angular.element(document.getElementsByClassName('rotbtn-'+$scope.now)).removeClass('active');
            angular.element(document.getElementsByClassName('rotbtn')).removeClass('active');
            angular.element(document.getElementsByClassName('rotbtn-'+$scope.next)).addClass('active');

            if($scope.lastkey==$scope.next) {
                $scope.now++;
                $scope.next = 0;
            }else if($scope.lastkey==$scope.now){
                $scope.now = 0;
                $scope.next++;
            }else{
                $scope.now++;
                $scope.next++;
            }



        }, 9000);

    }


    $scope.changeSlideByButton = function(k){

        $scope.stop = 4;


        angular.element(document.getElementsByClassName('slide')).removeClass('active');
        angular.element(document.getElementsByClassName('slide-'+k)).addClass('active');

        angular.element(document.getElementsByClassName('tags')).removeClass('active');
        angular.element(document.getElementsByClassName('tags-'+k)).addClass('active');

        angular.element(document.getElementsByClassName('rotor-title')).removeClass('active');
        angular.element(document.getElementsByClassName('title-'+k)).addClass('active');

        angular.element(document.getElementsByClassName('date-view')).removeClass('active');
        angular.element(document.getElementsByClassName('date-'+k)).addClass('active');

        angular.element(document.getElementsByClassName('color-beam')).removeClass('active');
        angular.element(document.getElementsByClassName('color-beam-'+k)).addClass('active');

        angular.element(document.getElementsByClassName('rotbtn')).removeClass('active');
        angular.element(document.getElementsByClassName('rotbtn-'+k)).addClass('active');


        if($scope.lastkey==k) {
            $scope.now=k;
            $scope.next = 0;
        }else{
            $scope.now = k;
            $scope.next++;
        }




    }


}]);

app.controller('SubscribeNewsletterController',['$scope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout','$rootScope', 'AppService', function($scope, $http, $log, $q, $location, $window, $filter, $timeout,$rootScope,AppService) {

    $scope.initData = function(){
        $scope.memberemail = '';
        $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;
        $scope.raporttext = '';
        $scope.show_raport_cloud = false;
    }

    $scope.checkEmail = function(){

        var deferred = $q.defer();

        $http.post(AppService.url+'/api/check/is/member/email/newsletter', {email:$scope.memberemail})
            .then(
                function successCallback(response) {

                    deferred.resolve(response.data);

                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.addEmailToBase = function(action){

        var deferred = $q.defer();

        $http.put(AppService.url+'/api/member/newsletter/subscribe', {email:$scope.memberemail, action:action})
            .then(
                function successCallback(response) {
                    //console.log(response);
                    $scope.loadingclass = '';
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    //console.log(reason);
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.subscribeAdd = function(){

        $scope.loadingclass = 'active-loading';

        $scope.checkEmail()
            .then(
                function(response){
                    console.log(response);
                    $scope.raporttext = response.message;
                    $scope.show_raport_cloud = true;

                    if(response.status=='not_exist'){
                        $scope.addEmailToBase(response.status);
                    }else if(response.status=='exist_not_subscribe'){
                        $scope.addEmailToBase(response.status);
                    }else if(response.status=='exist_not_confirmed_subscribe'){
                        $scope.addEmailToBase(response.status);
                    }else if(response.status=='exist_subscribe'){
                        $scope.loadingclass = '';
                    }


                },
                function(){
                    $scope.loadingclass = '';
                }
            )

    }


}]);