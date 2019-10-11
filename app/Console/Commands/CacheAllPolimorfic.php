<?php

namespace App\Console\Commands;

use App\Entities\Agenda;
use App\Entities\Content;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheAllPolimorfic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:polimorfic';

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

        foreach(Agenda::all() as $k=>$a){

            Cache::forever('agenda:categories:'.$a->id, Agenda::find($a->id)->categories()->get());
            Cache::forever('agenda:medias:'.$a->id, Agenda::find($a->id)->medias()->get());
            Cache::forever('agenda:galleries:'.$a->id, Agenda::find($a->id)->galleries()->get());
            Cache::forever('agenda:links:'.$a->id, Agenda::find($a->id)->links()->get());
            Cache::forever('agenda:entity:'.$a->id, Agenda::find($a->id));

        }

        foreach(Content::all() as $k=>$c){

            Cache::forever('content:categories:'.$c->id, Content::find($c->id)->categories()->get());
            Cache::forever('content:medias:'.$c->id, Content::find($c->id)->medias()->get());
            Cache::forever('content:galleries:'.$c->id, Content::find($c->id)->galleries()->get());
            Cache::forever('content:links:'.$c->id, Content::find($c->id)->links()->get());
            Cache::forever('content:entity:'.$c->id, Content::find($c->id));

        }

    }
}
