<div ng-view>
    <style>
        .well {height:45vh; overflow-x:hidden; overflow-y:auto}
        .btn.btn-info {min-width:100%; margin-bottom:5px}
    </style>
    <div ng-controller="OldBaseController">
        <div class="container">
        <div class="row">
            <div class="col-md-2">
            <div class="btn btn-info" ng-click="oldItems('exhib')">Stare wystawy</div>
            <div class="btn btn-info" ng-click="oldItems('event')">Stare eventy</div>
                <div class="btn btn-info" ng-click="oldEditions()">Stare książki</div>
            </div>
            <div class="col-md-10">
                <div class="well">
                    <div ng-repeat="item in items">[[item.post_id]] [[item.post_title]] [[item.start]] - [[item.ended]]</div>
                </div>
                <div class="well">
                    [[ items ]]
                </div>
        </div>
        </div>
    </div>
</div>