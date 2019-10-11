<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$images = \Illuminate\Support\Facades\Storage::disk('pictures')->files();


$factory->define(App\Entities\Place::class, function (Faker\Generator $faker) use ($images) {

    $name = ($faker->boolean)?$faker->city:$faker->address;

    $pic_keys_border = count($images)-1;

    $is_geo = $faker->boolean;

    return [
        'name' => $name,
        'alias' => str_slug($name,'-'),
        'image' => $images[$faker->numberBetween(0,$pic_keys_border)],
        'image_path' => ':',
        'disk' => 'pictures',
        'description'=>$faker->sentence(10,15),
        'lat'=>($is_geo)?$faker->latitude:null,
        'lng'=>($is_geo)?$faker->longitude:null,
        'params'=>'{}'
    ];
});





$factory->define(App\Entities\Content::class, function (Faker\Generator $faker) use ($images) {

    $langs = \App\Entities\Language::all();
    $langs_keys_border = $langs->count()-1;

    $title = $faker->sentence(5, 10);

    $pic_keys_border = count($images)-1;

    $type = $faker->regexify('(internal|external)');

    $is_image = $faker->boolean;

    $status = $faker->numberBetween(0,1);

    $lang = $langs[$faker->numberBetween(0,$langs_keys_border)]->id;

    $default_view_profile = config('view_profiles')['default_view_profile_content'];

//    $std = new stdClass();
//    $std->name = $default_view_profile['p_name'];
//    $std->icon = $default_view_profile['icon'];
//    $std->color = $default_view_profile['color'];
//    $json = \GuzzleHttp\json_encode($std);

    $prfs = \App\Entities\ViewProfile::where('type','content')->get()->toArray();
    $last_prfs_key = count($prfs)-1;
    $current_prfs = $prfs[$faker->numberBetween(0,$last_prfs_key)];
    $current_prfs['params'] = \GuzzleHttp\json_decode($current_prfs['params'],true);

    $std = new stdClass();
    $std->name = $current_prfs['profile_name'];
    $std->icon = $current_prfs['params']['icon'];
    $std->color = $current_prfs['params']['color'];
    $json = \GuzzleHttp\json_encode($std);


    return [
        'language_id' => $lang,
        'title' => $title,
        'alias' => str_slug($title, '-'),
        'image' => ($is_image)?$images[$faker->numberBetween(0,$pic_keys_border)]:null,
        'image_path' => ($is_image)?':':null,
        'disk' => ($is_image)?'pictures':null,
        'intro' => $faker->sentence(40, 100),
        'content' => $faker->sentence(100, 300),
        'author'=>$faker->firstName.' '.$faker->lastName,
        'type' => $type,
        'url' => ($type=='external')?$faker->url:null,
        'suffix'=>$default_view_profile['suffix'].\App\Entities\Language::find($lang)->tag,
        'params' => $json,
        'meta_description' => $faker->sentence(10, 20),
        'meta_keywords' => '',
        'status' => $status,
        'published_at'=>($status==1)?(\Carbon\Carbon::now()):null
    ];

});



$factory->define(App\Entities\Agenda::class, function (Faker\Generator $faker) use ($images) {

    $langs = \App\Entities\Language::all();
    $langs_keys_border = $langs->count()-1;

    $places = \App\Entities\Place::all();
    $places_keys_border = $langs->count()-1;

    $pic_keys_border = count($images)-1;

    $title = $faker->sentence(5, 10);


    $date_target = $faker->boolean;


    if($date_target){
        $start = \Carbon\Carbon::now()->subMonths(random_int(3,14));
        $is_the_same_day = $faker->boolean;
        $stop = \Carbon\Carbon::now()->subDays(random_int(1,3));
    }else{
        $start = \Carbon\Carbon::now()->addDays(random_int(3,14));
        $is_the_same_day = $faker->boolean;
        $stop = \Carbon\Carbon::now()->addMonths(random_int(1,3));
    }


    $start_string = $start->year.'-'.$start->month.'-'.$start->day.' '.$start->hour.':'.$start->minute.':'.$start->second;
    $stop_string = $stop->year.'-'.$stop->month.'-'.$stop->day.' '.$stop->hour.':'.$stop->minute.':'.$stop->second;

    $is_time_between = false;

    if($is_the_same_day){

        $begin_time = \Carbon\Carbon::now();
        $end_time = \Carbon\Carbon::now()->addHours($faker->numberBetween(1,4))->addMonth($faker->numberBetween(1,55));

        $start_time_string = $begin_time->hour.':'.$begin_time->minute.':'.$begin_time->second;
        $stop_time_string = $end_time->hour.':'.$end_time->minute.':'.$end_time->second;

        $is_time_between = $faker->boolean;

    }

    $lang = $langs[$faker->numberBetween(0,$langs_keys_border)]->id;

    $default_view_profile = config('view_profiles')['default_view_profile_agenda'];

//    $std = new stdClass();
//    $std->name = $default_view_profile['p_name'];
//    $std->icon = $default_view_profile['icon'];
//    $std->color = $default_view_profile['color'];
//    $json = \GuzzleHttp\json_encode($std);


    $prfs = \App\Entities\ViewProfile::where('type','agenda')->get()->toArray();
    $last_prfs_key = count($prfs)-1;
    $current_prfs = $prfs[$faker->numberBetween(0,$last_prfs_key)];
    $current_prfs['params'] = \GuzzleHttp\json_decode($current_prfs['params'],true);

    $std = new stdClass();
    $std->name = $current_prfs['profile_name'];
    $std->icon = $current_prfs['params']['icon'];
    $std->color = $current_prfs['params']['color'];
    $json = \GuzzleHttp\json_encode($std);


    return [
        'language_id' => $lang,
        'place_id' => ($faker->boolean)?$places[$faker->numberBetween(0,$places_keys_border)]->id:null,
        'title' => $title,
        'alias' => str_slug($title, '-'),
        'image' => $images[$faker->numberBetween(0,$pic_keys_border)],
        'image_path' => ':',
        'disk' => 'pictures',
        'intro' => $faker->sentence(40, 100),
        'content' => $faker->sentence(100, 300),
        'params' => $json,
        'begin' => $start_string,
        'end' => (!$is_the_same_day)?$stop_string:$start_string,
        'begin_time'=>($is_the_same_day)?$start_time_string:null,
        'end_time'=>($is_time_between && $is_the_same_day)?$stop_time_string:null,
        'suffix'=>$default_view_profile['suffix'].\App\Entities\Language::find($lang)->tag,
        'meta_description' => $faker->sentence(10, 20),
        'meta_keywords' => '',
        'status' => $faker->numberBetween(0,1)
    ];
});


