<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ParseAttachment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ParseAttachment';

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
        printf((new TesseractOCR(public_path('/assets/images/text.png')))->run());
    }
}
