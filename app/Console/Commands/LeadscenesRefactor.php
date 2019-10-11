<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories;

class LeadscenesRefactor extends Command
{

    private $leadscene;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leadscene:refactor';

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
    public function __construct(Repositories\LeadsceneRepositoryEloquent $leadscene)
    {
        $this->leadscene = $leadscene;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->leadscene->all() as $k=>$l){

        }
    }
}
