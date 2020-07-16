<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTrashedActionsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:create-trashed-actions-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create duplicacte of "actions" table that stores removed actions';

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
     * @return int
     */
    public function handle()
    {
        Schema::dropIfExists("_actions");
        DB::statement("CREATE TABLE _actions LIKE actions");
        $this->info("_actions table created successfully!");
    }
}
