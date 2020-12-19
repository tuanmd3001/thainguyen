<?php

namespace App\Console\Commands;

use App\Models\Admin\AdminUser;
use App\Models\Constants;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:RAP';

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
     * @return int
     */
    public function handle()
    {
        AdminUser::where('id', 1)->update(['password'=> Hash::make(Constants::DEFAULT_PASSWORD)]);
    }
}
