<?php

use Illuminate\Database\Seeder;
use Collective\Remote\RemoteFacade as SSH;

class SshSeederCache extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SSH::run([
            'cd /usr/home/DSH/domains/nowastrona.dsh.usermd.net/public_html',
            'php artisan cache:polimorfic',
        ]);
    }
}
