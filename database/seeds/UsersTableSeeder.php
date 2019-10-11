<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name'=>'Rafał Majewski',
            'email'=>'raphaelmaj@gmail.com',
            'password'=>bcrypt('wiliak100'),
            'remember_token' => str_random(10)
        ]);

        \App\User::create([
            'name'=>'Robert Radecki',
            'email'=>'r.radecki@dsh.waw.pl',
            'password'=>bcrypt('wiliak100'),
            'remember_token' => str_random(10)
        ]);

    }
}
