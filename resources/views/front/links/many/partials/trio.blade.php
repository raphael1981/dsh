<div
        class="controller-neutral"
        ng-controller="ManyTrioListController"
        ng-model="lid"
        ng-model="lang_tag"
        ng-init="
            lang_tag='{{ $language['tag'] }}';
            lid={{ $data->link->id  }};
            initData()
        "
>

    <div class="dsh-container trio-view trio-many-view">



        @foreach($data->many_data as $key=>$el)




        @endforeach



    </div>


</div>