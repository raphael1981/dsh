var app = angular.module('app',['ngSanitize', 'ngRoute', 'ui.tree', 'xeditable', 'ui.sortable','ngDialog','ui.select'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);

app.run(function(editableOptions, editableThemes) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
    //console.log(editableThemes);
    //console.log(editableOptions);
    editableThemes['bs3'].cancelTpl = '<button type="button" data-nodrag class="btn btn-default" ng-click="$form.$cancel()"><span></span></button>';
    editableThemes['bs3'].submitTpl = '<button type="submit" data-nodrag class="btn btn-primary"><span></span></button>';
    editableThemes['bs3'].formTpl = '<form class="form-inline editable-wrap" role="form"></form>';

});

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});


app.factory('IconsDirectiveService', function($location,AppService,$http,$q) {
    return {
        getAllIcons: function(){

            var deferred = $q.defer();

            $http.get(AppService.url+'/get/sections/icons')
                .then(
                    function successCallback(response){
                        deferred.resolve(response.data);
                    }
                )

            return deferred.promise;

        },

        getLinkIconIfExist: function(id){

            var deferred = $q.defer();

            $http.get(AppService.url+'/get/link/icon/'+id)
                .then(
                    function successCallback(response){
                        deferred.resolve(response.data);
                    }
                )

            return deferred.promise;
        }
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

        getLinkColorIfExist: function(id){

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


app.directive('editSelectSingleData', function($http,AppService) {
    return {
        templateUrl: '/templates/admin/super/links/single-data-select.html',
        link: function(scope, element, attributes){


            scope.frase = ''
            scope.collection = {
                elements:null
            }


            scope.$watchCollection(
                "collection",
                function( newValue, oldValue ) {
                    //console.log(newValue);
                }
            );


            scope.searchElements = function(){

                if(scope.frase.length>2){
                    $http.post(AppService.url+'/fast/find/singel/data', {frase:scope.frase})
                        .then(
                            function successCallback(response){
                                console.log(response.data);
                                scope.collection.elements = response.data;
                            }
                        )
                }else{
                    scope.collection = {
                        elements:null
                    }
                }
            }




        }
    };
});


app.directive('editIconSingleLink', function($http,AppService,IconsDirectiveService) {
    return {
        templateUrl: '/templates/admin/super/links/edit-icon-single-link.html',

        link: function(scope, element, attributes){


            scope.$on('icons-change', function(event, args) {

                //console.log(attributes.id);
                //console.log(args);
                //console.log(event);
                scope.id = args;

                IconsDirectiveService.getAllIcons()
                    .then(
                        function(response){
                            //console.log(response);
                            scope.icons = response;
                        }
                    );
                IconsDirectiveService.getLinkIconIfExist(scope.id)
                    .then(
                        function(response){
                            //console.log(response);
                            scope.link = response.link;
                            scope.params = JSON.parse(response.link.params);
                            scope.icon = response.icon;
                        }
                    );

            });

            scope.id = attributes.id


            IconsDirectiveService.getAllIcons()
                .then(
                    function(response){
                        //console.log(response);
                        scope.icons = response;
                    }
                );
            IconsDirectiveService.getLinkIconIfExist(scope.id)
                .then(
                    function(response){
                        //console.log(response);
                        scope.link = response.link;
                        scope.params = JSON.parse(response.link.params);
                        scope.icon = response.icon;
                    }
                );




        }
    };
});


app.directive('editColorSection', function($http, AppService, ColorsDirectiveService) {
    return {
        templateUrl: '/templates/admin/super/links/edit-color-section.html',

        link: function(scope, element, attributes){

            //console.log(attributes);
            scope.current_color = null;
            scope.link = null;

            scope.$on('color-change', function(event, args) {

                //console.log(args);
                scope.current_color = null;
                scope.link = null;

                ColorsDirectiveService.getAllColors()
                    .then(
                        function(response){
                            //console.log(response);
                            scope.colors = response;
                        }
                    );

                ColorsDirectiveService.getLinkColorIfExist(args)
                    .then(
                        function(response){
                            //console.log(response);
                            scope.link = response;
                        }
                    );



            });

            scope.id = attributes.id;

            ColorsDirectiveService.getAllColors()
                .then(
                    function(response){
                        //console.log(response);
                        scope.colors = response;
                    }
                );

            ColorsDirectiveService.getLinkColorIfExist(scope.id)
                .then(
                    function(response){
                        //console.log(response);
                        scope.link = response;
                    }
                );



        }
    };
});


app.directive('descriptionLinkEdit', function($http, $rootScope, AppService, ColorsDirectiveService) {
    return {
        templateUrl: '/templates/admin/super/links/edit-desc-link.html',

        link: function(scope, element, attributes){

            scope.data = {
                links:[],
                desc:'',
                is_show_desc:false
            };


            scope.$on('get-link-data', function(event, args) {

                //scope.is_show_desc = true;
                //console.log(args);

                //is_show_desc:true

                var params = JSON.parse(args.params);
                scope.data.is_show_desc = params.is_show_desc;

                if(args.description!=null){
                    scope.data.desc = args.description;
                }


                if(args.description_links!=null){

                    angular.forEach(args.description_links, function(item){
                        scope.data.links.push({
                            name:item.name,
                            link:item.link,
                            target:item.target
                        });
                    });

                }else{
                    scope.data.links = [];
                }


            });

            scope.addNewLinkForm = function(){
                scope.data.links.push({
                    name:'',
                    link:'',
                    target:'_self'
                });
            }

            scope.removeLink = function($index){
                scope.data.links.splice($index,1);
            }



            scope.$watchCollection(
                "data.links",
                function( newValue, oldValue ) {
                    //console.log(newValue);
                    $rootScope.$broadcast('links-change',newValue);
                }
            );


            scope.$watch(
                "data.desc",
                function( newValue, oldValue ) {
                    //console.log(newValue);
                    $rootScope.$broadcast('desc-change',newValue);
                }
            );

            scope.$watch(
                "data.is_show_desc",
                function( newValue, oldValue ) {
                    //console.log(newValue);
                    $rootScope.$broadcast('desc-is-show',newValue);
                }
            );


        }
    };
});



app.directive('branchMenuShow', function($http, $rootScope, AppService, ColorsDirectiveService) {
    return {
        templateUrl: '/templates/admin/super/links/branch-menu-show.html',

        link: function(scope, element, attributes){

            scope.params = [
                {
                    name:"Pokaż menu gałęzi",
                    value:true
                },
                {
                    name:"Nie pokazuj menu gałęzi",
                    value:false
                }
            ];

            scope.is_branch_menu = {

            };


            scope.$on('get-link-branch-show-status', function(event, args) {

                //console.log(args.params);
                var params = JSON.parse(args.params);
                scope.is_branch_menu = params.show_branch_menu;

            });




            scope.$watch(
                "is_branch_menu",
                function( newValue, oldValue ) {
                    //console.log(newValue);
                    $rootScope.$broadcast('branch-menu-status-change',newValue);
                }
            );


        }
    };
});



app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});

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


app.controller('LinksController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope','ngDialog','$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams, $rootScope,ngDialog,$sce) {



    $scope.initData = function(){
        $scope.link_id = null;
        $scope.link_id_icon = null;
        $scope.link_id_color = null;
        $scope.ltitle = '';
        $scope.editlink = null;
        $scope.templates = null;
        $scope.is_edit=false;
        $scope.is_selected_template = null;
        $scope.edited_params= {
            entity_model:null,
            url:null,
            categories:null,
            config:null
        };

        $scope.desc_data = {
            desc:'',
            links:[],
            is_show_desc:false
        };

        $scope.getConfigTemplates();
        $scope.getLinkTree();
        $scope.getAllTemplates();
        $scope.getCategories();
    }

    $scope.getConfigTemplates = function(){

        $http.get(AppService.url+'/get/config/templates')
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.config_temps = response.data;

                },
                function errorCallback(reason){

                }
            )

    }


    $scope.getLinkTree = function(){

        $http.get(AppService.url+'/links/tree/get/'+$rootScope.lang)
            .then(
                function successCallback(response){

                    //console.log(response);
                    $scope.nodes = response.data;

                },
                function errorCallback(reason){

                }
            )

    }



    $scope.getAllTemplates = function(){
        $http.get(AppService.url+'/get/all/templates')
            .then(
                function successCallback(response){
                    //console.log(response);
                    $scope.templates = response.data;
                }
            )
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



    $scope.addNode = function(scope, title){

        var nodeData = scope.$modelValue;
        //nodeData.nodes.push({
        //    id: nodeData.id * 10 + nodeData.nodes.length,
        //    title: nodeData.title + '.' + (nodeData.nodes.length + 1),
        //    nodes: []
        //});
        nodeData.nodes.push({
            id: null,
            title: title,
            nodes: []
        });

        $scope.saveTree();

        $scope.modalnewcat = 'hidden';
        $scope.ltitle = '';

    }

    $scope.remove = function(){

    }


    $scope.saveLinkTitle = function(){

        if($scope.ltitle.length>2){
            $scope.addNode($scope.treescope, $scope.ltitle);
        }else{
            $scope.ltitleclass = 'has-error';
        }


    }


    $scope.newSubItem = function (scope) {

        //console.log(scope);
        //$scope.saveTree();
        $scope.modalnewcat = '';
        $scope.treescope = scope;


    };

    $scope.active = function(item){
        if(item.status == 1){
            item.status = 0;
        }else{
            item.status = 1;
        }

        $http.put(AppService.url+'/set/active',{id:item.id,status:item.status})
            .then(function successCallback(response){
                console.log('active', response.data);
            },
                  function errorCallback(reason){
                      console.log(reason.data)
                  })

        //console.log('nooo',item)
    }


/*    $scope.changeLinkDest = function(data){
        console.log(data);

        $http.get(AppService.url+'/get/link/by/id/'+data)
            .then(
                function successCallback(response){
                    $scope.allItems = response.data.items.contents.concat(response.data.items.agendas)
                    $scope.editlink = response.data;
                    $log.info($scope.allItems);
                },
                function errorCallback(reason){

                }
            )

    }*/





    $scope.changeLinkDest = function(data){

        $scope.link_id = data;
        $scope.editlink = {};
        $scope.linkfulldata = null;
        $scope.is_edit = true;
        $scope.link_id_icon = null;
        $scope.link_id_color = null;

        $scope.edited_params= {
            entity_model:null,
            url:null,
            categories:null
        };

        $scope.getAddedAgendasAndContents(data);

        $scope.getFullLinkData()
            .then(
                function(response){

                    //$scope.linkfulldata = response;
                    $rootScope.$broadcast('get-link-data',$scope.linkfulldata);
                    $rootScope.$broadcast('get-link-branch-show-status',$scope.linkfulldata);
                    //console.log($scope.linkfulldata);

                    $scope.getTemplateType(data)
                        .then(
                            function(response){
                                //console.log('temp',response);
                                $scope.initConfigDistributor(response);

                            }
                        )

                }
            );



    }


    $scope.getFullLinkData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/full/link/data/'+$scope.link_id)
            .then(
                function successCallback(response){
                    deferred.resolve(response.data);
                },
                function errorCallback(reason){
                    deferred.reject(0);
                });

        return deferred.promise;

    }


    $scope.getTemplateType = function(id){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/template/by/link/id/'+id)
            .then(
                function successCallback(response){
                    //console.log(response.data);

                    if(response.data==0){
                        $scope.editlink.template = null;
                    }else{
                        $scope.editlink.template = response.data;
                        $scope.editlink.template.params = JSON.parse(response.data.params);
                    }

                    deferred.resolve($scope.editlink.template);

                },
                function errorCallback(reason){
                    deferred.reject(0);
                }
            )

        return deferred.promise;

    }

    $scope.$watchCollection('editlink.template', function(nVal, oVal){

        //console.log(nVal);
        //$scope.initConfigFilteredView();
        $scope.initConfigFilteredContentView();

    });


    ///////////////////////////////////////Config Get Data Functions/////////////////////////////////////////////////


    $scope.selectContent = function(el){

        var link = null;
        //console.log(el);
        //$scope.edited_params
        switch (el.entity_type){

            case 'contents':

                $scope.edited_params.entity_model = el;
                link = '/administrator/'+$scope.edited_params.entity_model.entity_type+'#!/edit/'+$scope.edited_params.entity_model.id+'?menu=0';
                $scope.edited_params.url = link;

                break;

            case 'agendas':

                $scope.edited_params.entity_model = el;
                link = '/administrator/'+$scope.edited_params.entity_model.entity_type+'#!/edit/'+$scope.edited_params.entity_model.id+'?menu=0';
                $scope.edited_params.url = link;

                break;

        }

    }



    $scope.getAddedAgendasAndContents = function(data){


        $http.get(AppService.url+'/get/link/by/id/'+data)
            .then(
                function successCallback(response){

                    //console.log(response.data);

                    $scope.editlink.link = {id : response.data[0].link_id,
                        title : response.data[0].link_title,
                        alias : response.data[0].link_alias}
                    //$scope.editlink = response.data;
                    $scope.editlink.items = [];
                    angular.forEach(response.data,function(item){
                        var type;
                        var row_type;
                        switch(item.lgb_linkgables_type){
                            case 'App\\Entities\\Content': type = 'artice';
                                row_type = 'Content';
                                break;
                            case 'App\\Entities\\Agenda': type = 'event';
                                row_type = 'Agenda';
                                break;
                        }
                        $scope.editlink.items.push({id: type=='event' ? item.ag_id : item.cnt_id,
                            title: type=='event' ? item.ag_title : item.cnt_title,
                            order: item.lgb_ord,
                            created_at: item.created_at ,
                            data_start: type=='event' ? item.data_start : null,
                            data_end:  type=='event' ? item.data_end : null,
                            url : type=='event' ?
                            '/administrator/agendas#!/edit/'+item.ag_id+'?menu=0' :
                            '/administrator/contents#!/edit/'+item.cnt_id+'?menu=0',
                            type : type,
                            row_type : row_type,
                            status :item.status})
                    });
                    //console.log(response.data)
                },
                function errorCallback(reason){

                }
            )

    }



    //////////////////////////////////////Config Init Functions/////////////////////////////////////////////////////


    $scope.initConfigDistributor = function(response){
        //console.log(response);
        //console.log($scope.link_id);



        if(response.params.is_filtered){
            $scope.initConfigFilteredView();
        }

        if(response.params.is_advanced_filtered){
            $scope.initConfigFilteredAdvancedView();
        }

        if(response.params.is_filtered_content){
            $scope.initConfigFilteredContentView();
        }

        if(response.params.is_filteredleaf){
            $scope.initConfigFilteredLeafView();
        }

        if(response.params.is_firtsleaf){

        }

        if(response.params.is_many){
            $scope.initConfigManyView();
        }

        if(response.params.is_single){
            $scope.initConfigSingleView();
        }

        if(response.params.is_unstandard){
            $scope.initConfigUnstandardView();
        }

        if(response.params.is_archive_content){
            $scope.initConfigFilteredArchiveContentView();
        }

    }



    $scope.initConfigSingleView = function(){
        //$scope.link_id;

        $http.post(AppService.url+'/get/config/data/single/'+$scope.link_id)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    //console.log($scope.edited_params);
                    $scope.edited_params.entity_model = response.data;
                    $scope.edited_params.url = '/administrator/'+$scope.edited_params.entity_model.entity_type+'#!/edit/'+$scope.edited_params.entity_model.id+'?menu=0';
                },
                function errorCallback(reason){

                }
            )

    }


    $scope.initConfigFilteredView = function(){

        $scope.elastic_views = [
            {type:'all', name:'Układ zmienny'},
            {type:'tiles', name:'Tylko kafeli'},
            {type:'repertuar', name:'Tylko układ repertuarowy'}
        ];

        $scope.group = [
            {type:true, name:'Tak'},
            {type:false, name:'Nie'}
        ];

        $scope.order = [
            {type:'asc', name:'Rosnąco'},
            {type:'desc', name:'Malejąco'}
        ];

        $scope.edited_params = {};

        $http.post(AppService.url+'/get/config/data/filtered/'+$scope.link_id)
            .then(
                function successCallback(response){
                    //console.log(response.data);

                    if(response.data.params.elastic_view){
                        $scope.edited_params.elastic_view = response.data.params.elastic_view;
                    }else{
                        $scope.edited_params.elastic_view = $scope.elastic_views[0];
                    }

                    if(response.data.params.order){
                        $scope.edited_params.order = response.data.params.order;
                    }else{
                        $scope.edited_params.order = $scope.order[0];
                    }

                    if(response.data.params.group){
                        $scope.edited_params.group = response.data.params.group;
                    }else{
                        $scope.edited_params.group = $scope.group[0];
                    }

                    $scope.edited_params.categories = response.data.cats;
                },
                function errorCallback(reason){

                }
            )

    }


    $scope.initConfigFilteredAdvancedView = function(){

        //$scope.elastic_views = [
        //    {type:'all', name:'Układ zmienny'},
        //    {type:'tiles', name:'Tylko kafeli'},
        //    {type:'repertuar', name:'Tylko układ repertuarowy'}
        //];
        //
        //$scope.group = [
        //    {type:true, name:'Tak'},
        //    {type:false, name:'Nie'}
        //];
        //
        //$scope.order = [
        //    {type:'asc', name:'Rosnąco'},
        //    {type:'desc', name:'Malejąco'}
        //];

        $scope.edited_params = {};

        $http.post(AppService.url+'/get/config/data/filtered/'+$scope.link_id)
            .then(
                function successCallback(response){
                    //console.log(response.data);

                    //if(response.data.params.elastic_view){
                    //    $scope.edited_params.elastic_view = response.data.params.elastic_view;
                    //}else{
                    //    $scope.edited_params.elastic_view = $scope.elastic_views[0];
                    //}
                    //
                    //if(response.data.params.order){
                    //    $scope.edited_params.order = response.data.params.order;
                    //}else{
                    //    $scope.edited_params.order = $scope.order[0];
                    //}
                    //
                    //if(response.data.params.group){
                    //    $scope.edited_params.group = response.data.params.group;
                    //}else{
                    //    $scope.edited_params.group = $scope.group[0];
                    //}

                    $scope.edited_params.categories = response.data.cats;
                },
                function errorCallback(reason){

                }
            )

    }


    $scope.initConfigFilteredContentView = function(){

        //$scope.elastic_views = [
        //    {type:'all', name:'Układ zmienny'},
        //    {type:'tiles', name:'Tylko kafeli'},
        //    {type:'repertuar', name:'Tylko układ repertuarowy'}
        //];

        //$scope.group = [
        //    {type:true, name:'Tak'},
        //    {type:false, name:'Nie'}
        //];

        $scope.order = [
            {type:'asc', name:'Rosnąco'},
            {type:'desc', name:'Malejąco'}
        ];

        $scope.is_year_in_filter = [
            {value:true, name:'Tak'},
            {value:false, name:'Nie'}
        ];

        $scope.is_filters_active = [
            {value:true, name:'Tak'},
            {value:false, name:'Nie'}
        ];

        $scope.edited_params = {};

        $http.post(AppService.url+'/get/config/data/filtered/'+$scope.link_id)
            .then(
                function successCallback(response){
                    //console.log(response.data);

                    //if(response.data.params.elastic_view){
                    //    $scope.edited_params.elastic_view = response.data.params.elastic_view;
                    //}else{
                    //    $scope.edited_params.elastic_view = $scope.elastic_views[0];
                    //}

                    if(response.data.params.order){
                        $scope.edited_params.order = response.data.params.order;
                    }else{
                        $scope.edited_params.order = $scope.order[0];
                    }

                    //if(response.data.params.group){
                    //    $scope.edited_params.group = response.data.params.group;
                    //}else{
                    //    $scope.edited_params.group = $scope.group[0];
                    //}

                    if(response.data.params.is_year_in_filter){
                        $scope.edited_params.is_year_in_filter = response.data.params.is_year_in_filter;
                    }else{
                        $scope.edited_params.is_year_in_filter = $scope.is_year_in_filter[0];
                    }

                    if(response.data.params.is_filters_active){
                        $scope.edited_params.is_filters_active = response.data.params.is_filters_active;
                    }else{
                        $scope.edited_params.is_filters_active = $scope.is_filters_active[0];
                    }


                    if(response.data.params.config) {
                        $scope.edited_params.config = response.data.params.config;
                    }else{
                        $scope.edited_params.config = $scope.config_temps.is_many[0];
                    }

                    $scope.edited_params.categories = response.data.cats;



                },
                function errorCallback(reason){

                }
            )

    }


    $scope.initConfigUnstandardView = function(){

        $http.post(AppService.url+'/get/config/data/unstandard/'+$scope.link_id)
            .then(
                function successCallback(response){
                    console.log('test',response.data);
                    $scope.edited_params.config = response.data.config;
                },
                function errorCallback(reason){

                }
            )

    }


    $scope.initConfigManyView = function(){

        $http.post(AppService.url+'/get/config/data/many/'+$scope.link_id)
            .then(
                function successCallback(response){
                    //console.log('test',response.data);
                    //$scope.edited_params.config = response.data.config;

                    if(response.data.config) {
                        $scope.edited_params.config = response.data.config;
                    }else{
                        $scope.edited_params.config = $scope.config_temps.is_many[0];
                    }

                },
                function errorCallback(reason){

                }
            )

    }



    $scope.initConfigFilteredLeafView = function(){

        $http.post(AppService.url+'/get/config/data/filterleaf/'+$scope.link_id)
            .then(
                function successCallback(response){
                    //console.log('test',response.data);
                    //$scope.edited_params.config = response.data.config;

                    if(response.data.config) {
                        $scope.edited_params.config = response.data.config;
                    }else{
                        $scope.edited_params.config = $scope.config_temps.is_many[0];
                    }

                },
                function errorCallback(reason){

                }
            )

    }



    $scope.initConfigFilteredArchiveContentView = function(){


        $scope.edited_params = {};

        $http.post(AppService.url+'/get/config/data/filtered/'+$scope.link_id)
            .then(
                function successCallback(response){
                    console.log(response.data);


                    $scope.edited_params.categories = response.data.cats;
                },
                function errorCallback(reason){

                }
            )

    }


    ///////////////////////////////////////Save Template///////////////////////////////////////////////////////////

    $scope.saveTemplateData = function(){


        var data = {id:$scope.link_id, tid:$scope.editlink.template.id, ttype:$scope.editlink.template.params, edit_params:$scope.edited_params, desc:$scope.desc_data};


        $http.put(AppService.url+'/save/template/params', data)
            .then(
                function successCallback(response){

                    console.log(response.data);
                    //$scope.initData();
                },
                function errorCallback(reason){

                }
            )


    }


    ///////////////////////////////////////Save Template///////////////////////////////////////////////////////////


    //////////////////////////////////////Config Init Functions/////////////////////////////////////////////////////


    ///////////////////////////////////////Config Get Data Functions/////////////////////////////////////////////////


    $scope.saveOrderData = function(){

        $http.put(AppService.url+'/set/new/order/link/data', {items:$scope.editlink.items, lid:$scope.link_id})
            .then(
                function successCallback(response){
                    //$scope.changeLinkDest($scope.link_id);
                    console.log(response.data);
                },
                function errorCallback(reason){
                    console.log('masakra',reason);
                }
            )

    }


    //////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////Color section change///////////////////////////////////////

    $rootScope.$broadcast('color-change',null);

    $scope.changeSectionColor = function(id){

        $scope.is_edit = false;
        $scope.editlink = {};
        $scope.link_id_icon = null;
        $scope.link_id_color = id;

        $rootScope.$broadcast('color-change',$scope.link_id_color);

    }


    $scope.setColorLink = function(id, color){
        $scope.changeColorOfFirstLink(id, color)
            .then(
                function(response){
                    $scope.saveTree();
                }
            )
    }


    $scope.changeColorOfFirstLink = function(id, color){

        var deferred = $q.defer();

        $http.put(AppService.url+'/change/set/link/color', {id:id, color:color})
            .then(
                function successCallback(response){
                    deferred.resolve(response.data);
                }
            )

        return deferred.promise;

    }


    ///////////////////////////////////Color section change///////////////////////////////////////


    ///////////////////////////////////IconChange////////////////////////////////////////////////

    $rootScope.$broadcast('icons-change',null);


    $scope.editIconLink = function(id){

        $scope.is_edit = false;
        $scope.editlink = {};
        $scope.link_id_icon = id;
        $scope.link_id_color = null;
        $rootScope.$broadcast('icons-change',id);


    }


    $scope.setIconLink = function(id, icon){
        //console.log(id);
        //console.log(icon);

        $http.put(AppService.url+'/change/set/link/icon', {id:id,icon:icon})
            .then(
                function successCallback(response){
                    //console.log(response);
                }
            )

    }



    ///////////////////////////////////IconChange////////////////////////////////////////////////

    $scope.checkIsThisNameOnTheSameLevel = function(name, ref, id){

        var deferred = $q.defer()

        $http.post(AppService.url+'/check/is/name/on/level', {name:name, ref:ref, id:id})
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    deferred.reject(1)

                },
                function errorCallback(reason){
                    deferred.resolve(0)
                }
            );

        return deferred.promise;

    }



    $scope.updateLinkNameBefore = function(data, ref, id){

        $scope.is_free_name = true;

        if (data!='') {

            return $scope.checkIsThisNameOnTheSameLevel(data, ref, id)
                .then(
                    function(response){
                        //console.log(response);
                        if(response==1){

                        }else{
                            return 'Nazwa jest już na tym poziomie';
                        }
                    },
                    function(reason){
                        //console.log(reason);
                    }
                )




        }else{
            return "Wpisz nazwę";
        }

    }


    $scope.updateLinkNameAfter = function(data, ref){

        //console.log(data, id);

    }


    /////////////////////////////////////////////////////////////////////////////////////////////


    /////////////////////////////////////Desc change/////////////////////////////////////////////


    $scope.$on('desc-change', function(event, args) {

        //console.log(args);
        $scope.desc_data.desc = args;

    });

    $scope.$on('links-change', function(event, args) {

        //console.log(args);
        $scope.desc_data.links = args;

    });

    $scope.$on('desc-is-show', function(event, args) {

        //console.log(args);
        $scope.desc_data.is_show_desc = args;

    });


    /////////////////////////////////////Desc change/////////////////////////////////////////////

    /////////////////////////////////////Branch Menu Show////////////////////////////////////////




    /////////////////////////////////////Branch Menu Show////////////////////////////////////////

    $scope.saveTree = function(){

        $http.put(AppService.url+'/update/links/tree', {tree:$scope.nodes, lang_id:$rootScope.lang})
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    if(response.data.success){
                        $scope.getLinkTree();
                        $scope.initData();
                    }

                },
                function errorCallback(reason){

                }
            )

    }


    $scope.removeFromLink = function(ev){
        //console.log('removeFromLink ',ev)
    }

    $scope.turnOffOn = function(ev,item){
        $scope.currItem = item;
        var status = item.status == 1 ? 0 : 1;
        $scope.currItem.status = status;
        $http.put(AppService.url+'/set/new/status/link', {link_id: $scope.editlink.link.id,
                                                          item : $scope.currItem})
             .then(function successCallback(response){
                 //console.log('z zapytania',response.data)
                 },
                   function errorCallback(reason){
                       //console.log('masakra',reason);

                   });
        //console.log('turnOffOn ',status);
    }

