<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TemplatesTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ViewProfilesTableSeeder::class);
//        $this->call(PlacesTableSeeder::class);
//        $this->call(ContentsTableSeeder::class);
//        $this->call(AgendasTableSeeder::class);
        $this->call(LinksTableSeeder::class);
//        $this->call(YoutubesTableSeeder::class);
//        $this->call(PicturesTableSeeder::class);
//        $this->call(GalleriesTableSeeder::class);
//        $this->call(MediasTableSeeder::class);
//        $this->call(GroupsTableSeeder::class);
//        $this->call(MembersTableSeeder::class);
//        $this->call(CategorygablesTableSeeder::class);
//        $this->call(LinkgablesTableSeeder::class);
//        $this->call(AgendagablesTableSeeder::class);
//        $this->call(GroupgablesTableSeeder::class);
//        $this->call(GallerygablesTableSeeder::class);
//        $this->call(PicturegablesTableSeeder::class);
//        $this->call(MediagablesTableSeeder::class);
        $this->call(SlidesTableSeeder::class);
        $this->call(PublicationsTableSeeder::class);

        $this->call(OldBaseTableSeeder::class);
        $this->call(SshSeederCache::class);
    }
}