$factory->define(App\Entities\Youtube::class, function (Faker\Generator $faker) {

    return [
        'yid' => 'tTYj9jdZfxk',
        'regex_tag' => ''
    ];

});


$factory->define(App\Entities\Picture::class, function (Faker\Generator $faker) use ($images) {

    $pic_keys_border = count($images)-1;

    return [
        'image_name' => $images[$faker->numberBetween(0,$pic_keys_border)],
        'image_path' => ':',
        'disk' => 'pictures',
        'translations' =>  '{"en":"'.$faker->sentence(6,14).'", "pl":"'.$faker->sentence(6,14).'"}'
    ];

});


$factory->define(App\Entities\Gallery::class, function (Faker\Generator $faker) {

    $title = $faker->sentence(2,10);
    $pictures = \App\Entities\Picture::all();
    $pic_last_key = count($pictures)-1;

    $pic_array = [];
    $faker->numberBetween(0,$pic_last_key);

    for($i=0;$i<49;$i++){

        if($faker->boolean){

            $pic = $pictures[$faker->numberBetween(0,$pic_last_key)]->id;

            if(!in_array($pic, $pic_array)){
                array_push($pic_array, $pic);
            }
        }

    }

    return [
        'title' => $title,
        'alias' => str_slug($title, '-'),
        'regex_tag' => '',
        'params' => '{}',
        'collection' =>\GuzzleHttp\json_encode($pic_array)
    ];

});







$factory->define(App\Entities\Group::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(2,6),
        'person_limit' => $faker->numberBetween(7,20),
        'params' => '{}',
    ];

});


$factory->define(App\Entities\Member::class, function (Faker\Generator $faker) {

    return [
        'email' => $faker->unique()->safeEmail,
        'password' => '',
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'newsletter' => $faker->numberBetween(-1,1),
        'status' => $faker->numberBetween(-1,1),
        'verification_token'=>str_slug(bcrypt($faker->sentence(2,5)))
    ];


});



$factory->define(App\Entities\Publication::class, function (Faker\Generator $faker) {

    $langs = \App\Entities\Language::all();
    $langs_keys_border = $langs->count()-1;
    $lang = $langs[$faker->numberBetween(0,$langs_keys_border)]->id;
    $default_view_profile = config('view_profiles')['default_view_profile_agenda'];

    $title = $faker->sentence(5,10);


    $prfs = \App\Entities\ViewProfile::where('type','content')->get()->toArray();
    $last_prfs_key = count($prfs)-1;
    $current_prfs = $prfs[$faker->numberBetween(0,$last_prfs_key)];
    $current_prfs['params'] = \GuzzleHttp\json_decode($current_prfs['params'],true);

    $std = new stdClass();
    $std->name = $current_prfs['profile_name'];
    $std->icon = $current_prfs['params']['icon'];
    $std->color = $current_prfs['params']['color'];
    $json = \GuzzleHttp\json_encode($std);

    return [
        'language_id' => $lang,
        'title' => $title,
        'alias'=>str_slug($title,'-'),
        'intro' => $faker->sentence(9,15),
        'content' => $faker->sentence(30,45),
        'suffix'=>$default_view_profile['suffix'].\App\Entities\Language::find($lang)->tag,
        'params'=>$json,
        'status'=>1
    ];


});