///////////////////////////////////////////////////////////////////////////////////////

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
                //console.log('zamykam')
    });
    }

    $scope.editText = function(ev,item){
        //console.log(item);
        $scope.currItem = item;
        //console.log('window',$window)
        $scope.openSubWindow('/templates/admin/super/links/textfromlink.html','ngdialog-theme-default');

/*        var template = item.type == 'event' ? '/templates/admin/super/agendas/edit-agenda.html' :
                                              '/templates/admin/super/contents/edit-content.html'
        $scope.openSubWindow('/templates/admin/super/agendas/edit-agenda.html','ngdialog-theme-default');*/
    }


    $scope.confirmRemoveFromLink = function(fn,item,address){
        var add = address ? address : 'data/'+item.id;
            $scope.forConfirmData = {
                fn: fn,
                item: item,
                query: "Czy chcesz usunąć z linku: <br />"+item.title+"?",
            };
            $scope.openSubWindow('/templates/admin/super/confirm_renderer.html','ngdialog-theme-small');
    }


    $scope.removeFromLink = function(item){
        $http.delete(AppService.url+'/remove/fromlink',{params:{linkId:$scope.editlink.link.id,
                                                        itemId:item.item.id,
                                                        type:item.item.row_type}})
             .then(function successCallback(response){
                                             //console.log('editlink',$scope.editlink.items)
                                             //console.log('response', response.data)
                                             angular.forEach($scope.editlink.items,function(it,idx){
                                                 if(it.id == item.item.id && it.row_type == item.item.row_type){
                                                     $scope.editlink.items.splice(idx,1);
                                                     $scope.saveOrderData();
                                                     //console.log(idx)
                                                 }
                                             })

                                                     },
                                              function errorCallback(reason){})

        //console.log('do usunięcia ' +  item.item.id + ' ' + item.item.row_type)
        //console.log('link ',$scope.editlink.link.id)
    }

    $scope.delegate = function(fn,data,address){
        fn(data);
    }


    $scope.toTrustedHTML = function( html ){
        return $sce.trustAsHtml( html );
    }

///////////////////////////////////////////////////////////////////////////////////////

}]);




app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/links/master.html',
        controller: 'LinksController'
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