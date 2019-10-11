<?php

namespace App\Console\Commands;

use App\Entities\Gallery;
use Illuminate\Console\Command;

class RemoveEmptyGalleries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:empty-galleries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $ar = [];

        foreach(Gallery::all() as $key=>$gall){

            if($gall->pictures()->count()==0){
                $this->info('remove gallery '.$gall->title);
                $gall->delete();
            }

        }


    }
}
