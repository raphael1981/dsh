<?php

use App\Entities\Language;
use Illuminate\Database\Seeder;

class ViewProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $default_view_config_content = config('view_profiles')['default_view_profile_content'];

        $std_content = new stdClass();
        $std_content->color = $default_view_config_content['color'];
        $std_content->icon = $default_view_config_content['icon'];

        $json_content = json_encode($std_content);

        $default_view_config_agenda = config('view_profiles')['default_view_profile_agenda'];


        $std_agenda = new stdClass();
        $std_agenda->color = $default_view_config_agenda['color'];
        $std_agenda->icon = $default_view_config_agenda['icon'];

        $json_agenda = json_encode($std_agenda);


        \App\Entities\ViewProfile::create([

            'language_id'=>2,
            'profile_name'=>$default_view_config_content['p_name'],
            'suffix'=>$default_view_config_content['suffix'].'pl',
            'type'=>'content',
            'params'=>$json_content

        ]);

        \App\Entities\ViewProfile::create([

            'language_id'=>2,
            'profile_name'=>$default_view_config_agenda['p_name'],
            'suffix'=>$default_view_config_agenda['suffix'].'pl',
            'type'=>'agenda',
            'params'=>$json_agenda

        ]);


        \App\Entities\ViewProfile::create([

            'language_id'=>1,
            'profile_name'=>$default_view_config_content['p_name'],
            'suffix'=>$default_view_config_content['suffix'].'en',
            'type'=>'content',
            'params'=>$json_content

        ]);

        \App\Entities\ViewProfile::create([

            'language_id'=>1,
            'profile_name'=>$default_view_config_agenda['p_name'],
            'suffix'=>$default_view_config_agenda['suffix'].'en',
            'type'=>'agenda',
            'params'=>$json_agenda

        ]);


        foreach(config('view_profiles')['view_profile_config'] as $key=>$value){


            $std = new stdClass();
            $std->color = $value['color'];
            $std->icon = $value['icon'];

            $json = json_encode($std);

            $lang = Language::where('tag', $value['language'])->first();

            \App\Entities\ViewProfile::create(
                [
                    'language_id'=>$lang->id,
                    'profile_name'=>$value['p_name'],
                    'suffix'=>$value['suffix'],
                    'type'=>$value['type'],
                    'params'=>$json
                ]
            );

        }
    }
}
