angular.module('app').directive('drn', ['$http', function($http) {
    return{
        restrict: 'AE',
        scope: true,
        link: function(scope,element,attrs){
            var parentTop = 0;
            var currRow = null;
            var colRows = [];
            var actRow = null;
            var lightRowParent = null;
            var handleRowParent = null;
            var isPossible = false;
            var halfPosTop = 0;
            var currPosTop = 0;
            var indexRowOld = 0;
            var indexRowNew = 0;

            //var items = [];

            //attrs.$observe('items', function(value){
            //items.push(angular.fromJson(value));
            //console.log(angular.fromJson(value))
            //});

            element.draggable({
                cursor: "move",
                containment: ".list",
                axis: "y",
                revert: false,
                start: function (event, ui) {
                    element.css('z-index',100)
                    parentTop = this.parentElement.offsetTop;
                    actRow = event.target;
                    handleRowParent = actRow.parentNode;
                    colRows = element.parent().parent().children();
                    for(var i=0; i<colRows.length; i++){
                        if(colRows[i] == this.parentNode){
                            indexRowOld = i;
                        }}

                },
                drag: function (event, ui) {
                    isPossible = false;
                    var halfPosLeft = 0;
                    var halfPosTop = 0;
                    var actPosBottom = 0;
                    var actPosTop = 0;
                    for(var i=0; i<colRows.length; i++){
                        halfPosTop = colRows[i].offsetTop +
                            colRows[i].offsetHeight / 2;
                        actPosBottom = actRow.offsetTop + actRow.offsetHeight;
                        actPosTop = actRow.offsetTop;
                        if(actPosBottom > halfPosTop &&
                            actPosTop <= halfPosTop
                        )
                        {
                            colRows[i].getElementsByTagName('div')[0].style.backgroundColor = "#e5efe7";
                            lightRowParent = colRows[i];
                            isPossible = true

                        }else{
                            colRows[i].getElementsByTagName('div')[0].style.backgroundColor = "#fff";
                        }
                    }
                },
                stop: function (event, ui) {
                    this.style.left = '0px';
                    this.style.top = '0px';
                    var lightContent = lightRowParent.getElementsByTagName('div')[0]
                    //handleRowParent.removeChild(actRow);
                    //lightRowParent.appendChild(actRow);
                    //lightRowParent.removeChild(lightContent);
                    //handleRowParent.appendChild(lightContent);
                    element.css('z-index',0)

                    for(var i=0; i<colRows.length; i++){
                        if(colRows[i] == lightRowParent){
                            indexRowNew = i;
                        }
                    }
                    var content;
                    if(indexRowOld > indexRowNew){

                        for(var i=indexRowNew; i<indexRowOld; i++){
                            content = colRows[i].getElementsByTagName('div')[0]
                            colRows[i].removeChild(content);
                            colRows[i+1].appendChild(content);
                        }
                    }else if(indexRowOld < indexRowNew){

                        for(var i=indexRowNew; i>indexRowOld; i--){

                            content = colRows[i].getElementsByTagName('div')[0]
                            colRows[i].removeChild(content);															colRows[i-1].appendChild(content);
                        }
                    }
                    try{
                        this.parentNode.removeChild(this)
                        lightRowParent.appendChild(this);
                    }catch(e){console.log('finall',e)}

                    var temp;
                    var items = angular.fromJson(attrs.items);
                    for(var i=0; i<colRows.length; i++){
                        temp = colRows[i].getElementsByTagName('div')[0].id.split('_');
                        for(var j=0; j<items.length; j++){
                            if(items[j].id == temp[1]){
                                items[j].order = (i+1);
                            }
                        }
                    }
                    var linkId = 0 //colRows.length > 0 ? colRows[0].link_id : 0;
                    console.log('adresik', attrs.adrs)

                    $http({
                        url: attrs.adrs,
                        method: "PUT",
                        headers: {'Content-Type': 'application/json'},
                        data: {link_id: attrs.idlink, items: items}
                    }).then(
                        function successCallback(response){
                            console.log('success',response.data);
                        },
                        function errorCallback(reason){
                            console.log('błąd',reason.data);
                        }
                    )



                    angular.forEach(colRows,function(items){
                        items.getElementsByTagName('div')[0].style.backgroundColor = '#ffffff';
                    })

                }
            })

        }
    }}]);
