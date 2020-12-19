<?php

namespace App\Console\Commands;

use App\Models\Admin\Attachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ParseImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ParseImage';

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
//    public function handle()
//    {
//        printf((new TesseractOCR(public_path('/assets/images/text.png')))->run());
//    }
    private const MAX_RETRY = 3;

    public function handle()
    {
        $log_msg = "---\n";
        $log_msg .= date("H:i:s") . "\n";
        $process_lock = $this->lock_process();
        if (!$process_lock) {
            $log_msg .= "Failed to lock process\n";
        } else {
            $attachment = Attachment::where('process_lock', $process_lock)->first();

            if ($attachment) {
                $log_msg .= "Processing process_lock:" . $process_lock . "\n";
                $log_msg .= "Attachment ID:" . $attachment->id . "\n";
                $process_error = $this->process($attachment);

                if ($process_error) {
                    $log_msg .= $process_error . "\n";
                } else {
                    $log_msg .= "Parse done\n";
                }
            } else {
                $log_msg = "";
            }
        }
        print ($log_msg);
    }

    private function lock_process()
    {
        $process_lock = uniqid();
        try {
            $query = sprintf("
                update attachments set attachments.process_lock = '%s'
                where attachments.parsed = %s
                and attachments.extension in ('%s')
                and (attachments.process_lock = '' or attachments.process_lock is null)
                and attachments.retry_count < %s
                order by attachments.updated_at asc limit 1",
                $process_lock,
                0,
                implode('\',\'', ['png', 'jpeg', 'gif']),
                self::MAX_RETRY
            );
            DB::update(DB::raw($query));
            return $process_lock;
        } catch (\Exception $e) {
            print_r($e->getMessage());
            return null;
        }
    }

    private function unlock_process($attachment, $process_msg, $content = null)
    {
        $attachment->process_lock = null;
        $attachment->process_msg = $process_msg;

        if ($content == null) {
            $attachment->retry_count += 1;
        }
        else {
            $attachment->parsed = 1;
            $attachment->content = $content;
            $attachment->retry_count = 0;
        }

        $attachment->save();
    }

    private function process($attachment)
    {
        try {
            $content = (new TesseractOCR(public_path('/assets/images/text.png')))->run();
            $this->unlock_process($attachment, '', $content);
            return null;
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            $this->unlock_process($attachment, $message);
            return $message;
        }
    }
}
